<?php

namespace COMP1688\CW\Controllers;

class SearchPortalController extends BaseUIController {

    /**
     * Search from services and return XML
     *  GET /search-xml  HTTP/1.1
     * @returns string
     */
    public function anySearchXml()
    {
        return $this->render('index.html', []);
    }
}
