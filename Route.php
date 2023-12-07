<?php

namespace Min\Router;

use Closure;

class Route
{
  protected string $path;
  public string|array|Closure $callable;
  public array $middleware = [];
  protected string $method;

  public function __construct(
    string $path,
    string $method,
    string|array|Closure $callable,
    array $middleware
  ) {
    $this->path = $path;
    $this->method = $method;
    $this->callable = $callable;
    $this->middleware = $middleware;
  }

  public function getPath(): string
  {
    return $this->path;
  }
  
  public function setPath($path)
  {
    $this->path = $path;
  }

  public function getMethod(): string
  {
    return $this->method;
  }

  public function getCallable(): array|Closure
  {
    return $this->callable;
  }

  public function getMiddleware()
  {
    return $this->middleware;
  }

  public function setMiddleware($middleware)
  {
    $this->middleware = $middleware;
  }
}
