<?php

/**
 * @author Jakub Heyduk <heyduk2@seznam.cz>
 */

namespace App\Model;



class BaseModel extends \Nette\Object
{

	/**
	 * @var \Nette\Database\Context
	 */
	private $context;



	/**
	 * @param \Nette\Database\Context $context
	 */
	public function __construct(\Nette\Database\Context $context)
	{
		$this->context = $context;
	}



	/**
	 * @return \Nette\Database\Connection
	 */
	public function getConnection()
	{
		return $this->connection;
	}



	/**
	 * @return \Nette\Database\Context
	 */
	public function getContext()
	{
		return $this->context;
	}
}