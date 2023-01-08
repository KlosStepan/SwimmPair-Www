<?php

abstract class UserRights
{
	//nesmime mit v session nizsi prava nez requireRole
	const SuperUser = 2;
	const VedouciKlubu = 1;
	const Rozhodci = 0;

	public static function getRightsById($value) {
		try {
			$class = new ReflectionClass(__CLASS__);
			$rights = array_flip($class->getConstants());
			return $rights[$value];
		} catch(ReflectionException $ex) {
			throw new RuntimeException();
		}
	}
}
class Auth
{
	public static function requireRole($role)
	{
		if (!isset($_SESSION['rights'])) {
			header('Location: /prihlaseni.php'); // TODO!
			exit();
		}
		//mam ostre nizsi nez je potreba, runtime exception
		if ($_SESSION['rights'] < $role) {
			echo '<h1>Not enough rights</h1>';
			echo $_SESSION['rights'];
			echo $role;
			throw new RuntimeException();
		}
	}
}