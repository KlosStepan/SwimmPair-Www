<?php
/**
 * PositionsManager has API functions to handle Position object/s and delivers it through web application.
 */
class PositionsManager
{
	private $mysqli;
	/**
	 * Initialize PositionsManager with live database connection.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	/**
	 * Retrieve all work positions needed at Cup to be administered. 
	 * @return array<Position>
	 */
	public function FindAllPositions()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllPositions`()');
		$positions = $this->_CreatePositionsFromStatement($statement);
		return $positions;
	}
	/**
	 * Get Position name based on id.
	 * @param int $posId
	 * @return string
	 */
	public function GetPositionNameById($posId)
	{
		$statement = $this->mysqli->prepare('CALL `GetPositionNameByID`(?)');
		$statement->bind_param('i', $posId);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * List of positions in our specific order that we desire to display at public website for statistical purposes.
	 * @return array<Position>
	 */
	public function DisplayedLiveStatsConfiguredPositions()
	{
		$statement = $this->mysqli->prepare('CALL `GetConfiguredStats`()');
		$positions = $this->_CreatePositionsFromStatement($statement);
		return $positions;
	}
	//Stats-Positions Configuration
	/**
	 * Delete setting of how positions should be dipslayed at public website.
	 * @return bool
	 */
	public function DeleteOldStatsPositions()
	{
		$statement = $this->mysqli->prepare('CALL `DeleteOldStatsPositions`()');
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Insert new setting of how positions should be dipslayed at public website.
	 * @param int $id
	 * @return bool
	 */
	public function InsertNewStatPosition($id)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewStatPosition`(?)');
		$statement->bind_param('i', $id);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	//DEPRECATE SOON
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
	/**
	 * This function creates a Position object or returns null.
	 * @param mysqli_stmt $statement
	 * @return array<Position>
	 */
	private function _CreatePositionsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$positions = [];
		foreach ($rows as $row) {
			$positions[] = $this->_CreatePositionFromRow($row);
		}
		return $positions;
	}
	/**
	 * This function creates new Position object from the row returned from database.
	 * @param array $row
	 * @return Position
	 */
	private function _CreatePositionFromRow(array $row)
	{
		return new Position($row['id'], $row['name']);
	}
	/**
	 * Get one thing from database from only position in one row.
	 * @param mysqli_stmt $statement
	 * @return mixed
	 */
	private function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		return $row[0];
	}
}