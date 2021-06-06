<?php 

class MainController
{
	public function actionIndex ()
	{
		require_once(ROOT."/views/main/index.php");
		return true;
	}

}


 ?>