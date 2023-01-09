<?php

class PositionsManager
{
	private $mysqli;
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}
	//
	public function FindAllPositions()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllPositions`()');
		$positions = $this->_CreatePositionsFromStatement($statement);
		return $positions;
	}
	public function GetPositionNameById($posId)
	{
		$statement = $this->mysqli->prepare('CALL `GetPositionNameByID`(?)');
		$statement->bind_param('i', $posId);
		return $this->_GetSingleResultFromStatement($statement);
	}
	public function DisplayedLiveStatsConfiguredPositions()
	{
		$statement = $this->mysqli->prepare('CALL `GetConfiguredStats`()');
		$positions = $this->_CreatePositionsFromStatement($statement);
		return $positions;
	}
	//Stats-Positions Configuration
	public function DeleteOldStatsPositions()
	{
		$statement = $this->mysqli->prepare('CALL `DeleteOldStatsPositions`()');
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function InsertNewStatPosition($id)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewStatPosition`(?)');
		$statement->bind_param('i', $id);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//DEPRECATE SOON
	public function UpdateStatsPositions($json_processed)
	{
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
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
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
	public function _CreatePositionFromRow(array $row)
	{
		return new Position($row['id'], $row['name']);
	}
	public function _GetSingleResultFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_NUM);
		$row = $rows[0];
		return $row[0];
	}
}