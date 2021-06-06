<?php
# UVM
# Component
# Router

class Router
{
	private $routes;
	private $emptyRoute;
	private $notFoundRoute;
	private $uri;

	# returns current uri
	public static function getUri()
	{
		if (!empty($_SERVER["REQUEST_URI"]))
		{
			$str = trim($_SERVER["REQUEST_URI"], "/");
			if ($pos = strpos($str, "?")) {
				$str = substr($str, 0, $pos);				
			}
			return $str;
		}
		return null;
	}

	# contructs Router object
	# accepts path to routes config file
	# loads all routes
	public function __construct()
	{
		$this->routes
		= include(ROOT."/config/routes.config.php");
	}

	# sets an empty route, route which will be
	# selected if uri is empty
	# accepts route as 1-item array with
	# key as pattern and value as path
	public function setEmptyRoute($route)
	{
		$this->emptyRoute = $route;
	}

	# sets an undefined route, route which will be
	# selected if nothing wasn't selected (404 page)
	# accepts route as 1-item array with
	# key as pattern and value as path
	public function setNotFoundRoute($route)
	{
		$this->notFoundRoute = $route;
	}

	# performs uri dispatching
	public function run()
	{
		$this->uri = Router::getUri();
		if (strlen($this->uri) == 0)
		{
			return $this->root();
		}
		foreach ($this->routes as $pattern => $path)
		{
			if (preg_match("~$pattern~", $this->uri))
			{
				return $this->call($pattern, $path);
			}
		}
		return $this->unmatch();
	}

	# performs dispatching if any route
	# wasn't performed
	private function unmatch()
	{
		if ($this->notFoundRoute == null)
			return false;
		if ($this->call(
			array_keys($this->notFoundRoute),
			array_values($this->notFoundRoute)) != null)
			return true;
		return false;
	}

	# performs dispatching if uri is empty
	private function root()
	{
		header("Location: /main");
		die();

		if ($this->emptyRoute == null)
			return false;
		if ($this->call(
			array_keys($this->emptyRoute),
			array_values($this->emptyRoute)) != null)
			return true;
		return false;
	}

	# performs including controller
	# and calling the action
	# returns result of action
	private function call($pattern, $path)
	{
		$internal = preg_replace("~$pattern~", $path,
			$this->uri);
		$segments = explode("/", $internal);
		$controllerName = ucfirst(
			array_shift($segments)."Controller");
		$actionName = "action".ucfirst(
			array_shift($segments));
		$controllerFile =
		ROOT."/controllers/".$controllerName.".php";
		if (file_exists($controllerFile))
		{
			include_once($controllerFile);
		}
		$controller = new $controllerName;
		return call_user_func_array(
			array($controller, $actionName),
			$segments);
	}
}

# UVM
?>