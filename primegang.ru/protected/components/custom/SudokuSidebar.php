<?php

Yii::import('zii.widgets.CPortlet');
 
class SudokuSidebar extends CPortlet {
	
	public $type="bombers";
	public $limit = 3;
	public $season = null;
	public $view = "_sudokusidebar";

	private $possibleTypes = array("bombers","goleadors");
 
    public function getStatistics() {
    	if(!in_array($this->type, $this->possibleTypes)) return array();
		$stats = array();
		switch ($this->type) {
			case 'bombers':
				$stats = PrognoseFunctions::getSudokuPointsStats($this->limit, $this->season);
				break;
			case 'goleadors':
				$stats = PrognoseFunctions::getSudokuGoalsStats($this->limit, $this->season);
				break;
		}
		return $stats;		
    }
	
	public function getHeader() {
    	$header = "";
    	if(!in_array($this->type, $this->possibleTypes)) return $header;

		switch ($this->type) {
			case 'bombers':
				$header = "Бомбардиры";
				break;
			case 'goleadors':
				$header = "Клуб Григория Федотова";
				break;
		}
		
        return $header;
	}
 
    protected function renderContent() {
		$header = $this->getHeader();
		
        $this->render($this->view, array(
        	'type'=>$this->type,
        	'widgetHeader'=>$header,
		));
    }
}

?>