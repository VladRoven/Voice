<?php 

require_once (ROOT.'/models/user.php');
require_once (ROOT.'/models/interview.php');

class CabinetController
{
	public function actionIndex()
	{
		if (isset($_SESSION['id'])) 
		{
			require_once (ROOT.'/views/cabinet/index.php');
			return true;
		}
		else
		{
			header('Location: /login');
			return true;
		}
	}

	public function actionLogout()
	{
		User::endSession();
		header('Location: /login');
		return true;
	}

	public function actionChangeName()
	{
		$name = $_POST['name'];
		$nameChek = '/^[А-ЯЁЇІЄҐ][а-яёїієґ]+/u';

		if (isset($name)) 
		{
			if(preg_match($nameChek, $name))
			{
				$result = User::changeName($_SESSION['id'], $name);
				if ($result) 
				{
					echo User::getName($_SESSION['id']);
					return true;
				}
				else
				{
					echo 'false';
					return false;
				}

			}
			else
			{
				echo 'false';
				return false;
			}
		}
		else
		{
			echo 'false';
			return false;
		}
	}

	public function actionCheckPass()
	{
		$pass = hash('sha256', $_POST['pass']);
		$passCheck = '/^[a-zA-Z0-9]+$/';

		if (isset($pass)) 
		{
			if (preg_match($passCheck, $pass)) 
			{
				$result = User::getPass($_SESSION['id']);

				if ($pass != $result) 
				{
					echo "false";
					return false;
				}
				else
				{
					echo "true";
					return true;
				}
			}
			else
			{
				echo 'false';
				return false;
			}
		}
		else
		{
			echo 'false';
			return false;
		}
	}

	public function actionChangePass()
	{
		$pass = $_POST['pass'];
		$passCheck = '/^[a-zA-Z0-9]+$/';

		if (isset($pass)) 
		{
			if (preg_match($passCheck, $pass)) 
			{
				$result = User::changePass($_SESSION['id'], $pass);

				if ($result) 
				{
					echo 'true';
					return true;
				}
				else
				{
					echo 'false';
					return false;
				}
			}
			else
			{
				echo 'false';
				return false;
			}
		}
		else
		{
			echo 'false';
			return false;
		}
	}

	public function actionCreateInterview()
	{
		$name = $_POST['name'];
		$nameChek = '/^[А-ЯЁЇІЄҐ][А-ЯЁЇІЄҐа-яёїієґ\"\'-:,.\s]+/u';

		if (isset($name)) 
		{
			if(preg_match($nameChek, $name))
			{
				$result = User::createInterview($_SESSION['id'], $name);
				if ($result) 
				{
					echo User::getLastAddInterview($_SESSION['id']);
					return true;
				}
				else
				{
					echo 'false';
					return false;
				}

			}
			else
			{
				echo 'false';
				return false;
			}
		}
		else
		{
			echo 'false';
			return false;
		}
	}

	public function actionCheckStatus()
	{
		$id = $_POST['id'];

		if (isset($id)) 
		{
			echo Interview::getStatus($id);
			return true;
		}
		return false;
	}
}



 ?>