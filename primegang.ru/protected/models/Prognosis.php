<?php

/**
 * This is the model class for table "prognosis".
 *
 * The followings are the available columns in table 'prognosis':
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_game
 * @property integer $score_team1_total
 * @property integer $score_team2_total
 * @property integer $computed
 * @property integer $balls
 */
class Prognosis extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'prognosis';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, id_game, score_team1_total, score_team2_total', 'required'),
			array('computed', 'boolean'),
			array('id_user, id_game, score_team1_total, score_team2_total, balls', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_user, id_game, score_team1_total, score_team2_total, computed, balls', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * @return current user prognosis.
	 */
	public static function loadUserGamePrognosis($gameId) {
		//ищем прогноз, сделанный текущим пользователем на текущую игру
		$prognosis = null;
		if(!Yii::app()->user->isGuest && Yii::app()->user->id) {
			$prognosis = self::model()->find("id_user=:id_user and id_game=:id_game and computed=1", array("id_user"=>Yii::app()->user->id, "id_game"=>$gameId));
		}
		return $prognosis;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'id_user'),
			'game' => array(self::BELONGS_TO, 'Games', 'id_game'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_user' => 'Пользователь',
			'id_game' => 'Игра',
			'score_team1_total' => 'Голы 1й команды, прогноз',
			'score_team2_total' => 'Голы 2й команды, прогноз',
			'computed' => 'Обсчитан',
			'balls' => 'Набранные баллы',
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
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('id_game',$this->id_game);
		$criteria->compare('score_team1_total',$this->score_team1_total);
		$criteria->compare('score_team2_total',$this->score_team2_total);
		$criteria->compare('computed',$this->computed);
		$criteria->compare('balls',$this->balls);
		
		$criteria->order = "id DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Prognosis the static model class
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
	}	

}
