<?php

namespace COMP1688\CW\Controllers;

use DOMDocument;
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

        $type = '';
        if (isset($_REQUEST['type'])) {
            $type = $_REQUEST['type'];
        }

        $client = new SoapClient('http://comp1688.azurewebsites.net/SittersService.asmx?WSDL');
        $args = ['type' => $type];
        $xmls = $client->sitters($args)->SittersResult->any;
        $xmlDom1 = new DOMDocument();
        $xmlDom1->loadXML($xmls, LIBXML_NOBLANKS);

        $url = 'http://comp1688.app/index.php?uri=sitters&type='.$type;
        $xmlDom2 = new DOMDocument();
        $xmlDom2->load($url);

        $xmlRoot1 = $xmlDom1->documentElement;
        foreach ($xmlDom2->documentElement->childNodes as $node2) {
            $node1 = $xmlDom1->importNode($node2, true);
            $xmlRoot1->appendChild($node1);
        }

        return $xmlDom1->saveXML();
    }
}
