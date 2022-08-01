<?php

class SubscribeFunctions {
	
	
	protected static function BasesByCondition_prerareCondition($condition, $mysql=true) {
		//готовим кондишен к eval
		/*
		Условие составляется из переменных и логических отношений между ними. Доступные переменные:
		
		DEAD_DAYS – количество дней простоя
		ALIVE_DAYS – количество дней между регистрацией и последней транзакцией ("живые дни")
		SIGNUP_DAYS – количество дней после регистрации (сколько времени зарегистрирован)
		SIGNUP_COMPLETED – регистрация завершена
		Отношения между условиями:
		
		NOT – НЕ; например, NOT SIGNUP_COMPLETED
		AND – И; DEAD_DAYS < 3 AND SIGNUP_COMPLETED
		OR – ИЛИ; например DEAD_DAYS < 3 OR ALIVE_DAYS > 5
		<, >, <=, >=, ==, != – меньше, больше, меньше или равно, больше или равно, равно, не равно
		*/
		if(!$mysql) {
			$replace = array(
				"DEAD_DAYS"=>"\$dead_days",
				"ALIVE_DAYS"=>"\$alive_days",
				"SIGNUP_DAYS"=>"\$signup_days",
				"SIGNUP_COMPLETED"=>"\$signup_completed",
				"NOT "=>"!",
				"AND"=>"\&\&",
				"OR"=>"||",
			);
		} else {
			$replace = array(
				"=="=>"=",
			);
		}
		$condition = str_replace(array_keys($replace), $replace, $condition);	
		return $condition;	
	}
	public static function basesByCondition($condition) {
		//кондишен - строка с переменными:
		//dead_days = время простоя
		//alive_days = время между регистрацией и последней транзакцией
		//signup_days = время между регистрацией и сейчас
		//signup_completed = регистрация завершена
		
		//condition должен работать как условие в mysql
		$condition = self::BasesByCondition_prerareCondition($condition);
		$sql = "
			SELECT * FROM (
				SELECT 
					`b`.`id`,
					`b`.`users_id` AS `OWNER_ID`,
					DATEDIFF(NOW(),`ba`.`date`)		 AS `DEAD_DAYS`,
					DATEDIFF(`ba`.`date`,`b`.`date`) AS `ALIVE_DAYS`,
					DATEDIFF(NOW(),`b`.`date`)		 AS `SIGNUP_DAYS`,
					IF(`b`.`subdomain` IS NULL OR `b`.`subdomain`=\"\", FALSE, TRUE) AS SIGNUP_COMPLETED 
				FROM `bases` AS `b`
				LEFT JOIN `bases_active` AS `ba` ON `b`.`id`=`ba`.`bases_id`
			) AS `tmp` WHERE 1";
		if(!empty($condition)) $sql .= " AND ".$condition;
		
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->queryAll();
		
		$modelIds = array();
		foreach($result as $row) $modelIds[] = $row['id'];
		
		$criteria = new CDbCriteria;
		$criteria->addInCondition("id",$modelIds);
		return Bases::model()->findAll($criteria);
	}
	
	protected static function getUnsubscribeHash($email) {
		if(empty($email->user) || empty($email->base)) return false;
		$substa = SubscribeStatus::model()->find("id_user = :id_user AND id_base = :id_base", array("id_user"=>$email->user->id, "id_base"=>$email->base->id));
		if($substa === null) {
			//создаем модель
			$substa = new SubscribeStatus;
			$substa->id_user = $email->user->id;
			$substa->id_base = $email->base->id;
			$substa->unsubscribe_hash = md5( $email->user->id .  $email->base->id . time());
			$substa->save();
		}
		return $substa->unsubscribe_hash;
	}
	
	protected static function processTemplate($email, $additional_params = null) {
		$ownername = "Без владельца";
		if(!empty($email->base->owner)) $ownername = (empty($email->base->owner->display_name))?$email->base->owner->username:$email->base->owner->display_name;
		
		$dead_days = 10000;
		$alive_days = 0;
		$base_activity = "Никогда";
		if(!empty($email->base->activity)) {
			$dead_days = floor( (time() - strtotime($email->base->activity->date)) / (60*60*24) );
			$alive_days = floor( (strtotime($email->base->activity->date) - strtotime($email->base->date)) / (60*60*24) );
			$base_activity = $email->base->activity->date;
		}
		$unsubscribe_hash = self::getUnsubscribeHash($email);

		$replace = array(
			"username"		=>$email->user->username,		"usermail"		=>$email->user->email,
			"display_name"	=>$email->user->display_name,	"userstate"		=>$email->user->state,
			"userphone"		=>$email->user->phone,			"basename"		=>$email->base->name,
			"ownermail"		=>$email->base->owner->email,	"subdomain"		=>$email->base->subdomain,
			"basephone"		=>$email->base->phone,			"config_id"		=>$email->base->config_id,
			"dead_days"		=>$dead_days, 					"alive_days"	=>$alive_days,
			"ownername"		=>$ownername,					"last_transaction"=>$base_activity,
			"unsubscribe_hash" =>$unsubscribe_hash,
		);
		
		$template	 = Templates::model()->findByPk($email->id_template);
		if($template === null) return false;

		$subject	 = $template->subject;
		$body		 = $template->body;
		
		foreach($replace as $key=>$value) {
			$needle = "[%".strtoupper($key)."%]";
			$subject = str_replace($needle, $value, $subject);
			$body	 = str_replace($needle, $value, $body);
		}
		
		//добавляем дополнительные параметры
		if(!empty($additional_params) && is_array($additional_params)) { 
			foreach($additional_params as $key=>$value) {
				$needle = "[%".strtoupper($key)."%]";
				$subject = str_replace($needle, $value, $subject);
				$body	 = str_replace($needle, $value, $body);
			}
		}
		
		$email->subject	 = $subject;
		$email->body	 = $body;
	}

	public static function processMail($email, $additional_params = null) {
		//$email - sent email, из шаблона восстанавливаем переменные
		self::processTemplate($email,$additional_params);
		return true;
	}

	public static function sendMail($email) {
		//отправляем почту пользователю
		$body	 = $email->body;
		$subject = $email->subject;
		$to = $email->user->email;
		
		$siteMail = CommonFunctions::getSiteOption('site_email');
		$siteName = CommonFunctions::getSiteOption('site_name');
		
		//помещаем письмо в стек отправки
        $mail = new MailsStack;
		$mail->charset		 = "utf-8";
		$mail->name_from	 = $siteName;
		$mail->email_from	 = $siteMail;
		$mail->name_to		 = $email->user->display_name;
		$mail->email_to		 = $to;
		$mail->subject		 = $subject;
		$mail->body			 = $body;
		$mail->time			 = date("Y-m-d H:i:s");
		$mail->save();
		
		//логируем это в базе
		$res = $email->save();
		
		return $res;
	}
	
}

?>