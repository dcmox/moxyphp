<?php
/* 
	Copyright © moxyphp.com - All rights reserved. 
	License: You are free to use this software for non-commercial purposes only.
	If you would like to use this software for commercial purposes, please
	contact me at daniel@moxyphp.com or via www.moxyphp.com/contact for a personal
	license.
*/

namespace Moxy;

class Paginate
{
	private $_prevIndex 	= 1;
	private $_nextIndex 	= 2;
	private $_curIndex 		= 1;
	private $_itemsPerPage	= 15;
	private $_totalItems	= 0;
	private $_totalPages	= 1;
	private $_url			= "";
	private $_bindIndex		= "page";
	
	public function __construct($args)
	{
		if (is_array($args) && (isset($args['totalPages']) || (isset($args['itemsPerPage']) && isset($args['totalItems']))))
		{
			foreach ($args as $k => $v)
			{
				if (isset($this->{'_'.$k}))
				{
					if ($k == 'totalItems' && is_string($v))
					{
						$dbh = DB::getConnection();
						$sth = $dbh->prepare(htmlspecialchars($v, ENT_QUOTES, 'UTF-8'));
						$sth->execute();
						$this->_totalItems = $sth->fetchColumn();
					}
					else
						$this->{'_'.$k} = $v;
				}
			}

			if ($this->_totalItems > 0 && $this->_itemsPerPage > 0)
				$this->_totalPages = ceil($this->_totalItems / $this->_itemsPerPage);

			if ($this->_url === "")
				if (isset($_GET['_c']))
					$this->_url = '/'. Request::getGet('_c');
				else
					$this->_url = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

			if (isset($args['bindIndex']) && Request::hasGet($this->_bindIndex))
				$this->_curIndex = Request::getGet($this->_bindIndex, 1);
	

			$this->updateIndexes();
		}
		else
			throw new Exception("Expecting array with at least itemsPerPage specified.");
	}

	public function getItemsPerPage()
	{
		return $this->_itemsPerPage;
	}

	public function getIndex()
	{
		return $this->_curIndex;
	}

	public function setUrl($url)
	{
		$this->_url = $url;
	}

	public function getUrl()
	{
		return $this->_url;
	}

	public function getPagination($style = "simple", $wrapper = "div", $class = "pagination")
	{
		if ($style === "simple")
		{
			$html = "<$wrapper class=\"$class\">";
			$html .= "<a href=\"{$this->_url}/{$this->_prevIndex}\">Prev</a><a href=\"{$this->_url}/{$this->_nextIndex}\">Next</a>";
			$html .= "</$wrapper>";
		}
		else
		{
			$html = "<$wrapper class=\"$class\">";
	
			for ($i = 1; $i < $this->_totalPages + 1; $i++)
			{
				if ($i === $this->_curIndex)
					$html .= "<a href=\"{$this->_url}/{$i}\" class=\"active\">{$i}</a>";
				else
					$html .= "<a href=\"{$this->_url}/{$i}\">{$i}</a>";
			}
			$html .= "</$wrapper>";
		}
		return $html;
	}

	public function increment()
	{
		if ($this->_curIndex + 1 <= $this->_totalPages)
		{	
			$this->_curIndex++;
			$this->updateIndex();
		}
	}

	public function decrement()
	{
		if ($this->_curIndex - 1 >= 1)
		{	
			$this->_curIndex--;
			$this->updateIndex();
		}
	}

	private function updateIndexes()
	{
		$this->_prevIndex = $this->_curIndex - 1 < 1 ? 1 : $this->_curIndex - 1;
		$this->_nextIndex = $this->_curIndex + 1 > $this->_totalPages ? $this->_totalPages : $this->_curIndex + 1;
	}

}
