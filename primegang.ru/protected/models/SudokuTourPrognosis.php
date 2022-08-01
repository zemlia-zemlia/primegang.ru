<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SudokuTourPrognosis extends CFormModel {
	public $id_tour; 
	public $id_player;
	public $isNewRecord = true;
	
	/**
	 * $_lines = array[time(1,2)][id_game] = object[game, prognosis] - прогнозы, сделанные текущим пользователем для отображения
	 */
	public $_lines;
	
	/**
	 * Прогнозы, сделанные текущим пользователем для записи в базу $lines = array[time(1,2)][id_game][p1, p2, p3]
	 */
	public $lines;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()	{
		return array();
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array();
	}
	
	public function load($id_tour, $id_player) {
		$this->_lines = array();
		
		$player = $tour = SudokuTeamPlayers::model()->findByPk($id_player);
		if(empty($player)) return false;
		
		$tour = SudokuTours::model()->findByPk($id_tour); 
		if(empty($tour)) return false;
		
		$this->id_tour = $tour->id;
		$this->id_player = $player->id;
		
		//загружаем сделанный игроком прогноз ($lines)
		foreach($tour->games as $game) {
			$prognosis = SudokuPrognosis::model()->find(
				"id_player=:id_player and id_game=:id_game",
				array("id_player"=>$player->id, "id_game"=>$game->id)
			);
			if(empty($prognosis)) $prognosis = new SudokuPrognosis;
			
			if(!empty($this->lines[$game->time][$game->id])) {
				$_p = $this->lines[$game->time][$game->id];
				$prognosis->p1 = $_p['p1'];
				$prognosis->p2 = $_p['p2'];
				$prognosis->p3 = $_p['p3'];
			}
			
			$_item = new stdClass;
			$_item->game = $game;
			$_item->prognosis = $prognosis;
			
			$this->_lines[$game->time][$game->id] = $_item;  
		}
	}
	
	
	private function validatePrognosis($t_index) {
		$time = $this->lines[$t_index];
		if(count($time) != 3) {
			$this->addError("lines","В тайме {$t_index} не 3 матча! Такой прогноз некорректен.");
			return true;
		}
		$merged = array();
		foreach($time as $game_id=>$game) {
			$g = SudokuGames::model()->findByPk($game_id);
			if(empty($g)) {
				$this->addError("lines","Игры c ID={$game_id} не существует.");
				return true;
			}
			
			$merged = array_merge($merged, array_values($game));
		}
		$merged_u = array_unique($merged);
		if(count($merged_u) != count($merged)) {
			$this->addError("lines","В прогнозе на тайм {$t_index} повторяются цифры! Такой прогноз некорректен.");
			return true;
		}
		foreach($merged_u as $mu)
			if(!is_numeric($mu)) {
				$this->addError("lines","В прогнозе на тайм {$t_index} некоторые значения не являются числами! Такой прогноз некорректен.");
				return true;
			} elseif($mu > 9) {
				$this->addError("lines","В прогнозе на тайм {$t_index} встречаются числа больше 9! Такой прогноз некорректен.");
				return true;
			}
		return false;
	}
	public function validate($attributes=null, $clearErrors=true) {
		$this->isNewRecord = false;
		
	    if($clearErrors)
	        $this->clearErrors();
	    if($this->beforeValidate()) {
	        foreach($this->getValidators() as $validator)
	            $validator->validate($this,$attributes);
	        $this->afterValidate();
			$hasErrors = $this->hasErrors();
			
			//do external validation here
			if(!$hasErrors) {
				$tour = SudokuTours::model()->findByPk($this->id_tour);
				if(empty($tour)) {
					$this->addError("id_tour","Такого тура не существует");
					$hasErrors = true;					
				}
			}
			
			if(!$hasErrors) {
				$player = SudokuTeamPlayers::model()->findByPk($this->id_player);
				if(empty($player)) {
					$this->addError("id_player","Такого пользователя не существует");
					$hasErrors = true;					
				}
			}
			
			//каждый прогноз в каждом тайме должен содержать 9 разных цифр
			if(!$hasErrors) $hasErrors = $this->validatePrognosis(1);
			if(!$hasErrors) $hasErrors = $this->validatePrognosis(2);
			
	        return !$hasErrors;
	    }
	    else return false;
	}

	public function save() {
		$this->isNewRecord = false;
		//сохраняем сделанные прогнозы
		foreach($this->lines as $time=>$games) {
			foreach($games as $id_game=>$_p) {
				$prognosis = SudokuPrognosis::model()->find(
					"id_game=:id_game and id_player=:id_player",
					array("id_game"=>$id_game, "id_player"=>$this->id_player)
				);
				if(empty($prognosis)) {
					$prognosis = new SudokuPrognosis;
					$prognosis->id_game = $id_game;
					$prognosis->id_player = $this->id_player;
				}
				$prognosis->p1 = $_p['p1'];
				$prognosis->p2 = $_p['p2'];
				$prognosis->p3 = $_p['p3'];
				$prognosis->save();
			}
		}
		return true;
	}
}
