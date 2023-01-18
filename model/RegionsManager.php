<?php
/**
 * Summary of RegionsManager
 */
class RegionsManager
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
	 * Summary of FindAllRegions
	 * @return array<Region>
	 */
	public function FindAllRegions()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRegions`()');
		return $this->_CreateRegionsFromStatement($statement);
	}
	/**
	 * Summary of GetRegionNameOfClub
	 * @param mixed $regionID
	 * @return mixed
	 */
	public function GetRegionNameOfClub($regionID)
	{
		$statement = $this->mysqli->prepare('CALL `GetRegionNameOfClub`(?)');
		$statement->bind_param('i', $regionID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	//Region handling
	/**
	 * Summary of GetRegionByID
	 * @param mixed $regionID
	 * @return Region|null
	 */
	public function GetRegionByID($regionID)
	{
		$statement = $this->mysqli->prepare('CALL `GetRegionByID`(?)');
		$statement->bind_param('i', $regionID);
		return $this->_CreateRegionFromStatement($statement);
	}
	/**
	 * Summary of InsertNewRegion
	 * @param mixed $name
	 * @param mixed $abbrev
	 * @return bool
	 */
	public function InsertNewRegion($name, $abbrev)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewRegion`(?, ?)');
		$statement->bind_param('ss', $name, $abbrev);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of UpdateRegion
	 * @param mixed $id
	 * @param mixed $name
	 * @param mixed $abbrev
	 * @return bool
	 */
	public function UpdateRegion($id, $name, $abbrev)
	{
		$statement = $this->mysqli->prepare('CALL `UpdateRegion`(?,?,?)');
		$statement->bind_param('iss', $id, $name, $abbrev);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
	/**
	 * Summary of _CreateRegionsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<Region>
	 */
	private function _CreateRegionsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

		$regions = [];
		foreach ($rows as $row) {
			$regions[] = $this->_CreateRegionFromRow($row);
		}

		return $regions;
	}
	/**
	 * Summary of _CreateRegionFromStatement
	 * @param mysqli_stmt $statement
	 * @return Region|null
	 */
	private function _CreateRegionFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row !== NULL) {
			return $this->_CreateRegionFromRow($row);
		} else {
			return NULL;
		}
	}
	/**
	 * Summary of _CreateRegionFromRow
	 * @param array $row
	 * @return Region
	 */
	private function _CreateRegionFromRow(array $row)
	{
		return new Region($row['id'], $row['name'], $row['abbreviation']);
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
		$row = $rows[0];
		return $row[0];
	}
}