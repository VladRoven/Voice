<?php 

require_once (ROOT."/models/login.php");
require_once (ROOT.'/models/user.php');

class LoginController
{
	public function actionIndex()
	{
		if (isset($_SESSION['id'])) 
		{
			header('Location: /cabinet');
			return;
		}
		else
		{
			require_once (ROOT."/views/login/index.php");
			return;
		}
	}

	public function actionLogin()
	{
		$email = $_POST['email'];
		$pass = $_POST['pass'];

		$passCheck = '/^[a-zA-Z0-9]+$/';
		$emailCheck = '/^[a-z0-9.]+@[a-z]+.[a-z]{2,4}/';

		if (isset($email) && isset($pass)) 
		{
			if (preg_match($emailCheck, $email) && preg_match($passCheck, $pass)) 
			{
				$result = Login::check($email, $pass);
				if ($result) 
				{
					if (isset($_SESSION['id'])) 
					{
						// echo $result;
						return false;
					}
					else
					{
						User::startSession($result);
						return true;
					}
				}
				else
				{
					echo 'error';
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}

	}

	public function actionSendPass()
	{
		$email = $_POST['email'];
		$emailCheck = '/^[a-z0-9.]+@[a-z]+.[a-z]{2,4}/';

		if (isset($email)) 
		{
			if(preg_match($emailCheck, $email))
			{
				$result = Login::checkEmail($email);

				if ($result) 
				{
					$newPass = Login::genPass();
					$mail = mail($email, 'Ваш пароль змiнено!', "Новий пароль вiд аккаунту $email: $newPass");

					if ($mail) 
					{
						User::changePass($result, $newPass);
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
					echo 'unreg';
					return false;
				}
			}
		}

	}
}



 ?>