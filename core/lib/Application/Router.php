<?php

namespace Check24\Application;

class Router
{
	/** @var Route[] */
	protected $routes = [];

	public function registerRoute(string $name, string $uri, $controller, ?array $methods = null): Router
	{
		$this->routes[$name] = new Route($uri, $controller, $methods);
		return $this;
	}

	public function getRoute(string $name): ?Route
	{
		if (isset($this->routes[$name]))
		{
			return $this->routes[$name];
		}
		return null;
	}

	public function match($httpMethod, $uri): ?Route
	{
		$path = urldecode($uri);

		foreach ($this->routes as $route)
		{
			if ($route->match($path) && (
					empty($route->getMethods())
					||
					in_array($httpMethod, $route->getMethods(), true)
				)
			)
			{
				return $route;
			}
		}
		return null;
	}

	/**
	 * @return Route[]
	 */
	public function getRoutes()
	{
		return $this->routes;
	}
}