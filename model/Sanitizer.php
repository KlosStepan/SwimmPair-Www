<?php
/**
 * Summary of Sanitizer
 */
class Sanitizer
{
	/**
	 * Summary of getPostInt
	 * @param mixed $key
	 * @throws RuntimeException
	 * @return int
	 */
	public static function getPostInt($key)
	{
		if (!isset($_POST[$key]) || !ctype_digit($_POST[$key])) {
			throw new RuntimeException();
		}
		return (int) $_POST[$key];
	}
	/**
	 * Summary of getPostString
	 * @param mixed $key
	 * @throws RuntimeException
	 * @return mixed
	 */
	public static function getPostString($key)
	{
		if (!isset($_POST[$key]) || !is_string($_POST[$key])) {
			throw new RuntimeException();
		}
		return $_POST[$key];
	}
	/**
	 * Summary of getGetInt
	 * @param mixed $key
	 * @throws RuntimeException
	 * @return int
	 */
	public static function getGetInt($key)
	{
		if (!isset($_GET[$key]) || !ctype_digit($_GET[$key])) {
			throw new RuntimeException();
		}
		return (int) $_GET[$key];
	}
	/**
	 * Summary of getGetString
	 * @param mixed $key
	 * @throws RuntimeException
	 * @return mixed
	 */
	public static function getGetString($key)
	{
		if (!isset($_GET[$key]) || !is_string($_GET[$key])) {
			throw new RuntimeException();
		}
		return $_GET[$key];
	}
}