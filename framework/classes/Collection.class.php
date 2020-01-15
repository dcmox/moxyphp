<?php
/* 
	Copyright © moxyphp.com - All rights reserved. 
	License: You are free to use this software for non-commercial purposes only.
	If you would like to use this software for commercial purposes, please
	contact me at daniel@moxyphp.com or via www.moxyphp.com/contact for a personal
	license.
*/

namespace Moxy;

class Collection
{
	private $_collection = array();

	private $_index = 0;
	

	public function __construct($collection)
	{
		$this->_collection = $collection;
	}

	public function push($obj)
	{
		$this->_index = 0;
		$this->_collection[] = $obj;
		return count($this->_collection);
	}

	public function count()
	{
		return count($this->_collection);
	}

	public function objects()
	{
		return $this->_collection;
	}

	public function pop()
	{
		$this->_index = 0;
		return array_pop($this->_collection);
	}

	public function unshift($obj)
	{
		$this->_index = 0;
		return array_unshift($this->_collection, $obj);
	}

	public function shift()
	{
		$this->_index = 0;
		return array_shift($this->_collection);
	}
	
	public function next()
	{
		$this->_index++;
		return isset($this->_collection[$this->_index]) ? $this->_collection[$this->_index] : null;
	}

	public function prev()
	{
		$this->_index--;
		return isset($this->_collection[$this->_index]) ? $this->_collection[$this->_index] : null;
	}

	public function first()
	{
		$this->_index = 0;
		
		return isset($this->_collection[0]) ? $this->_collection[0] : null;
	}

	public function last()
	{
		$this->_index = count($this->_collection)-1;
		return isset($this->_collection[$this->_index]) ? $this->_collection[$this->_index] : null;
	}
}