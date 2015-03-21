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
     * Search for services and return XML (Level 4)
     *  GET /search-xml  HTTP/1.1
     * @returns string
     */
    public function anySearchXml()
    {
        return $this->fetchSittersXml();
    }

    /**
     * Search for services and return XML (Level 8)
     *  GET /search-xml-bromley  HTTP/1.1
     * @returns string
     */
    public function anySearchXmlBromley()
    {
        return $this->fetchSittersXml(true);
    }

    /**
     * Display basic portal search form (Level 5)
     *  GET /search-display  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplay()
    {
        return $this->render('search/search-display.html', [
            'form_uri' => 'search-display-results'
        ]);
    }

    /**
     * Display basic portal search form (Level 8)
     *  GET /search-display-bromley  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayBromley()
    {
        return $this->render('search/search-display.html', [
            'form_uri' => 'search-display-results-bromley'
        ]);
    }

    /**
     * Display basic portal results (Level 5)
     *  GET /search-display-results  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayResults()
    {
        return $this->fetchSitters();
    }

    /**
     * Display basic portal results (Level 8)
     *  GET /search-display-results-bromley  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayResultsBromley()
    {
        return $this->fetchSitters(true);
    }

    /**
     * Display sitter's details page (Level 5)
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
     * Display portal search form using XSLT (Level 6)
     *  GET /search-display-xslt  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayXslt()
    {
        $input = $_GET;
        $query = $this->parseRequestSitters($input);
        $query['limit'] = '5'; // Hard setting limit
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
     * Display search form in Ajax-ed version of portal (Level 7)
     *  GET /search-display-ajax  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayAjax()
    {
        return $this->render('search/search-display-ajax.html', [
            'uri' => 'search-json'
        ]);
    }

    /**
     * Display search form in Ajax-ed version of portal (Level 8)
     *  GET /search-display-ajax-bromley  HTTP/1.1
     * @returns string
     */
    public function getSearchDisplayAjaxBromley()
    {
        return $this->render('search/search-display-ajax.html', [
            'uri' => 'search-json-bromley'
        ]);
    }

    /**
     * Search for services and return JSON for datable (Level 7)
     *  GET /search-json  HTTP/1.1
     * @returns json
     */
    public function getSearchJson()
    {
        return $this->fetchSittersJson();
    }

    /**
     * Search for services and return JSON for datable (Level 8)
     *  GET /search-json-bromley  HTTP/1.1
     * @returns json
     */
    public function getSearchJsonBromley()
    {
        return $this->fetchSittersJson(true);
    }

    /**
     * @param bool $fetchBromley
     * @return string
     * @throws \Exception
     */
    private function fetchSitters($fetchBromley = false)
    {
        // Get sitters
        $input = $_GET;
        $query = $this->parseRequestSitters($input);
        $query['limit'] = '5'; // Hard setting limit
        $aggr = ServiceAggregator::createAggregator($query);
        $aggr->fetchResultsSitters($fetchBromley);
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
            'uri_search' => (!$fetchBromley) ? 'search-display' : 'search-display-bromley',
            'uri_detail' => 'sitter-detail',
            'uri_results' => (!$fetchBromley) ? 'search-display-results' : 'search-display-results-bromley',
        ]);
    }

    /**
     * @param bool $fetchBromley
     * @return string
     */
    private function fetchSittersXml($fetchBromley = false)
    {
        header('Content-type: text/xml');

        $query = $this->parseRequestSitters($_REQUEST);
        $aggr = ServiceAggregator::createAggregator($query);
        if (!$aggr->fetchResultsSitters($fetchBromley)) {
            $error = $aggr->getErrorMessage();
            return $this->createErrorDoc($error)->saveXML();
        }

        return $aggr->getResults()->saveXML();
    }

    /**
     * @param bool $fetchBromley
     * @return string
     * @throws \Exception
     */
    private function fetchSittersJson($fetchBromley = false)
    {
        header('Content-Type: application/json');

        $query = $this->parseRequestSitters($_REQUEST);
        // Pagination settings
        $query['limit'] = '5';
        $query['page'] = ($_REQUEST['start'] / $query['limit']) + 1;

        $aggr = ServiceAggregator::createAggregator($query);
        if (!$aggr->fetchResultsSitters($fetchBromley)) {
            $error = $aggr->getErrorMessage();
            return $this->createErrorDoc($error)->saveXML();
        }

        // Map XML to array
        $sitters = XML2Array::createArray($aggr->getResults()->saveXML());
        $sitters = $sitters['sitters']['sitter'];
        $sittersArr['draw'] = $_REQUEST['draw'];
        $sittersArr['recordsTotal'] = $aggr->getResultCount();
        $sittersArr['recordsFiltered'] = $sittersArr['recordsTotal'];
        $sittersArr['data'] = [];

        $i = 0;
        foreach ($sitters as $sitter) {
            $sittersArr['data'][$i][] = $sitter['name']['firstname'] . " " . $sitter['name']['lastname'];
            $sittersArr['data'][$i][] = $sitter['service']['type'];
            $sittersArr['data'][$i][] = "&pound;" . $sitter['service']['charges'];
            $sittersArr['data'][$i][] = $sitter['service']['location'];
            $sittersArr['data'][$i][] = '<a href="index.php?uri=sitter-detail&#38;id='.$sitter['@attributes']['id'].'&#38;service='.$sitter['@attributes']['service'].'" title="View sitters details" class="btn btn-sm btn-info">Details</a>';
            $i++;
        }

        return json_encode($sittersArr);
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
