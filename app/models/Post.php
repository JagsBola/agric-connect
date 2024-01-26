<?php

namespace App\Models;

use PDO;

class Post {
    private $db;

    public function __construct(Db $db) {
        $this->db = $db;
    }

    public function getMyPosts($userId, $startIndex = 0, $perPage = 10, $sort = 'DESC') {
        $query = 'SELECT * FROM posts WHERE user_id = :user_id ORDER BY id ' . $sort . ' LIMIT :limit OFFSET :offset';
        
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $startIndex, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }
    
    public function getPosts($startIndex = 0, $perPage = 15, $categoryId = null, $searchParam = null) {
        $query = 'SELECT p.*, COALESCE(c.name, NULL) AS category, u.name AS poster_name, u.email AS poster_email
                  FROM posts p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.available = 1 ';
    
        if ($categoryId !== null) {
            $query .= ' AND p.category_id = :categoryId ';
        }
    
        if ($searchParam !== null) {
            $query .= ' AND LOWER(p.name) LIKE LOWER(:searchParam)';
        }
    
        $query .= ' ORDER BY id DESC LIMIT :limit OFFSET :offset';
    
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $startIndex, PDO::PARAM_INT);
    
        if ($categoryId !== null) {
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        }
    
        if ($searchParam !== null) {
            $searchParam = '%' . strtolower($searchParam) . '%';
            $stmt->bindParam(':searchParam', $searchParam, PDO::PARAM_STR);
        }
    
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    
    
    public function save($userId, $name, $content, $details, $available = 1) {
        extract($details);
        $query = 'INSERT INTO posts (user_id, name, image, category_id, content, post_type, unit, price, available_quantity, location, available) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->getConnection()->prepare($query);
        return $stmt->execute([$userId, $name, $image, $category_id, $content, $post_type, $unit, $price, $available_quantity, $location, $available]);
    }

    public function update($id, $userId, $name, $content, $details, $available = 1) {
        extract($details);
        $query = 'UPDATE posts SET name = ?, image = ?, category_id = ?, content = ?, unit = ?, price = ?, available_quantity = ?, location = ?, available = ?
                WHERE id = ? AND user_id = ?';
        
        $stmt = $this->db->getConnection()->prepare($query);
        
        return $stmt->execute([$name, $image, $category_id, $content, $unit, $price, $available_quantity, $location, $available, $id, $userId]);
    }

    public function delete($id, $userId) {
        // Delete post data from the database
        $query = 'DELETE FROM posts WHERE id = ? AND user_id = ?';
        $stmt = $this->db->getConnection()->prepare($query);
        return $stmt->execute([$id, $userId]);
    }

    public function getPost($id) {
        // Fetch post data from the database based on ID
        $query = 'SELECT posts.*, COALESCE(categories.name, NULL) AS category, users.name AS poster_name, users.email AS poster_email
                  FROM posts
                  JOIN users ON posts.user_id = users.id
                  LEFT JOIN categories ON posts.category_id = categories.id
                  
                  WHERE posts.id = ?';

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_BOTH);
    }

    public function getCategories() {
        $query = 'SELECT * FROM categories';
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTotalPostsCount($categoryId = null) {
        $query = 'SELECT COUNT(*) FROM posts';
    
        if ($categoryId !== null) {
            $query .= ' WHERE category_id = :categoryId';
        }
    
        $stmt = $this->db->getConnection()->prepare($query);
    
        if ($categoryId !== null) {
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        }
    
        $stmt->execute();
    
        return $stmt->fetchColumn();
    }    

    public function getMyTotalPostsCount($userId) {
        $query = 'SELECT COUNT(*) FROM posts WHERE user_id = ?';
    
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute([$userId]);
    
        return $stmt->fetchColumn();
    }
}