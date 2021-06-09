<?php

class PostsManager
{
	/**
	 * @var mysqli
	 */
	private $mysqli;

	/**
	 * PostsManager constructor.
	 * @param mysqli $mysqli
	 */
	public function __construct(mysqli $mysqli)
	{
		$this->mysqli = $mysqli;
	}

	/**
	 * @param $id
	 * @return NULL|Post
	 */
	public function GetPostByID($id)
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag` FROM `sp_posts` WHERE id=?');
		$statement = $this->mysqli->prepare("CALL `GetPostByID`(?);");
		$statement->bind_param('i', $id);

		return $this->_CreatePostOrNullFromStatement($statement);
	}

	/**
	 * @param $id
	 * @return NULL|Post
	 */
	public function GetFollowingPost($id)
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag` FROM `sp_posts` WHERE `id`<? AND `display_flag`=1 ORDER BY `id` DESC LIMIT 1');
		$statement = $this->mysqli->prepare('CALL `GetFollowingPost`(?);');
		$statement->bind_param('i', $id);

		return $this->_CreatePostOrNullFromStatement($statement);
	}

	/**
	 * @return Post[]
	 */
	public function FindLastThreePosts()
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag` FROM `sp_posts` WHERE `display_flag`=1  ORDER BY `id` DESC LIMIT ?');
		$statement = $this->mysqli->prepare('CALL `FindLastThreePosts`();');
		$posts = $this->_CreatePostsFromStatement($statement);

		return $posts;
	}

	/**
	 * @var int N
	 * @return Post[]
	 */
	public function FindLastNPosts($N)
	{
		//$statement = $this->mysqli->prepare('SELECT `id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag` FROM `sp_posts` WHERE `display_flag`=1 ORDER BY `id` DESC LIMIT ?');
		$statement = $this->mysqli->prepare('CALL `FindLastNPosts`(?);');
		$statement->bind_param('i', $N);
		$posts = $this->_CreatePostsFromStatement($statement);

		return $posts;
	}

    /**
     * @return Post[]
     */
    public function FindAllPostsOrderByIDDesc()
    {
        //$statement = $this->mysqli->prepare('SELECT `id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag` FROM `sp_posts` ORDER BY `id` DESC');
        $statement = $this->mysqli->prepare('CALL `FindAllPostsOrderByIDDesc`();');
        $posts = $this->_CreatePostsFromStatement($statement);

        return $posts;
    }

	/**
	 * @param $title
	 * @param $content
	 * @param $display_flag
	 * @param $author
	 * @param $signature_flag
	 * @return bool
	 */
	public function InsertNewPost($title, $content, $display_flag, $author, $signature_flag)
	{
		//$statement = $this->mysqli->prepare('INSERT INTO `sp_posts` (`id`, `timestamp`, `title`, `content`, `display_flag`, `author_user_id`, `signature_flag`) VALUES (NULL, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?)');
		$statement = $this->mysqli->prepare('CALL `InsertNewPost`(?, ?, ?, ?, ?);');
		$statement->bind_param('ssiii', $title, $content, $display_flag, $author, $signature_flag);
		if ($statement->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
    //not in documentation, but come on..
	public function InsertNewCupPSAPost($cupTitle, $cupID, $date_start, $date_end, $authorID, $clubAbbrev)
	{
		$PSATitle = "Nový závod ".$cupTitle." přidán";
		$PSAContent = "Byl přidám závod klubu ".$clubAbbrev.", který se koná od ".$date_start." do ".$date_end." Více informací o závodu na ".$_SERVER['SERVER_NAME']."/zavod.php?id=".$cupID;
		$this->InsertNewPost($PSATitle, $PSAContent, 1, $authorID, 1);
	}

    /**
     * @param $id, $title, $article
     * @return boolean
     */
    public function UpdatePost($id, $title, $content, $display_flag, $signature_flag)
    {
        $this->mysqli->begin_transaction();
        try
        {
            //displayed_flag, signature_flag
            $statement = $this->mysqli->prepare("CALL `UpdatePost`(?, ?, ?, ?, ?);");
            $statement->bind_param('ssiii', $title, $content, $id, $display_flag, $signature_flag);
            $statement->execute();

            $this->mysqli->commit();
            //return "Success, commited";
            return true;
        }
        catch(RuntimeException $e)
        {
            //echo $e->getMessage();
            $this->mysqli->rollback();
            //return "RuntimeException(), rollback";
            return false;
        }
    }

    //Private functions

	/**
	 * @param  mysqli_stmt $statement
	 * @return Post|NULL
	 */
	private function _CreatePostOrNullFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$row = $statement->get_result()->fetch_assoc();

		if ($row !== NULL)
		{
			return $this->_CreatePostFromRow($row);
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * @param  mysqli_stmt $statement
	 * @return Post[]
	 */
	private function _CreatePostsFromStatement(mysqli_stmt $statement)
	{
		$statement->execute();
		$rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

		$posts = [];
		foreach ($rows as $row)
		{
			$posts[] = $this->_CreatePostFromRow($row);
		}

		return $posts;
	}

	/**
	 * @param  array $row
	 * @return Post
	 */
	private function _CreatePostFromRow(array $row)
	{
		return new Post($row['id'], $row['timestamp'], $row['title'], $row['content'], $row['display_flag'], $row['author_user_id'], $row['signature_flag']);
	}

}
