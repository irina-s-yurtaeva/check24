<?php

namespace Check24\Application;

class PageJson extends Page
{
	public function render(callable $callback): ?string
	{
		header('Content-Type: application/json');
		try
		{
			$result = [
				'status' => 'OK',
				'data' => call_user_func($callback)
			];
		}
		catch (\Throwable $exception)
		{
			if (DEBUG_MODE)
			{
				throw $exception;
			}
			$result = [
				'status' => 'ERROR',
				'errors' => [
					['message' => $exception->getMessage(), 'code' => $exception->getCode()]
				]
			];
		}
		$res = json_encode($result);
		return  $res;
	}
}
