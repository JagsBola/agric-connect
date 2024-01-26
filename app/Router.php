<?php

namespace App;

class Router {
    private $routes = [];

    public function addRoute($url, $controller) {
        $this->routes[$url] = $controller;
    }

    public function route($url) {
        $urlParts = parse_url($url);

        // Extract the path and query parameters
        $path = $urlParts['path'];
        $query = $urlParts['query'] ?? '';

        // Check if the path is a defined route
        if (isset($this->routes[$path])) {
            $controller = $this->routes[$path];

            // Pass the query parameters to the controller
            $this->executeController($controller, $query);
        } else {
            // Check if the path is equivalent to '/index.php'
            if ($path === '/' && isset($this->routes['/index.php'])) {
                $controller = $this->routes['/index.php'];
                $this->executeController($controller, $query);
            } else {
                // Serve static files (images, CSS, JS)
                $this->serveStaticFiles($path);
            }
        }
    }

    
    private function executeController($controller, $query) {
        if (is_callable($controller)) {
            // If it's a closure, call it
            call_user_func($controller, $query);
        } elseif (is_string($controller) && strpos($controller, '@') !== false) {
            // If it's a string in the format "Controller@method"
            list($className, $method) = explode('@', $controller, 2);

            if (class_exists($className) && method_exists($className, $method)) {
                $instance = new $className();

                // Call the controller method with named arguments
                $this->callControllerMethod($instance, $method, $query);
            } else {
                $this->pageNotFound();
            }
        } else {
            $this->pageNotFound();
        }
    }

    private function serveStaticFiles($path) {
        $publicPath = __DIR__ . '/public/';
        $filePath = $publicPath . $path;

        if (file_exists($filePath)) {
            // Determine the MIME type
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'css' => 'text/css',
                'js' => 'application/javascript',
            ];

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';

            // Set the appropriate content type header
            header("Content-Type: $contentType");

            // Send the file content
            readfile($filePath);
            exit();
        } else {
            $this->pageNotFound();
        }
    }

    private function callControllerMethod($instance, $method, $query) {
        // Parse the query string into an associative array
        parse_str($query, $queryParams);

        // Call the controller method with named arguments
        $instance->$method(...$queryParams);
    }

    private function pageNotFound() {
        header("HTTP/1.0 404 Not Found");
        echo "404 Page Not Found";
        exit();
    }
}
