<?php

namespace COMP1688\CW;

use COMP1688\CW\EnvironmentHelper as Env;
use LSS\Array2XML;
use LSS\XML2Array;
use SoapClient;
use DOMDocument;

class ServiceAggregator {

    /**
     * @var DomDocument Results from service calls
     */
    private $results;

    /**
     * @var int Number of resutls from most recent call
     */
    private $resultCount = 0;

    /**
     * @var string Error message holder
     */
    private $errorMessage;

    /**
     * @var string Query arguments used in service calls
     */
    private $query;

    /**
     * Service aggregator factory (list of sitters)
     * @param array $query Query arguments used in service calls
     * @return ServiceAggregator
     */
    public static function createAggregator(array $query)
    {
        $aggr = new ServiceAggregator($query);
        return $aggr;
    }

    /**
     * Create new instance of service aggregator
     * @param array $query Query arguments used in service calls
     */
    private function __construct(array $query) {
        $this->query = $query;
    }

    public function fetchResultsSitters()
    {
        // Get results from .NET service (Lewisham)
        $client = new SoapClient($this->getServiceUrl('lewisham'));
        $xmls = $client->sitters(['type' => $this->query['type']])->SittersResult->any;
        $xmlDom1 = new DOMDocument();
        $xmlDom1->loadXML($xmls, LIBXML_NOBLANKS);

        // Get results from PHP service (Greenwich)
        $url = $this->getServiceUrl('greenwich').'/index.php?uri=sitters&type='.$this->query['type'].'';
        $xmlDom2 = new DOMDocument();
        $xmlDom2->load($url);

        // Merge results together
        $xmlRoot1 = $xmlDom1->documentElement;
        foreach ($xmlDom2->documentElement->childNodes as $node2) {
            $node1 = $xmlDom1->importNode($node2, true);
            $xmlRoot1->appendChild($node1);
        }

        // Convert xml to array
        $sitters = XML2Array::createArray($xmlDom1->saveXML());

        if (!empty($sitters['sitters'])) {
            $sitters = $sitters['sitters']['sitter'];
            $this->resultCount = count($sitters);

            // Sorting
            $className = 'COMP1688\\CW\\ServiceAggregator';
            switch ($this->query['sort']) {
                case 'priceasc':
                    usort($sitters, [$className, 'sortByChargesAsc']);
                    break;
                case 'pricedesc':
                    usort($sitters, [$className, 'sortByChargesDesc']);
                    break;
                case 'location':
                    usort($sitters, [$className, 'sortByLocation']);
                    break;
                default:
                    usort($sitters, [$className, 'sortByChargesAsc']);
            }

            // Pagination
            if (!empty($this->query['limit'])) {
                // Limit
                if (!ctype_digit($this->query['limit'])) {
                    $this->errorMessage = 'Limit parameter must be positive whole number';
                return false;
            }
                $limit = $this->query['limit'];
                // Page
                $page = 1;
                if (!empty($this->query['page'])) {
                    if (!ctype_digit($this->query['page'])) {
                        $this->errorMessage = 'Page parameter must be positive whole number';
                        return false;
                    }
                    $page = $this->query['page'];
                }

                $sitters = array_slice($sitters, $limit * ($page - 1), $limit);
            }

            // Convert array back to XML
            $sittersTop = [];
            if (!empty($sitters)) {
                $sittersTop['sitter'] = $sitters;
            }

            $this->results = Array2XML::createXML('sitters', $sittersTop);
            return true;
        }

        // Empty result
        $this->results = Array2XML::createXML('sitters', []);
        return true;
    }

    public function fetchResultsSitterDetails()
    {
        switch ($this->query['service']) {
            case 'lewisham':
                // Get results from .NET service (Lewisham)
                $client = new SoapClient($this->getServiceUrl('lewisham'));
                $xmls = $client->sitterDetails(['id' => $this->query['id']])->SitterDetailsResult->any;
                $xmlDom = new DOMDocument();
                $xmlDom->loadXML($xmls, LIBXML_NOBLANKS);
                $this->results = $xmlDom;

                return true;
            case 'greenwich':
                // Get results from PHP service (Greenwich)
                $url = $this->getServiceUrl('greenwich').'/index.php?uri=sitter-detail&id='.$this->query['id'].'';
                $xmlDom = new DOMDocument();
                $xmlDom->load($url);
                $this->results = $xmlDom;

                return true;
            case 'broomley':
                // TODO: Broomley we service in Node.js
                break;
        }
    }

    /**
     * Returns URL of the service based on location
     * @param $name
     * @return string
     */
    public function getServiceUrl($name) {
        $env = Env::getInstance();

        switch ($name) {
            case 'lewisham':
                $url = ($env->isDev()) ?
                    'http://comp1688.azurewebsites.net/SittersService.asmx?WSDL' :
                    'http://stuiis.cms.gre.ac.uk/fp202/comp1688/SittersService.asmx?WSDL';
                return $url;
            case 'greenwich':
                $serverName = ($env->isDev()) ? 'comp1688-service.app' : 'stuweb.cms.gre.ac.uk/~fp202';
                $url = 'http://'.$serverName;
                return $url;
            case 'broomley':
                // TODO: Broomley we service in Node.js
                break;
        }
    }

    /**
     * Sort sitters array by charges (ascending)
     * @param $sitterA
     * @param $sitterB
     * @return bool
     */
    private function sortByChargesAsc($sitterA, $sitterB) {
        if ($sitterA['service']['charges'] === $sitterB['service']['charges']) {
            return 0;
        }

        return ($sitterA['service']['charges'] < $sitterB['service']['charges']) ? -1 : 1;
    }

    /**
     * Sort sitters array by charges (descending)
     * @param $sitterA
     * @param $sitterB
     * @return bool
     */
    private function sortByChargesDesc($sitterA, $sitterB) {
        if ($sitterA['service']['charges'] === $sitterB['service']['charges']) {
            return 0;
        }

        return ($sitterA['service']['charges'] > $sitterB['service']['charges']) ? -1 : 1;
    }

    /**
     * Sort sitters array by location (A to Z)
     * @param $sitterA
     * @param $sitterB
     * @return bool
     */
    private function sortByLocation($sitterA, $sitterB) {
        if ($sitterA['service']['location'] === $sitterB['service']['location']) {
            return 0;
        }

        return ($sitterA['service']['location'] < $sitterB['service']['location']) ? -1 : 1;
    }

    /**
     * @return DOMDocument
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getResultCount()
    {
        return $this->resultCount;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
