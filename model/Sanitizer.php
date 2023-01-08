<?php

class Sanitizer
{
	public static function getPostInt($key)
	{
		if (!isset($_POST[$key]) || !ctype_digit($_POST[$key]))
		{
			throw new RuntimeException();
		}
		return (int)$_POST[$key];
	}
	public static function getPostString($key)
	{
		if (!isset($_POST[$key]) || !is_string($_POST[$key]))
		{
			throw new RuntimeException();
		}
		return $_POST[$key];
	}
	public static function getGetInt($key)
	{
		if (!isset($_GET[$key]) || !ctype_digit($_GET[$key]))
		{
			throw new RuntimeException();
		}
		return (int)$_GET[$key];
	}
	public static function getGetString($key)
	{
		if (!isset($_GET[$key]) || !is_string($_GET[$key]))
		{
			throw new RuntimeException();
		}
		return $_GET[$key];
	}
}