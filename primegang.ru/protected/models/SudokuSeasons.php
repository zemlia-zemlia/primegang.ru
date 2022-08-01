<?php

/**
 * This is the model class for table "sudoku_seasons".
 *
 * The followings are the available columns in table 'sudoku_seasons':
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $divisions
 * @property string $division_names
 * @property integer $archive
 */
class SudokuSeasons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sudoku_seasons';
	}
	
	/**
	 * @return String table alias for cms
	 */
	public function tableAlias() {
		return 'Сезоны игр (судоку)';
	}

	/**
	 * @return SudokuSeasons Current season model.
	 */
	public static function getCurrentSeason() {
		$criteria = new CDbCriteria();
		$criteria->order = 'id DESC';
		$criteria->condition = 'archive = 0 or archive is null';

		return self::model()->find($criteria);
	}

	/**
	 * @return SudokuSeasons Previous season model.
	 */
	public static function getPreviousSeason() {
		$criteria = new CDbCriteria();
		$criteria->order = 'id DESC';
		$criteria->condition = 'archive = 1';

		return self::model()->find($criteria);
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('archive, divisions', 'numerical', 'integerOnly'=>true),
			array('name, alias, division_names', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, alias, divisions, division_names, archive', 'safe', 'on'=>'search'),
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
			'divisions' => 'Количество дивизионов',
			'division_names' => 'Названия дивизионов, разделённые точкой с запятой - ";" (если не указаны, названия будут в виде "Дивизион №")',
			'archive' => 'Архив',
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
		$criteria->compare('archive',$this->archive);
		$criteria->compare('divisions',$this->divisions);
		$criteria->compare('division_names',$this->division_names);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => [
				'pageSize' => 100,
			],
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SudokuSeasons the static model class
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
