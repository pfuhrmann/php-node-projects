<?php

namespace COMP1688\CW\Controllers;

use LSS\Array2XML;
use COMP1688\CW\EnvironmentHelper as Env;
use DOMDocument;
use LSS\XML2Array;
use SoapClient;

class SearchPortalController extends BaseUIController {

    /**
     * Search from services and return XML
     *  GET /search-xml  HTTP/1.1
     * @returns string
     */
    public function anySearchXml()
    {
        header('Content-type: text/xml');

        $xmlDom = $this->loadMergedResults([
            'type' => isset($_REQUEST['type']) ? $_REQUEST['type'] : '',
            'sort' => isset($_REQUEST['sort']) ? $_REQUEST['sort'] : '',
            'limit' => isset($_REQUEST['limit']) ? $_REQUEST['limit'] : '',
            'page' => isset($_REQUEST['page']) ? $_REQUEST['page'] : '',
        ]);

        return $xmlDom->saveXML();
    }

    /**
     * Test graphical merged search results (Level 5)
     *  GET /search-display  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplay()
    {
        return $this->render('search/search-display.html', []);
    }

    /**
     * Test graphical merged search results (Level 5)
     *  POST /search-display-results  HTTP/1.1
     * @returns string
     */
    public function postSearchDisplayResults()
    {
        return $this->render('search/search-display-results.html', []);
    }

    /**
     * Get results from services and merge them into one DOM
     * @param array $query Query arguments used in service
     * @return DOMDocument
     */
    private function loadMergedResults(array $query)
    {
        $env = Env::getInstance();

        // Get results from .NET service (Lewisham)
        $wsdl = ($env->isDev()) ?
            'http://comp1688.azurewebsites.net/SittersService.asmx?WSDL' :
            'http://stuiis.cms.gre.ac.uk/fp202/comp1688/SittersService.asmx?WSDL';
        $client = new SoapClient($wsdl);
        $xmls = $client->sitters(['type' => $query['type']])->SittersResult->any;
        $xmlDom1 = new DOMDocument();
        $xmlDom1->loadXML($xmls, LIBXML_NOBLANKS);

        // Get results from PHP service (Greenwich)
        $serverName = ($env->isDev()) ? 'comp1688-service.app' : 'stuweb.cms.gre.ac.uk/~fp202';
        $url = 'http://'.$serverName.'/index.php?uri=sitters&type='.$query['type'].'';
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

            // Sorting
            $className = 'COMP1688\\CW\\Controllers\\SearchPortalController';
            switch ($query['sort']) {
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
            if (!empty($query['limit'])) {
                // Limit
                if (!is_int($query['limit'])) {
                    // TODO: Error return XML error message
                }
                $limit = $query['limit'];
                // Page
                $page = 1;
                if (!empty($query['page'])) {
                    if (!is_int($query['page'])) {
                        // TODO: Error return XML error message
                    }
                    $page = $query['page'];
                }

                $sitters = array_slice($sitters, $limit*($page-1), $limit);
            }

            // Convert array back to XML
            $sittersTop = [];
            if (!empty($sitters)) {
                $sittersTop['sitter'] = $sitters;
            }
            $xmlDom = Array2XML::createXML('sitters', $sittersTop);

            return $xmlDom;
        }

        // Empty result
        $sitters = [];
        $xmlDom = Array2XML::createXML('sitters', $sitters);

        return $xmlDom;
    }

    /**
     * Sort sitters array by charges (ascending)
     * @param $sitterA
     * @param $sitterB
     * @return bool
     * @noinspection PhpUnusedPrivateMethodInspection
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
}
