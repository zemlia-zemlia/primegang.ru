<?php

class SudokutoursController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view','create','update','admin','delete','select','deleteteam'),
				'users'=>CommonFunctions::getAdmins(),
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function actionDeleteteam() {
		if(!Yii::app()->request->isAjaxRequest) return;
		if(!isset($_POST['deleteteam'])) return;
		
		$_m = SudokuToursTeams::model()->findByPk($_POST['deleteteam']['id']);
		if(!empty($_m)) $_m->delete();
		else throw new CHttpException(404,'The requested page does not exist.');
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new SudokuTours;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SudokuTours']))
		{
			$model->attributes=$_POST['SudokuTours'];
			if(isset($_POST['SudokuTours']['_games'])) $model->_games = $_POST['SudokuTours']['_games'];
			if(isset($_POST['SudokuTours']['_teams'])) $model->_teams = $_POST['SudokuTours']['_teams'];
			
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SudokuTours']))
		{
			$model->attributes=$_POST['SudokuTours'];
			if(isset($_POST['SudokuTours']['_games'])) $model->_games = $_POST['SudokuTours']['_games'];
			if(isset($_POST['SudokuTours']['_teams'])) $model->_teams = $_POST['SudokuTours']['_teams'];
			
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex() {
	    $model=new SudokuTours('search');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['SudokuTours']))
	        $model->attributes=$_GET['SudokuTours'];

		$dataProvider=$model->search();
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model,
		));
	}
	
	/**
	 * Creates a view for select option.
	 */	
	public function actionSelect() {
	    $model=new SudokuTours('search');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['SudokuTours']))
	        $model->attributes=$_GET['SudokuTours'];

		$dataProvider=$model->search();
		$dataProvider->pagination->pageSize = 1000;
		$this->renderPartial('select',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model,
		));
	}
	

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SudokuTours('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SudokuTours']))
			$model->attributes=$_GET['SudokuTours'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SudokuTours the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SudokuTours::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SudokuTours $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sudoku-tours-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
