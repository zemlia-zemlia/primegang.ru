<?php

class PrognoseFunctions {
	
	public static function computePrognosis() {
		$uncomputed = Prognosis::model()->findAll(
			"computed=:fls",
			array("fls"=>0)
		);
		
		if(empty($uncomputed)) return;
		foreach($uncomputed as $prognosis) {
			$game = Games::model()->findByPk($prognosis->id_game);
			if(empty($game)) continue;
			if(!$game->ready) continue;
			
			$pr_score1 = $prognosis->score_team1_total;
			$pr_score2 = $prognosis->score_team2_total;
			
			$ga_score1 = $game->score_team1_total;
			$ga_score2 = $game->score_team2_total;
			
			$balls = 0;
			
			$result = false; $difference = false; $tie = false; $fact = false;
			
			if($pr_score1 > $pr_score2 && $ga_score1 > $ga_score2) $result = true;
			if($pr_score1 < $pr_score2 && $ga_score1 < $ga_score2) $result = true;
			
			if(abs($pr_score1 - $pr_score2) == abs($ga_score1 - $ga_score2)) $difference = true;

			if($pr_score1 == $pr_score2 && $ga_score1 == $ga_score2) $tie = true;
			
			if($pr_score1 == $ga_score1 && $pr_score2 == $ga_score2) $fact = true;
			
			if($result) $balls = 1;
			if($difference && $result) $balls = 2;
			if($tie) $balls = 3;
			if($fact) $balls = 4;
			
			//echo "фактический счет: {$ga_score1}:{$ga_score2}; <br/> прогнозный счет: {$pr_score1}:{$pr_score2} <br/> баллы: {$balls}<br/><br/><br/>";
			
			$prognosis->balls = $balls;
			$prognosis->computed = 1;
			$prognosis->save();
		}
	}

	public static function sudoku_getTeamPlayers($id_team, $id_tour) {

		$sql = "SELECT
			pr.id_game as id_game,
			sg.id_tour as id_tour,
			sg.time as time,
			sg.ready as ready,
			sg.score_team1_total as score_team1_total,
			sg.score_team2_total as score_team2_total,
			st.date as deadline1, 
			st.date_cap as deadline2,
							
			sp.id_team as id_team,
			sp.id as id_player,
			sp.name as name_player,
			
			pr.id as id_prognosis,
			IF(pr.line = 0, 99, pr.line) as line,
			pr.playing as playing,
			pr.prognosis as prognosis,
			pr.computed as computed,
			pr.points as points,
			pr.balls as goals
		FROM sudoku_team_players as sp 
		LEFT JOIN sudoku_prognosis as pr on sp.id = pr.id_player
		LEFT JOIN sudoku_games as sg on pr.id_game = sg.id
		LEFT JOIN sudoku_tours as st on sg.id_tour = st.id
		WHERE 1
		  AND sp.id_team = :id_team 
		  AND sg.id_tour = :id_tour 
		  AND sp.active = 1
		  AND pr.id IS NOT NULL
		ORDER BY id_tour, id_game, playing DESC, line
		";

		//ORDER BY id_tour, id_game, playing DESC, line DESC, id_player - old sorting rules
		// "AND pr.playing" = :playing removed because we're playing random players

		$params = array('id_team'=>$id_team, 'id_tour'=>$id_tour);//, 'playing' => 0);
		$command = Yii::app()->db->createCommand($sql);
		$command->params = $params;
		
		return $command->queryAll();
	}
	public static function sudoku_mergePlayers($players_team1, $players_team2) {

	    $merged = array();
	    $random = array();

	    $random_time_players = array();

		$players = array(1 => $players_team1, 2 => $players_team2);

		foreach($players as $index => $players_team) {
			foreach($players_team as $pt) {
				$id_game    = 'game'.$pt['id_game'];
				$time       = $pt['time'];
				$id_player  = $pt['id_player'];

				$game = self::getValues($pt, array('id_game','id_tour','ready','time','score_team1_total','score_team2_total', 'deadline1', 'deadline2'));

				$merged[$id_game]['game'] = $game;
				$random[$id_game]['game'] = $game;

				if($pt['line'] == 99) {
					$random[$id_game]['players'.$index][] = $pt;
					$random_time_players['players'.$index][$time][$id_player] = $id_player;
                } else {
				    $merged[$id_game]['players'.$index][] = $pt;
				}
			}
        }

        //раскидываем random по линиям, сохраняем прогнозы:
        //1. получим невыставленных игроков на первый тайм
        foreach($random_time_players as $pk => $pv)
		    foreach($pv as $tk => $tv)
		        $random_time_players[$pk][$tk] = self::shuffle($tv);

		//2. сортируем рандомных игроков согласно порядку в рандом_тайм_плеерс и добавляем в мержед
        foreach($random as $gameId => $row) {
            foreach($row as $players_key => $players) {
                if($players_key == 'game') continue;
                $time = $row['game']['time'];

                $arrange_order = isset($random_time_players[$players_key][$time]) ? $random_time_players[$players_key][$time] : array();
                if($arrange_order) {
                    $players = self::arrange($players, 'id_player', $arrange_order);

                    if(!isset($merged[$gameId][$players_key])) $merged[$gameId][$players_key] = array();

	                $deadline1 = strtotime($row['game']['deadline1']);
	                $deadline2 = strtotime($row['game']['deadline2']);
	                $now = time();

                    foreach($players as $player) {
                        $line = count($merged[$gameId][$players_key]) + 1;
                        if($line < 4) $player['line'] = $line;

	                    $merged[$gameId][$players_key][] = $player;

	                    ///в этом месте сохраняем расстановки на прогнозы
                        // для первого тайма: если дедлайн первого тайма прошел
                        if( $deadline1 < $now && $player['line'] < 4 && $time == 1 ) {
                            $prognosis = SudokuPrognosis::model()->findByPk($player['id_prognosis']);
                            $prognosis->playing = 1;
                            $prognosis->line = $player['line'];
                            $prognosis->save();
                        }

                        // для второго тайма: если дедлайн второго тайма прошел
	                    if( $deadline2 < $now && $player['line'] < 4 && $time == 2 ) {
		                    $prognosis = SudokuPrognosis::model()->findByPk($player['id_prognosis']);
		                    $prognosis->playing = 1;
		                    $prognosis->line = $player['line'];
		                    $prognosis->save();
	                    }
                    }
                }
            }
        }

		return $merged;
	}
	private static function sudoku_comparePrognoses($score1,$score2,$player1,$player2) {
		
		$prognosis_null = '{"p1":"0","p2":"0","p3":"0"}';
		$json_p1 = "";
		$json_p2 = "";
		
		if(!empty($player1) && empty($player2)) {
			$json_p1 = $player1['prognosis'];
			$json_p2 = $prognosis_null;
		} elseif(empty($player1) && !empty($player2)) {
			$json_p1 = $prognosis_null;
			$json_p2 = $player2['prognosis'];
		} else {
			$json_p1 = $player1['prognosis'];
			$json_p2 = $player2['prognosis'];
		} 
		
		$win1 = ($score1 > $score2); $win2 = ($score1 < $score2); $tee = ($score1 == $score2);
		
		$prognosis1 = json_decode($json_p1);
		$prognosis2 = json_decode($json_p2);
		
		$score_player1 = 0; $points_player1 = 0;
		$score_player2 = 0; $points_player2 = 0;
		
		//начинаем считать прогнозы, забитые голы зачисляем командам и игрокам (prognosis)
		if($win1) {
			if($prognosis1->p1 > $prognosis2->p1) {$score_player1 = 1; $points_player1 = $prognosis1->p1; $points_player2 = $prognosis2->p1;}
			if($prognosis1->p1 < $prognosis2->p1) {$score_player2 = 1; $points_player1 = $prognosis1->p1; $points_player2 = $prognosis2->p1;}
			if($prognosis1->p1 == $prognosis2->p1) {$points_player1 = $prognosis1->p1; $points_player2 = $prognosis2->p1;}
		} elseif($tee) {
			if($prognosis1->p2 > $prognosis2->p2) {$score_player1 = 1; $points_player1 = $prognosis1->p2; $points_player2 = $prognosis2->p2;}
			if($prognosis1->p2 < $prognosis2->p2) {$score_player2 = 1; $points_player1 = $prognosis1->p2; $points_player2 = $prognosis2->p2;}
			if($prognosis1->p2 == $prognosis2->p2) {$points_player1 = $prognosis1->p2; $points_player2 = $prognosis2->p2;}
		} elseif($win2) {
			if($prognosis1->p3 > $prognosis2->p3) {$score_player1 = 1; $points_player1 = $prognosis1->p3; $points_player2 = $prognosis2->p3;}
			if($prognosis1->p3 < $prognosis2->p3) {$score_player2 = 1; $points_player1 = $prognosis1->p3; $points_player2 = $prognosis2->p3;}
			if($prognosis1->p3 == $prognosis2->p3) {$points_player1 = $prognosis1->p3; $points_player2 = $prognosis2->p3;}
		}
		
		$answer = array(
			'score_player1'=>$score_player1, 'score_player2'=>$score_player2,
			'points_player1'=>$points_player1, 'points_player2'=>$points_player2,
		);
		return $answer;
	}
	
	//обсчет неиграющих игроков
	private static function computeBenchPlayers() {
		$sql = "SELECT
			p.id as id_prognosis,
			p.id_player as id_player,
			p.id_game as id_game,
			g.ready as ready,
			g.score_team1_total,
			g.score_team2_total,
			p.prognosis,
			p.computed
		FROM 
			`sudoku_prognosis` as p
			LEFT JOIN `sudoku_games` as g on g.id = p.id_game
			left join `sudoku_team_players` stp on p.id_player = stp.id
		WHERE
			p.computed = 0 and p.playing = 0 and g.ready = 1 and stp.active = 1";
		
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->queryAll();
		foreach($result as $row) {
			$id_prognosis = $row['id_prognosis'];
			$score1 = $row['score_team1_total'];
			$score2 = $row['score_team2_total'];
			$win1 = ($score1 > $score2); $win2 = ($score1 < $score2); $tee = ($score1 == $score2);
			$prognosis = json_decode($row['prognosis']);
			$points = 0;
			if($win1) $points = $prognosis->p1;
			elseif($tee) $points = $prognosis->p2;
			elseif($win2) $points = $prognosis->p3;
			
			$model = SudokuPrognosis::model()->findByPk($row['id_prognosis']);
			if(empty($model)) continue;
			
			$model->points = $points;
			$model->computed = 1;
			$model->save();
		}
	}	
	public static function computeSudokuPrognosis($criteria = null) {
	    if(empty($criteria)) {
	        $criteria = new CDbCriteria();
	        $criteria->addCondition('computed=0');
        }
		$uncomputed_teams = SudokuToursTeams::model()->findAll($criteria);


		foreach($uncomputed_teams as $sudoku_pair) {

            //никаких обсчетов до дедлайна
		    $tour = SudokuTours::model()->findByPk($sudoku_pair->id_tour);
		    $deadline_players = ($tour) ? strtotime($tour->date) : 0;
		    if($deadline_players > time()) continue;

			$players_team1 = self::sudoku_getTeamPlayers($sudoku_pair->id_sudoku_team1, $sudoku_pair->id_tour);
			$players_team2 = self::sudoku_getTeamPlayers($sudoku_pair->id_sudoku_team2, $sudoku_pair->id_tour);
			$merged = self::sudoku_mergePlayers($players_team1, $players_team2);

			$score_team1 = 0;
			$score_team2 = 0;
			$allReady = true;

			//неявка считается неявкой на первый тайм. нельзя поставить на втр
    		$missingTeam1 = (self::sudoku_countPlayers($players_team1) < 2);
			$missingTeam2 = (self::sudoku_countPlayers($players_team2) < 2);

			echo "Players 1 count: ".self::sudoku_countPlayers($players_team1) . " is missing: ".$missingTeam1 . "\n";
			echo "Players 2 count: ".self::sudoku_countPlayers($players_team2) . " is missing: ".$missingTeam2 . "\n";

			if($missingTeam1 || $missingTeam2) {
			    $score_team1 = (!$missingTeam1) ? 3 : 0;
				$score_team2 = (!$missingTeam2) ? 3 : 0;
				//проставляем и сохраняем нулевые очки для прогнозов
                foreach(array($players_team1, $players_team2) as $players) {
                    foreach($players as $player) {
	                    $pr = SudokuPrognosis::model()->findByPk($player['id_prognosis']);
	                    if(!$pr) continue;
	                    $pr->computed = true;
	                    $pr->balls = 0;
	                    $pr->points = 0;
	                    $pr->save();
                    }
                }
            } else {
                foreach($merged as $row) {
                    if(empty($row)) 			{$allReady=false; continue;}
                    if(!isset($row['game'])) 	{$allReady=false; continue;}
                    $game = $row['game'];
                    if(!$game['ready']) 		{$allReady=false; continue;}

                    $sc1 = $game['score_team1_total'];
                    $sc2 = $game['score_team2_total'];

                    for($i=0; $i < 3; $i++) {
                        //сравниваем игроков каждой команды с прогнозом на эту игру
                        $player1 = (isset($row['players1'][$i]))?$row['players1'][$i]:array();
                        $player2 = (isset($row['players2'][$i]))?$row['players2'][$i]:array();

                        if(empty($player1) && empty($player2)) continue;

                        $compareResults = self::sudoku_comparePrognoses($sc1, $sc2, $player1, $player2);

                        $score_player1	 = $compareResults['score_player1'];
                        $score_player2	 = $compareResults['score_player2'];
                        $points_player1	 = $compareResults['points_player1'];
                        $points_player2	 = $compareResults['points_player2'];

                        $score_team1 += $score_player1;
                        $score_team2 += $score_player2;

                        //зачисляем очки игрокам
                        if(isset($player1['id_prognosis'])) {
                            $_p1 = SudokuPrognosis::model()->findByPk($player1['id_prognosis']);
                            if(!empty($_p1)) {
                                $_p1->computed = true;
                                $_p1->balls = $score_player1;
                                $_p1->points = $points_player1;
                                $_p1->save();
                            }
                        }

                        if(isset($player2['id_prognosis'])) {
                            $_p2 = SudokuPrognosis::model()->findByPk($player2['id_prognosis']);
                            if(!empty($_p2)) {
                                $_p2->computed = true;
                                $_p2->balls = $score_player2;
                                $_p2->points = $points_player2;
                                $_p2->save();
                            }
                        }
                    }
                }
                if(empty($merged)) $allReady = false;
			}

			echo "Score : " . $score_team1 . " : " . $score_team2 . "\n";

			//начисляем очки командам
			$sudoku_pair->computed = $allReady;
			$sudoku_pair->score_team1_total = $score_team1;
			$sudoku_pair->score_team2_total = $score_team2;
			$sudoku_pair->missing_team1 = $missingTeam1;
			$sudoku_pair->missing_team2 = $missingTeam2;
			$sudoku_pair->save();
		}

		//обсчитываем необсчитанных игроков запаса
		self::computeBenchPlayers();
	}

	public static function getSudokuPointsStats($limit = null, $season_id = null) {
	    //сезон судоку:
        $season = self::getSeason($season_id);
		$sid = $season->id;

		//выводим все прогнозы с computed = 1
		$sql = "SELECT 
			pl.id_user,
			u.username,
			pl.name as name_player, 
			st.name as name_team, 
			COUNT(DISTINCT g.id_tour) as tour_count, 
			SUM(pr.points) as points 
		FROM sudoku_prognosis pr 
			LEFT JOIN sudoku_games as g on pr.id_game = g.id 
			LEFT JOIN sudoku_team_players pl on pr.id_player = pl.id
			LEFT JOIN users u on pl.id_user = u.id 
			LEFT JOIN sudoku_teams st on pl.id_team = st.id 
		WHERE 1
		  and pr.computed = 1 
		  and u.username IS NOT NULL
		  and g.id_season = :season
		GROUP BY pl.id_user 
		ORDER BY points DESC";
		if(!empty($limit)) $sql .= "\nLIMIT {$limit}";
		
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam('season', $sid);

		return $command->queryAll();
	}
	
	public static function getSudokuGoalsStats($limit = null, $season_id = null) {
		//сезон судоку:
		$season = self::getSeason($season_id);
		$sid = $season->id;

		//выводим все прогнозы с computed = 1
		$sql = "SELECT 
			pl.id_user,
			u.username, 
			pl.name as name_player, 
			st.name as name_team, 
			COUNT(DISTINCT g.id_tour) as tour_count, 
			SUM(pr.balls) as points 
		FROM sudoku_prognosis pr 
			LEFT JOIN sudoku_games as g on pr.id_game = g.id 
			LEFT JOIN sudoku_team_players pl on pr.id_player = pl.id 
			LEFT JOIN users u on pl.id_user = u.id
			LEFT JOIN sudoku_teams st on pl.id_team = st.id 
		WHERE 1 
		  and pr.computed = 1 
		  AND pr.playing = 1 
		  AND u.username IS NOT NULL
		  and g.id_season = :season
		GROUP BY pl.id_user 
		ORDER BY points DESC";
		if(!empty($limit)) $sql .= "\nLIMIT {$limit}";

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam('season', $sid);

		return $command->queryAll();
	}

	/**
     * Function searches for season. If it's null or not found returns only current season stats.
     */
	public static function getSeason($season_id) {

	    $season = null;
	    if(!$season_id)
	        $season = SudokuSeasons::getCurrentSeason();
        else {
	        $param = is_integer($season_id) ? 'id' : 'alias';
	        $season = SudokuSeasons::model()->find($param.' = :value and archive=1', array('value' => $season_id));
	        if(!$season) $season = SudokuSeasons::getCurrentSeason();
        }

        return $season;
    }

    private static function getValues($array, $values) {
	    return array_intersect_key($array, array_flip($values));
    }
    private static function shuffle($array) {
	    $shuffled = $array;
	    shuffle($shuffled);
	    return $shuffled;
    }

	private function arrange_map_callback($hay_row, $column) {
		return $hay_row[$column];
	}
    private static function arrange($haystack, $column, $arrange_order) {

	    $columns = array_pad(array(), count($haystack), $column);
        $hay_reduced = array_map(array('PrognoseFunctions', 'arrange_map_callback'), $haystack, $columns);

        $arranged = array();
        foreach($arrange_order as $key => $value) {
            $hay_key = array_search($value, $hay_reduced);
            $arranged[$key] = $haystack[$hay_key];
        }

        return $arranged;
    }

    private static function sudoku_countPlayers_callback($var) {
	    return $var['id_player'];
    }
    private static function sudoku_countPlayers($players) {
	    return count(
            array_unique(
                array_map(array('PrognoseFunctions', 'sudoku_countPlayers_callback'), $players)
            )
        );
    }

}


?>;