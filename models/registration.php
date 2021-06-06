<?php 


class Registration
{
	public static function checkMail($mail)
	{
		$db = Database::getConnection();
		
		$query = "SELECT u.email FROM users as u WHERE u.email = :mail";
		$result = $db->prepare($query);
		$result->bindParam(":mail", $mail, PDO::PARAM_STR);
		$result->execute();
		$fetch = $result->fetchAll(PDO::FETCH_ASSOC);

		if ($fetch) 
		{
			return $fetch;
		}

		return false;

	}

	public static function reg($name, $email, $pass)
	{
		$db = Database::getConnection();
		$passHash = hash('sha256', $pass);

		$query = "INSERT INTO users (name, email, `password`) VALUES 
		(
			:name,
			:email,
			:pass
		)";

		$result = $db->prepare($query);
		$result->bindParam(":name", $name, PDO::PARAM_STR);
		$result->bindParam(":email", $email, PDO::PARAM_STR);
		$result->bindParam(":pass", $passHash, PDO::PARAM_STR);

		if ($result->execute()) 
		{
			return true;
		}

		return false;
	}
}


 ?>