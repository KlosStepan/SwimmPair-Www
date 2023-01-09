<?php

class PagesManager
{
	/** @var mysqli */
	private $mysqli;

	/**
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	/* API FUNCTIONS */
	/**
	 * @param int $id
	 * @return Post|NULL
	 */
	public function GetPageByID($id)
	{
		//$statement = $this->mysqli->prepare('SELECT id, title, content FROM `sp_pages` WHERE id=?');
		$statement = $this->mysqli->prepare('CALL `GetPageByID`(?)');
		$statement->bind_param('i', $id);

		return $this->CreatePageOrNullFromStatement($statement);
	}

	/**
	 * @param $id
	 * @param $title
	 * @param $content
	 * @return null
	 */
	public function UpdatePage($id, $title, $content)
	{
		//$statement = $this->mysqli->prepare('UPDATE `sp_pages` SET `title` = ?, `content` = ?  WHERE `id` = ?');
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

	/* PRIVATE AUX FUNCTIONS */
	/**
	 * @param $statement
	 * @return null|Page
	 */
	private function CreatePageOrNullFromStatement($statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		//return $row;
		if ($row !== NULL)
		{
			return $this->CreatePageFromRow($row);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * @param array $row
	 * @return Page
	 */
	private function CreatePageFromRow(array $row)
	{
		return new Page($row['id'], $row['title'], $row['content']);
	}
}