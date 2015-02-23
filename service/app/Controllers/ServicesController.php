<?php

namespace COMP1688\CW\Controllers;

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
     * Create new instance of Services Controller
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
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

        try {
            // Retrieve sitters
            $params = [];
            $query = 'SELECT se.id, se.type, se.charges, se.location, si.first_name, si.first_name, si.last_name
                      FROM service se
                      INNER JOIN sitter si ON si.id = se.sitter_id';
            if (!empty($_REQUEST['type'])) {
                // Get sitter accounts by type
                $sitterType = $_REQUEST['type'];
                $query .= ' WHERE se.type LIKE ?';
                $params[] = '%'.$sitterType.'%';
            }

            $stmtSe = $this->db->prepare($query);
            $stmtSe->execute($params);

            while ($service = $stmtSe->fetch(PDO::FETCH_ASSOC)) {
                $xmlSitter = $xmlDom->createElement('sitter');
                // ID
                $xmlId = $xmlDom->CreateAttribute('id');
                $xmlId->value = $service['id'];
                $xmlSitter->appendChild($xmlId);
                // Service
                $xmlServ = $xmlDom->CreateAttribute('service');
                $xmlServ->value = 'greenwich';
                $xmlSitter->appendChild($xmlServ);
                // Name
                $xmlName = $xmlDom->createElement('name');
                $xmlSitter->appendChild($xmlName);
                // First Name
                $xmlFname = $xmlDom->createElement('firstname', $service['first_name']);
                $xmlName->appendChild($xmlFname);
                // Last Name
                $xmlLname = $xmlDom->createElement('lastname', $service['last_name']);
                $xmlName->appendChild($xmlLname);
                // Service
                $xmlService = $xmlDom->createElement('service');
                $xmlSitter->appendChild($xmlService);
                // Service type
                $xmlStype = $xmlDom->createElement('type', $service['type']);
                $xmlService->appendChild($xmlStype);
                // Service charges
                $xmlScharges = $xmlDom->createElement('charges', $service['charges']);
                $xmlService->appendChild($xmlScharges);
                // Service location
                $xmlSloc = $xmlDom->createElement('location', $service['location']);
                $xmlService->appendChild($xmlSloc);

                $xmlRoot->appendChild($xmlSitter);
            }

            return $xmlDom->saveXML();
        } catch (\Exception $e) {
            $xmlError = $xmlDom->createElement('error_message', $e->getMessage());
            $xmlRoot->appendChild($xmlError);
            return $xmlDom->saveXML();
        }
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

        try {
            // Validation of ID param
            if (!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
                $xmlError = $xmlDom->createElement('error_message', 'ID parameter not provided');
                $xmlRoot->appendChild($xmlError);
                return $xmlDom->saveXML();
            } else if (!is_numeric($_REQUEST['id'])) {
                $xmlError = $xmlDom->createElement('error_message', 'ID parameter must be numeric');
                $xmlRoot->appendChild($xmlError);
                return $xmlDom->saveXML();
            }

            // Retrieve sitters
            $sitterId = $_REQUEST['id'];
            $query = 'SELECT se.id, se.type, se.location, se.availability, se.description, se.charges, si.first_name, si.last_name, si.email, si.phone
                      FROM service se
                      INNER JOIN sitter si ON se.sitter_id = si.id
                      WHERE se.id = :id
                      LIMIT 1';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $sitterId, PDO::PARAM_INT);
            $stmt->execute();
            $sitter = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$sitter) {
                $xmlError = $xmlDom->createElement('error_message', 'Record with provided ID does not exist');
                $xmlRoot->appendChild($xmlError);
                return $xmlDom->saveXML();
            }

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
            $xmlServ = $xmlDom->createElement('service');
            $xmlRoot->appendChild($xmlServ);
            // Type
            $xmlStype = $xmlDom->createElement('type', $sitter['type']);
            $xmlServ->appendChild($xmlStype);
            // Location
            $xmlLoc = $xmlDom->createElement('location', $sitter['location']);
            $xmlServ->appendChild($xmlLoc);
            // Availability
            $xmlAvail = $xmlDom->createElement('availability', $sitter['availability']);
            $xmlServ->appendChild($xmlAvail);
            // Description
            $xmlDesc = $xmlDom->createElement('description', $sitter['description']);
            $xmlServ->appendChild($xmlDesc);
            // Charges
            $xmlCharg = $xmlDom->createElement('charges', $sitter['charges']);
            $xmlServ->appendChild($xmlCharg);
            // Images
            $xmlImg = $xmlDom->createElement('images');
            $xmlServ->appendChild($xmlImg);

            // Retrieve images
            $query = 'SELECT * FROM image WHERE service_id = :serviceId';
            $stmtImg = $this->db->prepare($query);
            $stmtImg->bindParam(':serviceId', $sitter['id'], PDO::PARAM_INT);
            $stmtImg->execute();

            while ($image = $stmtImg->fetch(PDO::FETCH_ASSOC)) {
                // Image
                $xmlImage = $xmlDom->createElement('image');
                $xmlImg->appendChild($xmlImage);
                // URL
                $xmlImgUrl = $xmlDom->createElement('image_url', $image['code']);
                $xmlImage->appendChild($xmlImgUrl);
            }

            return $xmlDom->saveXML();
        } catch (\Exception $e) {
            $xmlError = $xmlDom->createElement('error_message', $e->getMessage());
            $xmlRoot->appendChild($xmlError);
            return $xmlDom->saveXML();
        }
    }
}
