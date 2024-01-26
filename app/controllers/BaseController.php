<?php

use App\Models\Db;
use App\Models\User;
use App\Models\Utility;


class BaseController {
    
    protected $db;
    protected $utility;

    public $flashSession = [];

    /**
     * Start the session
     */
    public function __construct() {
        session_start();
        $this->db = new Db();  
        $this->utility = new Utility($this->db);
    }

    /**
     * Get the value of a GET request parameter
     */
    public function get($name) {
        return $_GET[$name] ?? null;
    }

    /**
     * Get the value of a POST request parameter
     */
    public function post($name) {
        return $_POST[$name] ?? null;
    }

    /**
     * Get the value of a FILES request parameter
     */
    public function files($name) {
        return $_FILES[$name] ?? null;
    }

    /**
     * Check if the request is a POST request
     */
    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    /**
     * Redirect to a given URL
     */
    protected function redirectTo($url, $data = []) {
        $queryString = http_build_query($data);
        $separator = (parse_url($url, PHP_URL_QUERY) == null) ? '?' : '&';
        $redirectUrl = $url . $separator . $queryString;
    
        header("Location: {$redirectUrl}");
        exit();
    }
    
    /**
     * Render a view
     */
    protected function renderView($viewName, $data = []) {
        extract($data);
        $auth = new User(new Db()); //authenticated user
        include "app/helpers.php";
        $flashSession = $this->flashSession;
        include "app/views/{$viewName}.php";
    }

    public function checkAuth() {
        if(!isset($_SESSION['user_id'])) {
            $this->redirectTo('/login');
        }
    }

    public function checkAdminAuth() {
        $this->checkAuth();
        if(strtolower($_SESSION['user_type']) != 'admin'){
            $this->redirectTo('/login');
        }
    }

    /**
     * Add a value to the flash session
     */
    protected function addToFlashSession($key, $value) {
        $this->flashSession[$key] = $value;
    }

    protected function handleImageUpload($file, $path = 'images') {
        $imageTmpName = $file['tmp_name'];
        $imageExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $imageName = uniqid().'.'.$imageExtension;
        $imagePath = $path.$imageName;
        move_uploaded_file($imageTmpName, $imagePath);

        return $imagePath;
    }
    /**
     * Dump and die
     */
    protected function dd(...$values) {
        echo '<pre>';
    
        foreach ($values as $value) {
            print_r($value);
            echo PHP_EOL;
        }
    
        echo '</pre>';
        die;
    }
    

}
