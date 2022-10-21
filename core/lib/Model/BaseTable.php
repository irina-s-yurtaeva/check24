<?php
//Here we are working with PDO instead of pure sql

namespace Check24\Model;

abstract class BaseTable
{
	abstract static function getTableName(): string;

	public static function getList(array $queryFields = []): Result
	{
		if (isset($GLOBALS['edbug']))
		{
die('!!!');

		}
		$tableName = static::getTableName();
		$sqlConstructor = "SELECT * FROM `{$tableName}`";
		$fieldConstructor = [];
		//region WHERE
		if (isset($queryFields['filter']) && is_array($queryFields['filter']))
		{
			//TODO make a white list for the fields.
			$fields = implode(' AND ', array_map(function($field) {
				return "`{$field}` = :{$field}";
			}, array_keys($queryFields['filter'])));
			$fieldConstructor = $queryFields['filter'];
			if ($fields !== '')
			{
				$sqlConstructor .= " WHERE ".$fields;
			}
		}
		//endregion
		//region ORDER BY
		if (isset($queryFields['order']) && is_array($queryFields['order']))
		{
			//TODO check $order fields for whitelist:
			array_walk($queryFields['order'], function(&$direction, $field) {
				$direction = "`{$field}` ".(strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC');
			});
			$sqlConstructor .= " ORDER BY ".implode(', ', $queryFields['order']);
		}
		//endregion
		//region OFFSET LIMIT
		if (isset($queryFields['limit']))
		{

		}
		if (isset($queryFields['offset']))
		{

		}
		//endregion
		if (isset($GLOBALS['edbug']))
		{
			?><pre><b>$sqlConstructor: </b><?print_r($sqlConstructor)?></pre><?

			?><pre><b>$queryFields: </b><?print_r($queryFields)?></pre><?

		}
		$stmt = static::getPDO()->prepare(
			$sqlConstructor
		);
		$stmt->execute($fieldConstructor);
		return new Result($stmt);
	}

	public static function update(int $id, array $fields)
	{
		//TODO make a white list for fields.
		$updateFields = implode(' AND ', array_map(function($field) {
			return "`{$field}` = :{$field}";
		}, array_keys($fields)));

		if ($fields !== '')
		{
			$tableName = static::getTableName();
			$stmt = static::getPDO()->prepare(
				"UPDATE `{$tableName}` SET ".$updateFields." WHERE `ID` = :ID"
			);
			$stmt->execute(array_merge($fields, ['ID' => $id]));
			return $stmt;
		}
		return null;
	}

	public static function getPDO(): \PDO
	{
		return \Check24\Model\Connection::getPDO();
	}
}