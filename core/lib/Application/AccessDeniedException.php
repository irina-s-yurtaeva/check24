<?php

namespace Check24\Application;

class AccessDeniedException extends \Exception
{
	public function __construct($message = "")
	{
		$message = $message ?: 'Access denied';
		parent::__construct($message);
	}
}
