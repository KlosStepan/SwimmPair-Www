<?php

class UsersManager
{
	private $mysqli;
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	public function GetUserByID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetUserByID`(?)');
		$statement->bind_param('i', $id);
		return $this->_CreateUserOrNullFromStatement($statement);
	}
	public function FindAllActiveUsersOrderByLastNameAsc()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllActiveUsersOrderByLastNameAsc`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	public function FindAllInactiveUsersOrderByLastNameAsc()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllInactiveUsersOrderByLastNameAsc`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	public function FindAllRegisteredTeamMembersForTheCup($cupId, $teamId)
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRegisteredTeamMembersForTheCup`(?,?)');
		$statement->bind_param('ii', $cupId, $teamId);
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	public function FindAllTeamMembers($teamId)
	{
		$statement = $this->mysqli->prepare('CALL `FindAllTeamMembers`(?)');
		$statement->bind_param('i', $teamId);
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	public function FindAllUsers()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllUsers`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	public function FindAllRegisteredUsersForTheCup($cupId)
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRegisteredUsersForTheCup`(?)');
		$statement->bind_param('i', $cupId);
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	public function FindPairedUsersOnCupForPosition($cupId, $position)
	{
		$statement = $this->mysqli->prepare('CALL `FindPairedUsersOnCupForPosition`(?,?)');
		$statement->bind_param('ii', $cupId, $position);
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	public function GetClubAbbreviationByAffiliationID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetClubAbbreviationByAffiliationID`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	public function GetClubNameByAffiliationID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetClubNameByAffiliationID`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	public function GetUserFullNameByID($userID)
	{
		$statement = $this->mysqli->prepare('CALL `GetUserFullNameByID`(?)');
		$statement->bind_param('i', $userID);
		return $this->_GetSingleResultFromTwoColsStatement($statement);
	}
	public function FindAllRefereeRanks()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRefereeRanks`()');
		$refereeRanks = $this->_CreateRefereeRanksFromStatement($statement);
		return $refereeRanks;
	}
	public function GetRefereeRank($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetRefereeRank`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	//Availability flagging 3 tagging functions
	public function IsComing($cupID, $userID)
	{
		$res = $this->_IsComingINT($cupID, $userID);
		if($res===1)
		{
			return true;
		}
		elseif($res==0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	public function RetComingCSSClass($cupID, $userID)
	{
		$res = $this->_IsComingINT($cupID, $userID);
		if($res===1)
		{
			return "registeredOne";
		}
		else
		{
			return "unRegisteredOne";
		}
	}
	public function RetStringComingFlag($cupID, $userID)
	{
		$res = $this->_IsComingINT($cupID, $userID);
		if($res===1)
		{
			return "1";
		}
		elseif($res===0)
		{
			return "0";
		}
		else // $res == null - nove prihlaseny, neni v databazi = going 
		{
			return "1";
		}
	}
	//TODO mby PK unique email
	public function GetUserEmailByID($userID)
	{
		$statement = $this->mysqli->prepare('CALL `GetUserEmailByID`(?)');
		$statement->bind_param('i', $userID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	public function SetPasswordForUser($uid, $passwd)
	{
		$passwdHashed = password_hash($passwd, PASSWORD_BCRYPT);
		$statement = $this->mysqli->prepare('CALL `SetPasswordForUser`(?,?)');
		$statement->bind_param('is', $uid, $passwdHashed);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function SetLoginEmailForUser($uid, $email)
	{
		$statement = $this->mysqli->prepare('CALL `SetLoginEmailForUser`(?,?)');
		$statement->bind_param('is', $uid, $email);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function SetRefereeRankForUser($uid, $rank)
	{
		$statement = $this->mysqli->prepare('CALL `SetRefereeRankForUser`(?,?)');
		$statement->bind_param('ii', $uid, $rank);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//Availability 3 PROCs vv
	public function SetAvailabilityRegister($uid, $cid)
	{
		$statement = $this->mysqli->prepare('CALL `SetAvailabilityRegister`(?,?)');
		$statement->bind_param('ii', $cid, $uid);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function SetAvailabilityCanGo($uid, $cid)
	{
		$statement = $this->mysqli->prepare('CALL `SetAvailabilityCanGo`(?,?)');
		$statement->bind_param('ii', $cid, $uid);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function SetAvailabilityCantGo($uid, $cid)
	{
		$statement = $this->mysqli->prepare('CALL `SetAvailabilityCantGo`(?,?)');
		$statement->bind_param('ii', $cid, $uid);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function IsEmailPresentAlready($email)
	{
		//echo "emailova adresa".$email."\r\n";
		$email_escaped = mysqli_real_escape_string($this->mysqli, $email);
		$statement = $this->mysqli->prepare('CALL `IsEmailPresentAlready`(?)');
		$statement->bind_param('s', $email_escaped);
		$statement->execute();
		$statement->store_result();
		$rows = $statement->num_rows();
		if ($rows == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function IsUserWithIDPresentAlready($id)
	{
		$statement = $this->mysqli->prepare('CALL `IsUserWithIDPresentAlready`(?)');
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->store_result();
		$rows = $statement->num_rows();
		if ($rows == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function RegisterUser($first_name, $last_name, $email, $password, $rights, $refRank, $klubaffil)
	{
		$passwordHashed = password_hash($password, PASSWORD_BCRYPT);
		$hash = md5(rand(0, 1000));
		//approved, activated
		$statement = $this->mysqli->prepate('CALL `RegisterUser`(?,?,?,?,?,1,1,?,?,?)');
		$statement->bind_param('sssssiii', $first_name, $last_name, $email, $passwordHashed, $hash, $rights, $refRank, $klubaffil);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//TODO mby uncomment and test at server after deployment
	public function EmailNewPersonRegistered($email, $password)
	{
		$to = $email;
		$subject = "Registrace do plaveckeho systemu";
		$message_body = 'Dobry den, byla provedena Vase registrace do plaveckeho systemu. Prihlasite se pomoci Vaseho emailu a hesla '.$password."ktere Vam bylo vytvoreno. Pekny den.";
		/*
		if (mail($to, $subject, $message_body)) {
			return true;
		} else {
			return false;
		}*/
		return false;
	}
	public function SetApprovedForUser($userID)
	{
		$statement = $this->mysqli->prepare('CALL `SetApprovedForUser`(?)');
		$statement->bind_param('i', $userID );
		if($statement->execute())
		{
			//echo "Approved userID:".$userID.".";
			return true;
		}
		else
		{
			return false;
		}
	}
	//TODO mby filter out NOT ATTENDING...&WHERE ATTENDANCE_FLAG = 1
	public function CountCupsAttendanceOfUserGivenYear($userID, $year)
	{
		$statement = $this->mysqli->prepare('CALL `CntCupsAttendOfUserGivenYear`(?,?)');
		$statement->bind_param('ii', $userID, $year);
		return $this->_GetSingleResultFromStatement($statement);
	}
	public function CountOverallStatisticsOfUserGivenYear($userID, $year)
	{
		$statement = $this->mysqli->prepare('CALL `CntOverallStatsOfUserGivenYear`(?,?)');
		$statement->bind_param('ii', $userID, $year);
		$idpoz_cnts = $this->_CreateStatsFromStatement($statement);
		return $idpoz_cnts;
	}
	public function CountClubSeasonalStatistics($clubID, $year)
	{
		$statement = $this->mysqli->prepare('CALL `CntClubSeasonalStats`(?,?)');
		$statement->bind_param('ii', $clubID, $year);
		$idpoz_cnts = $this->_CreateClubStatisticsFromStatement($statement);
		return $idpoz_cnts;
	}
	public function LoginCandidateToBeAuthorized($email)
	{
		$statement = $this->mysqli->prepare('CALL `LoginCandidateToBeAuthorized`(?)');
		$statement->bind_param('s', $email);
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		return $row;
	}
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
	public function _CreatePairsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$pairs = [];
		foreach ($rows as $row)
		{
			$pairs[] = $this->_CreatePairFromRow($row);
		}
		return $pairs;
	}
	public function _CreatePairFromRow(array $row)
	{
		return new PairPositionUser($row['position_id'], $row['user_id']);
	}
	//
	public function _CreateUsersFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$users = [];
		foreach ($rows as $row) {
			$users[] = $this->_CreateUserFromRow($row);
		}

		return $users;
	}
	public function _CreateUserOrNullFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row !== NULL)
		{
			return $this->_CreateUserFromRow($row);
		}
		else
		{
			return NULL;
		}
	}
	public function _CreateUserFromRow(array $row)
	{
		return new User($row['id'], $row['first_name'], $row['last_name'], $row['email'], $row['approved_flag'], $row['rights'], $row['referee_rank_id'], $row['affiliation_club_id']);
	}
	//
	public function _CreateRefereeRanksFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$refereeRanks = [];
		foreach ($rows as $row)
		{
			$refereeRanks[]=$this->_CreateRefereeRankFromRow($row);
		}
		return $refereeRanks;
	}
	public function _CreateRefereeRankFromRow(array $row)
	{
		return new RefereeRank($row['id'], $row['name']);
	}
	//
	public function _CreateStatsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$stats = [];
		foreach ($rows as $row) {
			$stats[] = $this->_CreateStatFromRow($row);
		}
		return $stats;
	}
	public function _CreateStatFromRow(array $row)
	{
		return new StatPositionCnt($row['position_id'], $row['cnt']);
	}
	//
	public function _CreateClubStatisticsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$clubstats = [];
		foreach ($rows as $row) {
			$clubstats[] = $this->_CreateSingleUserStatFromRow($row);
		}
		return $clubstats;
	}
	public function _CreateSingleUserStatFromRow(array $row)
	{
		return new StatUserCnt($row['user_id'], $row['cnt']);
	}
	//
	public function _IsComingINT($cupID, $userID)
	{
		$statement = $this->mysqli->prepare('CALL `IsComingINT`(?, ?)');
		$statement->bind_param('ii', $cupID, $userID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	//
	private function _GetSingleResultFromTwoColsStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		$result = $row[0]." ".$row[1];
		return $result;
	}
	private function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		if(!empty($rows))
		{
			$row = $rows[0];
			return $row[0];
		}
		else
		{
			return null;
		}
	}
}
