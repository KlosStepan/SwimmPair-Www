<?php
/**
 * Summary of UserRights
 */
abstract class UserRights
{
	//Can't have lower rights than what is in SESSION
	const SuperUser = 2;
	const VedouciKlubu = 1;
	const Rozhodci = 0;

	/**
	 * Summary of getRightsById
	 * @param mixed $value
	 * @throws RuntimeException
	 * @return mixed
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
 * Summary of Auth
 */
class Auth
{
	/**
	 * Summary of requireRole
	 * @param mixed $role
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