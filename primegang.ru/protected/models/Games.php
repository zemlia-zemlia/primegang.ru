<?php

/**
 * This is the model class for table "games".
 *
 * The followings are the available columns in table 'games':
 * @property integer $id
 * @property integer $id_league
 * @property integer $id_season
 * @property integer $id_tour
 * @property string $date
 * @property string $name
 * @property string $alias
 * @property string $text
 * @property integer $id_team1
 * @property integer $id_team2
 * @property integer $score_team1_total
 * @property integer $score_team2_total
 */
class Games extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'games';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_league, id_season, id_tour, date, id_team1, id_team2', 'required'),
			array('ready', 'boolean'),
			array('id_league, id_season, id_tour, id_team1, id_team2, score_team1_total, score_team2_total', 'numerical', 'integerOnly'=>true),
			array('name, alias', 'length', 'max'=>255),
			array('text', 'default',),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_league, id_season, id_tour, date, name, alias, text, id_team1, id_team2, score_team1_total, score_team2_total', 'safe', 'on'=>'search'),
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
			'league' => array(self::BELONGS_TO, 'Leagues', 'id_league'),
			'season' => array(self::BELONGS_TO, 'Seasons', 'id_season'),
			'tour' 	 => array(self::BELONGS_TO, 'Tours', 'id_tour'),
			'team1'  => array(self::BELONGS_TO, 'Teams', 'id_team1'),
			'team2'  => array(self::BELONGS_TO, 'Teams', 'id_team2'),
			'prognoses' => array(self::HAS_MANY, 'Prognosis', 'id_game'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
'id' => 'ID',
'id_league' => 'Лига',
'id_season' => 'Сезон',
'id_tour' => 'Тур',
'date' => 'Дата',
'name' => 'Название',
'alias' => 'Url',
'text' => 'Текст',
'id_team1' => 'Команда дома',
'id_team2' => 'Команда в гостях',
'score_team1_total' => 'Очки за матч хозяев',
'score_team2_total' => 'Очки за матч (гости)',
'ready'=>"Состоялась",
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
		$criteria->compare('id_league',$this->id_league);
		$criteria->compare('id_season',$this->id_season);
		$criteria->compare('id_tour',$this->id_tour);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('id_team1',$this->id_team1);
		$criteria->compare('id_team2',$this->id_team2);
		$criteria->compare('score_team1_total',$this->score_team1_total);
		$criteria->compare('score_team2_total',$this->score_team2_total);
		
		$criteria->order = "date DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Games the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeDelete() {
		//удаляем все прогнозы на эту игру
		Prognosis::model()->deleteAll("id_game=:id_game",array("id_game"=>$this->id));
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

	// добавил Sessa: должно пересчитывать ГранПри при добавлении в админке результата матча
//	protected function afterSave() {
//		PrognoseFunctions::computePrognosis();
//	}

	protected function afterFind() {
		$date = date('d.m.Y H:i', strtotime($this->date));
		$this->date = $date;
		
		$this->name = "";
		if(!empty($this->team1)) $this->name .= $this->team1->name." - ";
		if(!empty($this->team2)) $this->name .= $this->team2->name;
		
	   parent::afterFind();
	}

}
