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

	//3/3 Full Serialization
	//id, title, content
	//{"id":"1","title":"Kontakt","content":"Telefon je +420765987324"}
	public function Serialize()
	{
		$_serialized = "{\"id\":\"".$this->id."\",\"title\":\"".$this->title."\",\"content\":\"".$this->content."\"}";
		return $_serialized;
	}
}