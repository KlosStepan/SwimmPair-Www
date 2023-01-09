<?php

class PositionsManager
{
	/** @var mysqli */
	private $mysqli;

	/** @param mysqli $mysqli */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}

	/**
	 * @return Position[]
	 */
	public function FindAllPositions()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `name` FROM `sp_positions` ORDER BY `id` ASC');
		$statement = $this->mysqli->prepare('CALL `FindAllPositions`()');
		$positions = $this->_CreatePositionsFromStatement($statement);

		return $positions;
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return Position[]
	 */
	public function _CreatePositionsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

		$positions = [];
		foreach ($rows as $row)
		{
			$positions[]=$this->_CreatePositionFromRow($row);
		}
		return $positions;
	}

	/**
	 * @param array $row
	 * @return Position
	 */
	public function _CreatePositionFromRow(array $row)
	{
		return new Position($row['id'], $row['name']);
	}

	/**
	 * @param $posId
	 * @return string
	 */
	public function GetPositionNameById($posId)
	{
		//$statement = $this->mysqli->prepare('SELECT name FROM `sp_positions` WHERE `id`=? LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetPositionNameByID`(?)');
		$statement->bind_param('i', $posId);

		return $this->_GetSingleResultFromStatement($statement);
	}

	/**
	 * @param mysqli_stmt $statement
	 * @return string
	 */
	public function _GetSingleResultFromStatement(mysqli_stmt $statement){
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		return $row[0];
	}

	/**
	 * @return Position[]
	 */
	public function DisplayedLiveStatsConfiguredPositions()
	{
		//$statement = $this->mysqli->prepare('SELECT `sp_public_stats_config`.`position_id` as id, `sp_positions`.`name` AS position FROM `sp_public_stats_config` LEFT JOIN `sp_positions` ON `sp_public_stats_config`.`position_id`=`sp_positions`.`id` ORDER BY `position_id` ASC');
		$statement = $this->mysqli->prepare('CALL `GetConfiguredStats`()');
		$positions = $this->_CreatePositionsFromStatement($statement);

		return $positions;
	}

	//TODO FORMAT
	/**
	 * @param $json_processed
	 * @return bool
	 */
	public function UpdateStatsPositions($json_processed)
	{
		//echo "updateStatsPositions";
		$this->mysqli->begin_transaction();
		try
		{
			$statement = $this->mysqli->prepare('CALL `DeleteOldStatsPositions`()');
			$statement->execute();
			foreach ($json_processed as $record)
			{
				if (!isset($record["idpoz"]))
				{
					throw  new RuntimeException();
				}
				elseif (!ctype_digit($record["idpoz"]))
				{
					throw new RuntimeException();
				}
				$statement = $this->mysqli->prepare('CALL `InsertNewStatPosition`(?)');
				$statement->bind_param('i',$record["idpoz"]);
				$statement->execute();
			}
			$this->mysqli->commit();
			return true;
		}
		catch (RuntimeException $e)
		{
			echo $e->getMessage();
			$this->mysqli->rollback();
			return false;
		}
	}
}