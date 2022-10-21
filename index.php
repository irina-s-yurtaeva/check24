<?php

require_once __DIR__.'/core/prolog.php';

$router = (new \Check24\Application\Router())
	->registerRoute('index', '/', \Check24\Application\Page::getWrapper('/user/login.php'))
	->registerRoute('loginUser', '/login/', \Check24\Application\Page::getWrapper('/user/login.php' ))
	->registerRoute('registerUser', '/register/', \Check24\Application\Page::getWrapper('/user/register.php' ))
	->registerRoute('404', '/404/', \Check24\Application\Page::getWrapper('/404.php' ))
;
\Check24\Application\Page::getInstance()->setRouter($router);

if (!\Check24\Controller\User::getCurrent()->isAuthed())
{
	include DOC_ROOT.'/user/login.php';
}
else
{
	\Check24\Application\Page::getInstance()->getRouter()
		->registerRoute('index', '/', \Check24\Application\Page::getWrapper('/article/list.php'))
		->registerRoute('listArticle', '/articles/{pageNumber}/', \Check24\Application\Page::getWrapper('/article/list.php' ))
		->registerRoute('addArticle', '/articleadd/', \Check24\Application\Page::getWrapper('/article/edit.php' ))
		->registerRoute('editArticle', '/articleedit/{id}/', \Check24\Application\Page::getWrapper('/article/edit.php' ))
		->registerRoute('readArticle', '/articleread/{id}/', \Check24\Application\Page::getWrapper('/article/read.php' ))
	;
	\Check24\Application\Page::getInstance()->setRouter($router);
	$route = $router->match(
		$_SERVER['REQUEST_METHOD'] ?: 'GET',
		$_SERVER['PATH_INFO'] ?? '/'
	) ?: $router->getRoute('404');

	$callback = $route->getController();

	echo \Check24\Application\Page::getInstance()->render($callback);
}

require_once __DIR__.'/core/epilogue.php';