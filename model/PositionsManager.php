<?php
/**
 * Summary of PositionsManager
 */
class PositionsManager
{
	private $mysqli;
	/**
	 * Summary of __construct
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	/**
	 * Summary of FindAllPositions
	 * @return array<Position>
	 */
	public function FindAllPositions()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllPositions`()');
		$positions = $this->_CreatePositionsFromStatement($statement);
		return $positions;
	}
	/**
	 * Summary of GetPositionNameById
	 * @param mixed $posId
	 * @return mixed
	 */
	public function GetPositionNameById($posId)
	{
		$statement = $this->mysqli->prepare('CALL `GetPositionNameByID`(?)');
		$statement->bind_param('i', $posId);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Summary of DisplayedLiveStatsConfiguredPositions
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
	 * Summary of DeleteOldStatsPositions
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
	 * Summary of InsertNewStatPosition
	 * @param mixed $id
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
	 * Summary of _CreatePositionsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<Position>
	 */
	public function _CreatePositionsFromStatement(mysqli_stmt $statement)
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
	 * Summary of _CreatePositionFromRow
	 * @param array $row
	 * @return Position
	 */
	public function _CreatePositionFromRow(array $row)
	{
		return new Position($row['id'], $row['name']);
	}
	/**
	 * Summary of _GetSingleResultFromStatement
	 * @param mysqli_stmt $statement
	 * @return mixed
	 */
	public function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		return $row[0];
	}
}