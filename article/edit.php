<?php
if (!defined('DOC_ROOT'))
{
	exit(0);
}

require_once DOC_ROOT.'/core/prolog.php';

include DOC_ROOT.'/template/header.php';

if (!\Check24\Controller\User::getCurrent()->isAuthed())
{
	throw new \Check24\Application\AccessDeniedException();
}
$route = \Check24\Application\Page::getInstance()->getRouter()->getRoute('editArticle');
$params = $route->getParameters();

$article = null;

if ($params['id'] > 0)
{
	$pageTitle = 'Edit';
	$article = new \Check24\Controller\Article($params['id']);
	if (!$article->canEdit(\Check24\Controller\User::getCurrent()))
	{
		throw new \Check24\Application\AccessDeniedException("Can not edit.");
	}
}
?>
<div>
    <div>
        <h1><?= $pageTitle ?? 'Add article' ?></h1>
        <form data-id="<?= $article['ID'] ?? '' ?>" class="article">
            <div class="form-group">
                <label for="title">
                    Title (required)
                </label>
                <input type="text" name="title" value="<?=yu_preparetext($article['TITLE'] ?? '')?>" />
            </div>
            <div>
                <label for="text">
                    Text (required)
                </label>
	            <textarea name="body" cols="50" rows="10"><?=yu_preparetext($article['BODY'] ?? '')?></textarea>
            </div>
            <input type="submit" value="<?= isset($article['ID']) ? 'Update' : 'Add' ?>" />
        </form>
    </div>
</div>

<?php
include DOC_ROOT.'/template/footer.php';
