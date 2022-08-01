<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SudokuPlayingPrognosis extends CFormModel {
	public $playingPrognosis;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules() {
		return array();
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array();
	}
	
	public function validate($attributes=null, $clearErrors=true) {
	    if($clearErrors)
	        $this->clearErrors();
	    if($this->beforeValidate()) {
	        foreach($this->getValidators() as $validator)
	            $validator->validate($this,$attributes);
	        $this->afterValidate();
			$hasErrors = $this->hasErrors();
			
			return !$hasErrors;
	    }
	    else
	        return false;
	}

	public function save() {
		foreach($this->playingPrognosis as $game_id=>$p) {
			$game = SudokuGames::model()->findByPk($game_id);
			if(empty($game)) continue;
			foreach($p as $pid=>$value) {
				$prog = SudokuPrognosis::model()->findByPk($pid);
				if(empty($prog)) continue;
				if($prog->id_game != $game->id) continue;
				
				$prog->playing	 = ($value['playing']) ? true : false;
				$prog->line		 = $value['line'];
				$prog->save();							
			}
		}
		return true;
	}
}
