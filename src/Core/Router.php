<?php
namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $action, array $middlewares = []): void
    {
        $this->addRoute('GET', $uri, $action, $middlewares);
    }

    public function post(string $uri, string $action, array $middlewares = []): void
    {
        $this->addRoute('POST', $uri, $action, $middlewares);
    }

    private function addRoute(string $method, string $uri, string $action, array $middlewares): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                // Запуск middleware
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareClass = "Middleware\\" . ucfirst($middleware) . "Middleware";
                    if (class_exists($middlewareClass)) {
                        (new $middlewareClass())->handle();
                    }
                }

                // Вызов контроллера
                $this->callAction($route['action']);
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
        exit;
    }

    private function callAction(string $action): void
    {
        [$controllerName, $method] = explode('@', $action);
        $controllerClass = "Controllers\\$controllerName";
        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "Controller $controllerClass not found";
            exit;
        }
        $controller = new $controllerClass();
        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "Method $method not found in $controllerClass";
            exit;
        }
        $controller->$method(new Request());
    }
}