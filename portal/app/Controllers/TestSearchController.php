<?php

namespace COMP1688\CW\Controllers;

class TestSearchController extends BaseUIController {

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
        return $this->render('tests/search-xml.html', []);
    }
}
