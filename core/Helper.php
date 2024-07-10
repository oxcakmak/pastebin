<?php

/*
* Title: Helper Loader Class
* Description: Helper is a method class that imports the functions in the folder you specify and helps you access them easily.
* Author: Osman ÇAKMAK (info@oxcakmak.com | oxcakmak@hotmail.com)
* Version: 1.0.0
*/

class Helper
{
    private $instances = [];
    private $directories = [];

    public function __construct($directories)
    {
        // Convert a single directory string into an array
        $this->directories = is_array($directories) ? $directories : [$directories];
        $this->loadClasses();
    }

    private function loadClasses(): void
    {
        foreach ($this->directories as $directory) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if (in_array($file, ['.', '..'])) {
                    // Skip . and .. directories
                    continue;
                }

                $filePath = $directory . '/' . $file;
                if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
                    $className = pathinfo($filePath, PATHINFO_FILENAME);
                    require_once $filePath;
                    $this->instances[$className] = new $className();
                }
            }
        }
    }

    public function __get($property)
    {
        if (isset($this->instances[$property])) {
            return $this->instances[$property];
        }

        // Check for nested properties
        $parts = explode('.', $property);
        $className = array_shift($parts); // Get the first part (class name)

        if (isset($this->instances[$className])) {
            $instance = $this->instances[$className];
            // Recursively call __get on the instance for nested properties
            return call_user_func_array([$instance, '__get'], $parts);
        }

        throw new Exception("Property '$property' does not exist in the Hepa class.");
    }
}

?>
