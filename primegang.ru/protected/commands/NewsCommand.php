<?php

class NewsCommand extends CConsoleCommand {
    public function run($args) {
        //вытаскиваем новостные рассылки, близкие к текущей дате
        //вытаскиваем список баз, из них список юзеров
        //проверяем юзера, не отписался ли он от рассылок
        //если да, то шлем
        
		date_default_timezone_set("Europe/Moscow");
		
        $_news = Conditions::model()->findAll(
        	"DATE_FORMAT(date,\"%d-%m-%Y-%H\")=:date AND isNews=:isNews",
        	array("date"=>date("d-m-Y-H"),"isNews"=>1)
		);
		
		print_r(array("date"=>date("d-m-Y-H"),"isNews"=>1));
		//return;
		
		if(empty($_news)) return;
		
		foreach($_news as $_new) {
			//вытаскиваем список баз, подходящих под условие
			if(empty($_new->condition)) $_new->condition = "TRUE"; 
			$_bases = SubscribeFunctions::basesByCondition($_new->condition);
			
			if(empty($_bases)) return;
			
			foreach ($_bases as $_base) {
				//шлем письма всем пользователям
				foreach($_base->users as $_user) {
					//ищем субстат (без релейшен)
					$substat = SubscribeStatus::model()->find("id_user=:id_user",array("id_user"=>$_user->id));
					if(!empty($substat) && $substat->stopForeverNews == 1) continue;
					
					$mail = new SentEmails;
					$mail->date = date("Y-m-d H:i:s");
					$mail->id_user	 = $_user->id;
					$mail->id_base	 = $_base->id;
					$mail->id_template = $_new->id_template;
					
					SubscribeFunctions::processMail($mail);
					$res = SubscribeFunctions::sendMail($mail);
				}
			} 
		} 
    }
}

?>