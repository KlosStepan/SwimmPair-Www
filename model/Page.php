<?php

class Page
{
	/** @var int */
	public $id;

	/** @var string */
	public $title;

	/** @var string */
	public $content;

	public function __construct($id, $title, $content)
	{
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
	}

	/*TODO SPECIAL CHARS ESCAPING FIND OUT*/
	public function Serialize()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"title\":\"".$this->title."\",\"content\":\"".$this->content."\"}";
		return $_serialized;
	}
}