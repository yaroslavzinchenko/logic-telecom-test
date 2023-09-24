<?php
declare(strict_types=1);

namespace application\core;

use ReflectionMethod;

class Router
{
    protected array $routes = [];
    protected array $params = [];

    public function __construct() {
        $arr = require 'application/config/routes.php';
        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    /**
     * @param string $route
     * @param array $params
     * @return void
     */
    public function add(string $route, array $params): void {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    /**
     * Проверяет, существует ли такой маршрут.
     *
     * @return bool
     */
    public function match(): bool {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Основная функция, которая раскидывает запросы по нужным методам нужных контроллеров.
     *
     * @return void
     */
    public function run(): void {
        if ($this->match()) {
            $path = 'application\\controllers\\' . ucfirst($this->params['controller']) . 'Controller';
            if (class_exists($path)) {
                $method = strtolower($_SERVER['REQUEST_METHOD']);
                $action = $method . ucfirst($this->params['controller']) . ucfirst($this->params['action']);
                if (method_exists($path, $action)) {
                    $reflection = new ReflectionMethod($path, $action);
                    if (!$reflection->isPublic()) {
                        View::errorMessage('Action is not public.', 405);
                    }

                    $controller = new $path($this->params);
                    $controller->$action();
                } else {
                    View::errorMessage('Action does not exist', 404);
                }
            } else {
                View::errorMessage('Class does not exist', 404);
            }
        } else {
            View::errorMessage('Route does not exist', 404);
        }
    }

}