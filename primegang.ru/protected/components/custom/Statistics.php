<?php

Yii::import('zii.widgets.CPortlet');
 
class Statistics extends CPortlet {
	
	public $type="tour";
	public $dataId = null;
	public $limit = 10;
	public $view = "_sidebar";
	
	private $possibleTypes = array("tour","league","season");
 
    private function getStatistics() {
    	if(!in_array($this->type, $this->possibleTypes)) return array();
		
		$header = "";
		
		$where = "1";
		$params = array();
		switch ($this->type) {
			case 'tour':
				$where = "g.id_tour = :id_tour";
				$header = "Лидеры тура";
				$params = array("id_tour"=>$this->dataId);
				break;
			case 'league':
				$where = "g.id_league = :id_league";
				$params = array("id_league"=>$this->dataId);
				$header = "Лидеры лиги";
				break;
			case 'season':
				$where = "g.id_season = :id_season";
				$header = "Лидеры гран-при";
				if(empty($dataId)) {
					
					$criteria = new CDbCriteria();
					$criteria->order="id DESC";
					$season = Seasons::model()->find($criteria);
					$this->dataId = $season->id;
				} 
				$params = array("id_season"=>$this->dataId);
				break;
		}
		
		$sql = "SELECT p.id_user as id_user, SUM(p.balls) as balls from prognosis p 
		left join games g on p.id_game = g.id
		where {$where} and p.computed=1 group by id_user order by balls DESC";
		if(!empty($this->limit)) $sql .= " LIMIT {$this->limit}";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->params = $params;
		$result = $command->queryAll();
		
		$users = array();
		foreach($result as $row) {
			$user = User::model()->findByPk($row['id_user']);
			$user->balls = $row['balls'];
			$users[] = $user;
		}
		return $users;
    }
	
	private function getHeader() {
    	$header = "";
    	if(!in_array($this->type, $this->possibleTypes)) return $header;

		switch ($this->type) {
			case 'tour':
				$header = "Лидеры тура";
				break;
			case 'league':
				$header = "Лидеры лиги";
				break;
			case 'season':
				$header = "Лидеры гран-при";
				break;
		}
		
        return $header;
	}
 
    protected function renderContent() {
    	$statistics = $this->getStatistics();
		$header = $this->getHeader();
		
        $this->render($this->view, array(
        	'statistics'=>$statistics,
        	'widgetHeader'=>$header,
		));
    }
}

?>