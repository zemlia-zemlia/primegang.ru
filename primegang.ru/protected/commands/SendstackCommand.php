<?php

class SendstackCommand extends CConsoleCommand {
    public function run($args) {
        // тут делаем то, что нам нужно
        return;
        
        $criteria = new CDbCriteria();
		$criteria->limit = 10;
		
        $_stack = MailsStack::model()->findAll($criteria);
		foreach($_stack as $_mail) {
			
	        $mail = Yii::app()->Smtpmail;
	        $mail->CharSet = $_mail->charset;
	        $mail->SetFrom($_mail->email_from, $_mail->name_from);
	        $mail->Subject = $_mail->subject;
	        $mail->MsgHTML($_mail->body);
	        $mail->AddAddress($_mail->email_to, $_mail->name_to);
			
			echo "Sending to ".$_mail->email_to."...\n";
			$res = $mail->Send();
			
	        if(!$res) {
	        	$error_msg = "Mailer Error: " . $mail->ErrorInfo; 
	            error_log($error_msg);
				echo $error_msg."\n";
	        } else $_mail->delete();
			
			$mail->ClearAddresses();
		}
    }
}

?>