<?php

/**
 * This is the model class for table "leagues".
 *
 * The followings are the available columns in table 'leagues':
 * @property integer $id
 * @property string $name
 * @property string $alias
 */
class Leagues extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'leagues';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, alias', 'required'),
			array('name, alias', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, alias', 'safe', 'on'=>'search'),
		);
	}

	public function getLeagueFullStats() {
		if($this->isNewRecord) return array();
		$sql = "select 
			pr.id_user as id_user,
			g.id_league as id_tour,
			SUM(1) as game_count,
			SUM(pr.balls) AS points,
			SUM(IF(pr.balls=4,1,0)) AS fact,
			SUM(IF(pr.balls=3,1,0)) AS tee,
			SUM(IF(pr.balls=2,1,0)) AS diff,
			SUM(IF(pr.balls=1,1,0)) AS res
		FROM 
			prognosis as pr 
			LEFT JOIN games as g on pr.id_game = g.id
		WHERE g.id_league = :id_league and pr.computed = :computed
		GROUP BY pr.id_user
		ORDER BY points DESC,fact DESC,tee DESC,res DESC";
		$command = Yii::app()->db->createCommand($sql);
		$command->params = array("id_league"=>$this->id,'computed'=>1);
		$result = $command->queryAll();
		
		$stats = array();
		foreach($result as $row) {
			$object = json_decode(json_encode($row), FALSE);
			$object->user = User::model()->findByPk($object->id_user);
			$stats[] = $object;
		}
		
		return $stats;
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tours'   => array(self::HAS_MANY, 'Tours', 'id_league'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
'id' => 'ID',
'name' => 'Название',
'alias' => 'Url',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alias',$this->alias,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Leagues the static model class
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
