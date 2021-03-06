<?php

namespace ADT\TracySystemInfo;

use Nette\DI\Config\Adapters\NeonAdapter;
use Nette\Neon\Neon;

class Storage
{

	/** @var string */
	protected $storageFile;

	/**
	 * @param $storageFile
	 */
	public function __construct($storageFile)
	{
		$this->storageFile = $storageFile;
	}

	/**
	 * @return array
	 */
	public function get()
	{
		if (file_exists($this->storageFile)) {
			return (new NeonAdapter())->load($this->storageFile);
		}

		return [];
	}

	public function add($key, $value)
	{
		$values = $this->get();

		$values[$key] = $value;

		file_put_contents($this->storageFile, (new NeonAdapter())->dump($values));
	}

}
