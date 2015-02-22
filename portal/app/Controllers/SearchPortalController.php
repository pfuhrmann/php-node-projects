<?php

namespace COMP1688\CW\Controllers;

use DOMDocument;
use Kilte\Pagination\Pagination;
use LSS\XML2Array;
use COMP1688\CW\ServiceAggregator;

class SearchPortalController extends BaseUIController {

    /**
     * Search from services and return XML
     *  GET /search-xml  HTTP/1.1
     * @returns string
     */
    public function anySearchXml()
    {
        header('Content-type: text/xml');

        $query = $this->parseRequest($_REQUEST);
        $xmlDom = $this->loadMergedResults($query);

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
     *  GET /search-display-results  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayResults()
    {
        // Get sitters
        $input = $_GET;
        $query = $this->parseRequest($input);
        $query['limit'] = '2'; // Hard setting limit to 10
        $aggr = ServiceAggregator::createAggregator($query);
        $aggr->fetchResults();
        $sitters = XML2Array::createArray($aggr->getResults()->saveXML());
        $sitters = !empty($sitters['sitters']) ? $sitters['sitters']['sitter'] : [];

        // Check page param
        if (!ctype_digit($query['page'])) {
            $query['page'] = '1';
        }
        // Generate pagination
        $pagination = new Pagination($aggr->getResultCount(), $query['page'], $query['limit']);
        var_dump($aggr->getResultCount());

        return $this->render('search/search-display-results.html', [
            'input' => $input,
            'sitters' => $sitters,
            'pages' => $pagination->build(),
            'currentPage' => $query['page'],
        ]);
    }

    /**
     * Parse request parameters into array suitable for
     *  passing to service
     * @param array $input
     * @return array
     */
    private function parseRequest(array $input) {
        return [
            'type' => isset($input['type']) ? $input['type'] : '',
            'sort' => isset($input['sort']) ? $input['sort'] : '',
            'limit' => isset($input['limit']) ? $input['limit'] : '',
            'page' => isset($input['page']) ? $input['page'] : '',
        ];
    }

    /**
     * Create valid DOM error document
     * @param $message
     * @return DomDocument
     */
    private function createErrorDoc($message) {
        $sitters = ['error_message' => $message];
        $xmlDom = Array2XML::createXML('sitters', $sitters);

        return $xmlDom;
    }
}
