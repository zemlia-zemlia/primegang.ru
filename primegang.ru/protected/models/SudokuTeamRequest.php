<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SudokuTeamRequest extends CFormModel
{
	public $teamname;
	public $captainlogin;
	public $captainname;
	public $player1login;
	public $player2login;
	public $player3login;
	public $player4login;
	public $player5login;
	public $player1name;
	public $player2name;
	public $player3name;
	public $player4name;
	public $player5name;
	
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('teamname, captainlogin, captainname, player1login, player1name, player2login, player2name', 'required'),
			array('teamname, captainlogin, captainname, player1login, player1name, player2login, player2name, player3login, player3name, player4login, player4name, player5login, player5name', 'length', 'min'=>2, 'max'=>255),
			//array('teamname, captainname, player1name, player2name, player3name, player4name, player5name', 'length', 'min'=>5),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'teamname'		=> "Название команды",
			'captainlogin'	=> "Капитан",
			'captainname'	=> "Псевдоним",
			'player1login'	=> "Игрок 1",
			'player2login'	=> "Игрок 2",
			'player3login'	=> "Игрок 3",
			'player4login'	=> "Игрок 4",
			'player5login'	=> "Игрок 5",
			'player1name'	=> "Псевдоним",
			'player2name'	=> "Псевдоним",
			'player3name'	=> "Псевдоним",
			'player4name'	=> "Псевдоним",
			'player5name'	=> "Псевдоним",
		);
	}

	public function validate($attributes=null, $clearErrors=true) {
	    if($clearErrors)
	        $this->clearErrors();
	    if($this->beforeValidate())
	    {
	        foreach($this->getValidators() as $validator)
	            $validator->validate($this,$attributes);
	        $this->afterValidate();
			
			$hasErrors = $this->hasErrors();
			$existingUsers = null;
						
			if(!$hasErrors) {
				//проверяем наличие каждого ника в бд
				$in = array();
				$fields = array('captainlogin','player1login','player2login','player3login','player4login','player5login');
				foreach($fields as $f) if(!empty($this->$f)) $in[] = $this->$f;
				 
				$criteria = new CDbCriteria();
				$criteria->addInCondition("username",$in);
				$existingUsers = User::model()->findAll($criteria);
				
				$existingUsernames = array();
				foreach($existingUsers as $u) $existingUsernames[] = $u->username;
				
				foreach($fields as $f) {
					if(!empty($this->$f) && !in_array($this->$f, $existingUsernames)) { 
						$this->addError($f,"Пользователь с ником ".$this->$f." не зарегистрирован.");
						$hasErrors = true;
					}
				}
			}
			
			if(!$hasErrors) {
				//проверяем, не заняты ли игроки в другой команде
				foreach($existingUsers as $u) {
					$sudokuPlayer = SudokuTeamPlayers::model()->find("id_user=:id_user and active=1", array("id_user"=>$u->id));
					if($sudokuPlayer !== null) {
						$this->addError("","Пользователь {$u->username} уже состоит в команде ".$sudokuPlayer->team->name);
						$hasErrors = true;
					}
				}
			}
			
			if(!$hasErrors) {
				//проверяем, мб уже есть такая команда
				$teamAlias = CommonFunctions::transliterate(trim($this->teamname));
				$existingTeam = SudokuTeams::model()->find("alias=:alias",array("alias"=>$teamAlias));
				if($existingTeam !== null) {
					$this->addError("teamname","Такая команда уже заявлена!");
					$hasErrors = true;
				}
			}
			
	        return !$hasErrors;
	    }
	    else
	        return false;
	}

	public function save() {
		//заполняем заявку на создание команды
		//print_r($this); die();
		
		$team = new SudokuTeams;
		$team->name = $this->teamname;
		$team->alias = CommonFunctions::transliterate($this->teamname);
		$team->active = false;
		
		if(!$team->save()) return false;
		
		$fields = array('captain','player1','player2','player3','player4','player5');
		foreach($fields as $f) {
			$flogin = $f.'login';
			$fname = $f.'name';
			
			if(empty($this->$flogin)) continue;
			//создаем игроков
			$user = User::model()->find("username=:username",array("username"=>$this->$flogin));
			if($user === null) continue;
			
			$sudokuUser = new SudokuTeamPlayers();
			$sudokuUser->id_user = $user->id;
			$sudokuUser->id_team = $team->id;
			$sudokuUser->name = $this->$fname;
			$sudokuUser->alias = CommonFunctions::transliterate($this->$fname);
			$sudokuUser->captain = ($f=='captain')?true:false;
			$sudokuUser->active = true;
			if(!$sudokuUser->save()) return false;
		}
		return true;
	}
}
