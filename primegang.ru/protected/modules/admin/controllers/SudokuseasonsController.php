<?php

class SudokuseasonsController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','select', 'tableEdit'),
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

	public function actionTableEdit()
	{
        $season = SudokuSeasons::getCurrentSeason();

        if (Yii::app()->request->isPostRequest){

//            CVarDumper::dump($_POST, 5, true); die;
            $teams = $_POST;
            foreach ($teams as $id => $team) {
//                if ($id == 14){CVarDumper::dump($team, 5, true); die;}

                // add points
                $addPoints = Addpoints::model()->find('id_sudoku_team='. $id .
                    ' AND id_season='. $season->id);

                if ($addPoints) {
                    $addPoints->points = $team['addpoints'];
                    $addPoints->save();
                }
                else {
                    $addPoints = new Addpoints();
                    $addPoints->id_sudoku_team = $id;
                    $addPoints->id_season = $season->id;
                    $addPoints->points = $team['addpoints'];
                    $addPoints->save();
                }
                // end add points

                // add score
                $tourIds = Yii::app()->db->createCommand()
                    ->setFetchMode(PDO::FETCH_COLUMN,0)
                    ->select("id")
                    ->from(SudokuTours::model()->tableSchema->name)
                    ->where('id_season = ' . $season->id)
                    ->queryAll();
                $ids = implode(', ', array_map('intval', $tourIds));
                $updateTeam = SudokuToursTeams::model()->findAll(
                    'id_tour IN (' .$ids. ') AND (id_sudoku_team1=:id 
                    OR id_sudoku_team2=:id) ',
                    [ ':id' => $id]
                );


                if ($updateTeam) {
//                    CVarDumper::dump($updateTeam, 5, true); die;
                    $existScore = ['goals' => 0, 'missing' => 0];

                    foreach ($updateTeam as $uteam) {
                        if ($uteam->id_sudoku_team1 == $id) {
                            $existScore['goals'] = $existScore['goals'] + $uteam->score_team1_total;
                            $existScore['missing'] = $existScore['missing'] + $uteam->score_team2_total;
                        }
                        elseif ($uteam->id_sudoku_team2 == $id) {
                            $existScore['goals'] = $existScore['goals'] + $uteam->score_team2_total;
                            $existScore['missing'] = $existScore['missing'] + $uteam->score_team1_total;
                        }
                    }





                    $addScore = AddScore::model()->find('id_sudoku_team='. $id .
                        ' AND id_season='. $season->id);
                    if ($addScore) {
                        $addScore->goals = $team['goals'] - $existScore['goals'];
                        $addScore->missing = $team['missing'] - $existScore['missing'];
                        $addScore->save();
                    }
                    else {
                        $addScore = new AddScore();
                        $addScore->id_sudoku_team = $id;
                        $addScore->id_season = $season->id;
                        $addScore->goals = $team['goals'] - $existScore['goals'];
                        $addScore->missing = $team['missing'] - $existScore['missing'];
//                        CVarDumper::dump($existScore, 5, true);die;
                        $addScore->save();
                    }

//                    CVarDumper::dump($existScore, 5, true);


                } // end add score





            } // end foreach

        }


        $tourService = new TourService();
//        CVarDumper::dump($season->id, 5, true); die;
        $tourTable = $tourService->returnTourTable($season->id);
        // выборка списка дивизионов для сезона
        $res = Yii::app()->db->createCommand(
            'select `divisions`, `division_names` from `sudoku_seasons` where `id` = '.$season->id.
            ' limit 1')->queryAll();
        $divisions = $res[0]['divisions'];
        $division_names = explode(';', $res[0]['division_names']);

        $this->render('touredit', [
            'tourTable'=>$tourTable, 'season' => $season,
            'divisions' => $divisions, 'division_names' => $division_names, ]);
	}

    public function actionCreate()
    {
        $model=new SudokuSeasons;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['SudokuSeasons']))
        {
            $model->attributes=$_POST['SudokuSeasons'];
            if($model->save())
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
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

		if(isset($_POST['SudokuSeasons']))
		{
			$model->attributes=$_POST['SudokuSeasons'];
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
	    $model=new SudokuSeasons('search');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['SudokuSeasons']))
	        $model->attributes=$_GET['SudokuSeasons'];

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
	    $model=new SudokuSeasons('search');
	    $model->unsetAttributes();  // clear any default values
	    if(isset($_GET['SudokuSeasons']))
	        $model->attributes=$_GET['SudokuSeasons'];

		$dataProvider=$model->search();
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
		$model=new SudokuSeasons('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SudokuSeasons']))
			$model->attributes=$_GET['SudokuSeasons'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SudokuSeasons the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SudokuSeasons::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SudokuSeasons $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sudoku-seasons-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
