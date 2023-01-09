<?php

class UsersManager
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

	/**
	 * @param int $id
	 * @return User|NULL
	 */
	public function GetUserByID($id)
	{
		//$statement = $this->mysqli->prepare('SELECT id, first_name, last_name, email, approved_flag, rights, referee_rank_id, affiliation_club_id FROM `sp_users` WHERE id=?');
		$statement = $this->mysqli->prepare('CALL `GetUserByID`(?)');
		$statement->bind_param('i', $id);

		return $this->_CreateUserOrNullFromStatement($statement);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return User|NULL
	 */
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

	/**
	 * @param array $row
	 * @return User
	 */
	public function _CreateUserFromRow(array $row)
	{
		//create User class and constructor which we're gonna call from here
		return new User($row['id'], $row['first_name'], $row['last_name'], $row['email'], $row['approved_flag'], $row['rights'], $row['referee_rank_id'], $row['affiliation_club_id']);
	}

	/**
	 * @return User[]
	 */
	public function FindAllActiveUsersOrderByLastNameAsc()
	{
		//$statement = $this->mysqli->prepare('SELECT id, first_name, last_name, email, approved_flag, rights, referee_rank_id, affiliation_club_id FROM `sp_users` WHERE approved_flag=1 ORDER BY last_name ASC');
		$statement = $this->mysqli->prepare('CALL `FindAllActiveUsersOrderByLastNameAsc`()');
		$users = $this->_CreateUsersFromStatement($statement);

		return $users;
	}

	/**
	 * @return User[]
	 */
	public function FindAllInactiveUsersOrderByLastNameAsc()
	{
		//$statement = $this->mysqli->prepare('SELECT id, first_name, last_name, email, approved_flag, rights, referee_rank_id, affiliation_club_id FROM `sp_users` WHERE approved_flag=0 ORDER BY last_name ASC');
		$statement = $this->mysqli->prepare('CALL `FindAllInactiveUsersOrderByLastNameAsc`()');
		$users = $this->_CreateUsersFromStatement($statement);

		return $users;
	}

	/** param comment */
	public function FindAllRegisteredTeamMembersForTheCup($cupId, $teamId)
	{
		//SELECT dostupnost.id, dostupnost.zavodid, dostupnost.userid, users.klubaffil FROM `dostupnost` INNER JOIN users ON dostupnost.userid = users.id WHERE dostupnost.zavodid = 4 AND users.klubaffil = 2
		//$statement = $this->mysqli->prepare('SELECT sp_user_cup_availability.user_id as id, sp_users.first_name, sp_users.last_name, sp_users.email, sp_users.approved_flag, sp_users.rights, sp_users.referee_rank_id, sp_users.affiliation_club_id FROM sp_user_cup_availability INNER JOIN sp_users ON sp_user_cup_availability.user_id = sp_users.id WHERE sp_user_cup_availability.cup_id=? AND sp_users.affiliation_club_id=?');
		$statement = $this->mysqli->prepare('CALL `FindAllRegisteredTeamMembersForTheCup`(?,?)');
		$statement->bind_param('ii', $cupId, $teamId);
		$users = $this->_CreateUsersFromStatement($statement);

		return $users;
	}

	/**
	 * ret User[]
	 */
	public function FindAllTeamMembers($teamId)
	{
		//bind number, call the aux functions
		//$statement = $this->mysqli->prepare('SELECT `id`, `first_name`, `last_name`, `email`, `approved_flag`, `rights`, `referee_rank_id`, `affiliation_club_id` FROM `sp_users` WHERE affiliation_club_id=?');
		$statement = $this->mysqli->prepare('CALL `FindAllTeamMembers`(?)');
		$statement->bind_param('i', $teamId);
		$users = $this->_CreateUsersFromStatement($statement);

		return $users;
	}

	/**
	 * @return User[]
	 */
	public function FindAllUsers()
	{
		//bind number, call the aux functions
		//$statement = $this->mysqli->prepare('SELECT `id`, `first_name`, `last_name`, `email`, `approved_flag`, `rights`, `referee_rank_id`, `affiliation_club_id` FROM `sp_users` ');
		$statement = $this->mysqli->prepare('CALL `FindAllUsers`()');
		//$statement->bind_param('i', $teamId);
		$users = $this->_CreateUsersFromStatement($statement);

		return $users;
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return User[]
	 */
	public function _CreateUsersFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		//var_dump($rows);
		$users = [];
		foreach ($rows as $row) {
			$users[] = $this->_CreateUserFromRow($row);
		}

		return $users;
	}

	// DOSTUPNOST frame
	/**
	 * @param $cupId
	 * @return User[]
	 */
	public function FindAllRegisteredUsersForTheCup($cupId)
	{
		//$statement = $this->mysqli->prepare('SELECT `sp_user_cup_availability`.`user_id` as id, `sp_users`.`first_name`, `sp_users`.`last_name`, `sp_users`.`email`, `sp_users`.`approved_flag`, `sp_users`.`rights`, `sp_users`.`referee_rank_id`, `sp_users`.`affiliation_club_id` FROM `sp_user_cup_availability` INNER JOIN `sp_users` ON `sp_user_cup_availability`.`user_id` = `sp_users`.`id` WHERE `sp_user_cup_availability`.`cup_id`=?');
		$statement = $this->mysqli->prepare('CALL `FindAllRegisteredUsersForTheCup`(?)');
		$statement->bind_param('i', $cupId);
		$users = $this->_CreateUsersFromStatement($statement);

		return $users;
	}

	//TODO prolly deprecate- lepi jmena dostupna
	// DODELAT for CUPid [{userid, dostupnost},...], mozna struktura pro dostupnost na webu a v mobilu
	/**
	 * @param $cupId
	 * @return User[]
	 */
	public function FindAllNametagsForTheCup($cupId)
	{
		//$statement = $this->mysqli->prepare('SELECT DISTINCT `user_id` as id, `sp_users`.`first_name`, `sp_users`.`last_name`, `sp_users`.`email`, `sp_users`.`approved_flag`, `sp_users`.`rights`, `sp_users`.`referee_rank_id`, `sp_users`.`affiliation_club_id` FROM `sp_user_position_pairing` INNER JOIN `sp_users` ON `sp_users`.`id` = `sp_user_position_pairing`.`user_id` WHERE `cup_id`=?');
		$statement = $this->mysqli->prepare('CALL `FindAllNametagsForTheCup`(?)');
		$statement->bind_param('i', $cupId);
		$users = $this->_CreateUsersFromStatement($statement);

		return $users;
	}

	//TODO deprecate and merge this call mby
	/**
	 * @param $cupId
	 * @param $position
	 * @return User[]
	 */
	public function FindPairedUsersOnCupForPosition($cupId, $position)
	{
		//$statement = $this->mysqli->prepare('SELECT `sp_user_position_pairing`.`user_id` AS id, `sp_users`.`first_name`, `sp_users`.`last_name`, `sp_users`.`email`, `sp_users`.`approved_flag`, `sp_users`.`rights`, `sp_users`.`referee_rank_id`, `sp_users`.`affiliation_club_id` FROM `sp_user_position_pairing` INNER JOIN `sp_users` ON `sp_user_position_pairing`.`user_id` = `sp_users`.`id` WHERE `sp_user_position_pairing`.`cup_id`=? AND `sp_user_position_pairing`.`position_id`=?');
		$statement = $this->mysqli->prepare('CALL `FindPairedUsersOnCupForPosition`(?,?)');
		$statement->bind_param('ii', $cupId, $position);
		$users = $this->_CreateUsersFromStatement($statement);

		return $users;

	}

	/**
	 * @param $cupId
	 * @return array
	 */
	public function FindPairedPositionIDUserIDForCup($cupId)
	{
		//$statement = $this->mysqli->prepare('SELECT `position_id`, `user_id` FROM `sp_user_position_pairing` WHERE `cup_id`=? ');
		$statement = $this->mysqli->prepare('CALL `FindPairedPositionIDUserIDForCup`(?)');
		$statement->bind_param('i', $cupId);
		$users = $this->_CreatePairsFromStatement($statement);

		return $users;
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return array
	 */
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

	/**
	 * @param array $row
	 * @return PairPositionUser
	 */
	public function _CreatePairFromRow(array $row)
	{
		//create PairPositionUser class by calling constructor call here
		return new PairPositionUser($row['position_id'], $row['user_id']);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return StatPositionCnt[]
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
	 * @param array $row
	 * @return StatPositionCnt
	 */
	public function _CreateStatFromRow(array $row)
	{
		//create StatPositionCnt class and constructor which we're gonna call from here
		//TODO check what gets filled in
		return new StatPositionCnt($row['position_id'], $row['cnt']);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return StatPositionCnt[]
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
	 * @param array $row
	 * @return StatPositionCnt
	 */
	public function _CreateSingleUserStatFromRow(array $row)
	{
		//create StatUserCnt class and constructor which we're gonna call from here
		//TODO check what gets gilled in
		return new StatUserCnt($row['user_id'], $row['cnt']);
	}

	//WTF? to ClubsManager I guess
	/**
	 * @param $id
	 * @return string
	 */
	public function GetClubAbbreviationByAffiliationID($id)
	{
		//$statement = $this->mysqli->prepare('SELECT `abbreviation` FROM `sp_clubs` WHERE `id`=? LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetClubAbbreviationByAffiliationID`(?)');
		$statement->bind_param('i', $id);

		return $this->_GetSingleResultFromStatement($statement);
	}

	//WTF? to ClubsManager I guess
	/**
	 * @param $id
	 * @return string
	 */
	public function GetClubNameByAffiliationID($id)
	{
		//$statement = $this->mysqli->prepare('SELECT `name` FROM `sp_clubs` WHERE id=? LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetClubNameByAffiliationID`(?)');
		$statement->bind_param('i', $id);

		return $this->_GetSingleResultFromStatement($statement);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return string
	 */
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

	/**
	 * @param mysqli_stmt $statement
	 * @return string
	 */
	private function _GetSingleResultFromTwoColsStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		$result = $row[0]." ".$row[1];
		return $result;
	}

	/**
	 * @param $userID
	 * @return string[]
	 */
	public function GetUserFullNameByID($userID)
	{
		//$statement = $this->mysqli->prepare('SELECT `first_name`, `last_name` FROM `sp_users` WHERE `id`=? LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetUserFullNameByID`(?)');
		$statement->bind_param('i', $userID);

		return $this->_GetSingleResultFromTwoColsStatement($statement);
	}


	/** @return RefereeRank[] */
	public function FindAllRefereeRanks()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `name` FROM `sp_referee_ranks` ');
		$statement = $this->mysqli->prepare('CALL `FindAllRefereeRanks`()');
		$_refereeRanks = $this->_CreateRefereeRanksFromStatement($statement);

		return $_refereeRanks;
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return RefereeRank[]
	 */
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

	/**
	 * @param array $row
	 * @return RefereeRank
	 */
	public function _CreateRefereeRankFromRow(array $row)
	{
		return new RefereeRank($row['id'], $row['name']);
	}

	/**
	 * @param int $id
	 * @return string RankName
	 */
	public function GetRefereeRank($id)
	{
		//$statement = $this->mysqli->prepare('SELECT `name` FROM `sp_referee_ranks` WHERE `id`=? LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetRefereeRank`(?)');
		$statement->bind_param('i', $id);

		return $this->_GetSingleResultFromStatement($statement);
	}

	//returns 1 or 0 INT!!
	public function _IsComingINT($cupID, $userID)
	{
		//$statement = $this->mysqli->prepare('SELECT `attendance_flag` FROM `sp_user_cup_availability` WHERE `cup_id`=? AND `user_id`=?');
		$statement = $this->mysqli->prepare('CALL `IsComingINT`(?, ?)');
		$statement->bind_param('ii', $cupID, $userID);

		return $this->_GetSingleResultFromStatement($statement);
	}

	public function IsComing($cupID, $userID)
	{
		$_res = $this->_IsComingINT($cupID, $userID);
		if($_res==1)
		{
			return true;
		}
		elseif($_res==0)
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
		$_res = $this->_IsComingINT($cupID, $userID);
		if($_res==1)
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
		$_res = $this->_IsComingINT($cupID, $userID);
		if($_res===1)
		{
			return "1";
		}
		elseif($_res===0)
		{
			return "0";
		}
		else // $_res == null - nove prihlaseny, neni v databazi = going 
		{
			return "1";
		}
	}

	//TODO unikatni klic email maybe
	public function GetUserEmailByID($userID)
	{
		//$statement = $this->mysqli->prepare('SELECT `email` FROM `sp_users` WHERE `id`=? LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetUserEmailByID`(?)');
		$statement->bind_param('i', $userID);

		return $this->_GetSingleResultFromStatement($statement);
	}
	//OK
	public function SetPasswordForUser($uid, $passwd)
	{
		$passwdHashed = password_hash($passwd, PASSWORD_BCRYPT);
		//$statement = $this->mysqli->prepare('UPDATE `sp_users` SET `password`=? WHERE id=?');
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
		//$statement = $this->mysqli->prepare('UPDATE `sp_users` SET `email`=? WHERE `id`=?');
		$statement = $this->mysqli->prepare('CALL `SetLoginEmailForUser`(?,?)');
		//$statement->bind_param('si', $email, $uid);
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
		//$statement = $this->mysqli->prepare('UPDATE `sp_users` SET `referee_rank_id`=? WHERE `id`=?');
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

	//Availability 3Fs
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

	/**
	 * @param $email
	 * @return boolean
	 */
	public function IsEmailPresentAlready($email)
	{
		//echo "emailova adresa".$email."\r\n";
		$email_escaped = mysqli_real_escape_string($this->mysqli, $email);
		//$statement = $this->mysqli->prepare('SELECT * FROM `sp_users` WHERE email=?');
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
		//$statement = $this->mysqli->prepare('SELECT * FROM `sp_users` WHERE id=?');
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

	/**
	 * n params
	 * return boolean
	 */

	public function RegisterUser($first_name, $last_name, $email, $password, $rights, $refRank, $klubaffil)
	{
		$passwordHashed = password_hash($password, PASSWORD_BCRYPT);
		$hash = md5(rand(0, 1000));
		//approved, activated
		//$statement = $this->mysqli->prepare('INSERT INTO `sp_users` (`first_name`, `last_name`, `email`, `password`, `hash`, `active_flag`, `approved_flag`, `rights`, `ref_rank`, `affiliation_club_id`) VALUES (?,?,?,?,?,1,1,?,?,?)');
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

	//TODO uncomment and test at server after deployment
	/**
	 * @param $email, $password
	 * @return boolean
	 */
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

	//XHR calls here

	public function SetApprovedForUser($userID)
	{
		//$statement = $this->mysqli->prepare('UPDATE `sp_users` SET `approved_flag`='1' WHERE `sp_users`.`id`=?');
		$statement = $this->mysqli->prepare('CALL `SetApprovedForUser`(?)');
		$statement->bind_param('i', $userID );
		if($statement->execute())
		{
			echo "Approved userID:".$userID.".";
			return true;
		}
		else
		{
			return false;
		}
	}

	//ret cnt int
	//TODO mby filter out NOT ATTENDING...&WHERE ATTENDANCE_FLAG = 1
	public function CountCupsAttendanceOfUserGivenYear($userID, $year)
	{
		//$statement = $this->mysqli->prepare('SELECT COUNT(*) as cnt FROM (SELECT DISTINCT cup_id FROM sp_user_position_pairing WHERE user_id=?) MyAttendedCups INNER JOIN (SELECT id FROM sp_cups WHERE YEAR(sp_cups.time_start)=?) CupsThisYear ON MyAttendedCups.cup_id = CupsThisYear.id');
		$statement = $this->mysqli->prepare('CALL `CntCupsAttendOfUserGivenYear`(?,?)');
		$statement->bind_param('ii', $userID, $year);

		return $this->_GetSingleResultFromStatement($statement);
	}

	//TODO prepsat nova table struct
	//ret StatPositionCnt[]
	//TODO structure better query, also have I been here, make picture
	public function CountOverallStatisticsOfUserGivenYear($userID, $year)
	{
		//$statement = $this->mysqli->prepare('SELECT Position.id AS position_id, CASE WHEN Statistic.cnt IS NULL THEN 0 ELSE Statistic.cnt END AS cnt FROM (SELECT id FROM sp_positions ORDER BY sp_positions.id ASC) Position LEFT JOIN (SELECT position_id, COUNT(position_id) AS cnt FROM sp_user_position_pairing INNER JOIN sp_cups ON sp_user_position_pairing.cup_id = sp_cups.id WHERE sp_user_position_pairing.user_id=? AND YEAR(sp_cups.time_start)=? GROUP BY sp_user_position_pairing.position_id ORDER BY sp_user_position_pairing.position_id DESC) Statistic ON Position.id=Statistic.position_id ORDER BY Position.id ASC');
		$statement = $this->mysqli->prepare('CALL `CntOverallStatsOfUserGivenYear`(?,?)');
		$statement->bind_param('ii', $userID, $year);
		$idpoz_cnts = $this->_CreateStatsFromStatement($statement);

		return $idpoz_cnts;
	}

	//TODO prepsat nova table struct
	//ret StatUserCnt[] {iduser & cnts}
	public function CountClubSeasonalStatistics($clubID, $year)
	{
		//$statement = $this->mysqli->prepare('SELECT id as iduser, sum(CASE WHEN idzav IS NULL THEN 0 ELSE 1 END) AS cnt FROM (SELECT id, first_name, last_name, klubaffil FROM users WHERE klubaffil=?) t3 LEFT JOIN (SELECT t2.idzav, t2.iduser FROM (SELECT id FROM zavody WHERE YEAR(date)=?) t1 INNER JOIN (SELECT DISTINCT idzav, iduser FROM pozicerozhodci) t2 ON t1.id=t2.idzav) t4 ON t3.id=t4.iduser GROUP BY id');
		$statement = $this->mysqli->prepare('CALL `CntClubSeasonalStats`(?,?)');
		$statement->bind_param('ii', $clubID, $year);
		$idpoz_cnts = $this->_CreateClubStatisticsFromStatement($statement);

		return $idpoz_cnts;
	}

	//TO REIMPLEMENT FROM XHR
	public function updatePairing($JSON)
	{
		return true;
	}

	////MOBILE APP AUX FUNCTIONS
	//TODO Deprecate, implement token provisioning
	//UDP related content, keep or delete maybe
	//registrovat z appky
	public function RegisterUserFromAdminWrap($first_name, $last_name, $email, $password, $prava, $klub){
		//Creating here
		echo "checking if user w/ email exists\r\n";
		if ($this->IsEmailPresentAlready($email)) {
			echo "Runtime Error to come\r\n";
			throw new RuntimeException('User with this email exists');
		}
		else {
			echo "registering\r\n";
			if ($this->RegisterUser($first_name, $last_name, $email, $password, $prava, $klub)) {
				/*if ($this->sendYouWereRegisteredFromAdmin($email, $password)) {

				} else {

				}*/
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	//TODO prepsat na REST
	//"zalogovat" / vytvorit token tbp
	public function LoginFromXamarinWrap($username, $password)
	{
		/* User login process, checks if user exists and password is correct */

		// Escape email to protect against SQL injections
		$email = $this->mysqli->escape_string($username);
		//TODO bind this shit pls
		$result = $this->mysqli->query("SELECT * FROM `sp_users` WHERE `email`='$email'");

		if ($result->num_rows == 0) { // User doesn't exist
			//$_SESSION['message'] = "User with that email doesn't exist!";
			//header("location: error.php");
			$token = new Token(Action::UNFOUND, null, null, null, null, null, null);
			return $token;
		} else { // User exists
			$user = $result->fetch_assoc();

			if (password_verify($password, $user['password'])) {
				//$_SESSION['id'] = $user['id'];
				//$_SESSION['email'] = $user['email'];
				//$_SESSION['first_name'] = $user['first_name'];
				//$_SESSION['last_name'] = $user['last_name'];
				//$_SESSION['active'] = $user['active'];

				//Pridam Lukasovo schvaleni a Access rights
				//$_SESSION['approved'] = $user['approved'];
				//$_SESSION['rights'] = $user['rights'];
				//$_SESSION['klubaffil'] = $user['klubaffil'];

				// This is how we'll know the user is logged in
				//$_SESSION['logged_in'] = true;

				//TODO TODO100 add ref_rank along with other things into Token
				$token = new Token(Action::SUCC, $user['id'], $user['first_name'], $user['last_name'], $user['klubaffil'], $this->GetClubNameByAffiliationID($user['klubaffil']), $user['rights']);
				return $token;
				//header("location: profile.php");

			} else {
				//$_SESSION['message'] = "You have entered wrong password, try again!";
				//header("location: error.php");
				$token = new Token(Action::WRONGCRED, null, null, null, null, null, null);
				return $token;
			}
		}
		//return
	}

	//Login Candidate - To Be Authorised
	public function LoginCandidateTBA($email)
	{
		$statement = $this->mysqli->prepare('SELECT * FROM sp_users WHERE email=? LIMIT 1'); //reimplement as a f
		$statement->bind_param('s', $email);
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		return $row;
	}
}
