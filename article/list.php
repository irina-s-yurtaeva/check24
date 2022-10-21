<?php
if (!defined('DOC_ROOT'))
{
	exit(0);
}
require_once DOC_ROOT.'/core/prolog.php';

include DOC_ROOT.'/template/header.php';

$route = \Check24\Application\Page::getInstance()->getRouter()->getRoute('listArticle');
$routeForReading = \Check24\Application\Page::getInstance()->getRouter()->getRoute('readArticle');
$routeForEditing = \Check24\Application\Page::getInstance()->getRouter()->getRoute('editArticle');
$params = $route->getParameters();

$pageNumber = isset($params['pageNumber']) ? intval($params['pageNumber']) : 1;
$pageSize = 3;

$articleData = \Check24\Controller\Article::getListByThePage($pageNumber, $pageSize);
?>
	<h1>Articles</h1>
<?php
if (!($article = $articleData->fetch()))
{
	?><i>There are no articles yet.</i><?php
}
else
{
	$articlesCount = 0;
	?>
	<ul>
		<?php do {
			$articlesCount++;
			if ($articlesCount > $pageSize)
			{
				break;
			}
		?><li>
			<dl>
				<dt>
					Title: <?=yu_preparetext($article['TITLE'])?>
				</dt>
				<dt>
					Created: <?=yu_preparetext($article['CREATED'])?>
				</dt>
				<dt>
					Author: <?=yu_preparetext($article['AUTHOR_NAME'])?>
				</dt>
				<dt>
					<a href="<?=$routeForReading->getUri(['id' => $article['ID']])?>">read</a><?php
					if (\Check24\Controller\Article::createFromArray($article)->canEdit(
						\Check24\Controller\User::getCurrent()
						)
					)
					{
					?>
						<a href="<?=$routeForEditing->getUri(['id' => $article['ID']])?>">edit</a>
						<input type="button" data-action="delete-article" name="delete" value="Delete">
					<?php } ?>
				</dt>
				<dd>
					<?=yu_preparetext(substr($article['BODY'], 0, 1000))?>
				</dd>

			</dl>
			</li><?php
		} while ($article = $articleData->fetch())
		?>
	</ul>
	<?php
	if ($articlesCount > $pageSize || $pageNumber > 1)
	{
		?><nav><?php
		if ($pageNumber > 1)
		{
			?><a href="<?=$route->getUri(['pageNumber' => ($pageNumber - 1)])?>"><<<</a> <?php
		}
		?><b><?=$pageNumber?></b><?php
		if ($articlesCount > $pageSize)
		{
			?><a href="<?=$route->getUri(['pageNumber' => ($pageNumber + 1)])?>">>>></a><?
		}
		?></nav><?php
	}
}
include DOC_ROOT.'/template/footer.php';

