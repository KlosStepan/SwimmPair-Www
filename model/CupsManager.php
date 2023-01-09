<?php

class CupsManager
{
	private $mysqli;
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}

	public function FindAllUpcomingCupsEarliestFirst()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllUpcomingCupsEarliestFirst`()');
		$cups = $this->_CreateCupsFromStatement($statement);
		return $cups;
	}
	public function FindAllPastCupsMostRecentFirst()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllPastCupsMostRecentFirst`()');
		$cups = $this->_CreateCupsFromStatement($statement);
		return $cups;
	}
	public function FindPairingsForThisCup($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `FindPairingsForThisCup`(?)');
		$statement->bind_param('i', $cupID);
		$pairs = $this->_CreatePairsFromStatement($statement);
		return $pairs;
	}
	public function GetPairingHashForThisCup($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `HashPairingForThisCup`(?)');
		$statement->bind_param('i', $cupID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	public function GetNewCupIDByInfo($name, $date_begin, $date_end)
	{
		$statement = $this->mysqli->prepare('CALL `GetNewCupIDByInfo`(?,?,?)');
		$statement->bind_param('sss', $name, $date_begin, $date_end);
		return $this->_GetSingleResultFromStatement($statement);
	}
	public function IsUserAvailableForTheCup($userID, $cupID)
	{
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
			throw new RuntimeException(); //more registrations for one cup
		}
	}
	public function GetEarliestCupYear()
	{
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
		return (int)date("Y");
	}
	//Cup handling
	public function GetCupByID($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `GetCupByID`(?)');
		$statement->bind_param('i', $cupID);
		return $this->_CreateCupOrNullFromStatement($statement);
	}
	public function InsertNewCup($name, $date_begin, $date_end, $club, $content)
	{
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
	//public function UpdateCup($cupId) - TODO
	//public function DeleteCup($cupId) - TODO
	//Cup Availability
	public function DeleteOldAvailability($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `DeleteOldAvailability`(?)');
		$statement->bind_param('i', $cupID);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function InsertNewAvailability($cupID, $userID, $attendanceFlag)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewAvailability`(?,?,?)');
		$statement->bind_param('iii', $cupID, $userID, $attendanceFlag);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//Cup Pairing
	public function DeleteOldPairing($cupID)
	{
		$statement = $this->mysqli->prepare('CALL `DeleteOldPairing`(?)');
		$statement->bind_param('i', $cupID);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function InsertNewPairing($cupID, $posID, $userID)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewPairing`(?,?,?)');
		$statement->bind_param('iii', $cupID, $posID, $userID);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
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
	public function _CreateCupFromRow(array $row)
	{
		return new Cup($row['id'], $row['time_start'], $row['time_end'], $row['name'], $row['description'], $row['organizer_club_id']);
	}
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
	public function _CreatePairFromRow(array $row)
	{
		return new PairPositionUser($row['position_id'], $row['user_id']);
	}
	public function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		if($rows==NULL)
		{
			return null;
		}
		else
		{
			$row = $rows[0];
			return $row[0];
		}
	}
}