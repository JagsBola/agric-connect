<?php

namespace App\Models;

use PDO;

class Utility {

    private $db;

    public function __construct(Db $db) {
        $this->db = $db;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM users ORDER BY id DESC";
    
        // Prepare and execute the main query
        $statement =  $this->db->getConnection()->prepare($query);
        $statement->execute();
    
        return $statement->fetchAll(PDO::FETCH_OBJ);    
    }

}