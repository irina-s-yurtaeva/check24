<?php

namespace Check24\Controller;

use Check24\Model\Result;

//This is a part of the Model but it is more convenient to use it here. And autoloading (custom) works correct.

class ArticleTable extends \Check24\Model\BaseTable
{
	public static function getTableName(): string
	{
		return 'yu_article';
	}
}

class Article
{
	public static function getListByThePage(?int $pageNumber = null, ?int $pageSize = null)
	{
		?><pre><b>$pageNumber: </b><?print_r($pageNumber)?></pre><?
		?><pre><b>$pageSize: </b><?print_r($pageSize)?></pre><?

		$result = ArticleTable::getList([
			'order' => ['ID' => 'DESC']
		] + ($pageNumber > 1 && $pageSize > 0 ? [
			'offset' => ($pageNumber - 1) * $pageSize
		] : []) + ( $pageSize > 0 ? [
			'limit' => $pageSize + 1
		] : [])
		);
		return $result;
	}
}
