<?php
\spl_autoload_register(function ($className) {

	$file = ltrim($className, "\\");    // fix web env

	if (preg_match("#[^\\\\/a-zA-Z0-9_]#", $file))
		return;

	$file = str_replace('\\', '/', $file);
	$fileParts = explode("/", $file);

	if (strtolower($fileParts[0]) === "check24")
	{
		array_shift($fileParts);
		array_unshift($fileParts, 'lib');
	}
	$classPath = implode("/", $fileParts);

	$filePath = DOC_ROOT.'core/'.$classPath.".php";

	require_once $filePath;
});