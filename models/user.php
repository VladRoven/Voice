<?php 

require_once (ROOT.'/components/Gen.php');

class User
{
	public static function startSession($id)
	{
		session_start();
		$_SESSION['id'] = $id;
	}

	public static function endSession()
	{
		unset($_SESSION['id']);
		session_destroy();
	}

	public static function getName($id)
	{
		$db = Database::getConnection();
		$query = "SELECT u.name FROM users AS u WHERE u.id = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['name'];
	}

	public static function getCountInterviews($id)
	{
		$db = Database::getConnection();
		$query = "SELECT COUNT(id) FROM interviews WHERE user_id = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['COUNT(id)'];
	}

	public static function getEmail($id)
	{
		$db = Database::getConnection();
		$query = "SELECT u.email FROM users AS u WHERE u.id = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['email'];
	}

	public static function changeName($id, $name)
	{
		$db = Database::getConnection();
		$query = "UPDATE users SET name = :name WHERE id = :id";

		$result = $db->prepare($query);
		$result->bindParam(':name', $name, PDO::PARAM_STR);
		$result->bindParam(':id', $id, PDO::PARAM_INT);

		if ($result->execute()) 
		{
			return true;
		}

		return false;
	}

	public static function getPass($id)
	{
		$db = Database::getConnection();
		$query = "SELECT password FROM users WHERE id = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['password'];
	}

	public static function changePass($id, $pass)
	{
		$passHash = hash('sha256', $pass);
		$db = Database::getConnection();
		$query = "UPDATE users SET password = :pass WHERE id = :id";

		$result = $db->prepare($query);
		$result->bindParam(':pass', $passHash, PDO::PARAM_STR);
		$result->bindParam(':id', $id, PDO::PARAM_INT);

		if ($result->execute()) 
		{
			return true;
		}

		return false;
	}

	public static function getInterviews($id)
	{
		$db = Database::getConnection();
		$query = "SELECT id, name, status, id_constructor, id_interview FROM interviews WHERE user_id = :id ORDER BY id DESC";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_INT);
		$result->execute();

		return $result->fetchAll();
	}

	public static function createInterview($id, $name)
	{
		$link = '/data/interviews/' . Gen::name() .'.json';
		$secLink = ROOT.$link;
		$secondName = Gen::name();

		if (!file_exists($secLink)) 
		{
			$fp = fopen($secLink, "w"); 
		    fclose($fp);

		    $db = Database::getConnection();
			$query = "INSERT INTO interviews (user_id, name, id_constructor, count, `path`, date_start) VALUES (:user_id, :name, :secondName, 0, :link, NOW())";

			$result = $db->prepare($query);
			$result->bindParam(':user_id', $id, PDO::PARAM_INT);
			$result->bindParam(':name', $name, PDO::PARAM_STR);
			$result->bindParam(':secondName', $secondName, PDO::PARAM_STR);
			$result->bindParam(':link', $link, PDO::PARAM_STR);


			if ($result->execute()) 
			{
				return true;
			}

			return false;

		}
		else
		{
			return false;
		}
	}

	public static function getLastAddInterview($id)
	{
		$db = Database::getConnection();
		$query = "SELECT id_constructor FROM interviews WHERE user_id = :id ORDER BY id DESC LIMIT 1";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_INT);
		$result->execute();

		return $result->fetch(PDO::FETCH_ASSOC)['id_constructor'];
	}

	public static function isUsersInterview($id, $user_id)
	{
		$db = Database::getConnection();
		$query = "SELECT user_id FROM interviews WHERE id_constructor = :id OR id_interview = :id";

		$result = $db->prepare($query);
		$result->bindParam(':id', $id, PDO::PARAM_STR);
		$result->execute();
		$selectId = $result->fetch(PDO::FETCH_ASSOC)['user_id'];
		if ($user_id != $selectId) 
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}


 ?>