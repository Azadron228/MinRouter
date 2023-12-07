<?php

namespace Min\Router;

use Closure;
use Min\Router\Route;
use Symfony\Component\HttpFoundation\Request;

class Router
{
  use RouterMethods;
  private array $routes = [];
  protected array $middlewares = [];
  protected $router;
  protected string $prefix = '';

  public function route(string $path, string $method, string|array|Closure $callable, array $middleware = []): Route
  {
    $path = '/' . trim($this->prefix . $path, '/');
    $route = new Route($path, $method, $callable, $middleware);
    $this->routes[] = $route;

    return $route;
  }

  public function prefix(string $prefix, Closure $closure)
  {
    $previousPrefix = $this->prefix;
    $this->prefix .= $prefix;

    $closure($this);

    $this->prefix = $previousPrefix;
  }

  public function middleware(array $middleware)
  {
    $currentRoute = end($this->routes);

    if ($currentRoute instanceof Route) {
      $currentRoute->setMiddleware($middleware);
    } else {
      trigger_error('No route to apply middleware to.', E_USER_WARNING);
    }

    return $this;
  }


  public function midddleware(array $middleware)
  {
    $currentRoute = end($this->routes);
    if ($currentRoute !== false) {
      if (is_array($currentRoute)) {
        $currentRoute = (object)$currentRoute;
      }
      $currentRoute->middleware = $middleware;
    }

    return $this;
  }

  public function dispatch()
  {
    $path = $_SERVER['REQUEST_URI'];
    $method = $_SERVER["REQUEST_METHOD"];

    $matchedRoute = $this->findMatchingRoute($path, $method);

    if ($matchedRoute) {
      $this->executeMiddleware($matchedRoute);
      $this->executeCallable($matchedRoute);
    } else {
      echo "404";
    }
  }


  public function findMatchingRoute(string $path, string $method): ?Route
  {
    foreach ($this->routes as $route) {
      if ($this->matches($route, $path, $method)) {
        return $route;
      }
    }

    return null;
  }

  public function matches(Route $route, string $requestPath, string $requestMethod): bool
  {
    return $route->getPath() === $requestPath && $route->getMethod() === $requestMethod;
  }

  public function executeCallable(Route $route)
  {
    $callable = $route->getCallable();
    if (is_array($callable)) {
      $controller = new $callable[0];
      $method = $callable[1];
      $request = new Request();
      $controller->$method($request);
    } elseif (is_callable($callable)) {
      $callable();
    } else {
      echo "Invalid callable format";
    }
  }

  protected function executeMiddleware(Route $route)
  {
    $middlewares = $route->getMiddleware();
    foreach ($middlewares as $middlewareClass) {
      $middleware = new $middlewareClass();
      $middleware->handle();
    }
  }
}
