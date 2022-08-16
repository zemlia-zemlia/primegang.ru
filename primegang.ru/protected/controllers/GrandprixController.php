<?php

class GrandprixController extends Controller
{
	function init() {
		parent::init();
		Yii::app()->theme = 'prime';
	}

//    public function filters()
//    {
//        return array(
//            'accessControl', // perform access control for CRUD operations
//            'postOnly + delete', // we only allow deletion via POST request
//        );
//    }
//
//    /**
//     * Specifies the access control rules.
//     * This method is used by the 'accessControl' filter.
//     * @return array access control rules
//     */
//    public function accessRules()
//    {
//        return array(
//            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions'=>array('index','leagues','league'),
//                'users'=>array('*'),
//            ),
//            array('deny',  // deny all users
//                'users'=>array('*'),
//            ),
//        );
//    }

	private function getGranpriStats() {
		$crit = new CDbCriteria();

		// текущий сезон //
		$startSeasonYear = date('Y') - (date('n') < 7 ? 1 : 0);
		$currentSeason = 'sezon-' . $startSeasonYear . '-' . (++$startSeasonYear);
		$crit->condition = "alias = :alias";
		$crit->params = array('alias' => $currentSeason);

		$currentSeason = Seasons::model()->find($crit);
		if(empty($currentSeason)) return array(); 
		
		$sql = "select 
			pr.id_user as id_user,
			g.id_season,
			SUM(1) as game_count,
			SUM(pr.balls) AS points,
			SUM(IF(pr.balls=4,1,0)) AS fact,
			SUM(IF(pr.balls=3,1,0)) AS tee,
			SUM(IF(pr.balls=2,1,0)) AS diff,
			SUM(IF(pr.balls=1,1,0)) AS res
		FROM 
			prognosis as pr 
			LEFT JOIN games as g on pr.id_game = g.id
		WHERE pr.computed = :computed and g.id_season = :id_season
		GROUP BY pr.id_user
		ORDER BY points DESC";
		$command = Yii::app()->db->createCommand($sql);
		$command->params = array('computed'=>1, 'id_season'=>$currentSeason->id);
		$result = $command->queryAll();
		
		$stats = array();
		foreach($result as $row) {
			$object = json_decode(json_encode($row), FALSE);
			$object->user = User::model()->findByPk($object->id_user);
			$stats[] = $object;
		}
		
		return $stats;
	}


	public function actionIndex() {
        $seasons = Seasons::model()->findAll('archive=1 ORDER BY id DESC');
        $season = Seasons::getPreviousSeason();

        $this->render('archivelist', array('seasons' => $seasons, 'season' => $season));
	}


	public function actionLeagues($season_id) {
        $leagues = Leagues::model()
            ->findAll('id < 9 ORDER BY id DESC');
        $season = Seasons::getPreviousSeason();

        $this->render('leagues', [
            'leagues' => $leagues,
            'season' => $season,
            'season_id' => $season_id,
        ]);
	}

//    public function actionLeague($alias) {
//CVarDumper::dump($alias, 5, true);die;
//        $model=Leagues::model()
//            ->find('alias=:alias',array("alias"=>$alias));
//        if($model===null)
//            throw new CHttpException(404,'The requested page does not exist.');
//
//        $this->render('view_leagues',array(
//            'model'=>$model,
//        ));
//    }

//    public function actionArchive($s = null) {
//
//        $season = null;
//        if($s) {
//            $season = Seasons::model()->find('alias = :alias and archive=1', array('alias' => $s));
//            if(!$season) throw new CHttpException(404, 'Not found');
//        } else $season = Seasons::getPreviousSeason();
//
//        $tourTable = $this->returnTourTable($season->id);
//        $this->render('archive', array('tourTable'=>$tourTable, 'season' => $season));
//
//    }


//	public function actionIndex() {
//		$granpriStats = $this->getGranpriStats();
//		$this->render('index', array("stats"=>$granpriStats));
//	}
//



}