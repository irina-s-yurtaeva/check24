<?php

$settings = require_once __DIR__.'/../../core/.settings.php';
// First way to use autoload
require_once __DIR__.'/../../core/autoload.php';

// create database if not exists
$dbName = $settings['connection']['database'];
if (!extension_loaded('mysqli'))
{
	throw new \Exception('This script working only with MysqlI extension.');
}
if (preg_match("/[^0-9a-zA-Z_]/", $dbName, $matches) || strlen($dbName) > 64)
{
	throw new \Exception('Database name has some restrictions');
}

$conn = new \Check24\Model\Connection(array_merge($settings['connection'], ['database' => null]));
$conn->connect();

if (!$conn->selectDatabase($dbName))
{
	$conn->query("CREATE DATABASE `" . addslashes($dbName) . "`");
	if (!$conn->selectDatabase($dbName))
	{
		throw new \Exception('Database has not been created.');
	}

	try
	{
		$conn->query(
			"GRANT ALL ON `".addslashes($dbName)."`.* TO '".addslashes($settings['connection']['login'])."'@'".$settings['connection']['host']."' "
		);
	}
	catch (Check24\Model\SqlQueryException $e)
	{
		throw new \Exception('User has not been granted.');
	}
}

try
{
	$conn->query("ALTER DATABASE `".addslashes($dbName)."` CHARACTER SET UTF8 COLLATE utf8_unicode_ci");
}
catch (Check24\Model\SqlQueryException $e)
{
	throw new \Exception('Database collation is not changed.');
}
$conn->query("SET NAMES 'utf8'");

$res = explode(';', file_get_contents(__DIR__.'/install.sql'));
foreach ($res as $sql)
{
	$sql = trim($sql);
	if (!empty($sql))
	{
		$conn->query($sql);
	}
}

$dbRes = $conn->query('SELECT * FROM yu_user');
while ($res = $dbRes->fetch())
{
	$password = \Check24\Controller\User::generateToken($res['ID'], $res['LOGIN'], '111111');
	$conn->query('UPDATE yu_user SET PASSWORD=\''.addslashes($password).'\' WHERE ID='.intval($res['ID']));
}
$conn->disconnect();

