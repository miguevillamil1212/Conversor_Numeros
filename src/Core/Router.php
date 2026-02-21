<?php

namespace App\Core;

class Router
{
    protected static array $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'PATCH'  => [],
        'DELETE' => [],
    ];

    public static function get(string $uri, array $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post(string $uri, array $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    public static function put(string $uri, array $action)
    {
        self::$routes['PUT'][$uri] = $action;
    }

    public static function patch(string $uri, array $action)
    {
        self::$routes['PATCH'][$uri] = $action;
    }

    public static function delete(string $uri, array $action)
    {
        self::$routes['DELETE'][$uri] = $action;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // En Docker el proyecto corre en la raíz, no hay subcarpeta
        // Solo normalizamos la URI
        $uri = '/' . trim($uri, '/');
        if ($uri === '//') $uri = '/';

        foreach (self::$routes[$method] ?? [] as $route => $action) {
            $pattern = preg_replace('#\{[\w]+\}#', '([^/]+)', $route);

            if (preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches);
                return $this->runAction($route, $action, $matches);
            }
        }

        http_response_code(404);
        echo "404 - Ruta no encontrada en: " . $uri;
    }

    protected function runAction(string $route, array $action, array $params)
    {
        [$controllerClass, $method] = $action;

        $controller = new $controllerClass;

        $finalParams = $this->resolveBindings($route, $params);

        return call_user_func_array([$controller, $method], $finalParams);
    }

    protected function resolveBindings(string $route, array $params): array
    {
        preg_match_all('#\{([\w]+)\}#', $route, $keys);

        $resolved = [];

        foreach ($keys[1] as $index => $paramName) {
            $value = $params[$index];

            $modelClass = "App\\models\\" . ucfirst($paramName);

            if (class_exists($modelClass)) {
                $resolved[] = $modelClass::find($value);
            } else {
                $resolved[] = $value;
            }
        }

        return $resolved;
    }
}