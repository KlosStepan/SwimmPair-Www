<?php

class RegionsManager
{
	/** @var mysqli */
	private $mysqli;

	/***
	 * RegionsManager constructor.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}

	/**
	 * @return Region[]
	 */
	public function FindAllRegions()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `name`, `abbreviation` FROM `sp_regions` ORDER BY `id` ASC');
		$statement = $this->mysqli->prepare('CALL `FindAllRegions`()');

		return $this->_CreateRegionsFromStatement($statement);
	}

	/**
	 * @param $regionID
	 * @return null|Region
	 */
	public function GetRegionByID($regionID)
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `name`, `abbreviation` FROM `sp_regions` WHERE id=?');
		$statement = $this->mysqli->prepare('CALL `GetRegionByID`(?)');
		$statement->bind_param('i',$regionID);

		return $this->_CreateRegionFromStatement($statement);
	}

	/**
	 * @param $name
	 * @param $abbrev
	 * @return bool
	 */
	public function InsertNewRegion($name, $abbrev)
	{
		//$statement = $this->mysqli->prepare('INSERT INTO `sp_regions` (`id`, `name`, `abbreviation`) VALUES (NULL, ?, ?)');
		$statement = $this->mysqli->prepare('CALL `InsertNewRegion`(?, ?)');
		$statement->bind_param('ss', $name, $abbrev);
		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function UpdateRegion($id, $name, $abbrev)
	{
		//$statement = $this->mysqli->prepare('UPDATE sp_regions SET name=?, abbreviation=? WHERE id=?');
		$statement = $this->mysqli->prepare('CALL `UpdateRegion`(?,?,?)');
		$statement->bind_param('ssi', $name, $abbrev, $id);
		if($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function GetRegionNameOfClub($regionID)
	{
		//$statement = $this->mysqli->prepare('SELECT `name` FROM `sp_regions` WHERE `sp_regions`.`id`=?');
		$statement = $this->mysqli->prepare('CALL `GetRegionNameOfClub`(?)');
		$statement->bind_param('i',$regionID);

		return $this->_GetSingleResultFromStatement($statement);
	}

	/**
	 * @param array $row
	 * @return Region
	 */
	private function _CreateRegionFromRow(array $row)
	{
		return new Region($row['id'], $row['name'], $row['abbreviation']);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return null|Region
	 */
	private function _CreateRegionFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row!==NULL)
		{
			return $this->_CreateRegionFromRow($row);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return array
	 */
	private function _CreateRegionsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

		$regions = [];
		foreach($rows as $row) {
			$regions[] = $this->_CreateRegionFromRow($row);
		}

		return $regions;
	}

	/**
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