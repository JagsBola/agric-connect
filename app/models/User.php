<?php

namespace App\Models;

use PDO, App\Models\Post;

use ParagonIE\ConstantTime\Base64;

class User extends Session {
    private $db;
    private $post;

    public $id;
    public $name;
    public $email;
    public $type;

    public $address;

    public $active;

    public $image;

    public $created_at;

    public $publicKey;
    private $privateKey;


    /**
     * User class constructor.
     *
     * @param Db   $db The database connection instance.
     * @param null $id The user ID (optional).
     */
    public function __construct(Db $db, $id = null) {
        $this->db = $db;
        $this->post = new Post($db);
        $id = $id ?? $this->getUserId();
        $id ? $this->fetchUserDetails($id) : false;
    }

    /**
     * Fetch user details from the database based on user ID.
     *
     * @param int $userId The ID of the user.
     */
    private function fetchUserDetails($userId) {
        $query = "SELECT u.id, name, email, type, address, image, active, created_at, private_key, public_key
                FROM users u
                LEFT JOIN user_keys uk ON u.id = uk.user_id
                WHERE u.id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        $user ? $this->setUserProperties(get_object_vars($user)) : null;
    }

    /**
     * Set user properties based on the retrieved database values.
     *
     * @param array $properties An array of user properties.
     */
    private function setUserProperties($properties) {
        $excluded = ['private_key', 'public_key'];
        $this->publicKey = $properties['public_key'];
        $included = array_diff_key($properties, array_flip($excluded));
        array_map(fn($key, $value) => $this->$key = $value, array_keys($included), $included);
        $this->privateKey = $this->getUserId() === $this->id ? $properties['private_key'] : null;
    }

    /**
     * Get the public key of the user.
     *
     * @return string The user's public key.
     */
    public function getPublicKey() {
        return $this->publicKey;
    }

    /**
     * Get the private key of the user (if authenticated and requesting own private key).
     *
     * @return string|null The user's private key or null if not authenticated.
     */
    public function getPrivateKey() {
        // Check if the user is authenticated and requesting their own private key
        return $this->getUserId() == $this->id ? $this->privateKey : null;
    }

    /**
     * Update the user's profile.
     */
    public function updateProfile($name, $address, $image) {
        $query = 'UPDATE users SET name = ?, address = ?, image = ? WHERE id = ?';
        $stmt = $this->db->getConnection()->prepare($query);
        $status = $stmt->execute([$name, $address, $image, $this->id]);

        $status ? $this->fetchUserDetails($this->id) : null;

        return $status;
    }

    /**
     * Send an encrypted message to another user.
     *
     * @param User   $receiver The receiving user.
     * @param string $message  The message to be sent.
     *
     * @return string|null The encrypted message or null on failure.
     */
    public function sendMessage(User $receiver, $message) {
        $receiverPublicKey = $receiver->getPublicKey();

        $encryptedMessage = $this->encryptMessage($receiverPublicKey, $message);

        // Insert the encrypted message into the chats table
        $query = 'INSERT INTO chats (sender_id, receiver_id, encrypted_message) VALUES (?, ?, ?)';
        $stmt = $this->db->getConnection()->prepare($query);

        return $stmt->execute([$this->id, $receiver->id, $encryptedMessage]) ? $encryptedMessage : null;
    }

    /**
     * Get chat messages between the current user and another user.
     *
     * @param User|null $party The other user.
     *
     * @return array An array of chat messages.
     */
    public function getChats(User $party = null) {
        // Retrieve chats for the given user ID
        $query = 'SELECT id, sender_id, receiver_id, encrypted_message, created_at
        FROM chats
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY id ASC';
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$this->id, $party->id, $party->id, $this->id]);

        $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($chats as &$chat) {
            $chat['party_id'] = $chat['sender_id'] == $this->id ? $chat['receiver_id'] : $chat['sender_id'];
            $chat['decrypted_message'] = $this->decryptMessage($party->getPublicKey(), $chat['encrypted_message']);
        }

        return $chats;
    }

    /**
     * Get the last decrypted messages for the current user.
     *
     * @param int $limit The maximum number of messages to retrieve.
     *
     * @return array An array of decrypted messages.
     */
    public function getLastMessages($limit = 20) {
        $messages = [];
        $query = 'SELECT id, name, image, type, encrypted_message, sender_id, receiver_id, public_key
        FROM (
            SELECT c.id, u.name, u.image, u.type, c.encrypted_message, c.sender_id, c.receiver_id, uk.public_key,
                   ROW_NUMBER() OVER (PARTITION BY CASE WHEN c.sender_id = :user_id THEN c.receiver_id ELSE c.sender_id END
                                     ORDER BY c.id DESC) AS rn
            FROM chats c
            JOIN users u ON ((c.sender_id != :user_id AND c.sender_id = u.id) OR (c.receiver_id != :user_id AND c.receiver_id = u.id))
            JOIN user_keys uk ON (c.sender_id = uk.user_id OR c.receiver_id = uk.user_id)
            WHERE (c.sender_id = :user_id OR c.receiver_id = :user_id)
        ) AS ranked

        WHERE rn = 1
        ORDER BY id DESC
        LIMIT :limit';

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        while($messageData = $stmt->fetch(PDO::FETCH_OBJ)) {
            $senderPublicKey = $messageData->public_key;
            $encryptedMessage = $messageData->encrypted_message;

            // Decrypt the message using the sender's public key
            $decryptedMessage = $this->decryptMessage($senderPublicKey, $encryptedMessage);

            $messages[] = [
                'id' => $messageData->id,
                'name' => $messageData->name,
                'user_type' => $messageData->type,
                'party_image' => $messageData->image,
                'sender_id' => $messageData->sender_id,
                'receiver_id' => $messageData->receiver_id,
                'decrypted_message' => $decryptedMessage,

                'party_id' => $messageData->sender_id == $this->id ? $messageData->receiver_id : $messageData->sender_id
            ];
        }

        return $messages;
    }


    /**
     * Get the Last Chat Party Id
     */
    public function getLastChatPartyId($chats) {
        $chat = reset($chats);
        return $chat['sender_id'] == $this->id ? $chat['receiver_id'] : $chat['sender_id'];
    }

    /**
     * Encrypt a message using the party's public key.
     *
     * @param string $partyPublicKey The public key of the receiving party.
     * @param string $message        The message to be encrypted.
     *
     * @return string The encrypted message.
     */
    public function encryptMessage($partyPublicKey, $message) {
        // Load the receiver's public key
        $receiverPublicKey = Base64::decodeNoPadding($partyPublicKey);

        // Load the sender's private key
        $senderPrivateKey = Base64::decodeNoPadding($this->getPrivateKey());

        // Generate a shared key for encryption using ECDH
        $sharedKey = sodium_crypto_box_keypair_from_secretkey_and_publickey($senderPrivateKey, $receiverPublicKey);

        // Encrypt the message
        $nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);

        $ciphertext = sodium_crypto_box($message, $nonce, $sharedKey);

        // Combine the nonce and ciphertext for storage
        $encryptedMessage = $nonce.$ciphertext;

        return Base64::encodeUnpadded($encryptedMessage);
    }

    /**
     * Decrypt a message using the party's public key.
     *
     * @param string $partyPublicKey    The public key of the sending party.
     * @param string $encryptedMessage  The encrypted message to be decrypted.
     *
     * @return string The decrypted message.
     */
    public function decryptMessage($partyPublicKey, $encryptedMessage) {
        // Decode the base64 encoded message
        $decodedMessage = Base64::decodeNoPadding($encryptedMessage);

        // Load the sender's public key
        $partyPublicKey = Base64::decodeNoPadding($partyPublicKey);

        // Load the receiver's private key
        $receiverPrivateKey = Base64::decodeNoPadding($this->getPrivateKey());

        // Generate a shared key for encryption using ECDH
        $sharedKey = sodium_crypto_box_keypair_from_secretkey_and_publickey($receiverPrivateKey, $partyPublicKey);

        // Extract the nonce from the message
        $nonce = substr($decodedMessage, 0, SODIUM_CRYPTO_BOX_NONCEBYTES);

        // Extract the ciphertext from the message
        $ciphertext = substr($decodedMessage, SODIUM_CRYPTO_BOX_NONCEBYTES);

        // Decrypt the message
        $decryptedMessage = sodium_crypto_box_open($ciphertext, $nonce, $sharedKey);


        return $decryptedMessage;
    }


    /**
     * Get posts created by the current user.
     * @return array An array of posts created by the user.
     */
    public function getMyPosts($startIndex = 0, $perPage = 10, $sort = 'DESC') {
        return $this->post->getMyPosts($this->id, $startIndex = 0, $perPage = 10, $sort = 'DESC');
    }

    /**
     * Get the total number of posts created by the current user.
     */
    public function getMyTotalPostsCount() {
        return $this->post->getMyTotalPostsCount($this->id);
    }

    /**
     * Create a new post for the user.
     * @param string $content The content of the post.
     */
    public function createPost($name, $content, $details = []) {
        $details['post_type'] = $this->type;
        return $this->post->save($this->id, $name, $content, $details);
    }

    /**
     * Get a specific post by its ID.
     * @param int $postId The ID of the post to retrieve.
     * @return array|null An array representing the post or null if not found.
     */
    public function getPost($postId) {
        return $this->post->getPost($postId);
    }

    /**
     * Create a new post for the user.
     * @param string $content The content of the post.
     */
    public function updatePost($id, $name, $content, $details = []) {
        $details['post_type'] = $this->type;
        return $this->post->update($id, $this->id, $name, $content, $details);
    }

    /**
     * Delete a post by its ID.
     * @param int $postId The ID of the post to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deletePost($postId) {
        return $this->post->delete($postId, $this->id);
    }

    public function getCategories() {
        return $this->post->getCategories();
    }

    public function checkIsSeller() {
        $type = strtolower($this->type);
        return ($type == 'seller' || $type == 'farmer');
    }

    public function checkIsBuyer() {
        return strtolower($this->type) == 'buyer';
    }

    public function deactivate() {
        $query = "UPDATE users SET active = 0 WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function activate() {
        $query = "UPDATE users SET active = 1 WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function isAdmin(){
        return strtolower($this->type) == 'admin';
    }
}
