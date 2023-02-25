<?php
/**
 * Page is static website page with information in web application. It has some title and content.
 */
class Page
{
	public $id;
	public $title;
	public $content;
	/**
	 * Ctor of Page object for web application
	 * @param int $id
	 * @param string $title
	 * @param string $content
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
	 * Serialize outputs 3/3 these members: id, title, content
	 * @return string
	 */
	public function Serialize()
	{
		$_serialized = "{\"id\":\"" . $this->id . "\",\"title\":\"" . $this->title . "\",\"content\":\"" . $this->content . "\"}";
		return $_serialized;
	}
}