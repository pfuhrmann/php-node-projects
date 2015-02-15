<?php

namespace COMP1688\CW\Controllers;

use COMP1688\CW\DatabaseManager;
use DOMDocument;
use PDO;

/**
 * Sitters Web Service (REST XML)
 */
class ServicesController
{
    /**
     * @var PDO Database instance
     */
    private $db;

    /**
     * Create new instance of ServicesController
     */
    public function __construct()
    {
        $this->db = DatabaseManager::getInstance();
    }

    /**
     * Return valid, well-formed XML of all sitter accounts
     *  GET /sitters?type=string  HTTP/1.1
     *  POST /sitters type=string  HTTP/1.1
     * @returns string
     */
    public function anySitters()
    {
        header('Content-type: text/xml');
        // Prepare DOM document
        $xmlDom = new DOMDocument();
        $xmlDom->appendChild($xmlDom->createElement('sitters'));
        $xmlRoot = $xmlDom->documentElement;

        // Retrieve sitters
        $params = [];
        if (!empty($_GET['type'])) {
            // Get sitter accounts by type
            $sitterType = $_GET['type'];
            $query = 'SELECT si.* FROM sitter si INNER JOIN service se ON si.id = se.sitter_id WHERE se.type LIKE ? GROUP BY si.id';
            $params[] = '%'.$sitterType.'%';
        } else {
            // Get all sitter accounts
            $query = 'SELECT * FROM sitter si';
        }
        $stmtSi = $this->db->prepare($query);
        $stmtSi->execute($params);

        while ($sitter = $stmtSi->fetch(PDO::FETCH_ASSOC)) {
            $xmlSitter = $xmlDom->createElement('sitter');
            // ID
            $xmlId = $xmlDom->CreateAttribute('id');
            $xmlId->value = $sitter['id'];
            $xmlSitter->appendChild($xmlId);
            // Name
            $xmlName = $xmlDom->createElement('name');
            $xmlSitter->appendChild($xmlName);
            // First Name
            $xmlFname = $xmlDom->createElement('firstname', $sitter['first_name']);
            $xmlName->appendChild($xmlFname);
            // Last Name
            $xmlLname = $xmlDom->createElement('lastname', $sitter['last_name']);
            $xmlName->appendChild($xmlLname);
            // Email
            $xmlEmail = $xmlDom->createElement('email', $sitter['email']);
            $xmlSitter->appendChild($xmlEmail);
            // Phone
            $xmlPhone = $xmlDom->createElement('phone', $sitter['phone']);
            $xmlSitter->appendChild($xmlPhone);
            // Services
            $xmlServices = $xmlDom->createElement('services');
            $xmlSitter->appendChild($xmlServices);

            // Retrieve services
            $query = 'SELECT * FROM service WHERE sitter_id = ?';
            $stmtSe = $this->db->prepare($query);
            $stmtSe->execute([$sitter['id']]);

            while ($service = $stmtSe->fetch(PDO::FETCH_ASSOC)) {
                // Service type
                $xmlStype = $xmlDom->createElement('service_type', $service['type']);
                $xmlServices->appendChild($xmlStype);
            }

            $xmlRoot->appendChild($xmlSitter);
        }

        return $xmlDom->saveXML();
    }

    /**
     * Return valid, well-formed XML for one sitter matched by ID
     *  GET /sitter-details?id=int  HTTP/1.1
     *  POST /sitter-details id=int  HTTP/1.1
     * @returns string
     */
    public function anySitterDetail()
    {
        header('Content-type: text/xml');
        // Prepare DOM document
        $xmlDom = new DOMDocument();
        $xmlDom->appendChild($xmlDom->createElement('sitter_detail'));
        $xmlRoot = $xmlDom->documentElement;

        // Validation of ID param
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $xmlError = $xmlDom->createElement('error_message', 'ID parameter not provided');
            $xmlRoot->appendChild($xmlError);
            return $xmlDom->saveXML();
        } else if (!is_numeric($_GET['id'])) {
            $xmlError = $xmlDom->createElement('error_message', 'ID parameter must be numeric');
            $xmlRoot->appendChild($xmlError);
            return $xmlDom->saveXML();
        }

        // Retrieve sitters
        $sitterId = $_GET['id'];
        $query = 'SELECT * FROM sitter WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $sitterId, PDO::PARAM_INT);
        $stmt->execute();
        $sitter = $stmt->fetch(PDO::FETCH_ASSOC);

        // ID
        $xmlId = $xmlDom->CreateAttribute('id');
        $xmlId->value = $sitter['id'];
        $xmlRoot->appendChild($xmlId);
        // Name
        $xmlName = $xmlDom->createElement('name');
        $xmlRoot->appendChild($xmlName);
        // First Name
        $xmlFname = $xmlDom->createElement('firstname', $sitter['first_name']);
        $xmlName->appendChild($xmlFname);
        // Last Name
        $xmlLname = $xmlDom->createElement('lastname', $sitter['last_name']);
        $xmlName->appendChild($xmlLname);
        // Email
        $xmlEmail = $xmlDom->createElement('email', $sitter['email']);
        $xmlRoot->appendChild($xmlEmail);
        // Phone
        $xmlPhone = $xmlDom->createElement('phone', $sitter['phone']);
        $xmlRoot->appendChild($xmlPhone);
        // Services
        $xmlServ = $xmlDom->createElement('services');
        $xmlRoot->appendChild($xmlServ);

        // Retrieve services
        $query = 'SELECT * FROM service WHERE sitter_id = :sitterId';
        $stmtSe = $this->db->prepare($query);
        $stmtSe->bindParam(':sitterId', $sitter['id'], PDO::PARAM_INT);
        $stmtSe->execute();

        while ($service = $stmtSe->fetch(PDO::FETCH_ASSOC)) {
            // Service
            $xmlService = $xmlDom->createElement('service');
            $xmlServ->appendChild($xmlService);
            // Type
            $xmlStype = $xmlDom->createElement('type', $service['type']);
            $xmlService->appendChild($xmlStype);
            // Location
            $xmlLoc = $xmlDom->createElement('location', $service['location']);
            $xmlService->appendChild($xmlLoc);
            // Availability
            $xmlAvail = $xmlDom->createElement('availability', $service['availability']);
            $xmlService->appendChild($xmlAvail);
            // Description
            $xmlDesc = $xmlDom->createElement('description', $service['description']);
            $xmlService->appendChild($xmlDesc);
            // Charges
            $xmlCharg = $xmlDom->createElement('charges', $service['charges']);
            $xmlService->appendChild($xmlCharg);
            // Images
            $xmlImg = $xmlDom->createElement('images');
            $xmlService->appendChild($xmlImg);

            // Retrieve images
            $query = 'SELECT * FROM image WHERE service_id = :serviceId';
            $stmtImg = $this->db->prepare($query);
            $stmtImg->bindParam(':serviceId', $service['id'], PDO::PARAM_INT);
            $stmtImg->execute();

            while ($image = $stmtImg->fetch(PDO::FETCH_ASSOC)) {
                // URL
                $xmlImgUrl = $xmlDom->createElement('image_url', $image['code']);
                $xmlImg->appendChild($xmlImgUrl);
            }
        }

        return $xmlDom->saveXML();
    }
}
