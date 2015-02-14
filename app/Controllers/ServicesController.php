<?php

namespace COMP1687\CW\Controllers;

use COMP1687\CW\DatabaseManager;
use PDO;
use Respect\Validation\Validator;

/**
 * Sitters service
 */
class ServicesController
{
    /**
     * @var PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = DatabaseManager::getInstance();
    }

    /**
     * GET sitters
     */
    public function getSitters()
    {
        // Prepare query
        $params = [];
        if (!empty($_GET['type'])) {
            // Get sitter accounts by type
            $sitterType = $_GET['type'];
            $query = "SELECT * FROM sitters si INNER JOIN service se ON si.id = se.sitter_id WHERE se.type=?";
            $params[] = $sitterType;
        } else {
            // Get all sitter accounts
            $query = "SELECT * FROM sitters si, INNER JOIN service se ON si.id = se.sitter_id";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $sitters = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return "hello world";
    }

    /**
     * View service details
     * GET post-details
     */
    public function getSitterDetails()
    {
        $sitterID = $_GET['id'];
        // TODO
    }
}
