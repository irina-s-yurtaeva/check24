<?php

namespace Check24\Application;

class Route
{
	protected string $uri;

	protected string $matchUri;

	protected array $parameters = [];

	protected array $httpMethods = [];

	/** @var callable */
	protected $controller;

	public function __construct($uri, $controller, ?array $methods)
	{
		$this->uri = $uri;
		$this->controller = $controller;
		if ($methods)
		{
			$this->httpMethods = $methods;
		}
	}

	/**
	 * @return callable
	 */
	public function getController()
	{
		if (strpos($this->uri, '{') !== false)
		{
			$reflectionFunction = new \ReflectionFunction($this->controller);
			$args = [];
			foreach ($reflectionFunction->getParameters() as $param)
			{
				$args[] = $this->parameters[$param->getName()] ?? null;
			}
			return function() use ($args) {
				return call_user_func_array($this->controller, $args);
			};
//			$reflectionFunction->invokeArgs($args);
		}
		return $this->controller;
	}

	public function setParameters(array $parameters)
	{
		$this->parameters = array_merge(
			$this->parameters,
			array_intersect_key($parameters, $this->parameters)
		);
		return $this;
	}

	public function getParameters()
	{
		return $this->parameters;
	}

	public function compile()
	{
		if (isset($this->matchUri))
		{
			return;
		}

		$this->matchUri = "#^{$this->uri}$#";
		preg_match_all('/{([a-z0-9_]+)}/i', $this->uri, $matches);
		if ($matches)
		{
			$this->parameters = array_flip($matches[1]);
			foreach ($this->parameters as $parameterName => $val)
			{
				$pattern = '[^/]*';
				$this->matchUri = str_replace(
					"{{$parameterName}}",
					"(?<{$parameterName}>{$pattern})",
					$this->matchUri
				);
			}
		}
	}

	public function match($uriPath)
	{
		if ($uriPath === $this->uri)
		{
			return true;
		}

		if (strpos($this->uri, '{') !== false)
		{
			$this->compile();

			if (preg_match($this->matchUri, $uriPath, $matches))
			{
				$this->setParameters($matches);
				return true;
			}
		}

		return false;
	}

	function getMethods()
	{
		return $this->httpMethods;
	}

	function getUri(array $parameterValues = [])
	{
		$uri = $this->uri;

		if (strpos($uri, '{') !== false)
		{
			preg_match_all('/{([a-z0-9_]+)}/i', $this->uri, $matches);
			$parameters = array_flip($matches[1]);
			foreach ($parameters as $parameterName => $val)
			{
				$uri = str_replace(
					"{{$parameterName}}",
					$parameterValues[$parameterName] ?? '',
					$uri
				);
			}
		}
		return $uri;
	}
}