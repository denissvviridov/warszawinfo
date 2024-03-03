<?php

global $routes;

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if ($uri == '') {
    $array = explode('|', $routes['home']);
} else {
    $section = explode('/', $uri);

    if (count($section) == 3) {
        $uri = $section[0].'/'.$section[1].'/.*';

        define('ARGUMENT',$section[2]);
    }

    if (isset($routes[$uri])) {
        $array = explode('|', $routes[$uri]);
    } else {
        $array = explode('|', $routes['error']);
    }
}

$file = 'controllers/'.$array[0].'.php';
$class = $array[0];
$method = $array[1];

if (file_exists($file)) {
    require $file;
    if (class_exists($class) && method_exists($class, $method)) {
        $n = new $class;
        $n->$method();
    } else {
        echo 'Class ot method not found';
    }
} else {
    echo 'File not found';
}
