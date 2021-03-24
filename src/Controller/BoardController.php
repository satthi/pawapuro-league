<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class BoardController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($id = null)
    {
        $this->set('gameId', $id);
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Players');
        $this->loadModel('Teams');
        $this->loadModel('Games');
        if ($id !== 'random') {
        $game = $this->Games->get($id, [
            'contain' => [
                'HomeTeams',
                'VisitorTeams',
                'GameMembers' => [
                    'Players' => [
                        'Teams'
                    ]
                ],
            ]
        ]);
        } else {
        $game = $this->Games->find()
            ->contain([
                'HomeTeams',
                'VisitorTeams',
                'GameMembers' => [
                    'Players' => [
                        'Teams'
                    ]
                ],
            ])
            ->order('random()')
            ->first();
        $id = $game->id;
        }
        
        // playerの整理
        $players = [];
        $scores = [];
        foreach ($game->game_members as $gameMember) {
            if ($gameMember->stamen_flag == false) {
                continue;
            }
            $players[$gameMember->team_id][$gameMember->dajun] = $gameMember;
            $scores[$gameMember->player->id] = $this->stamenPlayerSeiri($gameMember->player, $gameMember->position, $id);
        }
        
        $this->set('game', $game);
        $this->set('players', $players);
        $this->set('scores', $scores);
    }




    
    private function stamenPlayerSeiri($playerInfo, $position, $gameId)
    {
        $this->loadModel('Players');
        $this->loadModel('GameMembers');
        //画像パス
        if (file_exists(ROOT . '/webroot/img/base_player/' . $playerInfo->base_player_id . '/file')) {
            $imgPath = Router::url('/img/base_player/' . $playerInfo->base_player_id . '/file');
        } else {
            $imgPath = Router::url('/img/noimage.jpg');
        }
        
        $displayPlayerInfoAvg = null;
        $displayPlayerInfoHr = null;
        $batterInfo = $this->GameMembers->find('all')
            ->contain(['Players' => ['Teams']])
            // ->where(['GameMembers.game_id' => $gameId])
            ->where(['GameMembers.player_id' => $playerInfo->id])
            ->select($this->GameMembers)
            ->select($this->GameMembers->Players)
            ->select(['dasu_count' => '(SELECT sum(Results.dasu_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id < ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->select(['hit_count' => '(SELECT sum(Results.hit_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id < ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->select(['hr_count' => '(SELECT sum(Results.hr_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id < ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])

            ->select(['rbi_count' => '(SELECT sum(GameResults.point) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id < ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->select(['steal_count' => '(SELECT count(CASE WHEN GameResults.type = 3 AND GameResults.out_num = 0 THEN 1 ELSE null END) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id < ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->first()
        ;
        $avg = '';
        if($batterInfo->dasu_count == 0) {
           $avg = sprintf('%0.3f', round(0, 3));
        } else {
           $avg = sprintf('%0.3f', round($batterInfo->hit_count / ($batterInfo->dasu_count), 3));
        }
        $avg = preg_replace('/^0/', '', $avg);

        $displayPlayerInfoAvg = $avg;
        $displayPlayerInfoHr = (int) $batterInfo->hr_count ;
        $displayPlayerInfoRbi = (int) $batterInfo->rbi_count ;
        $displayPlayerInfoSteal = (int) $batterInfo->steal_count ;
        $displayPlayerInfoDasu= (int) $batterInfo->dasu_count ;
        $displayPlayerInfoHit = (int) $batterInfo->hit_count ;
        $performData = [];
        $pitcherData = [];
        $era = '';
        $gameSum = '';
        $winSum =  '';
        $loseSum = '';
        $saveSum = '';
        $holdSum = '';
        $sansinSum = '';
        $inningSum ='';
        
        
        if ($position == 1) {
            // 試合勝ち負けセーブ
            $this->loadModel('GamePitcherResults');
            $this->loadModel('GameResults');
            $performData = $this->GamePitcherResults->find('all')
                ->select(['game_sum' => 'count(GamePitcherResults.id)'])
                ->select(['win_sum' => 'count(CASE WHEN GamePitcherResults.win = TRUE THEN 1 ELSE NULL END)'])
                ->select(['lose_sum' => 'count(CASE WHEN GamePitcherResults.lose = TRUE THEN 1 ELSE NULL END)'])
                ->select(['save_sum' => 'count(CASE WHEN GamePitcherResults.save = TRUE THEN 1 ELSE NULL END)'])
                ->select(['hold_sum' => 'count(CASE WHEN GamePitcherResults.hold = TRUE THEN 1 ELSE NULL END)'])
                ->contain('Games')
                ->where(['GamePitcherResults.pitcher_id' => $playerInfo->id])
                ->where(['Games.id <' => $gameId])
                ->group('GamePitcherResults.pitcher_id')
                ->first()
            ;
            
            // 防御率
            $pitcherData = $this->GameResults->find('all')
                ->select('GameResults.pitcher_id')
                ->select(['total_inning' => '(SELECT sum(InningGameResults.out_num) FROM game_results AS InningGameResults WHERE InningGameResults.pitcher_id = GameResults.pitcher_id AND InningGameResults.game_id < ' . $gameId . ')'])
                ->select(['total_jiseki' => '(SELECT sum(JisekiGamePithcerResults.jiseki) FROM game_pitcher_results AS JisekiGamePithcerResults WHERE JisekiGamePithcerResults.pitcher_id = GameResults.pitcher_id AND JisekiGamePithcerResults.game_id < ' . $gameId . ')'])
                ->select(['sansin_count' => '(SELECT sum(Results.sansin_flag::integer) FROM game_results AS SanshinGameResults LEFT JOIN results as Results ON SanshinGameResults.result_id = Results.id WHERE SanshinGameResults.pitcher_id = GameResults.pitcher_id AND SanshinGameResults.game_id < ' . $gameId . ')'])
                ->contain('Pitchers')
                ->contain('Results')
                ->group('GameResults.pitcher_id')
                ->group('Pitchers.name')
                // ->where(['GameResults.game_id' => $gameId])
                ->where(['Pitchers.id' => $playerInfo->id])
                ->where(['GameResults.type IN' => [2,3]])
                ->first()
            ;
	        if (!empty($pitcherData)) {
		        if ($pitcherData->total_inning == 0) {
		           $era = '-';
		        } else {
		           $era = sprintf('%0.2f', round($pitcherData->total_jiseki / ($pitcherData->total_inning) * 27, 2));
		        }
		        if (!empty($performData)) {
		        $gameSum = $performData->game_sum;
		        $winSum = $performData->win_sum ;
		        $loseSum = $performData->lose_sum;
		        $saveSum = $performData->save_sum;
		        $holdSum = $performData->hold_sum;
		        } else {
		        $gameSum = 0;
		        $winSum = 0;
		        $loseSum = 0;
		        $saveSum = 0;
		        $holdSum = 0;
		        }
		        $sansinSum = (int) $pitcherData->sansin_count;
		        $inningSum = (int) $pitcherData->total_inning;
				/*
	            $displayPlayerInfo = $era . ' ';
	            $displayPlayerInfo .= $performData->game_sum . '試合';
	            if ($performData->win_sum > 0) {
	                $displayPlayerInfo .= $performData->win_sum . '勝';
	            }
	            if ($performData->lose_sum > 0) {
	                $displayPlayerInfo .= $performData->lose_sum . '敗';
	            }
	            if ($performData->save_sum > 0) {
	                $displayPlayerInfo .= $performData->save_sum . 'S';
	            }
	            */
	        } else {
	           $era = '-';
		        $gameSum = '-';
		        $winSum =  '-';
		        $loseSum = '-';
		        $saveSum = '-';
		        $holdSum = '-';
		        $sansinSum = '-';
		        $inningSum = '-';
	            //$displayPlayerInfo = '0試合';
	        }
	    }

        return [
            'avg' => $displayPlayerInfoAvg,
            'hr' => $displayPlayerInfoHr,
            'rbi' => $displayPlayerInfoRbi,
            'steal' => $displayPlayerInfoSteal,
            'dasu' => $displayPlayerInfoDasu,
            'hit' => $displayPlayerInfoHit,
            'era' => $era,
            'img_path' => $imgPath,
		    'game_sum' =>  $gameSum,
		    'win_sum' => $winSum,
		    'lose_sum' => $loseSum,
		    'save_sum' => $saveSum,
		    'hold_sum' => $holdSum,
		    'sansin_sum' => $sansinSum,
		    'inning_sum' => $inningSum,
        ];
    }




}
