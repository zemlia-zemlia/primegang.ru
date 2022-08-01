<?php

/**
 * This is the model class for table "sudoku_tours_teams".
 *
 * The followings are the available columns in table 'sudoku_tours_teams':
 * @property integer $id
 * @property integer $id_tour
 * @property integer $division
 * @property integer $id_sudoku_team1
 * @property integer $id_sudoku_team2
 * @property integer $score_team1_total
 * @property integer $score_team2_total
 */
class SudokuToursTeams extends CActiveRecord {
	public $name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sudoku_tours_teams';
	}
	
	/**
	 * @return table alias for cms
	 */
	public function tableAlias() {
		return 'sudoku_tours_teams';
	}
	
	/**
	 * @return full pair statistics for tour protocol
	 */
	private function merge($players_team1, $players_team2) {
		$merged = array();
		
		$goals_team1 = 0;
		$goals_team2 = 0;
		$points_team1 = 0;
		$points_team2 = 0;
		
		foreach($players_team1 as $pl) {
			if(!$pl['playing']) continue;
			$id_player	 = $pl["id_player"];
			$id_game	 = $pl['id_game'];
			$time		 = "time".$pl['time'];
			
			$merged[$time]['players1'][$id_player]['info'] = array_intersect_key($pl, array("id_player"=>"",'name_player'=>''));
			$merged[$time]['players1'][$id_player]['games'][$id_game] = array_intersect_key($pl, array("id_game"=>'','time'=>'','ready'=>'','prognosis'=>'','computed'=>'','points'=>'','goals'=>''));
			
			$goals_team1	 += intval($pl['goals']);
			$points_team1	 += intval($pl['points']);
		}
		
		foreach($players_team2 as $pl) {
			if(!$pl['playing']) continue;
			$id_player = $pl["id_player"];
			$id_game = $pl['id_game'];
			$time		 = "time".$pl['time'];
			
			$merged[$time]['players2'][$id_player]['info'] = array_intersect_key($pl, array("id_player"=>"",'name_player'=>''));
			$merged[$time]['players2'][$id_player]['games'][$id_game] = array_intersect_key($pl, array("id_game"=>'','time'=>'','ready'=>'','prognosis'=>'','computed'=>'','points'=>'','goals'=>''));

			$goals_team2	 += intval($pl['goals']);
			$points_team2	 += intval($pl['points']);
		}
		
		$merged['totals'] = array(
			'goals_team1'	=>$this->score_team1_total,
			'goals_team2'	=>$this->score_team2_total,
			'points_team1'	=>$points_team1,
			'points_team2'	=>$points_team2,
		);
		
		return $merged;
		
	}
	public function statistics() {
		if($this->isNewRecord) return array();
		$players_team1 = PrognoseFunctions::sudoku_getTeamPlayers($this->id_sudoku_team1, $this->id_tour);
		$players_team2 = PrognoseFunctions::sudoku_getTeamPlayers($this->id_sudoku_team2, $this->id_tour);

		$merged = $this->merge($players_team1, $players_team2);
		$json = json_encode($merged);
		$merged = json_decode($json);
		
		return $merged;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_tour, division, id_sudoku_team1, id_sudoku_team2', 'required'),
			array('computed, missing_team1, missing_team2', 'boolean'),
			array('id_tour, division, id_sudoku_team1, id_sudoku_team2, score_team1_total, score_team2_total', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_tour, division, id_sudoku_team1, id_sudoku_team2, score_team1_total, score_team2_total', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tour'	 => array(self::BELONGS_TO, 'SudokuTours', 'id_tour'),
			'division'	 => array(self::BELONGS_TO, 'SudokuTeams', 'division'),
			'team1'	 => array(self::BELONGS_TO, 'SudokuTeams', 'id_sudoku_team1'),
			'team2'	 => array(self::BELONGS_TO, 'SudokuTeams', 'id_sudoku_team2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_tour' => 'Тур судоку',
			'division' => 'Дивизион',
			'id_sudoku_team1' => 'Команда 1',
			'id_sudoku_team2' => 'Команда 2',
			'score_team1_total' => 'Очки команды 1',
			'score_team2_total' => 'Очки команды 2',
			'missing_team1' => 'Неявка команды 1',
			'missing_team2' => 'Неявка команды 2',
			'computed'=>'Обсчитан',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_tour',$this->id_tour);
		$criteria->compare('division',$this->division);
		$criteria->compare('id_sudoku_team1',$this->id_sudoku_team1);
		$criteria->compare('id_sudoku_team2',$this->id_sudoku_team2);
		$criteria->compare('score_team1_total',$this->score_team1_total);
		$criteria->compare('score_team2_total',$this->score_team2_total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SudokuToursTeams the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	protected function beforeSave() {
		if(parent::beforeSave()) {
			return true;
		} else {
	       return false;
		}
	}
	
	protected function afterFind() {
	   parent::afterFind();
	   $name = "";
	   if(!empty($this->team1)) $name .= $this->team1->name; else $name .= "?";
	   $name .= " - ";
	   if(!empty($this->team2)) $name .= $this->team2->name; else $name .= "?";
	   $this->name = $name;
	}	

}
