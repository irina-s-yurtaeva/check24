<?php

namespace Check24\Model;

/**
 * This class can be abstract. Here two ways for working with mysql: via mysqli_init and PDO.
 */
class Connection
{
	protected $resource;
	protected bool $isConnected = false;

	protected string $host;
	protected int $port = 0;
	protected string $database;
	protected string $login;
	protected string $password;

	protected static ?Connection $instance;
	protected static \PDO $pdoInstance;

	private static array $configuration = [];

	public function __construct(array $configuration)
	{
		$this->host = $configuration['host'] ?? '';

		$host = $this->host;
		if (($pos = strpos($host, ":")) !== false)
		{
			$this->port = intval(substr($host, $pos + 1));
			$this->host = substr($host, 0, $pos);
		}

		$this->database = $configuration['database'] ?? '';
		$this->login = $configuration['login'] ?? '';
		$this->password = $configuration['password'] ?? '';

		self::$configuration = $configuration;
	}

	public function connect(): void
	{
		if ($this->isConnected)
		{
			return;
		}

		$connection = \mysqli_init();

		if (!$connection)
		{
			throw new DBException('Mysql init failed');
		}

		if ($this->port > 0)
		{
			$success = $connection->real_connect($this->host, $this->login, $this->password, $this->database, $this->port);
		}
		else
		{
			$success = $connection->real_connect($this->host, $this->login, $this->password, $this->database);
		}

		if (!$success)
		{
			throw new DBException(
				'Mysql connect error ['.$this->host.']',
				sprintf('(%s) %s', $connection->connect_errno, $connection->connect_error)
			);
		}

		$this->resource = $connection;
		$this->isConnected = true;
		$this->query("SET NAMES 'utf8'");
		$this->query("SET sql_mode=''");
		$this->query('SET collation_connection = "utf8_unicode_ci"');
	}

	public function query($sql)
	{
		$this->connect();

		// TODO Have to make some preparation to make safe sql. But not now.
		$result = $this->resource->query($sql, MYSQLI_STORE_RESULT);

		if (!$result)
		{
			throw new SqlQueryException('Mysql query error', $this->getErrorMessage(), $sql);
		}

		return new Result($result);
	}

	public function disconnect()
	{
		if ($this->isConnected)
		{
			$this->isConnected = false;
			$this->resource->close();
		}
	}

	/**
	 * Returns the resource of the connection.
	 *
	 * @return resource
	 */
	public function getResource()
	{
		$this->connect();
		return $this->resource;
	}

	public function isConnected()
	{
		return $this->isConnected;
	}

	public function getInsertedId()
	{
		return $this->getResource()->insert_id;
	}

	public function getAffectedRowsCount()
	{
		return $this->getResource()->affected_rows;
	}

	protected function getErrorMessage()
	{
		return sprintf("(%s) %s", $this->resource->errno, $this->resource->error);
	}

	public function selectDatabase($database)
	{
		return $this->resource->select_db($database);
	}

	public static function initConnection(array $config): void
	{
		if (!isset(static::$instance))
		{
			static::$instance = new static($config);
		}
	}

	public static function getInstance(): ?Connection
	{
		return static::$instance ?? null;
	}

	// New way for me to work with Database
	public static function getPDO(): ?\PDO
	{
		if (!isset(static::$pdoInstance) && isset(static::$instance))
		{
			$pdo = new \PDO(
				'mysql:dbname='.self::$configuration['database'].';host='.self::$configuration['host'],
				self::$configuration['login'],
				self::$configuration['password']
			);

			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			static::$pdoInstance = $pdo;
		}
		return static::$pdoInstance;
	}
}
