<?php

/**
 * This is the model class for table "teams".
 *
 * The followings are the available columns in table 'teams':
 * @property integer $id
 * @property integer $id_league
 * @property string $name
 * @property string $alias
 * @property string $town
 * @property string $image_url
 * @property string $text
 */
class Teams extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'teams';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_league, name, alias', 'required'),
			array('text', 'default'),
			array('id_league', 'numerical', 'integerOnly'=>true),
			array('name, alias, town, image_url', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_league, name, alias, town, image_url, text', 'safe', 'on'=>'search'),
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
			'name' => 'Название',
			'alias' => 'Url',
			'town' => 'Город',
			'image_url' => 'Фотография',
			'text' => 'Текст',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('town',$this->town,true);
		$criteria->compare('image_url',$this->image_url,true);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Teams the static model class
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
