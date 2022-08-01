<?php

/**
 * This is the model class for table "tours".
 *
 * The followings are the available columns in table 'tours':
 * @property integer $id
 * @property integer $id_league
 * @property integer $id_season
 * @property integer $tour_number
 */
class Tours extends CActiveRecord
{
	public $name;

	/**
	 * return current prognosis tour;
	 */
	public static function currentTour($id_league) {
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
			FROM tours t 
			LEFT JOIN games g on g.id_tour = t.id
			WHERE t.id_league = :id 
			GROUP BY id_tour 
			ORDER BY t.tour_number 
		) as tmp 
		WHERE tmp.ready < tmp.total 
		LIMIT 1";
		$command = Yii::app()->db->createCommand($sql);
		$command->params = array("id"=>$id_league);
		
		$res = $command->queryRow();
		
		if(empty($res)) return null;
		else return self::model()->findByPk($res['id_tour']);
				
		/*
		$criteria = new CDbCriteria;
		$criteria->order = "date";
		$criteria->condition = "date > NOW() and id_league=:id_league";
		$criteria->params = array("id_league"=>$id_league);
		
		$closestGame = Games::model()->find($criteria);
		if(empty($closestGame)) return null;
		
		return self::model()->findByPk($closestGame->id_tour);
		 * */
	}
	
	public function getTourFullStats() {
		if($this->isNewRecord) return array();
		$sql = "select 
			pr.id_user as id_user,
			g.id_tour as id_tour,
			SUM(1) as game_count,
			SUM(pr.balls) AS points,
			SUM(IF(pr.balls=4,1,0)) AS fact,
			SUM(IF(pr.balls=3,1,0)) AS tee,
			SUM(IF(pr.balls=2,1,0)) AS diff,
			SUM(IF(pr.balls=1,1,0)) AS res
		FROM 
			prognosis as pr 
			LEFT JOIN games as g on pr.id_game = g.id
		WHERE g.id_tour = :id_tour and pr.computed = :computed
		GROUP BY pr.id_user
		ORDER BY points DESC,fact DESC,tee DESC,res DESC";
		$command = Yii::app()->db->createCommand($sql);
		$command->params = array("id_tour"=>$this->id,'computed'=>1);
		$result = $command->queryAll();
		
		$stats = array();
		foreach($result as $row) {
			$object = json_decode(json_encode($row), FALSE);
			$object->user = User::model()->findByPk($object->id_user);
			$stats[] = $object;
		}
		
		return $stats;
	}
	
	public function getTourStats($limit = null) {
		if($this->isNewRecord) return array();

		$where = "g.id_tour = :id_tour and p.computed=:computed";
		$params = array("id_tour"=>$this->id,'computed'=>1);
		
		$sql = "SELECT p.id_user as id_user, SUM(p.balls) as balls from prognosis p 
		left join games g on p.id_game = g.id
		where {$where} group by id_user order by balls DESC";
		if(!empty($limit)) $sql .= " LIMIT {$limit}";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->params = $params;
		$result = $command->queryAll();
		
		$users = array();
		foreach($result as $row) {
			$user = User::model()->findByPk($row['id_user']);
			$user->balls = $row['balls'];
			$users[] = $user;
		}
		return $users;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tours';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_league, id_season, date, tour_number', 'required'),
			array('id_league, id_season, tour_number', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_league, id_season, tour_number', 'safe', 'on'=>'search'),
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
			'games' => array(self::HAS_MANY, 'Games', 'id_tour'),
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
'date' => "Дата начала тура",
'tour_number' => 'Номер тура',
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
		$criteria->compare('tour_number',$this->tour_number);
		
		$criteria->order = "date DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tours the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeDelete() {
		//удаляем все игры этого тура
		$tgames = Games::model()->findAll("id_tour=:id_tour",array("id_tour"=>$this->id));
		foreach($tgames as $g) $g->delete();
		
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
		$date = date('d.m.Y', strtotime($this->date));
		$this->date = $date;
	   $this->name = $this->tour_number." тур"; 
	   parent::afterFind();
	}	

}
