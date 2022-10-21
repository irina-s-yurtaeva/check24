<?php

namespace Check24\Model;

class Result implements \IteratorAggregate
{
	/** @var resource */
	protected $resource;

	public function __construct($result)
	{
		$this->resource = $result;
	}

	/**
	 * Returns database-specific resource of this result.
	 *
	 * @return null|resource
	 */
	public function getResource()
	{
		return $this->resource;
	}

	public function fetch()
	{
		if ($this->resource instanceof \PDOStatement)
		{
			$data = $this->resource->fetch(\PDO::FETCH_ASSOC);
		}
		else if (method_exists($this->resource, 'fetch_assoc'))
		{
			$data = $this->resource->fetch_assoc();
		}

		if (!$data)
		{
			return false;
		}

		return $data;
	}
	/**
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return \Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 * @since 5.0.0
	 */
	public function getIterator(): \Traversable
	{
		return new ResultIterator($this);
	}
}