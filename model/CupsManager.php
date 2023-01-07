<?php

class CupsManager
{
	/** @var mysqli */
	private $mysqli;

	/** @param  mysqli $mysqli*/
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}

	/**
	 * @return Cup[]
	 */
	public function FindAllUpcomingCupsEarliestFirst()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `time_start`, `time_end`, `name`, `description`, `organizer_club_id` FROM `sp_cups` WHERE `time_end` >= NOW() ORDER BY `time_start` ASC');
		$statement = $this->mysqli->prepare('CALL `FindAllUpcomingCupsEarliestFirst`()');
		$cups = $this->_CreateCupsFromStatement($statement);

		return $cups;
	}

	/**
	 * @return Cup[]
	 */
	public function FindAllPastCupsMostRecentFirst()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `time_start`, `time_end`, `name`, `description`, `organizer_club_id` FROM `sp_cups` WHERE `time_end` <= NOW() ORDER BY `time_start` DESC');
		$statement = $this->mysqli->prepare('CALL `FindAllPastCupsMostRecentFirst`()');
		$cups = $this->_CreateCupsFromStatement($statement);

		return $cups;
	}
	//here

	/**
	 * @param mysqli_stmt $statement
	 * @return Club[]
	 */
	public function _CreateCupsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

		$cups = [];
		foreach($rows as $row)
		{
			$cups[]=$this->_CreateCupFromRow($row);
		}
		return $cups;
	}

	/**
	 * @param array $row
	 * @return Cup
	 */
	public function _CreateCupFromRow(array $row)
	{
		return new Cup($row['id'], $row['time_start'], $row['time_end'], $row['name'], $row['description'], $row['organizer_club_id']);
	}

	/**
	 * @param $cupID
	 * @return string[]
	 */
	public function GetCupNameByID($cupID)
	{
		//$statement = $this->mysqli->prepare('SELECT `name` FROM `sp_cups` WHERE id=? LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetCupNameByID`(?)');
		$statement->bind_param('i', $cupID);

		return $this->_GetSingleResultFromStatement($statement);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return string[]
	 */
	public function _GetSingleResultFromStatement(mysqli_stmt $statement){
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		if($rows==NULL)
		{
			//OR NULL
			return null;
		}
		else
		{
			$row = $rows[0];
			return $row[0];
		}
	}

	/**
	 * @param $cupID
	 * @return Cup|null
	 */
	public function GetCupByID($cupID)
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `time_start`, `time_end`, `name`, `description`, `organizer_club_id` FROM `sp_cups` WHERE id=?');
		$statement = $this->mysqli->prepare('CALL `GetCupByID`(?)');
		$statement->bind_param('i', $cupID);

		return $this->_CreateCupOrNullFromStatement($statement);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return Cup|null
	 */
	public function _CreateCupOrNullFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row !== NULL)
		{
			return $this->_CreateCupFromRow($row);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * @param $cupID
	 * @return PairPositionUser[]
	 */
	public function FindPairingsForThisCup($cupID)
	{
		//$statement = $this->mysqli->prepare('SELECT `position_id`, `user_id` FROM `sp_user_position_pairing` WHERE `cup_id`=? ORDER BY `position_id`');
		$statement = $this->mysqli->prepare('CALL `FindPairingsForThisCup`(?)');
		$statement->bind_param('i', $cupID);
		$pairs = $this->_CreatePairsFromStatement($statement);

		return $pairs;
	}

	public function GetPairingHashForThisCup($cupID)
	{
		//$statement = $this->mysqli->prepare('SELECT MD5(GROUP_CONCAT(CONCAT(`position_id`, `user_id`))) as hash FROM `sp_user_position_pairing` WHERE `cup_id`=? ORDER BY `position_id`');
		$statement = $this->mysqli->prepare('CALL `HashPairingForThisCup`(?)');
		$statement->bind_param('i', $cupID);

		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * @param mysqli_stmt $statement
	 * @return PairPositionUser[]
	 */
	public function _CreatePairsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

		$pairs = [];
		foreach($rows as $row)
		{
			$pairs[]=$this->_CreatePairFromRow($row);
		}
		return $pairs;
	}

	/**
	 * @param array $row
	 * @return PairPositionUser
	 */
	public function _CreatePairFromRow(array $row)
	{
		return new PairPositionUser($row['position_id'], $row['user_id']);
	}

	/**
	 * @param $name
	 * @param $date_begin
	 * @param $date_end
	 * @param $club
	 * @param $content
	 * @return bool
	 */
	public function InsertNewCup($name, $date_begin, $date_end, $club, $content)
	{
		//$statement = $this->mysqli->prepare('INSERT INTO `sp_cups` (`time_start`, `time_end`, `name`, `description`, `organizer_club_id`) VALUES (?,?,?,?,?)');
		$statement = $this->mysqli->prepare('CALL `InsertNewCup`(?,?,?,?,?)');
		$statement->bind_param('sssis', $name, $date_begin, $date_end, $club, $content);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function GetNewCupIDByInfo($name, $date_begin, $date_end)
	{
		//$statement = $this->mysqli->prepare('SELECT id FROM `sp_cups` WHERE name=? AND time_start=? AND time_end=?');
		$statement = $this->mysqli->prepare('CALL `GetNewCupIDByInfo`(?,?,?)');
		$statement->bind_param('sss', $name, $date_begin, $date_end);

		return $this->_GetSingleResultFromStatement($statement);
	}

	/**
	 * check if my ID is registered
	 */
	public function IsUserAvailableForTheCup($userID, $cupID)
	{
		//$statement = $this->mysqli->prepare('SELECT * FROM `sp_user_cup_availability` WHERE `cup_id`=? AND `user_id`=?');
		$statement = $this->mysqli->prepare('CALL `IsUserAvailableForTheCup`(?,?)');
		$statement->bind_param('ii', $cupID, $userID);
		$statement->execute();
		//echo "userid: " . $userId . ", cupid: " . $cupId . "</br>";
		$res = $statement->get_result();
		//echo $res->num_rows . "</br>";
		if ($res->num_rows==0)
		{
			//echo "not registered";
			return false;
		}
		else if ($res->num_rows==1)
		{
			//echo "registered";
			return true;
		}
		else
		{
			throw new RuntimeException(); //more registrations for one cup, wtf
		}
	}

	public function UpdatePairingForThisCup($cupID, $json)
	{
		$this->mysqli->begin_transaction();
		try {
			//$statement = $this->mysqli->prepare('DELETE FROM `sp_user_position_pairing` WHERE `cup_id`=?');
			$statement = $this->mysqli->prepare('CALL `DeleteOldPairing`(?)');
			$statement->bind_param('i', $cupID);
			$statement->execute();


			foreach ($json as $record) {
				if (!isset($record["position_id"], $record["user_id"])) {
					throw  new RuntimeException();
				} elseif (!ctype_digit($record["idpoz"]) || !ctype_digit($record["iduser"])) {
					throw new RuntimeException();
				}
				//$statement = $this->mysqli->prepare('INSERT INTO `sp_user_position_pairing` (`id`, `cup_id`, `position_id`, `user_id`) VALUES (NULL, ? , ? , ?)');
				$statement = $this->mysqli->prepare('CALL `InsertNewPair`(?,?,?)');
				$statement->bind_param('iii', $cupID, $record["position_id"], $record["user_id"]);
				$statement->execute();
			}
			$this->mysqli->commit();
			return true;
		}
		catch (RuntimeException $e){
			//echo $e->getMessage();
			$this->mysqli->rollback();
			return false;
		}
	}

	//TODO check this (availability_flag) - automatic value 1, still insert null value into column
	public function UpdateAvailabilityForThisCup($cupID, $json)
	{
		$this->mysqli->begin_transaction();
		try {
			//drop all availabilities
			//$statement = $this->mysqli->prepare('DELETE FROM `sp_user_cup_availability` WHERE `cup_id`=?');
			$statement = $this->mysqli->prepare('CALL `DeleteOldAvailability`(?)');
			$statement->bind_param('i', $cupID);
			$statement->execute();


			foreach ($json as $record){
				if(!isset($record["cup_id"], $record["user_id"])) {
					throw new RuntimeException();
				} elseif (!ctype_digit($record["cup_id"]) || !ctype_digit($record["user_id"])) {
					throw new RuntimeException();
				}
				//$statement = $this->mysqli->prepare('INSERT INTO `sp_user_cup_availability` (`id`, `cup_id`, `user_id`) VALUES (NULL, ?, ?)');
				$statement = $this->mysqli->prepare('CALL `InsertNewAvailability`(?,?)');
				$statement->bind_param('ii', $record['cup_id'], $record['user_id']);
				$statement->execute();
			}
			$this->mysqli->commit();
			return true;
		}
		catch (RuntimeException $e){
			//echo $e->getMessage();
			$this->mysqli->rollback();
			return false;
		}
		return true;
	}

	//TODO insert null into automatic 1 flag
	public function AddAvailableUserForTheCup($cupID, $userID)
	{
		$this->mysqli->begin_transaction();
		try {
			//$statement = $this->mysqli->prepare('INSERT INTO `sp_user_cup_availability` (`id`, `cup_id`, `user_id`) VALUES (NULL, ?, ?)');
			$statement = $this->mysqli->prepare('CALL `InsertNewAvailability`(?,?)');
			$statement->bind_param('ii', $cupID, $userID);
			$statement->execute();

			$this->mysqli->commit();
			return true;
		}
		catch (RuntimeException $e) {
			//echo $e->getMessage();
			$this->mysqli->rollback();
			return false;
		}
	}

	public function GetEarliestCupYear()
	{
		//$statement = $this->mysqli->prepare('SELECT YEAR(`time_start`) FROM `sp_cups` ORDER BY `time_start` ASC LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetEarliestCupYear`()');
		$_ret = $this->_GetSingleResultFromStatement($statement);
		if($_ret!=null)
		{
			return $_ret;
		}
		else
		{
			return date("Y");
		}
	}

	public function GetMaximumCupYear()
	{
		return date("Y");
	}
}