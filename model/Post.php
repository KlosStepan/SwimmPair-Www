<?php

class Post
{
    /** @var int */
    public $id;

    /** @var int */
    public $timestamp;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var tinyint */
    public $display_flag;

    /** @var int */
    public $author_user_id;

    /** @var tinyint */
	public $signature_flag;

    public function __construct($id, $timestamp, $title, $content, $display_flag, $author_user_id, $signature_flag)
    {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->title = $title;
        $this->content = $content;
        $this->display_flag = $display_flag;
        $this->author_user_id = $author_user_id;
        $this->signature_flag = $signature_flag;
    }

    public function NullCtor()
    {
	    $this->id = null;
	    $this->timestamp = null;
	    $this->title = null;
	    $this->content = null;
	    $this->display_flag = null;
	    $this->author_user_id = null;
	    $this->signature_flag = null;
    }
	//TODO - INVESTIGATE HOW IMPLEMENTABLE THIS IS
    /*
    public function jsonSerialize()
	{
		return [
			'id'        => $this->id,
			'timestamp' => $this->timestamp,
			'title'     => htmlentities($this->title),
			'content'   => htmlentities($this->content)
		];
	}*/
    
    //7/7 Full Serialization
	//id, timestamp, title, content, display_flag, author_user_id, signature_flag
	//{"id":"1","timestamp":"2018-01-16 21:06:16","title":"Test title","content":"Hello! Blabla post","display_flag":"1","author_user_id":"21","signature_flag":"1"}
	public function Serialize()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"timestamp\":\"".$this->timestamp."\",\"title\":\"".$this->title."\",\"content\":\"".$this->content."\",\"display_flag\":\"".$this->display_flag."\",\"author_user_id\":\"".$this->author_user_id."\",\"signature_flag\":\"".$this->signature_flag."\"}";
		return $_serialized;
	}

}
