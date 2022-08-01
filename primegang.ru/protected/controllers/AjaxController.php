<?php

class AjaxController extends Controller
{
	public function actionTests() {
		PrognoseFunctions::computePrognosis();
	}
	
	public function actionUpdateprognosis()	{
		if(!Yii::app()->request->isAjaxRequest) return;
		$answer = array("success"=>false,"message"=>"");
		
		//let's update prognosis
		$errors = false;
		if(isset($_POST["Prognosis"])) {
			$_prognosis = $_POST["Prognosis"];
			foreach($_prognosis as $gameId=>$scores) {
				$game = Games::model()->findByPk($gameId);
				if($game===null) {$answer["message"] = "Неверный код игры!"; $errors = true;}
				
				if(strtotime($game->date) <= time()) {$answer["message"] = "Дедлайн подачи прогнозов уже прошел!"; $errors = true;}
				
				if(!$errors) {
					$score_team1 = $scores['score_team1'];
					$score_team2 = $scores['score_team2'];
					if(!is_numeric($score_team1) || !is_numeric($score_team2)) {$answer["message"] = "Неверный прогноз!"; $errors = true;}
				}
				
				if(!$errors) {
					if(Yii::app()->user->isGuest) {$answer["message"] = "Зарегистрируйтесь, прежде чем делать прогнозы!"; $errors = true;}
				}
				
				if(!$errors) {
					$score_team1 = intval($score_team1);
					$score_team2 = intval($score_team2);
					
					$prognosis = Prognosis::model()->find(
						"id_user=:id_user AND id_game=:id_game",
						array("id_user"=>Yii::app()->user->id, "id_game"=>$game->id)
					);
					if($prognosis === null) {
						$prognosis = new Prognosis;
						$prognosis->id_user = Yii::app()->user->id;
						$prognosis->id_game = $game->id;
					}
					$prognosis->score_team1_total = $score_team1;
					$prognosis->score_team2_total = $score_team2;
					
					if(!$prognosis->save()) {
						{$answer["message"] = "Прогноз не удалось сохранить. Попробуйте позже; возможно, мы это починим."; $errors = true;}
					}
				}
				
				if(!$errors) {
					$answer["success"] = true;
				}
			}
		}
		
		$this->renderPartial('updateprognosis',array(
			'answer'=>$answer,
		));
	}
}