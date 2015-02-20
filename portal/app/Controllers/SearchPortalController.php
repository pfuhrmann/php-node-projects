<?php

namespace COMP1688\CW\Controllers;

use DOMDocument;
use SoapClient;
use COMP1688\CW\EnvironmentHelper as Env;

class SearchPortalController extends BaseUIController {

    /**
     * Search from services and return XML
     *  GET /search-xml  HTTP/1.1
     * @returns string
     */
    public function anySearchXml()
    {
        header('Content-type: text/xml');

        $type = '';
        if (isset($_REQUEST['type'])) {
            $type = $_REQUEST['type'];
        }

        // Get results from .NET service (Lewisham)
        $client = new SoapClient('http://comp1688.azurewebsites.net/SittersService.asmx?WSDL');
        $args = ['type' => $type];
        $xmls = $client->sitters($args)->SittersResult->any;
        $xmlDom1 = new DOMDocument();
        $xmlDom1->loadXML($xmls, LIBXML_NOBLANKS);
        // Get results from PHP service (Greenwich)
        $env = Env::getInstance();
        $serverName = ($env->isDev()) ? 'comp1688-service.app' : 'stuweb.cms.gre.ac.uk/~fp202';
        $url = 'http://'.$serverName.'/index.php?uri=sitters&type='.$type;
        $xmlDom2 = new DOMDocument();
        $xmlDom2->load($url);
        // Merge results
        $xmlRoot1 = $xmlDom1->documentElement;
        foreach ($xmlDom2->documentElement->childNodes as $node2) {
            $node1 = $xmlDom1->importNode($node2, true);
            $xmlRoot1->appendChild($node1);
        }

        return $xmlDom1->saveXML();
    }
}
