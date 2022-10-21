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

class Article implements \ArrayAccess
{
	protected int $id;
	protected array $data;

	public function __construct(int $id, ?array $data = null)
	{
		$this->id = $id;
		if (!empty($data))
		{
			$this->data = $data;
		}
		else
		{
			$this->data = ArticleTable::getList(['filter' => ['ID' => $id]])->fetch();
		}
	}

	public function canEdit(User $currentUser): bool
	{
		if (
			$currentUser->isAuthed()
			&&
			(
				$currentUser->getId() == $this->data['AUTHOR_ID']
				||
				$currentUser->isEditor()
			)
		)
		{
			return true;
		}
		return false;
	}

	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->data);
	}

	public function offsetGet($offset)
	{
		return $this->data[$offset];
	}

	public function offsetSet($offset, $value)
	{

	}

	public function offsetUnset($offset)
	{

	}

	public static function createFromArray(array $data): self
	{
		return new static($data['ID'], $data);
	}

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
