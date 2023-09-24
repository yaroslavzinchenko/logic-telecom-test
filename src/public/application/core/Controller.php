<?php
declare(strict_types=1);

namespace application\core;

abstract class Controller
{
    public array $route;
    public $view;
    public $model;
    public $authRules;

    public function __construct(array $route) {
        $this->route = $route;

        $auth = $this->loadAuth();
        if (empty($auth)) {
            View::errorMessage('Unable to load auth file on line ' . __LINE__, 500);
        }
        if (!$this->isAuthorized()) {
            View::errorMessage('Forbidden on line ' . __LINE__, 403);
        }

        $this->view = new View();
        $this->model = $this->loadModel($route['controller']);
        if (empty($this->model)) {
            View::errorMessage('Unable to load model file on line ' . __LINE__, 500);
        }
    }

    /**
     * Возвращает объёкт модели.
     *
     * @param string $name
     * @return mixed|string
     */
    private function loadModel(string $name): mixed
    {
        $path = 'application\\models\\' . ucfirst($name);
        if (class_exists($path)) {
            return new $path;
        }

        return '';
    }

    /**
     * Возвращает путь к файлу с авторизационными данными.
     *
     * @return string
     */
    private function loadAuth(): string {
        $path = 'application\\auth\\auth.php';
        $path = str_replace('\\', '/', $path);
        if (file_exists($path)) {
            return $path;
        }
        return '';
    }

    /**
     * Проверяет авторизацию по токену.
     *
     * @return bool
     */
    private function isAuthorized(): bool {
        if (!isset($_POST['token'])) {
            View::errorMessage('Forbidden on line ' . __LINE__, 403);
        }
        $token = $_POST['token'];

        $authTokens = require 'application/auth/auth.php';
        if (!isset($authTokens[$token])) {
            return false;
        }
        $this->authRules = $authTokens[$token];
        return true;
    }
}