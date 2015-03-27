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
     * @var int Number of results from most recent call
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
    private function __construct(array $query)
    {
        $this->query = $query;
    }

    /**
     * Fetch services for sitters list
     * @param bool $fetchBromley Node.js service will be used in fetch process
     * @return bool True if fetch was successful
     * @throws \Exception
     */
    public function fetchResultsSitters($fetchBromley = false)
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

        // Merge Lewisham + Greenwich
        $this->mergeDom($xmlDom1, $xmlDom2);

        if ($fetchBromley) {
            // Merge Broomley with rest
            $url = $this->getServiceUrl('bromley').'/sitters?type='.$this->query['type'].'';
            $xmlDom3 = new DOMDocument();
            $xmlDom3->load($url);

            // Merge results together
            $this->mergeDom($xmlDom1, $xmlDom3);
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
                    if (!ctype_digit((string)$this->query['page'])) {
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

    /**
     * Fetch services for sitter details
     * @return bool True if fetch successful
     */
    public function fetchResultsSitterDetails()
    {
        switch ($this->query['service']) {
            case 'lewisham':
                // Get results from .NET service (Lewisham)
                $client = new SoapClient(self::getServiceUrl('lewisham'));
                $xmls = $client->sitterDetails(['id' => $this->query['id']])->SitterDetailsResult->any;
                $xmlDom = new DOMDocument();
                $xmlDom->loadXML($xmls, LIBXML_NOBLANKS);
                $this->results = $xmlDom;

                return true;
            case 'greenwich':
                // Get results from PHP service (Greenwich)
                $url = self::getServiceUrl('greenwich').'/index.php?uri=sitter-detail&id='.$this->query['id'];
                $xmlDom = new DOMDocument();
                $xmlDom->load($url);
                $this->results = $xmlDom;

                return true;
            case 'bromley':
                // Get results from Node.js service (Bromley)
                $url = self::getServiceUrl('bromley').'/sitter-details?id='.$this->query['id'];
                $xmlDom = new DOMDocument();
                $xmlDom->load($url);
                $this->results = $xmlDom;
                break;
        }

        return false;
    }

    /**
     * Returns URL of the service based on location
     * @param $name
     * @return string
     */
    public static function getServiceUrl($name) {
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
            case 'bromley':
                return ($env->isDev()) ? 'http://192.168.1.64:5000' : 'http://comp1688.herokuapp.com';
                break;
        }

        return false;
    }

    /**
     * Sort sitters array by charges (ascending)
     * @param $sitterA
     * @param $sitterB
     * @return bool
     */
    private function sortByChargesAsc($sitterA, $sitterB)
    {
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
    private function sortByChargesDesc($sitterA, $sitterB)
    {
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
    private function sortByLocation($sitterA, $sitterB)
    {
        if ($sitterA['service']['location'] === $sitterB['service']['location']) {
            return 0;
        }

        return ($sitterA['service']['location'] < $sitterB['service']['location']) ? -1 : 1;
    }

    /**
     * Merge two DOM's together
     * @param DOMDocument $xmlDomA
     * @param DOMDocument $xmlDomB
     */
    private function mergeDom(DOMDocument $xmlDomA, DOMDocument $xmlDomB) {
        $xmlRoot1 = $xmlDomA->documentElement;
        foreach ($xmlDomB->documentElement->childNodes as $node2) {
            $node1 = $xmlDomA->importNode($node2, true);
            $xmlRoot1->appendChild($node1);
        }
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
