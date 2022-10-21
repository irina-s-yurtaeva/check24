<?php
if (!defined('DOC_ROOT'))
{
	exit(0);
}
require_once DOC_ROOT.'/core/prolog.php';

include DOC_ROOT.'/template/header.php';

$route = \Check24\Application\Page::getInstance()->getRouter()->getRoute('listArticle');
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
				<dd>
					<?=yu_preparetext($article['BODY'])?>
				</dd>
				<?php
					if (\Check24\Controller\Article::createFromArray($article)->canEdit(
							\Check24\Controller\User::getCurrent()
						)
					)
					{
						?>
						<dd>
							<input type="button" data-action="edit-article" name="edit" value="Edit">
							<input type="button" data-action="delete-article" name="delete" value="Delete">
						</dd>
						<?php
					}
				?>
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

