<?php

namespace hokphp\core\components;

use hokphp\core\components\Controller;
use hokphp\core\components\Model;

/**
* Bootstrap class
*/
class Application
{
	public static $app;

	public static $dbConnection;

	public $params;

	public $username;

	public $isGuest = true;

	/**
	 * Contructor
	 * @param array $params  An array of params to include in the application
	 */
	public function __construct($params) {
		$this->params = $params;
	}

	/**
	 * Starts the web application
	 * @return void
	 */
	public function startApplication()
	{
		session_start();
		$this::$app = $this;
		$this::$dbConnection = \hokphp\core\components\Model::connectDatabase();
		$this->handleRequest();
	}

	/**
	 * Handles the request by trying to run an action, if an exception is caught, the handleException method is executed.
	 * @return void
	 */
	public function handleRequest()
	{
		$routeArray = static::getRoute();
		$controller = $routeArray['controller'];
		$action = $routeArray['action'];
		$params = $routeArray['params'];

		$this->handleSession();

		try {
			Controller::runAction($controller, $action);
		} catch (\Exception $e) {
			Controller::handleException($e->getMessage());
		}
	}
	
	/**
	 * Gets the current route from the r get parameter and returns an array
	 * including the controller, action, parameters and string route
	 * @return array
	 */
	public static function getRoute()
	{
		$params = '';
		$r = isset($_GET['r']) && !empty($_GET['r']) ? $_GET['r'] : '';
		if (empty($r)) {
			$controller = 'site';
			$action = 'index';
		} else {
			$ca = explode('/', $r);
			$controller = $ca[0];
			$action = isset($ca[1]) ? $ca[1] : 'index';
		}
		$route = $controller.'/'.$action;
		return [
			'controller'=>$controller,
			'action'=>$action,
			'params'=>$params,
			'route'=>$route,
		];
	}	

	/**
	 * Handles the current session to decide wether this user is a guest or not
	 * @return void
	 */
	public function handleSession()
	{
		if (isset($_SESSION['username'])) {
			$this->username = $_SESSION['username'];
			$this->isGuest = false;
		} else {
			$this->isGuest = true;
		}
	}

	/**
	 * Logs the current user out
	 * @return boolean
	 */
	public function logout()
	{
		unset($_SESSION['username']);
		$this->isGuest = true;
		return true;
	}

	/**
	 * Returns the base url of the application
	 * @return string
	 */
	public static function baseUrl()
	{
		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
		$baseUrl = $protocol."://".$_SERVER['SERVER_NAME'].strtok($_SERVER["REQUEST_URI"],'?');
		return $baseUrl;
	}	

	/**
	 * Creates a url by accepting a route and combining it with the base url
	 * @return string
	 */
	public static function createUrl($route)
	{
		return static::baseUrl().'?r='.$route;;
	}
	
	/**
	 * Checks wether the give route is the same as the current
	 * @param  string  $route The route to check
	 * @return boolean        
	 */
	public static function isCurrentRoute($route)
	{
		$routeArray = static::getRoute();
		return $route == $routeArray['route'] ? true : false;
	}	
}
