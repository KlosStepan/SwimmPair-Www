<?php
/**
 * UsersManager has API functions to handle User object/s and delivers is through web application.
 */
class UsersManager
{
	private $mysqli;
	/**
	 * Initialize UsersManager with live database connection.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	/**
	 * Get User by provided id.
	 * @param int $id
	 * @return User|null
	 */
	public function GetUserByID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetUserByID`(?)');
		$statement->bind_param('i', $id);
		return $this->_CreateUserOrNullFromStatement($statement);
	}
	/**
	 * Get list of active users alphabetically.
	 * @return array<User>
	 */
	public function FindAllActiveUsersOrderByLastNameAsc()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllActiveUsersOrderByLastNameAsc`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Get list of inactive user alphabetically.
	 * @return array<User>
	 */
	public function FindAllInactiveUsersOrderByLastNameAsc()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllInactiveUsersOrderByLastNameAsc`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * Get list of inactive users alphabetically.
	 * @param int $cupId
	 * @param int $teamId
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
	 * List of registered User teammates for desired Cup. 
	 * @param int $teamId
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
	 * List of all users from the web application.
	 * @return array<User>
	 */
	public function FindAllUsers()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllUsers`()');
		$users = $this->_CreateUsersFromStatement($statement);
		return $users;
	}
	/**
	 * List of registered users for Cup.
	 * @param int $cupId
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
	 * Get list of User s that are on Cup on Position. 
	 * @param int $cupId
	 * @param int $position
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
	 * Retrieve club abbreviation - short name code, provided Club id.
	 * @param int $id
	 * @return string
	 */
	public function GetClubAbbreviationByAffiliationID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetClubAbbreviationByAffiliationID`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Retrieve Club name by id.
	 * @param int $id
	 * @return string
	 */
	public function GetClubNameByAffiliationID($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetClubNameByAffiliationID`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Retrieve full name (first_name + last_name) of User by provided id.
	 * @param int $userID
	 * @return string
	 */
	public function GetUserFullNameByID($userID)
	{
		$statement = $this->mysqli->prepare('CALL `GetUserFullNameByID`(?)');
		$statement->bind_param('i', $userID);
		return $this->_GetSingleResultFromTwoColsStatement($statement);
	}
	/**
	 * Find all referee ranks in the web application.
	 * @return array<RefereeRank>
	 */
	public function FindAllRefereeRanks()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRefereeRanks`()');
		$refereeRanks = $this->_CreateRefereeRanksFromStatement($statement);
		return $refereeRanks;
	}
	/**
	 * Get referee rank name by its id.
	 * @param int $id
	 * @return string
	 */
	public function GetRefereeRank($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetRefereeRank`(?)');
		$statement->bind_param('i', $id);
		return $this->_GetSingleResultFromStatement($statement);
	}
	//Availability flagging 3 tagging functions
	/**
	 * Answer T/F if User is coming to Cup.
	 * @param int $cupID
	 * @param int $userID
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
	 * Return CSS class for element if User is/not coming for Cup.
	 * @param int $cupID
	 * @param int $userID
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
	 * Return string flag of coming/not coming as "1" or "0"
	 * @param int $cupID
	 * @param int $userID
	 * @return string
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
	 * Retrieve e-mail of User based on his id.
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
	 * Set new password for User as sysadmin.
	 * @param int $uid
	 * @param string $passwd
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
	 * Set new login email for User as sysadmin.
	 * @param int $uid
	 * @param string $email
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
	 * Set new referee rank for User as sysadmin.
	 * @param int $uid
	 * @param int $rank
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
	 * Set User's availability for cup.
	 * @param int $uid
	 * @param int $cid
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
	 * Flag available User as can go - TRUE.
	 * @param int $uid
	 * @param int $cid
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
	 * Flag available User as can't go - FALSE.
	 * @param int $uid
	 * @param int $cid
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
	 * Ask if User with this e-mail is already present in the system.
	 * @param string $email
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
	 * Ask if User with this id is already present in the system.
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
	 * Register new user in the web application.
	 * @param string $first_name
	 * @param string $last_name
	 * @param string $email
	 * @param string $password
	 * @param int $rights
	 * @param int $refRank
	 * @param int $klubaffil
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
	 * TODO - function that e-mails person and notifies him/her that registration to our web application happened. 
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
	 * Set approved flag for new User so he/she can use the system.
	 * @param int $userID
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
	 * Stats function that returns howmany cups User has attended for given year.
	 * @param int $userID
	 * @param int $year
	 * @return int
	 */
	public function CountCupsAttendanceOfUserGivenYear($userID, $year)
	{
		$statement = $this->mysqli->prepare('CALL `CntCupsAttendOfUserGivenYear`(?,?)');
		$statement->bind_param('ii', $userID, $year);
		return $this->_GetSingleResultFromStatement($statement);
	}
	/**
	 * Stats function that returns pairs StatPositionCnt of User for given year.
	 * @param int $userID
	 * @param int $year
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
	 * Stats function that returns User attendances for cups in given year.
	 * @param int $clubID
	 * @param int $year
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
	 * TODO LoginCandidateToBeAuthorized / mby depr.
	 * @param string $email
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
	private function _CreatePairsFromStatement(mysqli_stmt $statement)
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
	private function _CreatePairFromRow(array $row)
	{
		return new PairPositionUser($row['position_id'], $row['user_id']);
	}
	//
	/**
	 * Summary of _CreateUsersFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<User>
	 */
	private function _CreateUsersFromStatement(mysqli_stmt $statement)
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
	private function _CreateUserOrNullFromStatement(mysqli_stmt $statement)
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
	private function _CreateUserFromRow(array $row)
	{
		return new User($row['id'], $row['first_name'], $row['last_name'], $row['email'], $row['approved_flag'], $row['rights'], $row['referee_rank_id'], $row['affiliation_club_id']);
	}
	//
	/**
	 * Summary of _CreateRefereeRanksFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<RefereeRank>
	 */
	private function _CreateRefereeRanksFromStatement(mysqli_stmt $statement)
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
	private function _CreateRefereeRankFromRow(array $row)
	{
		return new RefereeRank($row['id'], $row['name']);
	}
	//
	/**
	 * Summary of _CreateStatsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<StatPositionCnt>
	 */
	private function _CreateStatsFromStatement(mysqli_stmt $statement)
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
	private function _CreateStatFromRow(array $row)
	{
		return new StatPositionCnt($row['position_id'], $row['cnt']);
	}
	//
	/**
	 * Summary of _CreateClubStatisticsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<StatUserCnt>
	 */
	private function _CreateClubStatisticsFromStatement(mysqli_stmt $statement)
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
	private function _CreateSingleUserStatFromRow(array $row)
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
	private function _IsComingINT($cupID, $userID)
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