<?php

class CupsManager
{
	private $mysqli;
	/**
	 * This constructor sets the mysqli object used to interact with the database.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	/**
	 * Summary of FindAllUpcomingCupsEarliestFirst
	 * @return array<Cup>
	 */
	public function FindAllUpcomingCupsEarliestFirst()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllUpcomingCupsEarliestFirst`()');
		$cups = $this->_CreateCupsFromStatement($statement);
		return $cups;
	}
	/**
	 * Summary of FindAllPastCupsMostRecentFirst
	 * @return array<Cup>
	 */
	public function FindAllPastCupsMostRecentFirst()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllPastCupsMostRecentFirst`()');
		$cups = $this->_CreateCupsFromStatement($statement);
		return $cups;
	}
	/**
	 * Summary of FindPairingsForThisCup
	 * @param mixed $cupID
	 * @return array<PairPositionUser>
	 */
	public function FindPairingsForThisCup($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `FindPairingsForThisCup`(?)');
		$statement->bind_param('i', $cupID);
		$pairs = $this->_CreatePairsFromStatement($statement);
		return $pairs;
	}
	/**
	 * Summary of GetPairingHashForThisCup
	 * @param mixed $cupID
	 * @return mixed
	 */
	public function GetPairingHashForThisCup($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `HashPairingForThisCup`(?)');
		$statement->bind_param('i', $cupID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Summary of GetNewCupIDByInfo
	 * @param mixed $name
	 * @param mixed $date_begin
	 * @param mixed $date_end
	 * @return mixed
	 */
	public function GetNewCupIDByInfo($name, $date_begin, $date_end)
	{
		$statement = $this->mysqli->prepare('CALL `GetNewCupIDByInfo`(?,?,?)');
		$statement->bind_param('sss', $name, $date_begin, $date_end);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Summary of IsUserAvailableForTheCup
	 * @param mixed $userID
	 * @param mixed $cupID
	 * @throws RuntimeException
	 * @return bool
	 */
	public function IsUserAvailableForTheCup($userID, $cupID)
	{
		$statement = $this->mysqli->prepare('CALL `IsUserAvailableForTheCup`(?,?)');
		$statement->bind_param('ii', $cupID, $userID);
		$statement->execute();
		//echo "userid: " . $userId . ", cupid: " . $cupId . "</br>";
		$res = $statement->get_result();
		//echo $res->num_rows . "</br>";
		if ($res->num_rows == 0) {
			//echo "not registered";
			return false;
		} else if ($res->num_rows == 1) {
			//echo "registered";
			return true;
		} else {
			throw new RuntimeException(); //more registrations for one cup
		}
	}
	/**
	 * Summary of GetEarliestCupYear
	 * @return mixed
	 */
	public function GetEarliestCupYear()
	{
		$statement = $this->mysqli->prepare('CALL `GetEarliestCupYear`()');
		$_ret = $this->_GetSingleResultFromStatement($statement);
		if ($_ret != null) {
			return $_ret;
		} else {
			return date("Y");
		}
	}
	/**
	 * Summary of GetMaximumCupYear
	 * @return int
	 */
	public function GetMaximumCupYear()
	{
		return (int) date("Y");
	}
	/**
	 * Summary of GetCupByID
	 * @param mixed $cupID
	 * @return Cup|null
	 */
	public function GetCupByID($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `GetCupByID`(?)');
		$statement->bind_param('i', $cupID);
		return $this->_CreateCupOrNullFromStatement($statement);
	}
	/**
	 * Summary of InsertNewCup
	 * @param mixed $name
	 * @param mixed $date_begin
	 * @param mixed $date_end
	 * @param mixed $club
	 * @param mixed $content
	 * @return bool
	 */
	public function InsertNewCup($name, $date_begin, $date_end, $club, $content)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewCup`(?,?,?,?,?)');
		$statement->bind_param('sssis', $name, $date_begin, $date_end, $club, $content);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	//public function UpdateCup($cupId) - TODO
	//public function DeleteCup($cupId) - TODO
	/**
	 * Summary of DeleteOldAvailability
	 * @param mixed $cupID
	 * @return bool
	 */
	public function DeleteOldAvailability($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `DeleteOldAvailability`(?)');
		$statement->bind_param('i', $cupID);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of InsertNewAvailability
	 * @param mixed $cupID
	 * @param mixed $userID
	 * @param mixed $attendanceFlag
	 * @return bool
	 */
	public function InsertNewAvailability($cupID, $userID, $attendanceFlag)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewAvailability`(?,?,?)');
		$statement->bind_param('iii', $cupID, $userID, $attendanceFlag);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of DeleteOldPairing
	 * @param mixed $cupID
	 * @return bool
	 */
	public function DeleteOldPairing($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `DeleteOldPairing`(?)');
		$statement->bind_param('i', $cupID);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of InsertNewPairing
	 * @param mixed $cupID
	 * @param mixed $posID
	 * @param mixed $userID
	 * @return bool
	 */
	public function InsertNewPairing($cupID, $posID, $userID)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewPairing`(?,?,?)');
		$statement->bind_param('iii', $cupID, $posID, $userID);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
	/**
	 * Summary of _CreateCupsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<Cup>
	 */
	public function _CreateCupsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$cups = [];
		foreach ($rows as $row) {
			$cups[] = $this->_CreateCupFromRow($row);
		}
		return $cups;
	}
	/**
	 * Summary of _CreateCupOrNullFromStatement
	 * @param mysqli_stmt $statement
	 * @return Cup|null
	 */
	public function _CreateCupOrNullFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row !== NULL) {
			return $this->_CreateCupFromRow($row);
		} else {
			return NULL;
		}
	}
	/**
	 * Summary of _CreateCupFromRow
	 * @param array $row
	 * @return Cup
	 */
	public function _CreateCupFromRow(array $row)
	{
		return new Cup($row['id'], $row['time_start'], $row['time_end'], $row['name'], $row['description'], $row['organizer_club_id']);
	}
	/**
	 * Summary of _CreatePairsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<PairPositionUser>
	 */
	public function _CreatePairsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$pairs = [];
		foreach ($rows as $row) {
			$pairs[] = $this->_CreatePairFromRow($row);
		}
		return $pairs;
	}
	/**
	 * Summary of _CreatePairFromRow
	 * @param array $row
	 * @return PairPositionUser
	 */
	public function _CreatePairFromRow(array $row)
	{
		return new PairPositionUser($row['position_id'], $row['user_id']);
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
		if ($rows == NULL) {
			return null;
		} else {
			$row = $rows[0];
			return $row[0];
		}
	}
}