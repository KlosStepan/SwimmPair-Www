<?php
/**
 * Summary of Page
 */
class Page
{
	public $id;
	public $title;
	public $content;
	/**
	 * Summary of __construct
	 * @param mixed $id
	 * @param mixed $title
	 * @param mixed $content
	 */
	public function __construct($id, $title, $content)
	{
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
	}
	//3/3 Full Serialization
	//id, title, content
	//{"id":"1","title":"Kontakt","content":"Telefon je +420765987324"}
	/**
	 * Summary of Serialize
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"title\":\"" . $this->title . "\",\"content\":\"" . $this->content . "\"}";
		return $_serialized;
	}
}