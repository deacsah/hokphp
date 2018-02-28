<?php 

namespace hokphp\core\components;

/**
* Bootstrap class
*/
abstract class Controller
{
	public $application;
	public $layout = 'main';

	/**
	 * Returns an array of actions and roles. 
	 * Possible roles: @ = authenticated user, ? = guest
	 * If an action is not defined in the array, the action is accessable by nobody
	 * @return array
	 */
	public abstract function accessControl();
	
	/**
 	 * Runs the given action in the given controller
	 * @param  string $controller	The controller to run
	 * @param  string $action    	The action to run
	 * @throws Exception 			If the user is not allowed to run $action
	 * @return void
	 */
	public static function runAction($controller = 'site', $action = 'index', $params = [])
	{
		$controllerName = '\hokphp\core\controllers\\'.str_replace("-", "", ucwords($controller)).'Controller';
		$actionName = 'action'.str_replace("-", "", ucwords($action));
		
		$controller = new $controllerName();
		if($controller->validateAccessControl($action)) {		
			$controller->beforeAction();
			call_user_func_array([$controller, $actionName], $params);
			$controller->afterAction();
		} else {
			throw new \Exception('403 Not allowed');
		}
	}

	/**
	 * Checks wether the current user is allowed to access this action
	 * @param  string $action  		The action that will be run after this method
	 * @return void 	
	 */
	public function validateAccessControl($action)
	{
		$accessRules = $this->accessControl();
		if (array_key_exists('deny', $accessRules) && array_key_exists('actions', $accessRules['deny'])) {
			if(array_search($action, $accessRules['deny']['actions']) !== false) {
				if (array_key_exists('role', $accessRules['deny'])) {
					foreach ($accessRules['deny']['role'] as $role) {
						if ($role == '@') {
							return Application::$app->isGuest ? true : false;
						} elseif($role == '?') {
							return Application::$app->isGuest ? false : true;
						} else {
							return true;
						}
					}
				}
			}
		}
		if (array_key_exists('allow', $accessRules) && array_key_exists('actions', $accessRules['allow'])) {
			if(array_search($action, $accessRules['allow']['actions']) !== false) {
				if (array_key_exists('role', $accessRules['allow'])) {
					foreach ($accessRules['allow']['role'] as $role) {
						if ($role == '@') {
							return Application::$app->isGuest ? false : true;
						} elseif($role == '?') {
							return Application::$app->isGuest ? true : false;
						} else {
							return true;
						}
					}
				}
			}
		}
		return false;
		// return true;
	}

	/**
	 * This method gets run before the given action
	 * @return void 	
	 */
	public function beforeAction()
	{
		return true;
	}

	/**
	 * This method gets run before the given action
	 * @return void
	 */
	public function afterAction()
	{
		return true;
	}

	/**
	 * Renders the specified view file with layout.
	 * @param  string $viewFile  	The view file to be rendered
	 * @param  array  $params    	Values that will be sent to the view
	 * @return string 			 	The html of the page
	 */
	public function render($viewFile, $params = [])
	{
		\hokphp\core\components\View::renderView($viewFile, $params, TRUE, $this->layout);
	}

	/**
	 * Renders the specified view file without layout.
	 * @param  string $viewFile   	The view file to be rendered
	 * @param  array  $params     	Values that will be sent to the view
	 * @return string 			  	The html of the page
	 */
	public function renderPartial($viewFile, $params = [])
	{
		\hokphp\core\components\View::renderView($viewFile, $params, FALSE);
	}
 
	/**
	 * Handles thrown exceptions and render the error view
	 * @param  string $message  	The exception message
	 * @return void
	 */
	public static function handleException($message)
	{
		\hokphp\core\components\View::renderView('site/error', ['message'=>$message]);
	}

	/**
	 * Redirects the user to the given route
	 * @param  string $route The route to redirect to
	 * @return void
	 */
	public function redirect($route)
	{
		$location = Application::baseUrl().'?r='.$route;
		header('Location: '.$location);
	}

}
