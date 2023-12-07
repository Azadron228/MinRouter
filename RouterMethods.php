<?php

namespace Min\Router;

use Closure;

trait RouterMethods
{

  public function get(string $path, string|array|Closure $callable): self
  {
    $this->route($path, 'GET', $callable, $this->middlewares);
    return $this;
  }

  public function post(string $path, string|array|Closure $callable): self
  {
    $this->route($path, 'POST', $callable, $this->middlewares);
    return $this;
  }

  public function put(string $path, string|array|Closure $callable): self
  {
    $this->route($path, 'PUT', $callable, $this->middlewares);
    return $this;
  }

  public function delete(string $path, string|array|Closure $callable): self
  {
    $this->route($path, 'DELETE', $callable, $this->middlewares);
    return $this;
  }

  public function patch(string $path, string|array|Closure $callable): self
  {
    $this->route($path, 'PATCH', $callable, $this->middlewares);
    return $this;
  }
}
