<?php

/**
 * This is the model class for table "sudoku_team_players".
 *
 * The followings are the available columns in table 'sudoku_team_players':
 * @property integer $id
 * @property integer $id_team
 * @property integer $id_user
 * @property string $name
 * @property string $alias
 * @property integer $captain
 * @property integer $active
 */
class SudokuTeamPlayers extends CActiveRecord
{
	/**
	 * Returns current sudoku player.
	 */
	public static function currentPlayer() {
		if(Yii::app()->user->isGuest) return false;
		
		return self::model()->find("id_user=:id_user and active=1",array("id_user" => Yii::app()->user->id));
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sudoku_team_players';
	}
	
	/**
	 * @return table alias for cms
	 */
	public function tableAlias() {
		return 'sudoku_team_players';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_team, id_user, name, alias', 'required'),
			array('captain, active', 'boolean'),
			array('active', 'default', 'value' => 1),
			array('id_team, id_user', 'numerical', 'integerOnly'=>true),
			array('name, alias', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_team, id_user, name, alias, captain, active', 'safe', 'on'=>'search'),
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
			'team' => array(self::BELONGS_TO, 'SudokuTeams', 'id_team'),
			'user' => array(self::BELONGS_TO, 'User', 'id_user'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_team' => 'Команда',
			'id_user' => 'Пользователь',
			'name' => 'Название',
			'alias' => 'Url',
			'captain' => 'Капитан',
			'active' => 'Активен',
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
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_team',$this->id_team);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('captain',$this->captain);
		$criteria->compare('active',$this->active);
		
		$criteria->order = "name";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SudokuTeamPlayers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeDelete() {
		SudokuPrognosis::model()->deleteAll("id_player=:id_player", array("id_player"=>$this->id));
		return parent::beforeDelete();
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
