<?php

namespace App\Models;

use Exception;


class DbSeed {

    private $db;

    public $seeds;

    public function __construct(Db $db){
        $this->db = $db;
        $this->db->createTables();
        $this->seeds = include 'seeds.php';
    }

    public function seedPostCategories(){
        foreach($this->seeds['categories'] as $category){
            $this->LoadPostCategoriesTable($category);
        }
    }

    /**
     * Insert Post category table if it doesn't exist
     */
    protected function LoadPostCategoriesTable($category) {
        $query = '
            INSERT INTO categories (name, image, description) VALUES (? , ? , ?)';
        try {
            $stmt = $this->db->getConnection()->prepare($query);
            return $stmt->execute([$category['name'], $category['image'], $category['description']]);
        } catch (Exception $e) {
            echo "Error creating posts table: " . $e->getMessage() . "\n";
        }
    }

}


