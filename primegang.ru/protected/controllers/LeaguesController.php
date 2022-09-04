<?php

class LeaguesController extends Controller
{
	function init() {
		parent::init();
		Yii::app()->theme = 'prime';
	}
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view','touronline'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($alias, $archive = false, $season = 0) {
		$this->render('view',array(
			'model'=>$this->loadModel($alias),
			'archive'=>($archive == 'true') ? true : false ,
			'season'=>$season,
		));
	}
	
	public function actionTouronline($id) {
		$model=Tours::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		$this->render('touronline',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Leagues');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Leagues the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($alias)
	{
		$model=Leagues::model()->find('alias=:alias',array("alias"=>$alias));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
