<?php

namespace Check24\Application;

class Page
{
	protected string $title = 'Check24 Test Blog';
	protected Router $router;

	protected static Page $instance;

	protected function __construct()
	{
	}

	public function render(callable $callback): ?string
	{
		header("Check24: test challenge");
		try
		{
			ob_start();
			call_user_func($callback);
			return ob_get_clean();
		}
		catch (\Throwable $exception)
		{
			if (DEBUG_MODE)
			{
				?><pre><b>$exception: </b><?print_r($exception)?></pre><?
			}
			else
			{
				?><pre>Everything is almost perfect.</pre><?
			}
			ob_end_clean();
			return null;
		}
	}

	public function setTitle(string $title)
	{
		$this->title = $title;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setRouter(Router $router): void
	{
		$this->router = $router;
	}

	public function getRouter(): ?Router
	{
		return $this->router;
	}

	public function getPageUrl(string $pageName, $parameters = []): string
	{
		return $this->router->getRoute($pageName)->getUri($parameters);
	}

	public function getJS(): string
	{
		//TODO make an including of JS from the page
		return '';
	}

	public static function getWrapper(string $staticFilePath)
	{
		return function() use ($staticFilePath) {
			include DOC_ROOT.$staticFilePath;
		};
	}

	public static function getInstance(): self
	{
		if (!isset(static::$instance))
		{
			static::$instance = new static();
		}
		return static::$instance;
	}

}
