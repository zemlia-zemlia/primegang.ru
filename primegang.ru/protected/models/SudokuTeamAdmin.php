<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SudokuTeamAdmin extends CFormModel {
	public $teamid; 
	public $teamname;
	public $addPlayerLogin;
	public $addPlayerName;
	public $removePlayers;
	public $setAdmin;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('teamname, addPlayerLogin, addPlayerName', 'length', 'max'=>255, 'min'=>3),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'teamname'		=> "Название команды",
			'addPlayerLogin'=> "Новый игрок",
			'addPlayerName'	=> "Псевдоним",
		);
	}
	
	public function load($team) {
		$this->teamname	 = $team->name;
		$this->teamid	 = $team->id;
	}
	
	public function validate($attributes=null, $clearErrors=true) {
	    if($clearErrors)
	        $this->clearErrors();
	    if($this->beforeValidate()) {
	        foreach($this->getValidators() as $validator)
	            $validator->validate($this,$attributes);
	        $this->afterValidate();
			
			$hasErrors = $this->hasErrors();
			
			//проверяем наличие такой команды
			if(!$hasErrors) {
				$team = SudokuTeams::model()->findByPk($this->teamid);
				if(empty($team)) {
					$this->addError("teamid","Команды с ID=".$this->teamid." не существует.");
					$hasErrors = true;
				}
			}
			
			$user = null;
			if(!$hasErrors && !empty($this->addPlayerLogin)) {
				//проверяем наличие ника нового игрока в бд
				$user = User::model()->find("username=:username",array("username"=>$this->addPlayerLogin));
				if($user===null) {
					$this->addError("addPlayerLogin","Пользователь с ником ".$this->addPlayerLogin." не зарегистрирован.");
					$hasErrors = true;
				}
			}
			
			if(!$hasErrors && !empty($this->addPlayerLogin) && !empty($user)) {
				//проверяем, не занят ли игрок в другой команде
				$sudokuPlayer = SudokuTeamPlayers::model()->find("id_user=:id_user and active=1", array("id_user"=>$user->id));
				if($sudokuPlayer !== null) {
					$this->addError("addPlayerLogin","Пользователь {$user->username} уже состоит в команде ".$sudokuPlayer->team->name);
					$hasErrors = true;
				}
			}
			
			if(!$hasErrors && !empty($this->addPlayerLogin) && empty($this->addPlayerName)) {
				$this->addError("addPlayerName","У игрока должен быть псевдоним!");
				$hasErrors = true;
			}
			
				
			if(!$hasErrors) {
				//проверяем, мб уже есть такая команда
				$teamAlias = CommonFunctions::transliterate(trim($this->teamname));
				$existingTeam = SudokuTeams::model()->find("alias=:alias",array("alias"=>$teamAlias));
				if($existingTeam !== null && $existingTeam->id != $this->teamid) {
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
		//редактирование команды
		$team = SudokuTeams::model()->findByPk($this->teamid);
		$team->name = $this->teamname;
		$team->save();
		
		//добавляем нового игрока
		if(!empty($this->addPlayerLogin)) {
			$user = User::model()->find("username=:username",array("username"=>$this->addPlayerLogin));
			$newPlayer = new SudokuTeamPlayers;
			$newPlayer->id_user = $user->id;
			$newPlayer->id_team = $team->id;
			$newPlayer->name = $this->addPlayerName;
			$newPlayer->active = 1;
			$newPlayer->alias = CommonFunctions::transliterate($newPlayer->name);
			$newPlayer->save();
		}
		
		//удаляем пользователей
		if(!empty($this->removePlayers)) {
			foreach($this->removePlayers as $playerId=>$remove) {
				if(!$remove) continue;
				$player = SudokuTeamPlayers::model()->findByPk($playerId);
				if(!empty($player)) $player->delete();
			}
		}
		return true;
	}
}
