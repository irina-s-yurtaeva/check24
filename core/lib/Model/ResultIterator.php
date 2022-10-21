<?php

namespace Check24\Model;

class ResultIterator implements \Iterator
{
	/** @var Result */
	private $result;
	private int $counter;
	private $currentData;

	public function __construct(Result $result)
	{
		$this->result = $result;
		$this->counter = -1;
	}

	public function current()
	{
		return $this->currentData;
	}

	public function next()
	{
		$this->currentData = $this->result->fetch();
		$this->counter++;
	}

	public function key()
	{
		return $this->counter;
	}

	public function valid()
	{
		return $this->currentData !== false;
	}

	public function rewind()
	{
		if ($this->counter > 0)
		{
			throw new DBException('Could not rewind the iterator');
		}

		$this->next();
	}
}