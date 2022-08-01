<?php

/**
 * This is the model class for table "sudoku_prognosis".
 *
 * The followings are the available columns in table 'sudoku_prognosis':
 * @property integer $id
 * @property integer $id_player
 * @property integer $id_game
 * @property string $prognosis
 * @property integer $computed
 * @property integer $balls
 */
class SudokuPrognosis extends CActiveRecord {
	public $p1;
	public $p2;
	public $p3;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sudoku_prognosis';
	}
	
	/**
	 * @return table alias for cms
	 */
	public function tableAlias() {
		return 'sudoku_prognosis';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_player, id_game, p1, p2, p3', 'required'),
			array('computed, playing', 'boolean'),
			array('id_player, id_game, balls, points, line', 'numerical', 'integerOnly'=>true),
			array('prognosis', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_player, id_game, prognosis, computed, balls', 'safe', 'on'=>'search'),
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
			'player'	 => array(self::BELONGS_TO, 'SudokuTeamPlayers', 'id_player'),
			'game'		 => array(self::BELONGS_TO, 'SudokuGames', 'id_game'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_player' => 'Игрок судоку',
			'id_game' => 'Игра судоку',
			'prognosis' => 'JSON прогноз',
			'playing'=>"Играет",
			'computed' => 'Обсчитан',
			'balls' => 'Набранные голы',
			'points' => 'Набранные очки',
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
		$criteria->compare('id_player',$this->id_player);
		$criteria->compare('id_game',$this->id_game);
		$criteria->compare('prognosis',$this->prognosis,true);
		$criteria->compare('computed',$this->computed);
		$criteria->compare('balls',$this->balls);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SudokuPrognosis the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	protected function beforeSave() {
		if(parent::beforeSave()) {
			$_prognosis = array("p1"=>$this->p1, "p2"=>$this->p2, "p3"=>$this->p3);
			$this->prognosis = json_encode($_prognosis);

			return true;
		} else {
	       return false;
		}
	}
	
	protected function afterFind() {
		parent::afterFind();
		$_prognosis = json_decode($this->prognosis, true);
		
		foreach($_prognosis as $key=>$value) $this->$key = $value;
	}	
}
