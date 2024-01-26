<?php

namespace App\Models;

class Session {

    public function __construct() {
        $this->startSession();
    }

    public function startSession() {
        $this->isSessionStarted() ? null : session_start();
    }

    public function isSessionStarted() {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    public function setUserSession($user) {
        if($this->isSessionStarted()) {
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_type'] = $user->type;
            return true;
        }
        
        return false;
    }

    public function isAuthenticated() {
        return $this->isSessionStarted() ? (isset($_SESSION['user'])) : false;
    }

    public function getUserId() {
        return $this->isAuthenticated() ? $_SESSION['user_id'] : null;
    }

    public function getUserType() {
        // Implement this logic based on your application requirements
        return $this->isAuthenticated() ? $_SESSION['user_type'] : null;
    }

    public function amIthisUser($userId) {
        return $this->isAuthenticated() && $this->getUserId() === $userId;
    }

    public function logoutUser() {
        session_unset();
        $this->isSessionStarted() ? session_destroy() : null;
        return true;
    }
}