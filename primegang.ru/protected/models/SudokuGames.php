<?php

/**
 * This is the model class for table "sudoku_games".
 *
 * The followings are the available columns in table 'sudoku_games':
 * @property integer $id
 * @property integer $id_tour
 * @property integer $id_season
 * @property integer $id_team1
 * @property integer $id_team2
 * @property integer $ready
 * @property string $date
 * @property integer $score_team1_total
 * @property integer $score_team2_total
 * @property integer $time
 */
class SudokuGames extends CActiveRecord {
	public $name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sudoku_games';
	}
	
	/**
	 * @return table alias for cms
	 */
	public function tableAlias() {
		return 'sudoku_games';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_tour, id_season, id_team1, id_team2, date, time', 'required'),
			array('ready', 'boolean'),
			array('id_tour, id_season, id_team1, id_team2, score_team1_total, score_team2_total, time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_tour, id_season, id_team1, id_team2, ready, date, score_team1_total, score_team2_total, time', 'safe', 'on'=>'search'),
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
			'season' => array(self::BELONGS_TO, 'SudokuSeasons', 'id_season'),
			'team1'	 => array(self::BELONGS_TO, 'Teams', 'id_team1'),
			'team2'	 => array(self::BELONGS_TO, 'Teams', 'id_team2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_tour' => 'Тур',
			'id_season' => 'Сезон',
			'id_team1' => 'Команда дома',
			'id_team2' => 'Команда в гостях',
			'ready' => 'Готов к обсчету',
			'date' => 'Дата матча',
			'score_team1_total' => 'Очки команды дома',
			'score_team2_total' => 'Очки команды в гостях',
			'time' => 'Тайм',
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
		$criteria->compare('id_season',$this->id_season);
		$criteria->compare('id_team1',$this->id_team1);
		$criteria->compare('id_team2',$this->id_team2);
		$criteria->compare('ready',$this->ready);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('score_team1_total',$this->score_team1_total);
		$criteria->compare('score_team2_total',$this->score_team2_total);
		$criteria->compare('time',$this->time);
		
		$criteria->order = "date DESC";
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SudokuGames the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeDelete() {
		SudokuPrognosis::model()->deleteAll("id_game=:id_game", array("id_game"=>$this->id));
		return parent::beforeDelete();
	}
	
	
	protected function beforeSave() {
		if(parent::beforeSave()) {
		$this->date = date('Y-m-d H:i:s', strtotime($this->date));
			return true;
		} else {
	       return false;
		}
	}
	
	protected function afterFind() {
		$date = date('d.m.Y H:i', strtotime($this->date));
		$this->date = $date;
		
		$this->name = "";
		if(!empty($this->team1)) $this->name .= $this->team1->name;
		else $this->name .= "?";

		$this->name .= " - ";

		if(!empty($this->team2)) $this->name .= $this->team2->name;
		else $this->name .= "?";
	   
	   parent::afterFind();
	}	

}
