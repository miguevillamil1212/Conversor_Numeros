<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../routes/web.php';

// Ajustar la URI para subcarpeta de XAMPP
$basePath = '/Conversor_Numeros/public';
$uri = $_SERVER['REQUEST_URI'];

// Esto "quita" la parte de /Conversor_Numeros/public para que Router lo lea bien
if (str_starts_with($uri, $basePath)) {
    $_SERVER['REQUEST_URI'] = substr($uri, strlen($basePath));
}

// Dispatch
$router = new App\Core\Router();
$router->dispatch();