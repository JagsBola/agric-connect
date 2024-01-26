<?php
namespace App\Models;

use PDO;
use PDOException;
use ParagonIE\ConstantTime\Base64;

class Auth extends Session {
    private $db;
    private $session;

    public function __construct(Db $db) {
        $this->db = $db;
        $this->logoutUser();
        parent::__construct();
    }

    public function checkIfUserExists($email){
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user ? true : false;
    }

    public function registerUser($name, $email, $address, $type, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (name, email, address, type, password) VALUES (?, ?, ?, ?, ?)';
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$name, $email, $address, $type, $hashedPassword]);
            
            // Get the user ID
            $userId = $this->db->getConnection()->lastInsertId();
            
            // Generate and save ECC key pair
            $this->generateAndSaveKeyPair($userId);

            return (new User ($this->db, $userId));

        } catch (PDOException $e) {
           echo "Error executing query: " . $e->getMessage();
        }
    }

    public function loginUser($email, $pswd) {
        $sql = 'SELECT * FROM users WHERE email = ? AND active = 1';
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if($user && password_verify($pswd, ($user->password))) {
            $sessionStatus = $this->setUserSession($user);
            return $sessionStatus ? (new User($this->db, $user->id)) : null;
        } else {
            return null;
        }
    }

    /**
     * Generate and save ECC key pair.
     */
    private function generateAndSaveKeyPair($userId) {
        // Generate ECC key pair
        $keyPair = sodium_crypto_box_keypair();

        // Extract private and public keys
        $privateKey = sodium_crypto_box_secretkey($keyPair);
        $publicKey = sodium_crypto_box_publickey($keyPair);

        // Encode keys using constant-time base64 encoding
        $encodedPrivateKey = Base64::encodeUnpadded($privateKey);
        $encodedPublicKey = Base64::encodeUnpadded($publicKey);

        // Save the keys in the user_keys table
        $queryKeys = 'INSERT INTO user_keys (user_id, private_key, public_key) VALUES (?, ?, ?)';
        $stmtKeys = $this->db->getConnection()->prepare($queryKeys);
        $stmtKeys->execute([$userId, $encodedPrivateKey, $encodedPublicKey]);

        return $keyPair;
    }
}