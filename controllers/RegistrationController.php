<?php 

require_once (ROOT.'/models/registration.php');

class RegistrationController
{
	public function actionIndex()
	{
		require_once (ROOT."/views/registration/index.php");
		return true;
	}

	public function actionCheck()
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$pass = $_POST['password'];

		$emailCheck = '/^[a-z0-9.]+@[a-z]+.[a-z]{2,4}/';
		$nameChek = '/^[А-ЯЁЇІЄҐ][а-яёїієґ]+/u';
		// $passCheck = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^\w\s]).{6,}/';
		$passCheck = '/^[a-zA-Z0-9]+$/';

		$result = Registration::checkMail($email);

		if ($result) 
		{
			echo 'reg';
			return false;
		}
		else
		{
			if (isset($name) && isset($email) && isset($pass)) 
			{
				if (preg_match($emailCheck, $email) && preg_match($nameChek, $name) && preg_match($passCheck, $pass)) 
				{
					if (Registration::reg($name, $email, $pass)) 
					{
						echo 'success reg';
						return true;
					}
					else
					{
						echo 'fail reg';
						return false;
					}
				}
				else
				{
					echo 'fail reg';
					return false;
				}
			}
			else
			{
				echo 'fail reg';
				return false;
			}
		}
	}
}


 ?>