<?php 

namespace hokphp\core\controllers;

use hokphp\core\components\Application;
use hokphp\core\components\Controller as BaseController;

/**
* The default controller
*/
class AdminController extends BaseController
{
	public $layout = 'admin';

	/**
	 * @inheritdoc
	 */
	public function accessControl()
	{
		return [
			'allow'=>[
				'actions'=>['index'],
				'role'=>['@'],
			],
		];
	}

	/**
	 * The default action. 
	 * @return void
	 */
	public function actionIndex()
	{
		$this->render('admin/index', [
			// 'clientSatisfaction'=>$clientSatisfaction,
			// 'errors'=>$errors,
			// 'success'=>$success,	
		]);
	}
}
