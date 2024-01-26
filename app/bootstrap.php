<?php

// Autoloader for Composer dependencies
// require_once '../vendor/autoload.php';

// Your additional bootstrap code goes here
// For example, you might set up a database connection, configure your application, etc.

// Autoloader for your application classes (assuming PSR-4 autoloading)
spl_autoload_register(function ($class) {
    // Define the base directory where your namespace starts
    $baseDir = __DIR__ . '/';

    // Replace namespace separator with directory separator, and add '.php' to create the full path
    $classPath = str_replace('\\', '/', $class) . '.php';

    // Combine the base directory and the class path
    $filePath = $baseDir .'controllers/'. $classPath;
 
    // Check if the file exists before requiring it
    if (file_exists($filePath)) {
        require_once $filePath;
    }
    
});

