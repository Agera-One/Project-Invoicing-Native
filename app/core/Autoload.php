<?php

function load_class($class_name)
{
    $directories = [
        '../app/core/',
        '../app/controllers/',
        '../app/models/',
        '../app/services/',
    ];

    foreach ($directories as $directory) {
        $path_to_file = $directory . $class_name . '.php';

        if (file_exists($path_to_file)) {
            require_once($path_to_file);
            return;
        }
    }
}

spl_autoload_register('load_class');
