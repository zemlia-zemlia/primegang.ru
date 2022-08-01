<?php

class SudokuteamsController extends Controller
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
				'actions'=>array('index','moderation','view','update','admin','delete','select','manageplayer'),
				'users'=>CommonFunctions::getAdmins(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	
	/**
	 * actions set vicecaptain, captain, fireplayer
	 */
	public function actionManageplayer() {
		if(!Yii::app()->request->isAjaxRequest) return;
		if(!isset($_POST['Manageplayer'])) return;
		
		$_action = $_POST['Manageplayer']['action'];
		$_id	 = $_POST['Manageplayer']['id'];
		
		if(!in_array($_action, array("setcaptain","setvicecaptain","fireplayer")))
			throw new CHttpException(404,'The requested page does not exist.');
		
		$player = SudokuTeamPlayers::model()->findByPk($_id);
		if(empty($player) || $player===null) throw new CHttpException(404,'The requested page does not exist.');
		
		switch ($_action) {
			case 'setcaptain':
				$player->captain = true;
				$player->vicecaptain = false;				
				break;
			case 'setvicecaptain':
				$player->captain = false;
				$player->vicecaptain = true;
				break;
			case 'fireplayer':
				$player->captain = false;
				$player->vicecaptain = false;
				break;
		}
		if(!$player->save()) throw new CHttpException(500,'Model error.');
		switch ($_action) {
			case 'setcaptain':
				echo "Капитан";
				break;
			case 'setvicecaptain':
				echo "Вице-капитан";
				break;
			case 'fireplayer':
				echo "Игрок";
				break;
		}
		
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

		if(isset($_POST['SudokuTeams']))
		{
			$model->attributes=$_POST['SudokuTeams'];
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
	    $model=new SudokuTeams('search');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['SudokuTeams']))
	        $model->attributes=$_GET['SudokuTeams'];

		$dataProvider=$model->search();
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model,
		));
	}

	/**
	 * Lists inactive models.
	 */
	public function actionModeration() {

		$dataProvider=new CActiveDataProvider('SudokuTeams',array('criteria'=>array(
			'order'		=>"date DESC",
			'condition'	=>'active=:active',
			'params'	=>array('active'=>'0'),
		)));
		$this->render('simplelist',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Creates a view for select option.
	 */	
	public function actionSelect() {
	    $model=new SudokuTeams('search');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['SudokuTeams']))
	        $model->attributes=$_GET['SudokuTeams'];

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
		$model=new SudokuTeams('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SudokuTeams']))
			$model->attributes=$_GET['SudokuTeams'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SudokuTeams the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SudokuTeams::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SudokuTeams $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sudoku-teams-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
