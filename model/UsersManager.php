<?php
/**
 * Summary of UsersManager
 */
class UsersManager
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
	 * Summary of GetUserByID
	 * @param mixed $id
	 * @return User|null
	 */
	public function GetUserByID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetUserByID`(?)');
		$statement->bind_param('i', $id);
		return $this->_CreateUserOrNullFromStatement($statement);
	}
	/**
	 * Summary of FindAllActiveUsersOrderByLastNameAsc
	 * @return array<User>
	 */
	public function FindAllActiveUsersOrderByLastNameAsc()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllActiveUsersOrderByLastNameAsc`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Summary of FindAllInactiveUsersOrderByLastNameAsc
	 * @return array<User>
	 */
	public function FindAllInactiveUsersOrderByLastNameAsc()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllInactiveUsersOrderByLastNameAsc`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Summary of FindAllRegisteredTeamMembersForTheCup
	 * @param mixed $cupId
	 * @param mixed $teamId
	 * @return array<User>
	 */
	public function FindAllRegisteredTeamMembersForTheCup($cupId, $teamId)
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRegisteredTeamMembersForTheCup`(?,?)');
		$statement->bind_param('ii', $cupId, $teamId);
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Summary of FindAllTeamMembers
	 * @param mixed $teamId
	 * @return array<User>
	 */
	public function FindAllTeamMembers($teamId)
	{
		$statement = $this->mysqli->prepare('CALL `FindAllTeamMembers`(?)');
		$statement->bind_param('i', $teamId);
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Summary of FindAllUsers
	 * @return array<User>
	 */
	public function FindAllUsers()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllUsers`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Summary of FindAllRegisteredUsersForTheCup
	 * @param mixed $cupId
	 * @return array<User>
	 */
	public function FindAllRegisteredUsersForTheCup($cupId)
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRegisteredUsersForTheCup`(?)');
		$statement->bind_param('i', $cupId);
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Summary of FindPairedUsersOnCupForPosition
	 * @param mixed $cupId
	 * @param mixed $position
	 * @return array<User>
	 */
	public function FindPairedUsersOnCupForPosition($cupId, $position)
	{
		$statement = $this->mysqli->prepare('CALL `FindPairedUsersOnCupForPosition`(?,?)');
		$statement->bind_param('ii', $cupId, $position);
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Summary of GetClubAbbreviationByAffiliationID
	 * @param mixed $id
	 * @return mixed
	 */
	public function GetClubAbbreviationByAffiliationID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetClubAbbreviationByAffiliationID`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Summary of GetClubNameByAffiliationID
	 * @param mixed $id
	 * @return mixed
	 */
	public function GetClubNameByAffiliationID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetClubNameByAffiliationID`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Summary of GetUserFullNameByID
	 * @param mixed $userID
	 * @return string
	 */
	public function GetUserFullNameByID($userID)
	{
		$statement = $this->mysqli->prepare('CALL `GetUserFullNameByID`(?)');
		$statement->bind_param('i', $userID);
		return $this->_GetSingleResultFromTwoColsStatement($statement);
	}
	/**
	 * Summary of FindAllRefereeRanks
	 * @return array<RefereeRank>
	 */
	public function FindAllRefereeRanks()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRefereeRanks`()');
		$refereeRanks = $this->_CreateRefereeRanksFromStatement($statement);
		return $refereeRanks;
	}
	/**
	 * Summary of GetRefereeRank
	 * @param mixed $id
	 * @return mixed
	 */
	public function GetRefereeRank($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetRefereeRank`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	//Availability flagging 3 tagging functions
	/**
	 * Summary of IsComing
	 * @param mixed $cupID
	 * @param mixed $userID
	 * @return bool
	 */
	public function IsComing($cupID, $userID)
	{
		$res = $this->_IsComingINT($cupID, $userID);
		if ($res === 1) {
			return true;
		} elseif ($res == 0) {
			return false;
		} else {
			return true;
		}
	}
	/**
	 * Summary of RetComingCSSClass
	 * @param mixed $cupID
	 * @param mixed $userID
	 * @return string
	 */
	public function RetComingCSSClass($cupID, $userID)
	{
		$res = $this->_IsComingINT($cupID, $userID);
		if ($res === 1) {
			return "registeredOne";
		} else {
			return "unRegisteredOne";
		}
	}
	/**
	 * Summary of RetStringComingFlag
	 * @param mixed $cupID
	 * @param mixed $userID
	 * @return int|string
	 */
	public function RetStringComingFlag($cupID, $userID)
	{
		$res = $this->_IsComingINT($cupID, $userID);
		if ($res === 1) {
			return "1";
		} elseif ($res === 0) {
			return "0";
		} else // $res == null - nove prihlaseny, neni v databazi = going 
		{
			return "1";
		}
	}
	//TODO mby PK unique email
	/**
	 * Summary of GetUserEmailByID
	 * @param mixed $userID
	 * @return mixed
	 */
	public function GetUserEmailByID($userID)
	{
		$statement = $this->mysqli->prepare('CALL `GetUserEmailByID`(?)');
		$statement->bind_param('i', $userID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Summary of SetPasswordForUser
	 * @param mixed $uid
	 * @param mixed $passwd
	 * @return bool
	 */
	public function SetPasswordForUser($uid, $passwd)
	{
		$passwdHashed = password_hash($passwd, PASSWORD_BCRYPT);
		$statement = $this->mysqli->prepare('CALL `SetPasswordForUser`(?,?)');
		$statement->bind_param('is', $uid, $passwdHashed);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of SetLoginEmailForUser
	 * @param mixed $uid
	 * @param mixed $email
	 * @return bool
	 */
	public function SetLoginEmailForUser($uid, $email)
	{
		$statement = $this->mysqli->prepare('CALL `SetLoginEmailForUser`(?,?)');
		$statement->bind_param('is', $uid, $email);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of SetRefereeRankForUser
	 * @param mixed $uid
	 * @param mixed $rank
	 * @return bool
	 */
	public function SetRefereeRankForUser($uid, $rank)
	{
		$statement = $this->mysqli->prepare('CALL `SetRefereeRankForUser`(?,?)');
		$statement->bind_param('ii', $uid, $rank);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	//Availability 3 PROCs vv
	/**
	 * Summary of SetAvailabilityRegister
	 * @param mixed $uid
	 * @param mixed $cid
	 * @return bool
	 */
	public function SetAvailabilityRegister($uid, $cid)
	{
		$statement = $this->mysqli->prepare('CALL `SetAvailabilityRegister`(?,?)');
		$statement->bind_param('ii', $cid, $uid);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of SetAvailabilityCanGo
	 * @param mixed $uid
	 * @param mixed $cid
	 * @return bool
	 */
	public function SetAvailabilityCanGo($uid, $cid)
	{
		$statement = $this->mysqli->prepare('CALL `SetAvailabilityCanGo`(?,?)');
		$statement->bind_param('ii', $cid, $uid);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of SetAvailabilityCantGo
	 * @param mixed $uid
	 * @param mixed $cid
	 * @return bool
	 */
	public function SetAvailabilityCantGo($uid, $cid)
	{
		$statement = $this->mysqli->prepare('CALL `SetAvailabilityCantGo`(?,?)');
		$statement->bind_param('ii', $cid, $uid);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of IsEmailPresentAlready
	 * @param mixed $email
	 * @return bool
	 */
	public function IsEmailPresentAlready($email)
	{
		//echo "emailova adresa".$email."\r\n";
		$email_escaped = mysqli_real_escape_string($this->mysqli, $email);
		$statement = $this->mysqli->prepare('CALL `IsEmailPresentAlready`(?)');
		$statement->bind_param('s', $email_escaped);
		$statement->execute();
		$statement->store_result();
		$rows = $statement->num_rows();
		if ($rows == 1) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of IsUserWithIDPresentAlready
	 * @param mixed $id
	 * @return bool
	 */
	public function IsUserWithIDPresentAlready($id)
	{
		$statement = $this->mysqli->prepare('CALL `IsUserWithIDPresentAlready`(?)');
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->store_result();
		$rows = $statement->num_rows();
		if ($rows == 1) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of RegisterUser
	 * @param mixed $first_name
	 * @param mixed $last_name
	 * @param mixed $email
	 * @param mixed $password
	 * @param mixed $rights
	 * @param mixed $refRank
	 * @param mixed $klubaffil
	 * @return bool
	 */
	public function RegisterUser($first_name, $last_name, $email, $password, $rights, $refRank, $klubaffil)
	{
		$passwordHashed = password_hash($password, PASSWORD_BCRYPT);
		$hash = md5(rand(0, 1000));
		//approved, activated
		$statement = $this->mysqli->prepare('CALL `RegisterUser`(?,?,?,?,?,1,1,?,?,?)');
		$statement->bind_param('sssssiii', $first_name, $last_name, $email, $passwordHashed, $hash, $rights, $refRank, $klubaffil);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	//TODO mby uncomment and test at server after deployment
	/**
	 * Summary of EmailNewPersonRegistered
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool
	 */
	public function EmailNewPersonRegistered($email, $password)
	{
		$to = $email;
		$subject = "Registrace do plaveckeho systemu";
		$message_body = 'Dobry den, byla provedena Vase registrace do plaveckeho systemu. Prihlasite se pomoci Vaseho emailu a hesla ' . $password . "ktere Vam bylo vytvoreno. Pekny den.";
		/*
		if (mail($to, $subject, $message_body)) {
		return true;
		} else {
		return false;
		}*/
		return false;
	}
	/**
	 * Summary of SetApprovedForUser
	 * @param mixed $userID
	 * @return bool
	 */
	public function SetApprovedForUser($userID)
	{
		$statement = $this->mysqli->prepare('CALL `SetApprovedForUser`(?)');
		$statement->bind_param('i', $userID);
		if ($statement->execute()) {
			//echo "Approved userID:".$userID.".";
			return true;
		} else {
			return false;
		}
	}
	//TODO mby filter out NOT ATTENDING...&WHERE ATTENDANCE_FLAG = 1
	/**
	 * Summary of CountCupsAttendanceOfUserGivenYear
	 * @param mixed $userID
	 * @param mixed $year
	 * @return mixed
	 */
	public function CountCupsAttendanceOfUserGivenYear($userID, $year)
	{
		$statement = $this->mysqli->prepare('CALL `CntCupsAttendOfUserGivenYear`(?,?)');
		$statement->bind_param('ii', $userID, $year);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Summary of CountOverallStatisticsOfUserGivenYear
	 * @param mixed $userID
	 * @param mixed $year
	 * @return array<StatPositionCnt>
	 */
	public function CountOverallStatisticsOfUserGivenYear($userID, $year)
	{
		$statement = $this->mysqli->prepare('CALL `CntOverallStatsOfUserGivenYear`(?,?)');
		$statement->bind_param('ii', $userID, $year);
		$idpoz_cnts = $this->_CreateStatsFromStatement($statement);
		return $idpoz_cnts;
	}
	/**
	 * Summary of CountClubSeasonalStatistics
	 * @param mixed $clubID
	 * @param mixed $year
	 * @return array<StatUserCnt>
	 */
	public function CountClubSeasonalStatistics($clubID, $year)
	{
		$statement = $this->mysqli->prepare('CALL `CntClubSeasonalStats`(?,?)');
		$statement->bind_param('ii', $clubID, $year);
		$idpoz_cnts = $this->_CreateClubStatisticsFromStatement($statement);
		return $idpoz_cnts;
	}
	/**
	 * Summary of LoginCandidateToBeAuthorized
	 * @param mixed $email
	 * @return array|bool|null
	 */
	public function LoginCandidateToBeAuthorized($email)
	{
		$statement = $this->mysqli->prepare('CALL `LoginCandidateToBeAuthorized`(?)');
		$statement->bind_param('s', $email);
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		return $row;
	}
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
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
	//
	/**
	 * Summary of _CreateUsersFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<User>
	 */
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
	/**
	 * Summary of _CreateUserOrNullFromStatement
	 * @param mysqli_stmt $statement
	 * @return User|null
	 */
	public function _CreateUserOrNullFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row !== NULL) {
			return $this->_CreateUserFromRow($row);
		} else {
			return NULL;
		}
	}
	/**
	 * Summary of _CreateUserFromRow
	 * @param array $row
	 * @return User
	 */
	public function _CreateUserFromRow(array $row)
	{
		return new User($row['id'], $row['first_name'], $row['last_name'], $row['email'], $row['approved_flag'], $row['rights'], $row['referee_rank_id'], $row['affiliation_club_id']);
	}
	//
	/**
	 * Summary of _CreateRefereeRanksFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<RefereeRank>
	 */
	public function _CreateRefereeRanksFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$refereeRanks = [];
		foreach ($rows as $row) {
			$refereeRanks[] = $this->_CreateRefereeRankFromRow($row);
		}
		return $refereeRanks;
	}
	/**
	 * Summary of _CreateRefereeRankFromRow
	 * @param array $row
	 * @return RefereeRank
	 */
	public function _CreateRefereeRankFromRow(array $row)
	{
		return new RefereeRank($row['id'], $row['name']);
	}
	//
	/**
	 * Summary of _CreateStatsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<StatPositionCnt>
	 */
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
	/**
	 * Summary of _CreateStatFromRow
	 * @param array $row
	 * @return StatPositionCnt
	 */
	public function _CreateStatFromRow(array $row)
	{
		return new StatPositionCnt($row['position_id'], $row['cnt']);
	}
	//
	/**
	 * Summary of _CreateClubStatisticsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<StatUserCnt>
	 */
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
	/**
	 * Summary of _CreateSingleUserStatFromRow
	 * @param array $row
	 * @return StatUserCnt
	 */
	public function _CreateSingleUserStatFromRow(array $row)
	{
		return new StatUserCnt($row['user_id'], $row['cnt']);
	}
	//
	/**
	 * Summary of _IsComingINT
	 * @param mixed $cupID
	 * @param mixed $userID
	 * @return mixed
	 */
	public function _IsComingINT($cupID, $userID)
	{
		$statement = $this->mysqli->prepare('CALL `IsComingINT`(?, ?)');
		$statement->bind_param('ii', $cupID, $userID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	//
	/**
	 * Summary of _GetSingleResultFromTwoColsStatement
	 * @param mysqli_stmt $statement
	 * @return string
	 */
	private function _GetSingleResultFromTwoColsStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		$result = $row[0] . " " . $row[1];
		return $result;
	}
	/**
	 * Summary of _GetSingleResultFromStatement
	 * @param mysqli_stmt $statement
	 * @return mixed
	 */
	private function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		if (!empty($rows)) {
			$row = $rows[0];
			return $row[0];
		} else {
			return null;
		}
	}
}