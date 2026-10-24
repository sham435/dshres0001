<?php
declare(strict_types=1);

namespace Core;

/**
 * Simple Router
 * 
 * Maps URLs to controller actions with support for
 * GET, POST, PUT, DELETE methods and route parameters.
 */
class Router
{
    private array $routes = [];
    private array $namedRoutes = [];

    public function get(string $path, string $handler, ?string $name = null): self
    {
        return $this->addRoute('GET', $path, $handler, $name);
    }

    public function post(string $path, string $handler, ?string $name = null): self
    {
        return $this->addRoute('POST', $path, $handler, $name);
    }

    public function put(string $path, string $handler, ?string $name = null): self
    {
        return $this->addRoute('PUT', $path, $handler, $name);
    }

    public function delete(string $path, string $handler, ?string $name = null): self
    {
        return $this->addRoute('DELETE', $path, $handler, $name);
    }

    public function any(string $path, string $handler, ?string $name = null): self
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE', 'PATCH'] as $method) {
            $this->addRoute($method, $path, $handler, $name);
        }
        return $this;
    }

    private function addRoute(string $method, string $path, string $handler, ?string $name): self
    {
        $pattern = $this->convertToRegex($path);
        $this->routes[] = [
            'method'  => $method,
            'path'    => $path,
            'pattern' => $pattern,
            'handler' => $handler,
        ];

        if ($name !== null) {
            $this->namedRoutes[$name] = $path;
        }

        return $this;
    }

    private function convertToRegex(string $path): string
    {
        // Convert {param} to named capture groups
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        // 404
        http_response_code(404);
        echo '404 - Page Not Found';
    }

    private function callHandler(string $handler, array $params): void
    {
        [$controllerName, $action] = explode('@', $handler);

        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller not found: {$controllerClass}");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            throw new \RuntimeException("Action not found: {$action}");
        }

        call_user_func_array([$controller, $action], $params);
    }

    public function url(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new \RuntimeException("Named route not found: {$name}");
        }

        $path = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }
        return $path;
    }
}