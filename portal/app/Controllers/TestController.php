<?php

namespace COMP1688\CW\Controllers;

use COMP1688\CW\ServiceAggregator;

class TestController extends BaseUIController {

    /**
     * Display list of services
     *  GET /  HTTP/1.1
     * @returns string
     */
    public function getIndex()
    {
        return $this->render('index.html', []);
    }

    /**
     * Test merged search results (Level 4)
     *  GET /test-search-xml  HTTP/1.1
     * @returns string
     */
    public function getTestSearchXml()
    {
        return $this->render('tests/search-xml.html', [
            'uri' => 'search-xml'
        ]);
    }

    /**
     * Test merged search results (Level 8)
     *  GET /test-search-xml-bromley  HTTP/1.1
     * @returns string
     */
    public function getTestSearchXmlBromley()
    {
        return $this->render('tests/search-xml.html', [
            'uri' => 'search-xml-bromley'
        ]);
    }

    /**
     * Test merged search results (Level 4)
     *  GET /test-bromley-sitters  HTTP/1.1
     * @returns string
     */
    public function getTestBromleySitters()
    {
        return $this->render('tests/sitters-bromley.html', [
            'uri' => ServiceAggregator::getServiceUrl('bromley').'/sitters'
        ]);
    }

    /**
     * Test merged search results (Level 8)
     *  GET /test-bromley-sitter-details  HTTP/1.1
     * @returns string
     */
    public function getTestBromleySitterDetails()
    {
        return $this->render('tests/sitter-details-bromley.html', [
            'uri' => ServiceAggregator::getServiceUrl('bromley').'/sitter-details'
        ]);
    }
}
