<?php

/**
 * This is the model class for table "sudoku_tours".
 *
 * The followings are the available columns in table 'sudoku_tours':
 * @property integer $id
 * @property integer $id_season
 * @property integer $tour_number
 * @property string $date
 */
class SudokuTours extends CActiveRecord {
	public $name;
	public $_games; //массив матчей, на которые будут делаться прогнозы 
	public $_teams; //массив играющих в паре команд
	
	/**
	 * return current prognosis tour;
	 */
	public static function currentTour() {
		//возвращаем тур с ближайшим дедлайном, 
		
		//вернуть ближайший по номеру тур, у которого не все матчи ready
		$sql = "SELECT 
			tmp.id_tour, 
			tmp.tour_number 
		FROM ( 
			SELECT 
				t.id as id_tour, 
				t.tour_number, 
				SUM(g.ready) as ready, 
				SUM(1) as total 
			FROM sudoku_tours t 
			LEFT JOIN sudoku_games g on g.id_tour = t.id 
			GROUP BY id_tour 
			ORDER BY t.tour_number 
		) as tmp 
		WHERE tmp.ready < tmp.total 
		LIMIT 1";
		$command = Yii::app()->db->createCommand($sql);
		$res = $command->queryRow();
		
		if(empty($res)) return null;
		else return self::model()->findByPk($res['id_tour']);
		
		/*
		$criteria = new CDbCriteria;
		$criteria->order = "date";
		$criteria->condition = "date > NOW()";
		
		$closestGame = SudokuGames::model()->find($criteria);
		if(empty($closestGame)) return null;
		
		return self::model()->findByPk($closestGame->id_tour);
		 * */
	}
	
	/**
	 * return tour ready teams
	 */
	public function getReadyTeams() {
		if($this->isNewRecord) return array();
		//команда готова, когда есть хотя бы 3 playing прогноза, у которых line > 0
		$sql = "SELECT
			g.id_tour,
			pr.id_player,
			pl.id_team,
			st.name as name_team,
			SUM(pr.playing) as playing,
			SUM(pr.line) as line
		FROM sudoku_prognosis pr
			LEFT JOIN sudoku_games as g on pr.id_game = g.id
			LEFT JOIN sudoku_team_players pl on pr.id_player = pl.id
			LEFT JOIN sudoku_teams st on pl.id_team = st.id
		WHERE pr.playing = 1 and pr.line > 0 and g.id_tour = :id
		GROUP BY pl.id_team";
		$command = Yii::app()->db->createCommand($sql);
		$command->params = array("id"=>$this->id);
		return $command->queryAll();
	}
	
	/**
	 * return tour users statistic
	 */
	public function getUserTourStats() {
		if($this->isNewRecord) return array();
		$sql = "select 
			pr.id_player as id_player,
			sp.name as name_player,
			g.id_tour as id_tour,
			SUM(pr.points) AS points
		FROM 
			sudoku_prognosis as pr 
			LEFT JOIN sudoku_games as g on pr.id_game = g.id
			LEFT JOIN sudoku_team_players as sp on pr.id_player = sp.id
		WHERE g.id_tour = :id_tour and pr.computed = :computed
		GROUP BY pr.id_player
		ORDER BY points DESC";
		$command = Yii::app()->db->createCommand($sql);
		$command->params = array("id_tour"=>$this->id,'computed'=>1);
		$stats = $command->queryAll();
		
		return $stats;
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sudoku_tours';
	}
	
	/**
	 * @return table alias for cms
	 */
	public function tableAlias() {
		return 'sudoku_tours';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_season, tour_number, date, date_cap', 'required'),
			array('id_season, tour_number', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_season, tour_number, date', 'safe', 'on'=>'search'),
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
			'season'	 => array(self::BELONGS_TO, 'SudokuSeasons', 'id_season'),
			'games' 	 => array(self::HAS_MANY, 'SudokuGames','id_tour'),
			'teams'		 => array(self::HAS_MANY, 'SudokuToursTeams','id_tour'), //играющие в паре команды
			'prognoses'  => array(self::HAS_MANY, 'SudokuPrognosis','id_tour',), //сделанные на тур прогнозы
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_season' => 'Сезон',
			'tour_number' => 'Номер тура',
			'date' => 'Дедлайн первого тайма',
			'date_cap' => 'Дедлайн второго тайма',
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
		$criteria->compare('id_season',$this->id_season);
		$criteria->compare('tour_number',$this->tour_number);
		$criteria->compare('date',$this->date,true);
		
		$criteria->order = "date DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SudokuTours the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	protected function beforeSave() {
		if(parent::beforeSave()) {
		$this->date = date('Y-m-d H:i:s', strtotime($this->date));
		$this->date_cap = date('Y-m-d H:i:s', strtotime($this->date_cap));
			return true;
		} else {
	       return false;
		}
	}
	
	protected function beforeDelete() {
		$tgames = SudokuGames::model()->findAll("id_tour=:id_tour",array("id_tour"=>$this->id));
		foreach($tgames as $g) $g->delete();
		
		SudokuToursTeams::model()->deleteAll("id_tour=:id_tour",array("id_tour"=>$this->id));
		return parent::beforeDelete();
	}
	
	protected function afterSave() {
		if(!empty($this->_games)) //сохраняем игры в туре
			foreach($this->_games as $_game) {
				if(!empty($_game['id'])) $game = SudokuGames::model()->findByPk($_game['id']);
				else $game = new SudokuGames;
				
				$game->attributes = $_game;
				$game->id_season = $this->id_season;
				$game->id_tour = $this->id;
				$game->save();
			}
		if(!empty($this->_teams)) //сохраняем играющие команды в туре
			foreach($this->_teams as $_team) {
				$team = new SudokuToursTeams;
				$team->attributes = $_team;
				$team->id_tour = $this->id;
				$team->save();
			}
		
		//считаем прогноз судоку
		$criteria = new CDbCriteria();
		$criteria->addCondition('id_tour = :id_tour');
		$criteria->params['id_tour'] = $this->id;

		PrognoseFunctions::computeSudokuPrognosis($criteria);
	}
	
	protected function afterFind() {
		$date = date('d.m.Y H:i', strtotime($this->date));
		$date_cap = date('d.m.Y H:i', strtotime($this->date_cap));
		
		$this->date = $date;
		$this->date_cap = $date_cap;
		
		$this->name = "Тур ".$this->tour_number." (".$this->season->name.")";
	   
	   parent::afterFind();
	}	

}
