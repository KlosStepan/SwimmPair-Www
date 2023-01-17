<?php

class PagesManager
{
	private $mysqli;
	/**
 	* PagesManager constructor.
 	*
 	* @param mysqli $mysqli
 	*
 	* This constructor sets the mysqli object used to interact with the database.
 	*/
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//public function CreatePage() - TODO 
	//public function DeletePage() - TODO
	/**
 	* GetPageByID
 	*
 	* @param int $id
 	*
 	* @return mixed
 	*
 	* This function retrieves a page from the database by its ID.
 	*/
	public function GetPageByID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetPageByID`(?)');
		$statement->bind_param('i', $id);
		return $this->_CreatePageOrNullFromStatement($statement);
	}
	/**
	 * UpdatePage
	 *
	 * @param int $id
	 * @param string $title
	 * @param string $content
	 *
	 * @return bool
	 *
	 * This function updates a page in the database.
	 */
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
	/**
	 * _CreatePageOrNullFromStatement
	 *
	 * @param $statement
	 *
	 * @return mixed
	 *
	 * This function creates a page object or returns null.
	 */
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
	/**
 	* _CreatePageFromRow
 	*
 	* @param array $row
 	*
 	* @return Page
 	*
 	* This function creates a new Page object from a row in the database.
 	*/
	private function _CreatePageFromRow(array $row)
	{
		return new Page($row['id'], $row['title'], $row['content']);
	}
}