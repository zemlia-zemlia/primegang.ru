<?php

class LogoutController extends Controller
{
	public $defaultAction = 'logout';
	
	function init() {
		parent::init();
		Yii::app()->theme = 'prime';
	}
	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->controller->module->returnLogoutUrl);
	}

}