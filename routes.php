<?php

# routes.php

$controllers = array(
    'pages' => ['home', 'about', 'search', 'shop', 'detail', 'cart', 'checkout', 'orders', 'contact', 'error'],
    'adminPages' => [
        'home', 'manageAuthor', 'manageGenre', 'managePublisher', 'manageBook', 'manageBookDetail', 'manageOrders'
    ],
    'log' => ['login', 'register', 'logout']
);



if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
    $controller = 'pages';
    $action = 'error';
}



include_once('controllers/' . $controller . '_controller.php');


$klass = str_replace('_', '', ucwords($controller, '_')) . 'Controller';
$controller = new $klass;
$controller->$action();
