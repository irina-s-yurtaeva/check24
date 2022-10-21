<?php

session_start();

$settings = require_once __DIR__.'/.settings.php';
// we can use composer. To cover this point: "The integration of composer is explicitly desired."
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
// or private autoloader in case of safety. I would prefer this variant.
//require_once __DIR__.'/autoload.php';

\Check24\Model\Connection::initConnection($settings['connection']);
