<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 01.08.2022
 * Time: 10:33
 */



class TourService
{


    public function returnTourTable($season_id) {
        // выборка количества дивизионов в сезоне $season_id
        $res = Yii::app()->db->createCommand('select `divisions` from `sudoku_seasons` where `id` = '.$season_id.' limit 1')->queryAll();

//        \CVarDumper::dump($res, 5, true); die;

        $divisions = $res[0]['divisions'];
        $result = array();
        for ($d = 1; $d <= $divisions; $d++)
        { //по каждому дивизиону
            //выводим таблицу только на те туры, у которых все игры ready
            $sql = "
			SELECT
				_teams.id_team,
				_teams.id_tour as idtour,
				SUM(_teams.goals) AS goals,
				SUM(_teams.misses) AS misses,
				SUM(_teams.goals - _teams.misses) AS diff,
				SUM(_teams.points) AS points,
				SUM(_teams.win) AS win,
				SUM(_teams.tee) AS tee,
				SUM(_teams.fail) AS fail,
				SUM(1) as tour_count
			FROM (
				SELECT
					t.id AS id_tour,
					t.tour_number,
					SUM(g.ready) as ready,
					SUM(1) as total
				FROM sudoku_tours t 
				LEFT JOIN sudoku_games g on g.id_tour = t.id
				WHERE t.id_season = :season
				GROUP BY t.id
				ORDER BY t.tour_number
			) as _tours
			LEFT JOIN (
					SELECT 
						stt.id_tour,
						stt.id_sudoku_team1 as id_team,
						IF(stt.score_team1_total > stt.score_team2_total, 1, 0) AS win,
						IF(stt.score_team1_total = stt.score_team2_total and 
						NOT(stt.missing_team1 AND stt.missing_team2), 1, 0) AS tee,
						IF(stt.score_team1_total < stt.score_team2_total, 1, 0) AS fail,
						stt.score_team1_total as goals,
						stt.score_team2_total as misses,
						IF(
							stt.score_team1_total > stt.score_team2_total, 
							2, 
							IF(
								stt.score_team1_total = stt.score_team2_total and NOT(stt.missing_team1 AND stt.missing_team2), 1, 0
							)
						)
						 +
						 (
						 SELECT addpt.points as addpoints
						 FROM addpoints addpt
						 WHERE addpt.id_sudoku_team = stt.id_sudoku_team1
						 AND addpt.id_tour = stt.id_tour
						 LIMIT 1
						 )
						 as points
					FROM sudoku_tours_teams stt
					left join sudoku_tours t on t.id = stt.id_tour
					WHERE stt.division = $d and t.id_season = :season
				UNION
					SELECT 
						stt.id_tour,
						stt.id_sudoku_team2 as id_team,
						IF(stt.score_team1_total < stt.score_team2_total, 1, 0) AS win,
						IF(stt.score_team1_total = stt.score_team2_total and 
						NOT(stt.missing_team1 AND stt.missing_team2), 1, 0) AS tee,
						IF(stt.score_team1_total > stt.score_team2_total, 1, 0) AS fail,
						stt.score_team2_total as goals,
						stt.score_team1_total as misses,
						IF(
							stt.score_team1_total < stt.score_team2_total, 
							2, 
							IF(
								stt.score_team1_total = stt.score_team2_total 
								and NOT(stt.missing_team1 AND stt.missing_team2), 1, 0
							)
						) +
						 (
						 SELECT addpt.points as addpoints
						 FROM addpoints addpt
						 WHERE addpt.id_sudoku_team = stt.id_sudoku_team2
						 AND addpt.id_tour = stt.id_tour
						 LIMIT 1
						 )
						  as points
					FROM sudoku_tours_teams stt
					left join sudoku_tours t on t.id = stt.id_tour
					WHERE stt.division = $d and t.id_season = :season
			) AS _teams ON _teams.id_tour = _tours.id_tour
			WHERE _tours.ready = _tours.total
			GROUP BY _teams.id_team
			ORDER BY points DESC, diff DESC, goals DESC
		";//stt.division = $d
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam('season', $season_id);
            $result[] = $command->queryAll();
//            CVarDumper::dump($result, 5, true); die;
        }
        return $result;
    }




}