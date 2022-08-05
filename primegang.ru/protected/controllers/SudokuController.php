<?php
//use services\TourService;
//use CWebModule;

class SudokuController extends Controller {

//    /** @var TourService */
//    private $tourService;
//
//
//    /**
//     * @param string $id
//     * @param CWebModule $module
//     * @param TourService $tourService
//     * @param array $config
//     */
//    public function __construct(
//        string $id,
//        null,
//        TourService $tourService,
//        array $config = []
//    )
//    {
//        parent::__construct($id, $module, $config);
//
//        $this->tourService = $tourService;
//    }
//


	function init() {
		parent::init();
		Yii::app()->theme = 'prime';
	}
		
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}

	private function returnTourTable($season_id) {
	    // выборка количества дивизионов в сезоне $season_id
	    $res = Yii::app()->db->createCommand('select `divisions` from `sudoku_seasons` where `id` = '.$season_id.' limit 1')->queryAll();
	    $divisions = $res[0]['divisions'];
	    $result = array();
	    for ($d = 1; $d <= $divisions; $d++)
	    { //по каждому дивизиону
		//выводим таблицу только на те туры, у которых все игры ready
		$sql = "
			SELECT
				_teams.id_team,
				SUM(_teams.goals) AS goals,
				SUM(_teams.misses) AS misses,
				SUM(_teams.goals - _teams.misses) AS diff,
				SUM(_teams.points) AS points,
				SUM(_teams.win) AS win,
				SUM(_teams.tee) AS tee,
				SUM(_teams.fail) AS fail,
				SUM(1) as tour_count
			FROM (
				SELECT
					t.id AS id_tour,
					t.tour_number,
					SUM(g.ready) as ready,
					SUM(1) as total
				FROM sudoku_tours t 
				LEFT JOIN sudoku_games g on g.id_tour = t.id
				WHERE t.id_season = :season
				GROUP BY t.id
				ORDER BY t.tour_number
			) as _tours
			LEFT JOIN (
					SELECT 
						stt.id_tour,
						stt.id_sudoku_team1 as id_team,
						IF(stt.score_team1_total > stt.score_team2_total, 1, 0) AS win,
						IF(stt.score_team1_total = stt.score_team2_total and NOT(stt.missing_team1 AND stt.missing_team2), 1, 0) AS tee,
						IF(stt.score_team1_total < stt.score_team2_total, 1, 0) AS fail,
						stt.score_team1_total as goals,
						stt.score_team2_total as misses,
						IF(
							stt.score_team1_total > stt.score_team2_total, 
							2, 
							IF(
								stt.score_team1_total = stt.score_team2_total and NOT(stt.missing_team1 AND stt.missing_team2), 1, 0
							)
						) as points
					FROM sudoku_tours_teams stt
					left join sudoku_tours t on t.id = stt.id_tour
					WHERE stt.division = $d and t.id_season = :season
				UNION
					SELECT 
						stt.id_tour,
						stt.id_sudoku_team2 as id_team,
						IF(stt.score_team1_total < stt.score_team2_total, 1, 0) AS win,
						IF(stt.score_team1_total = stt.score_team2_total and NOT(stt.missing_team1 AND stt.missing_team2), 1, 0) AS tee,
						IF(stt.score_team1_total > stt.score_team2_total, 1, 0) AS fail,
						stt.score_team2_total as goals,
						stt.score_team1_total as misses,
						IF(
							stt.score_team1_total < stt.score_team2_total, 
							2, 
							IF(
								stt.score_team1_total = stt.score_team2_total and NOT(stt.missing_team1 AND stt.missing_team2), 1, 0
							)
						) as points
					FROM sudoku_tours_teams stt
					left join sudoku_tours t on t.id = stt.id_tour
					WHERE stt.division = $d and t.id_season = :season
			) AS _teams ON _teams.id_tour = _tours.id_tour
			WHERE _tours.ready = _tours.total
			GROUP BY _teams.id_team
			ORDER BY points DESC, diff DESC, goals DESC
		";//stt.division = $d
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam('season', $season_id);
		$result[] = $command->queryAll();
	    }
	    return $result;
	}

	public function actionIndex() {
		$model = new SudokuTourPrognosis();
		
		if(isset($_POST['SudokuTourPrognosis'])) {
			$model->id_tour		 = $_POST['SudokuTourPrognosis']['id_tour'];
			$model->id_player	 = $_POST['SudokuTourPrognosis']['id_player'];
			if(isset($_POST['SudokuTourPrognosis']['lines'])) $model->lines = $_POST['SudokuTourPrognosis']['lines'];
			
			if($model->validate()) {
				if($model->save()) {
					Yii::app()->user->setFlash('prognosis','Изменения сохранены. Спасибо за прогноз!');
				} else Yii::app()->user->setFlash('prognosis','При записи возникли ошибки. Пожалуйста, уведомьте администратора сайта об этом, и мы исправим ошибку в кратчайшие сроки.');
				$this->refresh();
			}
		}

        $tourService = new TourService();

		$season = SudokuSeasons::getCurrentSeason();
		$tourTable = $tourService->returnTourTable($season->id);
		$this->render('index', array('currentPrognosis'=>$model, 'tourTable'=>$tourTable, 'season' => $season));
	}
	
	public function actionTouronline($id) {
		$model = SudokuTours::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		if(strtotime($model->date) > time())
			throw new CHttpException(403,'Онлайн тура пока закрыт :)');
		
		$this->render('touronline', array('model'=>$model));
	} 

	public function actionSendprognosis() {
		$this->render('sendprognosis');
	}

	public function actionTeamrequest() {
		$model = new SudokuTeamRequest();
		if(isset($_POST['SudokuTeamRequest']))
		{
			$model->attributes = $_POST['SudokuTeamRequest'];
			 
			if($model->validate()) {
				if($model->save()) {
					Yii::app()->user->setFlash('teamrequest','Спасибо за заявку! После того, как администратор проверит ее, вы сможете участвовать в турнире по судоку!');
				} else Yii::app()->user->setFlash('teamrequest','При записи заявки возникли ошибки. Пожалуйста, уведомьте администратора сайта об этом, и мы исправим ошибку в кратчайшие сроки.');
				$this->refresh();
			}
		}
		
		$this->render('teamrequest', array(
			'model'=>$model,
		));
	}
	
	public function actionBombers($s = null) {
		$season = null;
		if($s) {
			$season = SudokuSeasons::model()->find('alias = :alias', array('alias' => $s));
			if(!$season) throw new CHttpException(404, 'Not found');
		} else $season = SudokuSeasons::getCurrentSeason();

		$this->render('bombers', array(
			'season' => $season,
		));
	}

	public function actionGoleadors($s = null) {
		$season = null;
		if($s) {
			$season = SudokuSeasons::model()->find('alias = :alias', array('alias' => $s));
			if(!$season) throw new CHttpException(404, 'Not found');
		} else $season = SudokuSeasons::getCurrentSeason();

		$this->render('goleadors', array(
			'season' => $season,
		));
	}
	
	public function actionUpdateplayingprognosis() {
		$model = new SudokuPlayingPrognosis();
		if(isset($_POST['PlayingPrognosis'])) {
			$model->playingPrognosis = $_POST['PlayingPrognosis'];
			
			$model->save();
		}
	}
	
	public function actionTeam() {
		$model = new SudokuTeamAdmin();
		
		if(isset($_POST['SudokuTeamAdmin'])) {
			$model->attributes = $_POST['SudokuTeamAdmin'];
			$model->teamid = $_POST['SudokuTeamAdmin']['teamid'];
			if(isset($_POST['SudokuTeamAdmin']['removePlayers'])) $model->removePlayers = $_POST['SudokuTeamAdmin']['removePlayers'];
			
			if($model->validate()) {
				if($model->save()) {
					Yii::app()->user->setFlash('team','Изменения сохранены. Команда отправится на проверку модератором. ');
				} else Yii::app()->user->setFlash('team','При записи возникли ошибки. Пожалуйста, уведомьте администратора сайта об этом, и мы исправим ошибку в кратчайшие сроки.');
				$this->refresh();
			}
		}
		$this->render('team',array(
			'model'=>$model,
		));
	}

	/**
	 * actions set vicecaptain, captain, fireplayer
	 */
	public function actionManageplayer() {
		if(!Yii::app()->request->isAjaxRequest) return;
		if(!isset($_POST['Manageplayer'])) return;
		
		$_action = $_POST['Manageplayer']['action'];
		$_id	 = $_POST['Manageplayer']['id'];
		
		if(!in_array($_action, array("setcaptain","setvicecaptain","fireplayer","deleteplayer")))
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
			case 'deleteplayer':
				$player->captain = false;
				$player->vicecaptain = false;
				$player->active = 0;
				break;
		}
		
		$res = $player->save();

		if(!$res) throw new CHttpException(500,'Model error.');
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
			case 'deleteplayer':
				echo "Игрок удален";
				break;
		}
		
	}

	public function actionArchive($s = null) {

		$season = null;
		if($s) {
			$season = SudokuSeasons::model()->find('alias = :alias and archive=1', array('alias' => $s));
			if(!$season) throw new CHttpException(404, 'Not found');
		} else $season = SudokuSeasons::getPreviousSeason();

		$tourTable = $this->returnTourTable($season->id);
		$this->render('archive', array('tourTable'=>$tourTable, 'season' => $season));

	}

	public function actionArchivelist() {

		$seasons = SudokuSeasons::model()->findAll('archive=1');
		$season = SudokuSeasons::getPreviousSeason();

		$this->render('archivelist', array('seasons' => $seasons, 'season' => $season));

	}

}