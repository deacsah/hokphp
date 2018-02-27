<?php 

namespace hokphp\core\controllers;

use hokphp\core\components\Application;
use hokphp\core\components\Controller as BaseController;
use hokphp\core\models\LoginForm;

/**
* The default controller
*/
class SiteController extends BaseController
{
	/**
	 * @inheritdoc
	 */
	public function accessControl()
	{
		return [
			'deny'=>[
				'actions'=>[
					'logout',
				],
				'role'=>['?'],
			],
			'allow'=>[
				'actions'=>[
					'index',
					'login',
				],
				'role'=>['*'],
			],
		];
	}

	/**
	 * The default action. 
	 * @return void 	An action prints the output and does not return any value
	 */
	public function actionIndex()
	{
		$this->render('site/index');
	}

	/**
	 * The default action. 
	 * @return void 	An action prints the output and does not return any value
	 */
	public function actionLogin()
	{	
		$success = false;
		$errors = false;

		$loginForm = new LoginForm;

		// validate post data
		if (isset($_POST['loginForm'])) {

			$loginForm->assign($_POST['loginForm'], [
				'username'=>'username', 
				'password'=>'password'
			]);

			if($loginForm->validate(['username', 'password'])) {
				if($loginForm->login()) {
					return $this->redirect('admin/index');
				}
			}
		}
		
		$this->render('site/login', [
			'loginForm'=>$loginForm,
			'errors'=>$errors,
			'success'=>$success,	
		]);	
	}	

	public function actionLogout()
	{
		if (Application::$app->isGuest) {
			return $this->redirect('site/login');
		}
		Application::$app->logout();
		return $this->redirect('site/index');
	}
}
