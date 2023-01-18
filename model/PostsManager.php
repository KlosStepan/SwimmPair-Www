<?php
/**
 * Summary of PostsManager
 */
class PostsManager
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
	 * Summary of GetPostByID
	 * @param mixed $id
	 * @return Post|null
	 */
	public function GetPostByID($id)
	{
		$statement = $this->mysqli->prepare("CALL `GetPostByID`(?);");
		$statement->bind_param('i', $id);
		return $this->_CreatePostOrNullFromStatement($statement);
	}
	/**
	 * Summary of GetFollowingPost
	 * @param mixed $id
	 * @return Post|null
	 */
	public function GetFollowingPost($id)
	{
		$statement = $this->mysqli->prepare('CALL `GetFollowingPost`(?);');
		$statement->bind_param('i', $id);
		return $this->_CreatePostOrNullFromStatement($statement);
	}
	/**
	 * Summary of FindLastNPosts
	 * @param mixed $N
	 * @return array<Post>
	 */
	public function FindLastNPosts($N)
	{
		$statement = $this->mysqli->prepare('CALL `FindLastNPosts`(?);');
		$statement->bind_param('i', $N);
		$posts = $this->_CreatePostsFromStatement($statement);
		return $posts;
	}
	/**
	 * Summary of FindAllPostsOrderByIDDesc
	 * @return array<Post>
	 */
	public function FindAllPostsOrderByIDDesc()
	{
		$statement = $this->mysqli->prepare('CALL `FindAllPostsOrderByIDDesc`();');
		$posts = $this->_CreatePostsFromStatement($statement);
		return $posts;
	}
	/**
	 * Summary of InsertNewCupPSAPost
	 * @param mixed $cupTitle
	 * @param mixed $cupID
	 * @param mixed $date_start
	 * @param mixed $date_end
	 * @param mixed $authorID
	 * @param mixed $clubAbbrev
	 * @return void
	 */
	public function InsertNewCupPSAPost($cupTitle, $cupID, $date_start, $date_end, $authorID, $clubAbbrev)
	{
		$PSATitle = "Nový závod " . $cupTitle . " přidán";
		$PSAContent = "Byl přidám závod klubu " . $clubAbbrev . ", který se koná od " . $date_start . " do " . $date_end . " Více informací o závodu na " . $_SERVER['SERVER_NAME'] . "/zavod.php?id=" . $cupID;
		$this->InsertNewPost($PSATitle, $PSAContent, 1, $authorID, 1);
	}
	//Post handling
	/**
	 * Summary of InsertNewPost
	 * @param mixed $title
	 * @param mixed $content
	 * @param mixed $display_flag
	 * @param mixed $author
	 * @param mixed $signature_flag
	 * @return bool
	 */
	public function InsertNewPost($title, $content, $display_flag, $author, $signature_flag)
	{
		$statement = $this->mysqli->prepare('CALL `InsertNewPost`(?, ?, ?, ?, ?);');
		$statement->bind_param('ssiii', $title, $content, $display_flag, $author, $signature_flag);
		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Summary of UpdatePost
	 * @param mixed $id
	 * @param mixed $title
	 * @param mixed $content
	 * @param mixed $display_flag
	 * @param mixed $signature_flag
	 * @return bool
	 */
	public function UpdatePost($id, $title, $content, $display_flag, $signature_flag)
	{
		$this->mysqli->begin_transaction();
		try {
			//displayed_flag, signature_flag
			$statement = $this->mysqli->prepare("CALL `UpdatePost`(?, ?, ?, ?, ?);");
			//$statement->bind_param('ssiii', $title, $content, $id, $display_flag, $signature_flag);
			$statement->bind_param('issii', $id, $title, $content, $display_flag, $signature_flag);
			$statement->execute();

			$this->mysqli->commit();
			//return "Success, commited";
			return true;
		} catch (RuntimeException $e) {
			//echo $e->getMessage();
			$this->mysqli->rollback();
			//return "RuntimeException(), rollback";
			return false;
		}
	}
	//public function DeletePost($id) - TODO
	//PRIVATE FUNCTIONS - ORM-ing DB client lib results
	/**
	 * Summary of _CreatePostOrNullFromStatement
	 * @param mysqli_stmt $statement
	 * @return Post|null
	 */
	private function _CreatePostOrNullFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();
		if ($row !== NULL) {
			return $this->_CreatePostFromRow($row);
		} else {
			return NULL;
		}
	}
	/**
	 * Summary of _CreatePostsFromStatement
	 * @param mysqli_stmt $statement
	 * @return array<Post>
	 */
	private function _CreatePostsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
		$posts = [];
		foreach ($rows as $row) {
			$posts[] = $this->_CreatePostFromRow($row);
		}
		return $posts;
	}
	/**
	 * Summary of _CreatePostFromRow
	 * @param array $row
	 * @return Post
	 */
	private function _CreatePostFromRow(array $row)
	{
		return new Post($row['id'], $row['timestamp'], $row['title'], $row['content'], $row['display_flag'], $row['author_user_id'], $row['signature_flag']);
	}
}