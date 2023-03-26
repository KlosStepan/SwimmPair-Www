<?php
/**
 * RegionsManager has API functions to handle Region object/s and delivers it through web application.
 */
class RegionsManager
{
	private $mysqli;
	/**
	 * Initialize RegionsManager with live database connection.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	/**
	 * Find all regions in under administration.
	 * @return array<Region>
	 */
	public function FindAllRegions()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllRegions`()');
		return $this->_CreateRegionsFromStatement($statement);
	}
	/**
	 * Return name of Region in which Club is located.
	 * @param int $regionID
	 * @return string
	 */
	public function GetRegionNameOfClub($regionID)
	{
		$statement = $this->mysqli->prepare('CALL `GetRegionNameOfClub`(?)');
		$statement->bind_param('i', $regionID);
		return $this->_GetSingleResultFromStatement($statement);
	}
	//Region handling
	/**
	 * Return Region provided its id.
	 * @param int $regionID
	 * @return Region|null
	 */
	public function GetRegionByID($regionID)
	{
		$statement = $this->mysqli->prepare('CALL `GetRegionByID`(?)');
		$statement->bind_param('i', $regionID);
		return $this->_CreateRegionFromStatement($statement);
	}
	/**
	 * Insert new Region into the web application.
	 * @param string $name
	 * @param string $abbrev
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
	 * Update already existing Region in the web application.
	 * @param int $id
	 * @param string $name
	 * @param string $abbrev
	 * @return bool
	 */
	public function UpdateRegion($id, $name, $abbrev)
	{
		$statement = $this->mysqli->prepare('CALL `UpdateRegion`(?,?,?)');
		$statement->bind_param('iss', $id, $name, $abbrev);
		if ($statement->execute()) {
			$affectedRows = $statement->affected_rows;
			$statement->close();
			return ($affectedRows > 0);
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