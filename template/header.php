<?php
$page = \Check24\Application\Page::getInstance();
$title = $page->getTitle();
?>

<!DOCTYPE html>
<html style="height: 100%;">
<head>
	<title><?= $title ?></title>
	<meta charset="utf-8">
	<meta name="author" content="Ira Yu">
<style>
	.yu-menu div {
		padding: 10px;
	}
</style>
</head>
<body style="height:100%;">
	<div class="yu-menu" style="display: flex;  justify-content: flex-start; justify-items: flex-start;">
		<?php if (\Check24\Controller\User::getCurrent()->isAuthed()): ?>
			<div>
				<a href="<?=htmlspecialchars($page->getPageUrl('index'))?>">The main page</a>
			</div>
			<div>
				<a href="<?=htmlspecialchars($page->getPageUrl('addArticle'))?>">Add article</a>
			</div>
			<div>
				<a data-action="logout" href="<?=htmlspecialchars($page->getPageUrl('loginUser'))?>">Log Out</a>
			</div>
		<?php else: ?>
			<div>
				<a href="<?=htmlspecialchars($page->getPageUrl('loginUser'))?>">Log in</a>
			</div>
			<div>
				<a href="<?=htmlspecialchars($page->getPageUrl('registerUser'))?>">Sing in</a>
			</div>
		<?php endif; ?>
	</div>
	<div id="workarea" style="height:calc(100% - 200px); ">