<?php 


class Login
{
	const SYMBOLS = "asdfghjklqwertyuiopzxcvbnm1234567890QWERTYUIOPSDFGHJKLZXCVBNM";

	public static function check($email, $pass)
	{
		$db = Database::getConnection();
		$pass = hash('sha256', $pass);
		$query = "SELECT u.id FROM users AS u WHERE u.email = :email AND u.password = :pass";

		$result = $db->prepare($query);
		$result->bindParam(':email', $email, PDO::PARAM_STR);
		$result->bindParam(':pass', $pass, PDO::PARAM_STR);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['id'];
	}

	public static function checkEmail($email)
	{
		$db = Database::getConnection();
		$query = "SELECT id FROM users WHERE email = :email";

		$result = $db->prepare($query);
		$result->bindParam(':email', $email, PDO::PARAM_STR);
		$result->execute();
		
		return $result->fetch(PDO::FETCH_ASSOC)['id'];
		
	}

	public static function rand($len)
	{
		$res = '';

		for ($i = 0; $i < $len; ++$i)
		{
			$res .= self::SYMBOLS[rand(0, strlen(self::SYMBOLS) - 1)];
		}

		return $res;
	}

	public static function genPass()
	{

		$now = strval(time() * 1000);
		$hash = hash("sha256", $now);

		return self::rand(4)
			. substr($hash, strlen($hash) - 20)
			. self::rand(4);
		
	}
}



 ?>