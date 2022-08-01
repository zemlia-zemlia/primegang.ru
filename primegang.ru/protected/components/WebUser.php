<?php

class WebUser extends CWebUser {
    private $_model = null;
 
    function getRole() {
        if($user = $this->getModel()){
            if ($user->isAdmin) {
            	return "admin";
            } else 
            	return "guest";
        }
		return "guest";
    }
 
    private function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = SUsers::model()->findByPk($this->id);
        }
        return $this->_model;
    }
}

?>