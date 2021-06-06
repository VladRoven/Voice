<?php 

require_once (ROOT.'/models/user.php');
require_once (ROOT.'/models/interview.php');

class ConstructorController 
{
	public function actionIndex()
	{
		if (isset($_SESSION['id'])) 
		{
			if (isset($_GET['id'])) 
			{
				$status = Interview::getStatus($_GET['id']);
				if ($status == "") 
				{
					if (User::isUsersInterview($_GET['id'], $_SESSION['id'])) 
					{
						require_once(ROOT."/views/constructor/index.php");
						return true;
					}
					else
					{
						header('Location: /cabinet');
						return false;
					}
				}
				else
				{
					header('Location: /cabinet');
					return false;
				}
			}
			else
			{
				header('Location: /main');
				return false;
			}
		}
		else
		{
			header('Location: /login');
			return true;
		}
	}

	public function actionDeleteInterview()
	{
		$id = $_POST['id'];
		$user_id = $_SESSION['id'];

		if (isset($id)) 
		{
			if (isset($user_id)) 
			{
				$ok = User::isUsersInterview($id, $user_id);
				if ($ok) 
				{
					$path = ROOT.Interview::getPath($id);
					if (isset($path)) 
					{
						$result = Interview::deleteInterview($id, $path);
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

	public function actionPostInterview()
	{
		$id = $_POST['id'];
		$user_id = $_SESSION['id'];

		if (isset($id)) 
		{
			if (isset($user_id)) 
			{
				$ok = User::isUsersInterview($id, $user_id);
				if ($ok) 
				{
					$isJson = Interview::getDataJson($id);
					if ($isJson != '' && count($isJson) > 0) 
					{
						$result = Interview::postInterview($id);
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
						echo 'null';
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
		else
		{
			echo 'false';
			return false;
		}
	}

	public function actionAddQuestion()
	{
		$id = $_POST['id'];
		$arrayQuestion = $_POST['arrayQuestion'];
		$user_id = $_SESSION['id'];

		if (isset($id) && isset($arrayQuestion)) 
		{
			if (isset($user_id)) 
			{
				$ok = User::isUsersInterview($id, $user_id);
				if ($ok) 
				{
					if ($arrayQuestion != '[]') 
					{
						$ok = Interview::setJson($id, $arrayQuestion);
						if ($ok) 
						{
							Interview::getQuestionsForConstructor($id);
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
						echo 'null';
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
		else
		{
			echo 'false';
			return false;
		}
	}

	public function actionDeleteQuestion()
	{
		$id = $_POST['id'];
		$index = $_POST['index'];
		$user_id = $_SESSION['id'];

		if (isset($id)) 
		{
			if (isset($user_id)) 
			{
				$ok = User::isUsersInterview($id, $user_id);
				if ($ok) 
				{
					$isJson = Interview::getDataJson($id);
					if ($isJson != '') 
					{
						$result = Interview::deleteQuestion($id, $index, $isJson);
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

	public function actionGetDataEdit()
	{
		$id = $_POST['id'];
		$index = $_POST['index'];
		$user_id = $_SESSION['id'];

		if (isset($id)) 
		{
			if (isset($user_id)) 
			{
				$ok = User::isUsersInterview($id, $user_id);
				if ($ok) 
				{
					$isJson = Interview::getDataJson($id);
					if ($isJson != '') 
					{
						echo json_encode($isJson[$index]);
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
		else
		{
			echo 'false';
			return false;
		}
	}

	public function actionEditQuestion()
	{
		$id = $_POST['id'];
		$arrayQuestion = $_POST['arrayQuestion'];
		$index = $_POST['index'];
		$user_id = $_SESSION['id'];

		if (isset($id) && isset($arrayQuestion) && isset($index)) 
		{
			if (isset($user_id)) 
			{
				$ok = User::isUsersInterview($id, $user_id);
				if ($ok) 
				{
					if ($arrayQuestion != '[]') 
					{
						$ok = Interview::editJson($id, $arrayQuestion, $index);
						if ($ok) 
						{
							Interview::getQuestionsForConstructor($id);
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
						echo 'null';
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
		else
		{
			echo 'false';
			return false;
		}
	}
}



 ?>