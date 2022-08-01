<?php

/**
 * This is the model class for table "sudoku_teams".
 *
 * The followings are the available columns in table 'sudoku_teams':
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $active
 */
class SudokuTeams extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sudoku_teams';
	}
	
	/**
	 * @return table alias for cms
	 */
	public function tableAlias() {
		return 'sudoku_teams';
	}
	
	/**
	 * @return team lineups
	 */
	public function getLineups($id_tour,$time, $benchOnly = false) {
		if($this->isNewRecord) return array();
		
		$bench = "AND 1";
		if($benchOnly) $bench = "AND pr.playing = 0 and pr.line = 0";
		
		$sql = "SELECT
			g.id_tour,
			g.time,
			pr.id_player,
			pr.id_game,
			pl.id_user,
			pl.name as name_player,
			pl.id_team,
			pr.prognosis,
			pr.points
		FROM sudoku_prognosis pr
			LEFT JOIN sudoku_games as g on pr.id_game = g.id
			LEFT JOIN sudoku_team_players pl on pr.id_player = pl.id
			LEFT JOIN sudoku_teams st on pl.id_team = st.id
		WHERE g.id_tour = :id_tour and g.time = :time and pl.id_team = :id_team and pl.active = 1 {$bench}
		ORDER BY g.id";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->params = array("id_tour"=>$id_tour, "time"=>$time, "id_team"=>$this->id);
		$result = $command->queryAll();
		
		$pretty = array();
		foreach($result as $row) {
			$id_player = $row['id_player'];
			$id_game = $row["id_game"];
			$pretty[$id_player]['info']['id_player'] = $id_player;
			$pretty[$id_player]['info']['id_user'] = $row['id_user'];
			$pretty[$id_player]['info']['name_player'] = $row['name_player'];
			$pretty[$id_player]['prognosis'][$id_game]['json'] = $row['prognosis'];
			$pretty[$id_player]['prognosis'][$id_game]['points'] = $row['points'];
		}
		return $pretty;
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
			array('date', 'default'),
			array('active', 'boolean'),
			array('name, alias, image_url', 'length', 'max'=>255),
			array('comment', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, alias, active', 'safe', 'on'=>'search'),
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
			'players'   => array(self::HAS_MANY, 'SudokuTeamPlayers', 'id_team', 'condition' => 'active=1'),
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
			'active' => 'Активна',
			'comment'=>"Комментарий администратора (виден капитану команды, если она не активна)",
			'date'=>"Время модификации",
			'image_url' => 'Логотип',
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
	 * @return SudokuTeams the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	protected function beforeDelete() {
		SudokuTeamPlayers::model()->deleteAll("id_team=:id_team",array("id_team"=>$this->id));
		SudokuToursTeams::model()->deleteAll("id_sudoku_team1=:id_team OR id_sudoku_team2=:id_team",array("id_team"=>$this->id));
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
