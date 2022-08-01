<?php

class GrandprixController extends Controller
{
	function init() {
		parent::init();
		Yii::app()->theme = 'prime';
	}


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
		$granpriStats = $this->getGranpriStats();
		$this->render('index', array("stats"=>$granpriStats));
	}
}