<?php 

require_once (ROOT.'/models/interview.php');
require_once (ROOT.'/models/user.php');

class InterviewController
{
	
	public function actionIndex()
	{
		if (isset($_SESSION['id'])) 
		{
			if (Interview::isSetInterview($_GET['id'])) 
			{
				if (Interview::checkUserAnswer($_SESSION['id'], $_GET['id'])) 
				{
					
					if ($_SESSION['id'] == User::isUsersInterview($_GET['id'], $_SESSION['id']))
					{
						require_once (ROOT.'/views/interview/creator.php');
						return true;
					}
					else
					{
						if (Interview::getStatus($_GET['id']) == 0) 
						{
							require_once (ROOT.'/views/interview/interview_complete.php');
							return false;
						}
						require_once (ROOT.'/views/interview/user.php');
						return true;
					}
				}
				else
				{
					require_once (ROOT.'/views/interview/interview_complete.php');
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
			header('Location: /login?id='.$_GET['id']);
			return false;
		}
	}

	public function actionCounter()
	{
		if (isset($_POST['id']) && isset($_POST['itr'])) 
		{
			if ($_POST['id'] == Interview::getIdInterview($_POST['id'])) 
			{
				echo Interview::setQuestion($_POST['id'], $_POST['itr']);
				return true;
			}
			return false;		
		}
		return false;
	}

	public function actionInterviewEnd()
	{
		if (isset($_POST['vars']) && isset($_POST['id'])) 
		{
			if (Interview::getStatus($_POST['id']) == 0) 
			{
				echo 'close';
			}
			else
			{
				if (!is_null($_POST['vars']) && !is_null($_POST['id'])) 
				{
					$arrayAnswer = $_POST['vars'];
					$check = true;
					$itr = 0;
					foreach ($arrayAnswer as $value) 
					{
						if ($value == -1) 
						{
							$check = false;
							die();
						}
						else
						{
							$result = false;
							if ($value['type'] == 1) 
							{
								$result = Interview::addAnswerVariant($_POST['id'], $_SESSION['id'], $itr, $value['answer']);
							}
							elseif ($value['type'] == 2) 
							{
								$result = Interview::addAnswerScale($_POST['id'], $_SESSION['id'], $itr, $value['answer']);
							}
							elseif ($value['type'] == 3) 
							{
								$result = Interview::addAnswerText($_POST['id'], $_SESSION['id'], $itr, $value['answer']);
							}

							if ($result) 
							{
								$check = true;
								$itr = $itr + 1;
							}
							else
							{
								$check = false;
							}

						}

					}

					$check = Interview::updateCount($_POST['id']);

					if ($check) 
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
			}
		}
	}

	public function actionShowOpenAnswer()
	{
		echo Interview::showOpenAnswer($_POST['id'], intval($_POST['offset']), $_POST['question']);
		return;
	}

	public function actionChangeStatus()
	{
		$array = ["change" => Interview::changeStatus($_POST['id']), "date" => Interview::getDateEnd($_POST['id']), "status" => Interview::getStatus($_POST['id'])];
		echo json_encode($array);
		return;
	}

	public function actionDelInterviewActive()
	{
		if (isset($_POST['id'])) 
		{
			$ok = User::isUsersInterview($_POST['id'], $_SESSION['id']);
			if ($ok) 
			{
				$result = Interview::deleteInterviewActive($_POST['id']);
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
				echo "false";
				return;
			}
		}
		else
		{
			echo 'false';
			return;
		}
	}

	public function actionGetReport()
	{
		$name = Interview::report($_GET['id']);
		header("Content-Type: application/octet-stream");
		header("Content-Transfer-Encoding: Binary");
		header('Content-disposition: attachment; filename='.$name[0].'.xlsx');
		echo readfile(ROOT.'/data/'.$name[1].'.xlsx');
		unlink(ROOT.'/data/'.$name[1].'.xlsx');
		die();
	}
}


 ?>