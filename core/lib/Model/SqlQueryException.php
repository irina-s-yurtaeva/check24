<?php

namespace Check24\Model;

class SqlQueryException extends \Exception
{
	protected string $sql = "";

	public function __construct($message = "", $databaseMessage = "", $sql = "")
	{
		echo '$message: '.$message."\n";
		echo '$databaseMessage: '.$databaseMessage."\n";
		echo '$sql: '.$sql."\n";
		parent::__construct($message, $databaseMessage);
		$this->sql = $sql;
	}
}
