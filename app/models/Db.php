<?php

namespace App\Models;

use PDO;
use Exception;

use Dotenv\Dotenv;

class Db {
    private $db;

    public $test;

    public $dbType;

    public function __construct($test = false) {
        $this->test = $test;
        !$this->db ? $this->connect() : null;
    }

    public function connect() {
        // Load environment variables from .env
        Dotenv::createImmutable(__DIR__.'/../../')->load();

        // Define the expected environment variable names
        $envVariables = ['DB_TYPE', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS'];

        // Extract the environment variables into individual variables
        extract(array_intersect_key($_ENV, array_flip($envVariables)));

        $this->dbType = $this->test ? 'sqlite' : $DB_TYPE;

        $dbName = $this->test ? $DB_NAME . '_test' : $DB_NAME;

        try {
            if ($this->dbType === 'sqlite') {
                $this->test && file_exists($dbName . '.db') ? unlink($dbName . '.db') : null;
                $dsn = "sqlite:$dbName.db";
                $this->db = new PDO($dsn);
            } elseif ($this->dbType === 'mysql') {
                $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$dbName";
                $this->db = new PDO($dsn, $DB_USER, $DB_PASS);
            } else {
                //die("Unsupported database type");
            }
        } catch (Exception $e) {
            echo "Error connecting to database: " . $e->getMessage() . "\n";
            exit();
        }
    }

    public function getConnection() {
        return $this->db;
    }

    public function isMysql() {
        return $this->dbType === 'mysql';
    }

    public function isSqlite() {
        return $this->dbType === 'sqlite';
    }

    private function setAutoIncrement(){
        return $this->isMysql() ? 'AUTO_INCREMENT' : 'AUTOINCREMENT';
    }

    public function createTables() {
        $this->createUsersTable();
        $this->createChatsTable();
        $this->createUserKeysTable();
        $this->createCategoriesTable();
        $this->createPostsTable();
    }

    // Method to create the users table if it doesn't exist
    protected function createUsersTable() {
        $query = '
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY '.$this->setAutoIncrement().',
                    name TEXT NOT NULL,
                    email TEXT NOT NULL,
                    address TEXT,
                    type TEXT,
                    image TEXT,
                    password TEXT NOT NULL,
                    active INTEGER NOT NULL DEFAULT 1,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP '. ($this->isMysql() ? ', UNIQUE (email(255))' : '') .'
                )
            ';
        try {
            $this->db->exec($query);
        } catch (Exception $e) {
            //echo "Error creating users table: ".$e->getMessage()."\n";
        }
    }

    protected function createUserKeysTable() {
        $query = '
            CREATE TABLE IF NOT EXISTS user_keys (
                id INTEGER PRIMARY KEY '.$this->setAutoIncrement().',
                user_id INT UNIQUE,
                private_key TEXT,
                public_key TEXT,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ';
        try {
            $this->db->exec($query);
        } catch (Exception $e) {
            //echo "Error creating user_keys table: ".$e->getMessage()."\n";
        }
    }

    // Additional method to create the chats table if it doesn't exist
    protected function createChatsTable() {
        $query = '
                CREATE TABLE IF NOT EXISTS chats (
                    id INTEGER PRIMARY KEY '.$this->setAutoIncrement().',
                    sender_id INTEGER,
                    receiver_id INTEGER,
                    message TEXT,
                    encrypted_message TEXT,
                    FOREIGN KEY (sender_id) REFERENCES users(id),
                    FOREIGN KEY (receiver_id) REFERENCES users(id)
                )
            ';
        try {
            $this->db->exec($query);
        } catch (Exception $e) {
            //echo "Error creating chats table: ".$e->getMessage()."\n";
        }
    }

    /**
     * Create the posts table if it doesn't exist
     */
    protected function createPostsTable() {
        $query = '
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY '.$this->setAutoIncrement().',
            user_id INTEGER,
            name TEXT,
            price DOUBLE,
            unit TEXT,
            available_quantity INTEGER,
            available BOOLEAN,
            category_id INTEGER,
            location TEXT,
            post_type TEXT,
            image TEXT,
            content LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
        ';

        try {
            $this->db->exec($query);
        } catch (Exception $e) {
            //echo "Error creating posts table: " . $e->getMessage() . "\n";
        }
    }

        /**
     * Create the posts table if it doesn't exist
     */
    protected function createCategoriesTable() {
        $query = '
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY '.$this->setAutoIncrement().',
                name TEXT,
                image TEXT,
                description TEXT
            )
        ';
        try {
            $this->db->exec($query);
        } catch (Exception $e) { 
            //echo "Error creating categories table: " . $e->getMessage() . "\n";
        }
    }

    public function logQuery($query, $params) {
        // Replace placeholders with actual parameter values
        foreach ($params as $key => $value) {
            $query = str_replace($key, $this->getConnection()->quote($value), $query);
        }
    
        // Display or log the final query
       //echo "Executed SQL Query: " . $query . PHP_EOL;
    }

}