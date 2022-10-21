<?php

namespace Check24\Controller;

use Check24\Model\Result;

//This is a part of the Model but it is more convenient to use it here. And autoloading (custom) works correct.
class UserTable extends \Check24\Model\BaseTable
{
	public static function getTableName(): string
	{
		return 'yu_user';
	}

	public static function getByLogin(string $login): \Check24\Model\Result
	{
		return static::getList([
			'filter' => ['LOGIN' => $login]
		]);
	}
}

class User
{
	protected ?int $id;
	protected string $token;
	protected ?array $data;
	protected static User $instance;

	public function __construct(?int $id)
	{
		if ($id > 0 && ($res = UserTable::getList(['filter' => ['ID' => $id]])->fetch()))
		{
			$this->id = $id;
			$this->data = $res;
		}
	}

	public function isAuthed(): bool
	{
		return isset($_SESSION['userId']) && $this->id > 0 && (intval($_SESSION['userId']) === intval($this->id));
	}

	public static function auth(string $login, string $password)
	{
		if ($res = UserTable::getByLogin($login)->fetch())
		{
			if (password_verify(implode('-', [$res['ID'], $res['LOGIN'], $password]), $res['PASSWORD']))
			{
				$_SESSION['userId'] = (int) $res['ID'];

				if (password_needs_rehash($res['PASSWORD'], PASSWORD_DEFAULT))
				{
					$newHash = static::generateToken($res['ID'], $res['LOGIN'], $password);
					UserTable::update($res['ID'], ['PASSWORD' => $newHash]);
				}
				return true;
			}
		}
		throw new \Error('User was not found.');
	}

	public static function register(string $login, string $password, string $password2)
	{
		//TODO
	}

	public function getCSRFToken(): string
	{
		if (!isset($this->token))
		{
			$this->token = md5('SomeSalt'.session_id());
		}
		return $this->token;
	}

	public function logout()
	{
		unset($_SESSION['userId']);
		session_destroy();
	}

	public static function checkCSRFToken(string $token): bool
	{
		return $token === static::getCurrent()->getCSRFToken();
	}

	public static function getCurrent(): self
	{
		if (!isset(static::$instance))
		{
			static::$instance = new static($_SESSION['userId'] ?? null);
		}
		return static::$instance;
	}

	public static function generateToken(): string
	{
		return password_hash(implode('-', func_get_args()), PASSWORD_DEFAULT);
	}
}
