<?php
#UVM
	date_default_timezone_set("Europe/Kiev");
	
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	session_start();
	define("ROOT", dirname(__FILE__));
	require_once(ROOT."/components/Router.php");
	require_once(ROOT.'/components/Tools.php');
	require_once(ROOT.'/components/database.php');
	$router = new Router();
	$router->run();
	

#UVM
?>