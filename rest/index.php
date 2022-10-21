<?php

//There is a gateway for actions. The answer is always in json format.
require_once __DIR__.'/../core/prolog.php';

$router = (new \Check24\Application\Router())
	// Autowiring just to test it :)
	->registerRoute('userApi', '/user/{action}/', function($action) {
		if ($action === 'login')
		{
			\Check24\Controller\User::auth($_POST['login'], $_POST['password']);
		}
		else if ($action === 'register')
		{
			\Check24\Controller\User::register($_POST['login'], $_POST['password'], $_POST['password2']);
		}
		else if ($action === 'logout')
		{
			\Check24\Controller\User::getCurrent()->logout();
		}
	})
	->registerRoute('404', '/404/', function() {})
;

\Check24\Application\PageJson::getInstance()->setRouter($router);

if (\Check24\Controller\User::checkCSRFToken($_POST['tk']) !== true)
{
	$route = new \Check24\Application\Route('/error', function() {
		throw new \Exception('Bad csrf token');
	}, []);
}
else
{
	$route = $router->match(
		$_SERVER['REQUEST_METHOD'] ?: 'GET',
		$_SERVER['PATH_INFO'] ?: '/'
	);
}


$callback = ($route ?: $router->getRoute('404'))->getController();

echo \Check24\Application\PageJson::getInstance()->render($callback);

require_once __DIR__.'/../core/epilogue.php';