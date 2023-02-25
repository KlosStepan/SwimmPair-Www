<?php
/**
 * UserRights has roles with rights numbers in descending order. The larger the number the higher the access. It can also return name of the role based on its number. User can access page of same and lower rights.
 */
abstract class UserRights
{
	//Can't have lower rights than what is in SESSION
	const SuperUser = 2;
	const VedouciKlubu = 1;
	const Rozhodci = 0;

	/**
	 * Returns name of the role based on its number from this UserRights enumberation class.
	 * @param int $value
	 * @throws RuntimeException
	 * @return string
	 */
	public static function getRightsById($value)
	{
		try {
			$class = new ReflectionClass(__CLASS__);
			$rights = array_flip($class->getConstants());
			return $rights[$value];
		} catch (ReflectionException $ex) {
			throw new RuntimeException();
		}
	}
}
/**
 * Auth authenticates User for access through the web application. It can check if User's SESSION rights (UserRights) is set and appropriate.
 */
class Auth
{
	/**
	 * Summary of requireRole
	 * @param int $role
	 * @throws RuntimeException
	 * @return void
	 */
	public static function requireRole($role)
	{
		if (!isset($_SESSION['rights'])) {
			header('Location: /prihlaseni.php'); // TODO!
			exit();
		}
		//Sharply lower, runtime exception
		if ($_SESSION['rights'] < $role) {
			echo '<h1>Not enough rights</h1>';
			echo $_SESSION['rights'];
			echo $role;
			throw new RuntimeException();
		}
	}
}