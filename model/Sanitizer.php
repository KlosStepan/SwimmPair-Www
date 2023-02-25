<?php
/**
 * Sanitizer class serves as anti XSS protection. It handles Int and String parameters provided via HTTP GET and HTTP POST.
 */
class Sanitizer
{
	/**
	 * Process Integer provided via. HTTP POST.
	 * @param int $key
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
	 * Process String provided via. HTTP POST.
	 * @param string $key
	 * @throws RuntimeException
	 * @return string
	 */
	public static function getPostString($key)
	{
		if (!isset($_POST[$key]) || !is_string($_POST[$key])) {
			throw new RuntimeException();
		}
		return $_POST[$key];
	}
	/**
	 * Process Integer provided via. HTTP GET.
	 * @param int $key
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
	 * Process String provided via. HTTP GET.
	 * @param string $key
	 * @throws RuntimeException
	 * @return string
	 */
	public static function getGetString($key)
	{
		if (!isset($_GET[$key]) || !is_string($_GET[$key])) {
			throw new RuntimeException();
		}
		return $_GET[$key];
	}
}