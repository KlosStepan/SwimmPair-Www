<?php
/**
 * PagesManager has API functions to handle Page object and delivers it through web application.
 */
class PagesManager
{
	private $mysqli;
	/**
	 * Initialize PagesManager with live database connection.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//public function CreatePage() - TODO 
	//public function DeletePage() - TODO
	/**
	 * Get Page from database by its id.
	 * @param int $id
	 * @return Page|null
	 */
	public function GetPageByID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetPageByID`(?)');
		$statement->bind_param('i', $id);
		return $this->_CreatePageOrNullFromStatement($statement);
	}
	/**
	 * Updates Page in the web application.
	 * @param int $id
	 * @param string $title
	 * @param string $content
	 * @return bool
	 */
	public function UpdatePage($id, $title, $content)
	{
		$statement = $this->mysqli->prepare('CALL `UpdatePage`(?,?,?)');
		$statement->bind_param('iss', $id, $title, $content);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * This function creates a Page object or returns null.
	 * @param mysqli_stmt $statement
	 * @return Page|null
	 */
	private function _CreatePageOrNullFromStatement($statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row !== NULL) {
			return $this->_CreatePageFromRow($row);
		} else {
			return NULL;
		}
	}
	/**
	 * This function creates new Page object from the row returned from database.
	 * @param array $row
	 * @return Page
	 */
	private function _CreatePageFromRow(array $row)
	{
		return new Page($row['id'], $row['title'], $row['content']);
	}
}