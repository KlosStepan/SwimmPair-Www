<?php

class PagesManager
{
	private $mysqli;
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	//Page handling
	//public function CreatePage() - TODO 
	//public function DeletePage() - TODO
	public function GetPageByID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetPageByID`(?)');
		$statement->bind_param('i', $id);
		return $this->_CreatePageOrNullFromStatement($statement);
	}
	public function UpdatePage($id, $title, $content)
	{
		$statement = $this->mysqli->prepare('CALL `UpdatePage`(?,?,?)');
		$statement->bind_param('iss', $id, $title, $content);
		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
	private function _CreatePageOrNullFromStatement($statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row !== NULL)
		{
			return $this->_CreatePageFromRow($row);
		}
		else
		{
			return NULL;
		}
	}
	private function _CreatePageFromRow(array $row)
	{
		return new Page($row['id'], $row['title'], $row['content']);
	}
}