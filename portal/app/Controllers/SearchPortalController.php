<?php

namespace COMP1688\CW\Controllers;

use COMP1688\CW\ServiceAggregator;
use DOMDocument;
use Kilte\Pagination\Pagination;
use LSS\Array2XML;
use LSS\XML2Array;
use XSLTProcessor;

class SearchPortalController extends BaseUIController {

    /**
     * Search from services and return XML (Level 4)
     *  GET /search-xml  HTTP/1.1
     * @returns string
     */
    public function anySearchXml()
    {
        header('Content-type: text/xml');

        $query = $this->parseRequestSitters($_REQUEST);
        $aggr = ServiceAggregator::createAggregator($query);
        if (!$aggr->fetchResultsSitters()) {
            $error = $aggr->getErrorMessage();
            return $this->createErrorDoc($error)->saveXML();
        }

        return $aggr->getResults()->saveXML();
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
     *  GET /search-display-results  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayResults()
    {
        // Get sitters
        $input = $_GET;
        $query = $this->parseRequestSitters($input);
        $query['limit'] = '5'; // Hard setting limit to 5
        $aggr = ServiceAggregator::createAggregator($query);
        $aggr->fetchResultsSitters();
        $sitters = XML2Array::createArray($aggr->getResults()->saveXML());
        $sitters = !empty($sitters['sitters']) ? $sitters['sitters']['sitter'] : [];

        // We have only 1 result
        if (isset($sitters['name'])) {
            $sittersA[] = $sitters;
            $sitters = $sittersA;
        }

        // Generate pagination
        if (!ctype_digit($query['page'])) {
            $query['page'] = '1';
        }
        $pagination = new Pagination($aggr->getResultCount(), $query['page'], $query['limit']);

        return $this->render('search/search-display-results.html', [
            'input' => $input,
            'sitters' => $sitters,
            'pages' => $pagination->build(),
            'currentPage' => $query['page'],
        ]);
    }

    /**
     * Test graphical merged search results (Level 5)
     *  GET /sitter-detail  HTTP/1.1
     * @returns string
     */
    public function getSitterDetail()
    {
        // Get sitters
        $input = $_GET;
        $query = $this->parseRequestSitterDetail($input);
        $aggr = ServiceAggregator::createAggregator($query);
        $aggr->fetchResultsSitterDetails();
        $sitter = XML2Array::createArray($aggr->getResults()->saveXML());
        $sitter = $sitter['sitter_detail'];

        return $this->render('search/search-display-detail.html', [
            'input' => $input,
            'sitter' => $sitter,
        ]);
    }

    /**
     * Test graphical merged search results (Level 5)
     *  GET /search-display-xslt  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayXslt()
    {
        $input = $_GET;
        $query = $this->parseRequestSitters($input);
        $query['limit'] = '5'; // Hard setting limit to 10
        $aggr = ServiceAggregator::createAggregator($query);
        $aggr->fetchResultsSitters();
        $doc = $aggr->getResults();

        $xslt = new XSLTProcessor();
        $xsl = new DOMDocument();
        $xsl->load('sitters.xsl', LIBXML_NOCDATA);
        $xslt->importStylesheet($xsl);

        return $xslt->transformToXML($doc);
    }

    /**
     * Parse request parameters into array suitable for
     *  passing to service
     * @param array $input
     * @return array
     */
    private function parseRequestSitters(array $input)
    {
        return [
            'type' => isset($input['type']) ? $input['type'] : '',
            'sort' => isset($input['sort']) ? $input['sort'] : '',
            'limit' => isset($input['limit']) ? $input['limit'] : '',
            'page' => isset($input['page']) ? $input['page'] : '',
        ];
    }

    /**
     * Parse request parameters into array suitable for
     *  passing to service
     * @param array $input
     * @return array
     */
    private function parseRequestSitterDetail(array $input)
    {
        return [
            'id' => isset($input['id']) ? $input['id'] : '',
            'service' => isset($input['service']) ? $input['service'] : '',
        ];
    }

    /**
     * Create valid DOM error document
     * @param $message
     * @return DomDocument
     */
    private function createErrorDoc($message)
    {
        $sitters = ['error_message' => $message];
        $xmlDom = Array2XML::createXML('sitters', $sitters);

        return $xmlDom;
    }
}
