<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * Games Controller
 *
 * @property \App\Model\Table\GamesTable $Games
 */
class GamesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($season_id = null)
    {
        $games = $this->Games->find('all')
            ->contain(['HomeTeams', 'VisitorTeams'])
            ->where(['Games.season_id' => $season_id])
           // ->order(['Games.id' => 'ASC'])
        ;
        $checkquery = clone $games;
        $minDate = $checkquery->order(['Games.date' => 'ASC'])->first()->date;
        $maxDate = $checkquery->order(['Games.date' => 'DESC'])->first()->date;
        
        $games = $games->order(['Games.date' => 'ASC', 'Games.id' => 'ASC']);
        $gameLists = [];
        foreach ($games as $game) {
            $gameLists[$game->date->format('Y-m-d')][] = $game;
        }

        $this->set(compact('gameLists','minDate','maxDate','season_id'));
        $this->set('_serialize', ['games']);
    }
    
    public function play($gameID = null, $dh_flag = false)
    {
        $this->loadModel('GameInnings');
        $gameInfo = $this->Games->get($gameID, [
            'contain' => [
                'HomeTeams',
                'VisitorTeams',
                'WinPitchers',
                'LosePitchers',
                'SavePitchers',
            ]
        ]);
        
        // 今のイニングを取得
        // 試合開始前は1回表と取り扱う
        /*
        if ($gameInfo->status == 0) {
            $gameInfo->status = 1;
        }
        */
        $this->set('gameInfo', $gameInfo);
        // スタメンの設定 
        if (!$this->stamenSetting($gameInfo, $dh_flag)) {
            return;
        }
        // そのイニングが最初かどうかを判定
        $inningCount = $this->GameInnings->find('all')
            ->where(['GameInnings.game_id' => $gameID])
            ->where(['GameInnings.inning' => $gameInfo->status])
            ->count()
            ;
        // プレイ登録画面
        if (
            // 9回裏/10回裏/11回裏/12回裏時点で後攻がリードしている場合は終了
            (
                ($gameInfo->status == 18 || $gameInfo->status == 20 || $gameInfo->status == 22 || $gameInfo->status == 24) &&
                $gameInfo->visitor_point < $gameInfo->home_point
            ) ||
            (
                // 10回表～12回表開始時点でどちらかがリードしている場合は終了
                ($gameInfo->status == 19 ||$gameInfo->status == 21 ||$gameInfo->status == 23) && 
                $gameInfo->visitor_point != $gameInfo->home_point &&
                $inningCount == 0
            ) ||
            (
                // 13回の表には突入しないので終了
                ($gameInfo->status == 25)
            )
        ) {
            return $this->afterGame($gameInfo);
            //$gameInfo->status == 99;
        }
        
        if ($gameInfo->status != 99) {
            return $this->playDisplay($gameInfo);
        }
        // 最終結果閲覧
        $this->gameResultDisplay($gameInfo);
        return;
    }
    
    private function stamenSetting($gameInfo, $dh_flag)
    {
        //メンバーがセットされているかチェック
        $this->loadModel('GameMembers');
        $this->loadModel('Players');
        // ビジターから
        $checkTeam = $gameInfo->visitor_team;
        if (!$this->stamenSettingParts($gameInfo, $checkTeam, $dh_flag)) {
            return false;
        }
        // ホームチーム
        $checkTeam = $gameInfo->home_team;
        if (!$this->stamenSettingParts($gameInfo, $checkTeam, $dh_flag)) {
            return false;
        }
        // 両方設定完了
        return true;
    }
    
    private function stamenSettingParts($gameInfo, $checkTeam, $dh_flag)
    {
        $this->loadModel('Games');
        $this->loadModel('Accidents');
        // いなかったらセッティング
        $checkId = $checkTeam->id;
        $gameMemberInfo = $this->GameMembers->find('all')
            ->where(['GameMembers.game_id' => $gameInfo->id])
            ->where(['GameMembers.team_id' => $checkId])
            ->count()
            ;
        if ($gameMemberInfo != 0) {
            return true;
        }
        // 全メンバーを取得
        $players = $this->Players->find('all')
            // ここ10試合の成績も取得
            ->where(['Players.team_id' => $checkId])
            ->where(['Players.trade_flag' => false])
        ;
        // けが人を取得
        $accidents = $this->Accidents->find('all')
            ->contain('Players')
            ->where(['Players.team_id' => $checkId])
            ->where(['Accidents.start_date <=' => $gameInfo->date])
            ->where(['Accidents.end_date >=' => $gameInfo->date])
            ->order(['Accidents.end_date' => 'ASC']);
        // けが人復帰者
        $accidentEnds = $this->Accidents->find('all')
            ->contain('Players')
            ->where(['Players.team_id' => $checkId])
            ->where(['Accidents.end_date <' => $gameInfo->date])
            ->where(['Accidents.end_date >' => $gameInfo->date->subDay(7)])
            ->order(['Accidents.end_date' => 'ASC']);
        
        // 前回のゲームがあればそのスタメンを取得する
        $recentGame = $this->Games->find('all')
            ->where([
                'OR' => [
                    'Games.home_team_id' => $checkId,
                    'Games.visitor_team_id' => $checkId,
                ],
                'Games.status' => 99
            ])
            ->order(['Games.date' =>'DESC'])
            ->order(['Games.id' =>'DESC'])
            ->first()
        ;
        $stamen = [];
        $dh_plus_flag = false;
        if (is_null($recentGame)) {
            // ない場合は適当に
            $dajun = 1;
            foreach($players as $player) {
                // DHじゃないときとP/DH以外は普通
                if ($dh_flag == false || ($dajun != 1 && $dajun != 10)) {
                    $stamen[$player->id] = [
                        'dajun' => $dajun,
                        'position' => $dajun,
                        'player' => $player
                    ];
                } else {
                    if ($dajun == 1) {
                        // 指名打者
                        $stamen[$player->id] = [
                            'dajun' => 1,
                            'position' => 99,
                            'player' => $player
                        ];
                    } else {
                        // P
                        $stamen[$player->id] = [
                            'dajun' => 10,
                            'position' => 1,
                            'player' => $player
                        ];
                    }
                }
                if ($dh_flag == false) {
                    if ($dajun == 9) {
                        break;
                    }
                } else {
                    // 指名打者対策
                    if ($dajun == 10) {
                        break;
                    }
                }
                $dajun++;
            }
        } else {
            //前回のゲームがある場合は前回のゲームのスタメン
            $gameMemberInfos = $this->GameMembers->find('all')
                ->where(['GameMembers.game_id' => $recentGame->id])
                ->where(['GameMembers.team_id' => $checkId])
                ->where(['GameMembers.stamen_flag' => true])
                ->contain('Players')
                ->order(['GameMembers.dajun' => 'ASC'])
                ;

            foreach ($gameMemberInfos as $gameMemberInfo) {
                // DHなし
                if ($dh_flag == false) {
                    // DHあり＞DHなしとしたときの対応
                    // 10番は飛ばす
                    if ($gameMemberInfo->dajun == 10) {
                        continue;
                    }
                    // DHはピッチャーにする
                    if ($gameMemberInfo->position == 99) {
                        $gameMemberInfo->position = 1;
                    }
                } else {
                    // DHあり
                    // DHなし＞DHありとしたときの対応
                    // ピッチャーはDHとする
                    if ($gameMemberInfo->dajun != 10 && $gameMemberInfo->position == 1) {
                        $gameMemberInfo->position = 99;
                        $dh_plus_flag = true;
                    }
                }
                $stamen[$gameMemberInfo->player->id] = [
                    'dajun' => $gameMemberInfo->dajun,
                    'position' => $gameMemberInfo->position,
                    'player' => $gameMemberInfo->player
                ];
            }
        }
        $hikae = [];
        foreach($players as $player) {
            if (!empty($stamen[$player->id])) continue;
            // DHなし＞DHありの場合Pをセットする
            if ($dh_plus_flag == true) {
                // 
                $stamen[$player->id] = [
                    'dajun' => 10,
                    'position' => 1,
                    'player' => $player
                ];
                $dh_plus_flag = false;
                // 控えには追加しない
                continue;
            }

            $hikae[$player->id] = [
                'player' => $player
            ];
        }
        // ここ10試合のPのリスト
        // ここ10試合のゲームID
        $recentGameIds = $this->Games->find('all')
            ->where([
                'OR' => [
                    ['Games.home_team_id' => $checkId],
                    ['Games.visitor_team_id' => $checkId],
                ]
            ])
            ->where(['Games.status' => 99])
            ->order(['Games.date' => 'DESC'])
            ->order(['Games.id' => 'DESC'])
            ->find('list', [ 'keyField' => 'id', 'valueField' => 'id' ])
            ->limit(10)
            ->toArray();
        if (empty($recentGameIds)) {
            $recentGameIds[] = 0;
        }
        $this->loadModel('GameResults');
        $pitcherDatas = $this->GameResults->find('all')
            ->select('GameResults.pitcher_id')
            ->select(['out_num' => 'sum(GameResults.out_num)'])
            ->select(['Pitchers.name_short'])
            ->select(['Pitchers.type_p'])
            ->select(['Pitchers.type_c'])
            ->select(['Pitchers.type_i'])
            ->select(['Pitchers.type_o'])
            ->select(['Games.date'])
            ->contain('Games')
            ->contain('Pitchers')
            ->group('GameResults.pitcher_id')
            ->group('Pitchers.name_short')
            ->group('Pitchers.type_p')
            ->group('Pitchers.type_c')
            ->group('Pitchers.type_i')
            ->group('Pitchers.type_o')
            ->group('GameResults.game_id')
            ->group('Games.date')
            ->where(['GameResults.game_id IN' => $recentGameIds])
            ->where(['Pitchers.team_id' => $checkId])
            ->where(['GameResults.type IN' => [2,3]])
            ->order(['Games.date' => 'DESC'])
            ->order(['GameResults.game_id' => 'DESC'])
            ->order(['(SELECT OrderGameResults.id FROM game_results AS OrderGameResults WHERE OrderGameResults.pitcher_id = GameResults.pitcher_id AND OrderGameResults.game_id = GameResults.game_id LIMIT 1)' => 'ASC'])
        ;
        $this->set('accidents', $accidents);
        $this->set('checkTeam', $checkTeam);
        $this->set('stamen', $stamen);
        $this->set('accidentEnds', $accidentEnds);
        
        $this->set('dh_flag', $dh_flag);
        $this->set('hikae', $hikae);
        $this->set('pitcherDatas', $pitcherDatas);
        $this->set('positionLists', Configure::read('positionLists'));
        $this->set('positionColors', Configure::read('positionColors'));
        $this->render('stamen_setting');
        return false;
    }
    
    private function playDisplay($gameInfo)
    {
        // member取得
        // visitor
        $this->loadModel('Results');
        $this->loadModel('GameInnings');
        $this->loadModel('GameMembers');
        $this->loadModel('GameResults');
        $visitorMemberInfoSets = $this->GameMembers->getNowMemberLists($gameInfo->id, $gameInfo->visitor_team->id);
        $visitorMembers = $visitorMemberInfoSets['memberInfo'];
        $visitorPitcherId = $visitorMemberInfoSets['pitcherId'];
        
        $homeMemberInfoSets = $this->GameMembers->getNowMemberLists($gameInfo->id, $gameInfo->home_team->id);
        $homeMembers = $homeMemberInfoSets['memberInfo'];
        $homePitcherId = $homeMemberInfoSets['pitcherId'];

        // 奇数時は1回表
        // 攻撃は先行 守備は後攻
        if ($gameInfo->status == 0 || $gameInfo->out_num == 3) {
            $attack_team_id = null;
            $defence_team_id = null;
            $pitcherId = null;
        } elseif ($gameInfo->status % 2 == 1) {
            $attack_team_id = $gameInfo->visitor_team->id;
            $defence_team_id = $gameInfo->home_team->id;
            $pitcherId = $homePitcherId;
        } else {
            $attack_team_id = $gameInfo->home_team->id;
            $defence_team_id = $gameInfo->visitor_team->id;
            $pitcherId = $visitorPitcherId;
        }
        // 打者
        $beforeBatter = $this->GameResults->find('all')
            ->where(['GameResults.game_id' => $gameInfo->id])
            ->where(['GameResults.team_id' => $attack_team_id])
            ->where(['GameResults.type' => 2])
            ->order(['GameResults.id' => 'DESC'])
            ->first()
        ;
        if (empty($beforeBatter)) {
            $batter_dajun = 1;
        } else {
            $batter_dajun = $beforeBatter->dajun + 1;
            if ($batter_dajun == 10) {
                $batter_dajun = 1;
            }
        }

        if ($gameInfo->status == 0  || $gameInfo->out_num == 3) {
            $batterId = null;
        } elseif ($gameInfo->status % 2 == 1) {
            $batterId = $visitorMembers[$batter_dajun]->player_id;
        } else {
            $batterId = $homeMembers[$batter_dajun]->player_id;
        }
        
        // スコアボード
        $innings = $this->GameInnings->find('all')
            ->where(['GameInnings.game_id' => $gameInfo->id])
            ;
        //操作しやすいように配列に変更
        $inningInfos = [];
        foreach ($innings as $inning) {
            $inningInfos[$inning->inning] = $inning;
        }
        
        // イニング当初かどうか
        $inningInfo = $this->GameResults->find('all')
        	->where([
        		'GameResults.game_id' => $gameInfo->id,
        		'GameResults.inning' => $gameInfo->status,
	        ])
	        ->contain('Teams')
	        ->contain('Batters')
	        ->order(['GameResults.id' => 'DESC'])
	        ->all();
	    // 選手の交代チェック
	    
	    $firstInningInfo = $inningInfo->first();
	    $inningLastType = null;
	    if (!$inningInfo->isEmpty()) {
	        $inningLastType = $firstInningInfo->type;
	    }
	    
	    $targetChangeTeam = '';
	    $changeMembers = [];
	    
	    if ($inningLastType == 1) {
	        // 対象チーム
	        $targetChangeTeam = $firstInningInfo->team;
	        foreach ($inningInfo as $inningInfoParts) {
	            if ($inningInfoParts->type != 1 || $inningInfoParts->team_id != $firstInningInfo->team_id) {
	                break;
	            }
	            $changeMembers[] = $inningInfoParts;
	        }
	    }
	    //紹介順は古い順がいい
	    $changeMembers = array_reverse($changeMembers);
	    
	    // 結果表示用
	    $results = $this->GameResults->find('all')
	        ->contain('Results')
	        ->where(['GameResults.game_id' => $gameInfo->id])
	        ->where(['GameResults.type' => 2])
	        ->order(['GameResults.id' => 'ASC'])
	        ;
	    $resultsSets = [];
	    foreach ($results as $result) {
	    	$resultsSets[$result->target_player_id][] = $result;
	    
	    }
	    
        
        $this->set('positionLists', Configure::read('positionLists'));
        $this->set('positionColors', Configure::read('positionColors'));
        $this->set('resultSet', $this->Results->displaySet());
        $this->set('visitorMembers', $visitorMembers);
        $this->set('homeMembers', $homeMembers);
        $this->set('gameInfo', $gameInfo);
        $this->set('attack_team_id', $attack_team_id);
        $this->set('defence_team_id', $defence_team_id);
        $this->set('batter_dajun', $batter_dajun);
        $this->set('pitcherId', $pitcherId);
        $this->set('batterId', $batterId);
        $this->set('targetChangeTeam', $targetChangeTeam);
        $this->set('changeMembers', $changeMembers);
        $this->set('inningInfos', $inningInfos);
        $this->set('inningLastType', $inningLastType);
        $this->set('resultsSets', $resultsSets);
        $this->render('play_display');
    }
    
    public function memberChange()
    {
        $this->loadModel('GameMembers');
        $this->loadModel('GameResults');
        $datas = $this->request->data;
        // game_membersとgame_resultsにそれぞれ登録
        foreach ($datas as $data) {
            $gameMember = $this->GameMembers->newEntity();
            $gameMember = $this->GameMembers->patchEntity($gameMember, $data);
            $this->GameMembers->save($gameMember);
            $gameResult = $this->GameResults->newEntity();
            $gameResultData = [];
            $gameResultData = [
            	'game_id' => $data['game_id'],
            	'team_id' => $data['team_id'],
            	'target_player_id' => $data['player_id'],
            	'type' => 1,
            	'dajun' => $data['dajun'],
            	'position' => $data['position'],
            	'game_id' => $data['game_id'],
            	'inning' => $data['inning'],
            ];
            $gameResult = $this->GameResults->patchEntity($gameResult, $gameResultData);
            $this->GameResults->save($gameResult);
        }
        exit;
    }
    
    public function nextInning()
    {
        $this->loadModel('Results');
        $this->loadModel('Games');
        $this->loadModel('GameResults');
        $this->loadModel('GameInnings');
        $data = $this->request->data;

        $this->Games->gameNextInning($data['game_id']);
        
        exit;
    }
    
    public function resultSave()
    {
        $this->loadModel('Results');
        $this->loadModel('Games');
        $this->loadModel('GameResults');
        $this->loadModel('GameInnings');
        $data = $this->request->data;
        // game_resultsに登録
        $gameResult = $this->GameResults->newEntity();
        $gameResultData = [];
        $gameResultData = [
            'game_id' => $data['game_id'],
            'team_id' => $data['team_id'],
            'target_player_id' => $data['player_id'],
            'type' => 2,
            'dajun' => $data['dajun'],
            'pitcher_id' => $data['pitcher_id'],
            'game_id' => $data['game_id'],
            'inning' => $data['inning'],
            'result_id' => $data['result'],
            'out_num' => $data['out_num'],
            'point' => $data['point'],
        ];
        $gameResult = $this->GameResults->patchEntity($gameResult, $gameResultData);
        $this->GameResults->save($gameResult);
        
        $result = $this->Results->get($data['result']);
        
        $this->Games->gameInfoUpdate(
            $data['game_id'],
            $data['point'],
            $data['out_num'],
            $data['inning'],
            $result->hit_flag
        );
        
        exit;
    }
    
    
    public function pointOnlySave()
    {
        $this->loadModel('Games');
        $this->loadModel('GameResults');
        $this->loadModel('GameInnings');
        $data = $this->request->data;
        // game_resultsに登録
        $gameResult = $this->GameResults->newEntity();
        $gameResultData = [];
        $gameResultData = [
            'game_id' => $data['game_id'],
            'team_id' => $data['team_id'],
            'type' => 5,
            'inning' => $data['inning'],
            'point' => $data['point'],
        ];
        $gameResult = $this->GameResults->patchEntity($gameResult, $gameResultData);
        $this->GameResults->save($gameResult);
        
        $this->Games->gameInfoUpdate(
            $data['game_id'],
            $data['point'],
            0,
            $data['inning'],
            false
        );
        
        exit;
    }
    
    
    public function back()
    {
        $this->loadModel('Games');
        $this->loadModel('GameResults');
        $this->loadModel('GameMembers');
        $this->loadModel('GameInnings');
        $game_id = $this->request->data['game_id'];
        $game = $this->Games->get($game_id);
        // 直近の情報取得
        $recentGameResults = $this->GameResults->find('all')
            ->contain('Results')
            ->where(['GameResults.game_id' => $game_id])
            ->order(['GameResults.id' => 'DESC'])
            // 20個以上一気に巻き戻るようなことはないので
            ->limit(40)
            // ここで確定させちゃう
            ->all()
        ;
        // なんもない時はないはずだけど。。。
        if (empty($recentGameResults)) {
            exit;
        }
        $firstInfo = $recentGameResults->first();
        $inningInfo = $this->GameInnings->find('all')
            ->where(['GameInnings.game_id' => $game_id])
            ->where(['GameInnings.inning' => $firstInfo->inning])
            ->first()
            ;
        // 1(選手交代など)の時はtype1が続く限り巻き戻す
        if ($firstInfo->type == 1) {
            foreach ($recentGameResults as $recentGameResult) {
                if ($recentGameResult->type != 1) {
                    break;
                }
                // GameMembersも同時に巻き戻しする
                $targetGameMember = $this->GameMembers->find('all')
                    ->where(['GameMembers.game_id' => $game_id])
                    ->where(['GameMembers.team_id' => $recentGameResult->team_id])
                    ->where(['GameMembers.dajun' => $recentGameResult->dajun])
                    ->where(['GameMembers.position' => $recentGameResult->position])
                    ->where(['GameMembers.player_id' => $recentGameResult->target_player_id])
                    ->first()
                    ;
                // いないことはないはず
                if (empty($targetGameMember)) {
                    exit;
                }
                $this->GameMembers->delete($targetGameMember);
                $this->GameResults->delete($recentGameResult);
            }
           $this->Flash->success('選手交代を取り消し');
        } else if ($firstInfo->type == 2) {
            // gameの調整
            $game->out_num = $game->out_num - $firstInfo->out_num;
            if ($game->out_num < 0) {
                $game->out_num = $game->out_num + 3;
                if ($game->status != 99) {
                    $game->status -= 1;
                } else {
                    $game->status = $inningInfo->inning;
                }
            }
            if ($game->home_team_id == $firstInfo->team_id) {
                $game->home_point -= $firstInfo->point;
            } else {
                $game->visitor_point -= $firstInfo->point;
            }
            $this->Games->save($game);
            //イニングの調整
            if ($firstInfo->result->hit_flag == true) {
                $inningInfo->hit -= 1;
            }
            $inningInfo->point -= $firstInfo->point;
            $this->GameInnings->save($inningInfo);
           $this->GameResults->delete($firstInfo);
           $this->Flash->success('打撃結果を取り消し');
        } else if ($firstInfo->type == 3) {
            // gameの調整
            $game->out_num = $game->out_num - $firstInfo->out_num;
            if ($game->out_num < 0) {
                $game->out_num = $game->out_num + 3;
                if ($game->status != 99) {
                    $game->status -= 1;
                } else {
                    $game->status = $inningInfo->inning;
                }
            }
            $this->Games->save($game);
           $this->GameResults->delete($firstInfo);
           $this->Flash->success('盗塁を取り消し');
        } else if ($firstInfo->type == 5) {
            // gameの調整
            $game->out_num = $game->out_num - $firstInfo->out_num;
            if ($game->home_team_id == $firstInfo->team_id) {
                $game->home_point -= $firstInfo->point;
            } else {
                $game->visitor_point -= $firstInfo->point;
            }
            $this->Games->save($game);
            //イニングの調整
            $inningInfo->point -= $firstInfo->point;
            $this->GameInnings->save($inningInfo);
           $this->GameResults->delete($firstInfo);
           $this->Flash->success('得点のみを取り消し');
        }
        
        exit;
    }
    
    
    public function afterGameSave($gameId = null)
    {
        $this->loadModel('Accidents');
        $this->loadModel('Teams');
        $this->loadModel('Games');
        $this->loadModel('Players');
        $this->loadModel('GamePitcherResults');
        $this->loadModel('GameResults');
        
        $datas = $this->request->data;
        // ゲーム情報の取得
        $gameInfo = $this->Games->get($gameId, [
            'contain' => [
                'HomeTeams',
                'VisitorTeams',
            ]
        ]);


        // けが人の判定
        $this->Accidents->accidentCheck($gameId);

        // 投手情報の保存
        $win_id = null;
        $lose_id = null;
        $save_id = null;
        foreach ($datas as $player_id => $data) {
            $inningInfo = $this->GameResults->find('all')
            	->select(['inning_sum' => 'sum(GameResults.out_num)'])
            	->where(['GameResults.game_id' => $gameId])
            	->where(['GameResults.pitcher_id' => $player_id])
            	->group('GameResults.pitcher_id')
            	->first()
            	;
            $gamePitcherResultInfo = $this->GamePitcherResults->newEntity();
            $gamePitcherResultInfo->game_id = $gameId;
            $gamePitcherResultInfo->team_id = $data['team_id'];
            $gamePitcherResultInfo->pitcher_id = $data['player_id'];
            $gamePitcherResultInfo->jiseki = $data['jiseki'];
            $gamePitcherResultInfo->inning = $inningInfo->inning_sum;
            $gamePitcherResultInfo->win = ($data['win'] == 'true');
            if ($data['win'] == 'true') {
                $win_id = $data['player_id'];
            }
            $gamePitcherResultInfo->lose = ($data['lose'] == 'true');
            if ($data['lose'] == 'true') {
                $lose_id = $data['player_id'];
            }
            $gamePitcherResultInfo->save = ($data['save'] == 'true');
            if ($data['save'] == 'true') {
                $save_id = $data['player_id'];
            }
            $gamePitcherResultInfo->hold = ($data['hp'] == 'true');
            $this->GamePitcherResults->save($gamePitcherResultInfo);
            
        }
        
        // ゲーム情報の後処理
        $gameInfo->status = 99;
        $gameInfo->win_pitcher_id = $win_id;
        $gameInfo->lose_pitcher_id = $lose_id;
        $gameInfo->save_pitcher_id = $save_id;
        
        $this->Games->save($gameInfo);

        // 個人成績の後処理
        // 毎回全データ集計し直そうかｗこれくらいならｗ
        $this->Players->batterShukei($gameInfo->season_id);
        $this->Players->pitcherShukei($gameInfo->season_id);
        $this->Teams->teamShukei($gameInfo->season_id);
        
        exit;
    }
    
    private function afterGame($gameInfo) {
        // member取得
        // visitor
        $this->loadModel('GameInnings');
        $this->loadModel('GameMembers');
        $this->loadModel('GameResults');
        $visitorMemberInfos = $this->GameMembers->find('all')
            ->contain(['Players' => ['Teams']])
            ->where(['GameMembers.game_id' => $gameInfo->id])
            ->where(['GameMembers.team_id' => $gameInfo->visitor_team->id])
            ->order(['GameMembers.id' => 'ASC'])
        ;
        $visitorMembers = [];
        $visitorPitchers = [];
        foreach ($visitorMemberInfos as $visitorMemberInfo) {
            $visitorMembers[$visitorMemberInfo->dajun] = $visitorMemberInfo;
            if ($visitorMemberInfo->position == 1) {
                $visitorPitchers[$visitorMemberInfo->player->id] = $visitorMemberInfo->player;
            }
        }
        
        $homeMemberInfos = $this->GameMembers->find('all')
            ->contain(['Players' => ['Teams']])
            ->where(['GameMembers.game_id' => $gameInfo->id])
            ->where(['GameMembers.team_id' => $gameInfo->home_team->id])
            ->order(['GameMembers.id' => 'ASC'])
        ;
        //home
        $homeMembers = [];
        $visitorhomePitchers = [];
        foreach ($homeMemberInfos as $homeMemberInfo) {
            $homeMembers[$homeMemberInfo->dajun] = $homeMemberInfo;
            if ($homeMemberInfo->position == 1) {
                $homePitchers[$homeMemberInfo->player->id] = $homeMemberInfo->player;
            }
        }
        
        // スコアボード
        $innings = $this->GameInnings->find('all')
            ->where(['GameInnings.game_id' => $gameInfo->id])
            ;
        //操作しやすいように配列に変更
        $inningInfos = [];
        foreach ($innings as $inning) {
            $inningInfos[$inning->inning] = $inning;
        }
        
        $this->set('positionLists', Configure::read('positionLists'));
        $this->set('positionColors', Configure::read('positionColors'));
        $this->set('visitorMembers', $visitorMembers);
        $this->set('homeMembers', $homeMembers);
        $this->set('gameInfo', $gameInfo);
        $this->set('inningInfos', $inningInfos);
        $this->set('visitorPitchers', $visitorPitchers);
        $this->set('homePitchers', $homePitchers);
        
        $this->render('after_game');
        
        return;
    }
    
    private function gameResultDisplay($gameInfo) {
        // member取得
        // visitor
        $this->loadModel('GameInnings');
        $this->loadModel('GameMembers');
        $this->loadModel('GameResults');
        $this->loadModel('Accidents');
        // けが人発生
        $accidents = $this->Accidents->find()
            ->contain('Players')
            ->where(['Accidents.start_date' => $gameInfo->date])
            ->where(['Players.team_id IN' => [$gameInfo->visitor_team->id, $gameInfo->home_team->id]]);
        
        $visitorMemberInfos = $this->GameMembers->find('all')
            ->contain(['Players' => ['Teams']])
            ->where(['GameMembers.game_id' => $gameInfo->id])
            ->where(['GameMembers.team_id' => $gameInfo->visitor_team->id])
            ->select($this->GameMembers)
            ->select($this->GameMembers->Players)
            ->select(['dasu_count' => '(SELECT sum(Results.dasu_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameInfo->id . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->select(['hit_count' => '(SELECT sum(Results.hit_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameInfo->id . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->select(['hr_count' => '(SELECT sum(Results.hr_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameInfo->id . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->order(['GameMembers.id' => 'ASC'])
        ;
        $visitorMembers = [];
        $visitorPitchers = [];
        foreach ($visitorMemberInfos as $visitorMemberInfo) {
            $visitorMembers[$visitorMemberInfo->dajun][] = $visitorMemberInfo;
            if ($visitorMemberInfo->position == 1) {
                $visitorPitchers[$visitorMemberInfo->player->id] = $visitorMemberInfo->player->name;
            }
        }
        
        $homeMemberInfos = $this->GameMembers->find('all')
            ->contain(['Players' => ['Teams']])
            ->where(['GameMembers.game_id' => $gameInfo->id])
            ->where(['GameMembers.team_id' => $gameInfo->home_team->id])
            ->select($this->GameMembers)
            ->select($this->GameMembers->Players)
            ->select(['dasu_count' => '(SELECT sum(Results.dasu_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameInfo->id . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->select(['hit_count' => '(SELECT sum(Results.hit_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameInfo->id . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->select(['hr_count' => '(SELECT sum(Results.hr_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameInfo->id . ' AND GameResults.target_player_id = GameMembers.player_id)'])
            ->order(['GameMembers.id' => 'ASC'])
        ;
        //home
        $homeMembers = [];
        $visitorhomePitchers = [];
        foreach ($homeMemberInfos as $homeMemberInfo) {
            $homeMembers[$homeMemberInfo->dajun][] = $homeMemberInfo;
            if ($homeMemberInfo->position == 1) {
                $homePitchers[$homeMemberInfo->player->id] = $homeMemberInfo->player->name;
            }
        }
        
        // スコアボード
        $innings = $this->GameInnings->find('all')
            ->where(['GameInnings.game_id' => $gameInfo->id])
            ;
        //操作しやすいように配列に変更
        $inningInfos = [];
        foreach ($innings as $inning) {
            $inningInfos[$inning->inning] = $inning;
        }
        
        // 結果
        $homeGameResultInfos = $this->GameResults->find('all')
            ->contain('Results')
            ->where(['GameResults.game_id' => $gameInfo->id])
            ->where(['GameResults.team_id' => $gameInfo->home_team->id])
            ->where(['GameResults.type' => 2])
            ->order(['GameResults.id' => 'ASC'])
        ;
        // 配列で整理
        $homeGameResultLists = [];
        foreach ($homeGameResultInfos as $homeGameResultInfo) {
            $homeGameResultLists[$homeGameResultInfo->dajun][] = $homeGameResultInfo;
        }
        // 結果
        $visitorGameResultInfos = $this->GameResults->find('all')
            ->contain('Results')
            ->where(['GameResults.game_id' => $gameInfo->id])
            ->where(['GameResults.team_id' => $gameInfo->visitor_team->id])
            ->where(['GameResults.type' => 2])
            ->order(['GameResults.id' => 'ASC'])
        ;
        // 配列で整理
        $visitorGameResultLists = [];
        foreach ($visitorGameResultInfos as $visitorGameResultInfo) {
            $visitorGameResultLists[$visitorGameResultInfo->dajun][] = $visitorGameResultInfo;
        }
        
        
        // 投手結果(ホーム)
        $homePitcherDatas = $this->GameResults->find('all')
            ->select('GameResults.pitcher_id')
            ->select(['out_num' => 'sum(GameResults.out_num)'])
            ->select(['sansin_count' => 'sum(Results.sansin_flag::integer)'])
            ->select(['yontama_count' => 'sum(Results.walk_flag::integer)'])
            ->select(['hit_count' => 'sum(Results.hit_flag::integer)'])
            ->select(['hr_count' => 'sum(Results.hr_flag::integer)'])
            ->select(['Pitchers.name'])
            ->select(['total_inning' => '(SELECT sum(InningGameResults.out_num) FROM game_results AS InningGameResults WHERE InningGameResults.pitcher_id = GameResults.pitcher_id AND InningGameResults.game_id <= ' . $gameInfo->id . ')'])
            ->select(['total_jiseki' => '(SELECT sum(JisekiGamePithcerResults.jiseki) FROM game_pitcher_results AS JisekiGamePithcerResults WHERE JisekiGamePithcerResults.pitcher_id = GameResults.pitcher_id AND JisekiGamePithcerResults.game_id <= ' . $gameInfo->id . ')'])
            ->contain('Pitchers')
            ->contain('Results')
            ->group('GameResults.pitcher_id')
            ->group('Pitchers.name')
            ->where(['GameResults.game_id' => $gameInfo->id])
            ->where(['Pitchers.team_id' => $gameInfo->home_team->id])
            ->where(['GameResults.type IN' => [2,3]])
            ->order(['(SELECT OrderGameResults.id FROM game_results AS OrderGameResults WHERE OrderGameResults.pitcher_id = GameResults.pitcher_id AND OrderGameResults.game_id = ' . $gameInfo->id . ' LIMIT 1)' => 'ASC'])
        ;
        
        // 投手結果(ビジター)
        $visitorPitcherDatas = $this->GameResults->find('all')
            ->select('GameResults.pitcher_id')
            ->select(['out_num' => 'sum(GameResults.out_num)'])
            ->select(['sansin_count' => 'sum(Results.sansin_flag::integer)'])
            ->select(['yontama_count' => 'sum(Results.walk_flag::integer)'])
            ->select(['hit_count' => 'sum(Results.hit_flag::integer)'])
            ->select(['hr_count' => 'sum(Results.hr_flag::integer)'])
            ->select(['Pitchers.name'])
              ->select(['total_inning' => '(SELECT sum(InningGameResults.out_num) FROM game_results AS InningGameResults WHERE InningGameResults.pitcher_id = GameResults.pitcher_id AND InningGameResults.game_id <= ' . $gameInfo->id . ')'])
            ->select(['total_jiseki' => '(SELECT sum(JisekiGamePithcerResults.jiseki) FROM game_pitcher_results AS JisekiGamePithcerResults WHERE JisekiGamePithcerResults.pitcher_id = GameResults.pitcher_id AND JisekiGamePithcerResults.game_id <= ' . $gameInfo->id . ')'])
            ->contain('Pitchers')
            ->contain('Results')
            ->group('Pitchers.name')
            ->group('GameResults.pitcher_id')
            ->where(['GameResults.game_id' => $gameInfo->id])
            ->where(['Pitchers.team_id' => $gameInfo->visitor_team->id])
            ->where(['GameResults.type IN' => [2,3]])
            ->order(['(SELECT OrderGameResults.id FROM game_results AS OrderGameResults WHERE OrderGameResults.pitcher_id = GameResults.pitcher_id AND OrderGameResults.game_id = ' . $gameInfo->id . ' LIMIT 1)' => 'ASC'])
        ;
        //自責点・勝敗の取得
        $this->loadModel('GamePitcherResults');
        $gamePitcherResults = $this->GamePitcherResults->find('all')
            ->where(['GamePitcherResults.game_id' => $gameInfo->id])
            ;
        //配列化
        $gamePitcherResultLists = [];
        foreach ($gamePitcherResults as $gamePitcherResult) {
            $gamePitcherResultLists[$gamePitcherResult->pitcher_id] = $gamePitcherResult;
        }
        
        // ホームラン
        $homeruns = $this->GameResults->find('all')
            ->select(['homerun_count' => '(SELECT sum(HomerunResults.hr_flag::integer) FROM game_results as HomerunGameResults LEFT JOIN results AS HomerunResults ON HomerunGameResults.result_id = HomerunResults.id WHERE HomerunGameResults.target_player_id = GameResults.target_player_id AND HomerunGameResults.id <= GameResults.id)'])
            ->select('Batters.name')
            ->select('Pitchers.name')
            ->contain('Results')
            ->contain('Batters')
            ->contain('Pitchers')
            ->where(['GameResults.game_id' => $gameInfo->id])
            ->where(['GameResults.type' => 2])
            ->where(['Results.hr_flag' => true])

            ->order(['GameResults.id' => 'ASC'])
            ;
        // 前後の試合 (ホームチーム)
        $beforeHomeTeamGame = $this->Games->find('all')
            ->where([0 => ['OR' => ['Games.visitor_team_id' => $gameInfo->home_team->id,'Games.home_team_id' => $gameInfo->home_team->id]]])
            ->where([1 => ['OR' => 
                ['Games.date <' => $gameInfo->date,
                [
                    'Games.date' => $gameInfo->date,
                    'Games.id <' => $gameInfo->id
                ]],
            ]])
            ->where(['Games.season_id' => $gameInfo->season_id])
            ->where(['Games.status' => 99])
            ->order(['Games.date' => 'DESC'])
            ->order(['Games.id' => 'DESC'])
            ->first()
        ;
        $nextHomeTeamGame = $this->Games->find('all')
            ->where([0 => ['OR' => ['Games.visitor_team_id' => $gameInfo->home_team->id,'Games.home_team_id' => $gameInfo->home_team->id]]])
            ->where([1 => ['OR' => 
                ['Games.date >' => $gameInfo->date,
                [
                    'Games.date' => $gameInfo->date,
                    'Games.id >' => $gameInfo->id
                ]],
            ]])
            ->where(['Games.season_id' => $gameInfo->season_id])
            ->where(['Games.status' => 99])
            ->order(['Games.date' => 'ASC'])
            ->order(['Games.id' => 'ASC'])
            ->first()
        ;
        // 前後の試合 (ビジターチーム)
        $beforeVisitorTeamGame = $this->Games->find('all')
            ->where([0 => ['OR' => ['Games.visitor_team_id' => $gameInfo->visitor_team->id,'Games.home_team_id' => $gameInfo->visitor_team->id]]])
            ->where([1 => ['OR' => 
                ['Games.date <' => $gameInfo->date,
                [
                    'Games.date' => $gameInfo->date,
                    'Games.id <' => $gameInfo->id
                ]],
            ]])
            ->where(['Games.season_id' => $gameInfo->season_id])
            ->where(['Games.status' => 99])
            ->order(['Games.date' => 'DESC'])
            ->order(['Games.id' => 'DESC'])
            ->first()
        ;
        $nextVisitorTeamGame = $this->Games->find('all')
            ->where([0 => ['OR' => ['Games.visitor_team_id' => $gameInfo->visitor_team->id,'Games.home_team_id' => $gameInfo->visitor_team->id]]])
            ->where([1 => ['OR' => 
                ['Games.date >' => $gameInfo->date,
                [
                    'Games.date' => $gameInfo->date,
                    'Games.id >' => $gameInfo->id
                ]],
            ]])
            ->where(['Games.season_id' => $gameInfo->season_id])
            ->where(['Games.status' => 99])
            ->order(['Games.date' => 'ASC'])
            ->order(['Games.id' => 'ASC'])
            ->first()
        ;
        // 前後の試合 (全体)
        $beforeGame = $this->Games->find('all')
            ->where([1 => ['OR' => 
                ['Games.date <' => $gameInfo->date,
                [
                    'Games.date' => $gameInfo->date,
                    'Games.id <' => $gameInfo->id
                ]],
            ]])
            ->where(['Games.season_id' => $gameInfo->season_id])
            ->where(['Games.status' => 99])
            ->order(['Games.date' => 'DESC'])
            ->order(['Games.id' => 'DESC'])
            ->first()
        ;
        $nextGame = $this->Games->find('all')
            ->where(['OR' => 
                [
                    'Games.date >' => $gameInfo->date,
                    [
                        'Games.date' => $gameInfo->date,
                        'Games.id >' => $gameInfo->id
                    ],
                ]
            ])
            ->where(['Games.season_id' => $gameInfo->season_id])
            ->where(['Games.status' => 99])
            ->order(['Games.date' => 'ASC'])
            ->order(['Games.id' => 'ASC'])
            ->first()
        ;
        $this->set('accidents', $accidents);
        $this->set('positionLists', Configure::read('positionLists'));
        $this->set('positionColors', Configure::read('positionColors'));
        $this->set('resultSet', Configure::read('resultSet'));
        $this->set('visitorMembers', $visitorMembers);
        $this->set('homeMembers', $homeMembers);
        $this->set('gameInfo', $gameInfo);
        $this->set('inningInfos', $inningInfos);
        $this->set('visitorGameResultLists', $visitorGameResultLists);
        $this->set('homeGameResultLists', $homeGameResultLists);
        $this->set('homePitcherDatas', $homePitcherDatas);
        $this->set('visitorPitcherDatas', $visitorPitcherDatas);
        $this->set('gamePitcherResultLists', $gamePitcherResultLists);
        $this->set('homeruns', $homeruns);
        $this->set('beforeHomeTeamGame', $beforeHomeTeamGame);
        $this->set('nextHomeTeamGame', $nextHomeTeamGame);
        $this->set('beforeVisitorTeamGame', $beforeVisitorTeamGame);
        $this->set('nextVisitorTeamGame', $nextVisitorTeamGame);
        $this->set('beforeGame', $beforeGame);
        $this->set('nextGame', $nextGame);
        
        $this->render('after_game_result');
        
        return;
    }
    
    public function pinchHitter()
    {
        $game_id = $this->request->query['game_id'];
        $team_id = $this->request->query['team_id'];
        $dajun = $this->request->query['dajun'];
        $this->loadModel('Games');
        $this->loadModel('Teams');
        $this->loadModel('Players');
        $this->loadModel('GameMembers');
        $this->loadModel('GameResults');
        $game = $this->Games->get($game_id);
        if ($this->request->is('post') && !empty($this->request->data['change_player_id'])) {
            // pinchHitter登録
            if ($this->GameMembers->memberUpdate($game_id, $team_id, $dajun, $this->request->data['change_player_id'], 10)) {
                return $this->redirect(['action' => 'close']);
            }
        }
        $team = $this->Teams->get($team_id);
        // 今のバッター
        $nowBatter = $this->GameMembers->find('all')
            ->where(['GameMembers.dajun' => $dajun])
            ->where(['GameMembers.team_id' => $team_id])
            ->where(['GameMembers.game_id' => $game_id])
            ->contain('Players')
            ->order(['GameMembers.id' => 'DESC'])
            ->first()
        ;
        
        // 交代候補選手
        $pinchHitterLists = $this->Players->changePlayerLists($game_id, $team_id);
        $this->set('nowBatter', $nowBatter);
        $this->set('pinchHitterLists', $pinchHitterLists);
        
    }
    
    public function pinchRunner()
    {
        $game_id = $this->request->query['game_id'];
        $team_id = $this->request->query['team_id'];
        $this->loadModel('Games');
        $this->loadModel('Teams');
        $this->loadModel('Players');
        $this->loadModel('GameMembers');
        $this->loadModel('GameResults');
        $game = $this->Games->get($game_id);
        if ($this->request->is('post') && !empty($this->request->data['change_dajun']) && !empty($this->request->data['change_player_id'])) {
            // pinchRunner登録
            if ($this->GameMembers->memberUpdate($game_id, $team_id, $this->request->data['change_dajun'], $this->request->data['change_player_id'], 11)) {
                return $this->redirect(['action' => 'close']);
            }
        }
        $team = $this->Teams->get($team_id);
        // 今の打順全部
        $memberInfos = $this->GameMembers->find('all')
            ->contain(['Players'])
            ->where(['GameMembers.game_id' => $game_id])
            ->where(['GameMembers.team_id' => $team_id])
            ->order(['GameMembers.id' => 'ASC'])
        ;
        //home
        $nowMembers = [];
        foreach ($memberInfos as $memberInfo) {
            $nowMembers[$memberInfo->dajun] = $memberInfo;
        }
        ksort($nowMembers);
        
        // 交代候補選手
        $pinchHitterLists = $this->Players->changePlayerLists($game_id, $team_id);
        $this->set('nowMembers', $nowMembers);
        $this->set('pinchHitterLists', $pinchHitterLists);
        
    }
    
    public function positionChange()
    {
        $game_id = $this->request->query['game_id'];
        $team_id = $this->request->query['team_id'];
        $this->loadModel('Games');
        $this->loadModel('Teams');
        $this->loadModel('Players');
        $this->loadModel('GameMembers');
        $this->loadModel('GameResults');
        $game = $this->Games->get($game_id);
        // 今の打順全部
        $memberInfos = $this->GameMembers->find('all')
            ->contain(['Players'])
            ->where(['GameMembers.game_id' => $game_id])
            ->where(['GameMembers.team_id' => $team_id])
            ->order(['GameMembers.id' => 'ASC'])
        ;
        //home
        $nowMembers = [];
        foreach ($memberInfos as $memberInfo) {
            $nowMembers[$memberInfo->dajun]['member_info'] = $memberInfo;
            // positionだけは代打/代走は更新リストに入れない
            if ($memberInfo->position != 10 && $memberInfo->position != 11) {
                $nowMembers[$memberInfo->dajun]['position'] = $memberInfo->position;
            }
        }
        $dh_flag = false;
        foreach ($nowMembers as $nowMember) {
            if ($nowMember['position'] == 99) {
                $dh_flag = true;
            }
        }
        ksort($nowMembers);
        if ($this->request->is('post')) {
            // 変更しない人をリストから外していく
            $changeLists = $this->request->data;
            foreach ($changeLists['Players'] as $changeListKey => $changeList) {
                if (
                    $changeList['position'] == $nowMembers[$changeListKey]['member_info']->position &&
                    $changeList['player_id'] == $nowMembers[$changeListKey]['member_info']->player_id
                ) {
                    unset($changeLists['Players'][$changeListKey]);
                }
            }
            foreach ($changeLists['Players'] as $changeListKey => $changeList) {
                if ($this->GameMembers->memberUpdate($game_id, $team_id, $changeListKey, $changeList['player_id'], $changeList['position'])) {
                    // なんか判定するかも？
                }
            }
            return $this->redirect(['action' => 'close']);
        }
        $team = $this->Teams->get($team_id);
        
        // 交代候補選手
        $pinchHitterLists = $this->Players->changePlayerLists($game_id, $team_id);
        $this->set('nowMembers', $nowMembers);
        $this->set('pinchHitterLists', $pinchHitterLists);
        $this->set('dh_flag', $dh_flag);
        $this->set('positionLists', Configure::read('positionLists'));
        $this->set('positionColors', Configure::read('positionColors'));
    }
    
    public function stealCheck()
    {
        $game_id = $this->request->query['game_id'];
        $team_id = $this->request->query['team_id'];
        $this->loadModel('Games');
        $this->loadModel('Teams');
        $this->loadModel('Players');
        $this->loadModel('GameMembers');
        $this->loadModel('GameResults');
        $game = $this->Games->get($game_id);
        if ($this->request->is('post') && !empty($this->request->data['target_player_id'])) {
            $pitcher_id = $this->GameMembers->find('all')
                ->where(['GameMembers.game_id' => $game_id])
                ->where(['not' => ['GameMembers.team_id' => $team_id]])
                ->where(['GameMembers.position' => 1])
                ->order(['GameMembers.id' => 'DESC'])
                ->first()
                ->player_id
            ;
            $target_player_id = $this->request->data['target_player_id'];
            $out_num = (int) $this->request->data['result'];
            $this->loadModel('GameInnings');
            
            $gameResultInfo = $this->GameResults->newEntity();
            $gameResultInfo->game_id = $game_id;
            $gameResultInfo->team_id = $team_id;
            $gameResultInfo->target_player_id = $target_player_id;
            $gameResultInfo->type = 3;
            $gameResultInfo->pitcher_id = $pitcher_id;
            $gameResultInfo->out_num = $out_num;
            $gameResultInfo->inning = $game->status;
            $this->GameResults->save($gameResultInfo);
            
            
            $gameInfo = $this->Games->get($game_id, [
                'contain' => [
                    'HomeTeams',
                    'VisitorTeams',
                    'WinPitchers',
                    'LosePitchers',
                    'SavePitchers',
                ]
            ]);
            
	        $this->Games->gameInfoUpdate(
	            $game_id,
	            0,
	            $out_num,
	            $game->status,
	            false
	        );
            
            return $this->redirect(['action' => 'close']);
        }
        $team = $this->Teams->get($team_id);
        // 今の打順全部
        $memberInfos = $this->GameMembers->find('all')
            ->contain(['Players'])
            ->where(['GameMembers.game_id' => $game_id])
            ->where(['GameMembers.team_id' => $team_id])
            ->order(['GameMembers.id' => 'ASC'])
        ;
        //home
        $nowMembers = [];
        foreach ($memberInfos as $memberInfo) {
            $nowMembers[$memberInfo->dajun] = $memberInfo;
        }
        ksort($nowMembers);
        
        $this->set('nowMembers', $nowMembers);
        
    }
    
    public function close()
    {
    
    }
    
    public function stamenDemo($seasonId = null)
    {
        if ($this->request->is('post')) {
            $this->request->session()->delete('StamenDemo');
            $this->redirect(['action' => 'stamenDemoSet', $seasonId, $this->request->data['home_team_id'],  $this->request->data['visitor_team_id']]);
        }

        $this->loadModel('Teams');
        $teams = $this->Teams->find('list')
            ->order(['Teams.id' => 'ASC'])
            ->where(['Teams.season_id' => $seasonId])
        ;
        $this->set('teams', $teams);
        $this->set('seasonId', $seasonId);
    }

    public function stamenDemoSet($seasonId = null, $homeTeamID = null,$visitorTeamID = null, $gameId = null)
    {
        $this->set('seasonId', $seasonId);
        $this->set('setgameId', $gameId);
        // visitorチーム
        if ($gameId) {
            if ($gameId == 'random') {
                $randomGameId = $this->Games->find('all')
                    ->where(['Games.status' => 99])
                    ->order('random()')
                    ->first()->id;
                $this->stamenDemoPlay($randomGameId);
            } else {
                $this->stamenDemoPlay($gameId);
            }
        } elseif (!$this->request->session()->check('StamenDemo.visitor')) {
            $this->set('type', 'visitor');
            return $this->stamenDemoSetParts($visitorTeamID);
        } elseif (!$this->request->session()->check('StamenDemo.home')) {
            $this->set('type', 'home');
            return $this->stamenDemoSetParts($homeTeamID);
        } else {
            // でも画面へ
            $this->stamenDemoPlay();
        }
    }
    
    private function stamenDemoSetParts($teamId)
    {
        $this->loadModel('Games');
        $this->loadModel('Players');
        $this->loadModel('GameMembers');
        $this->loadModel('Teams');
        $checkTeam = $this->Teams->get($teamId);
        // いなかったらセッティング
        // 全メンバーを取得
        $players = $this->Players->find('all')
            // ここ10試合の成績も取得
            ->where(['Players.team_id' => $teamId])
        ;
        
        // 前回のゲームがあればそのスタメンを取得する
        $recentGame = $this->Games->find('all')
            ->where([
                'OR' => [
                    'Games.home_team_id' => $teamId,
                    'Games.visitor_team_id' => $teamId,
                ],
                'Games.status' => 99
            ])
            ->order(['Games.date' =>'DESC'])
            ->order(['Games.id' =>'DESC'])
            ->first()
        ;
        $stamen = [];
        if (is_null($recentGame)) {
            // ない場合は適当に
            $dajun = 1;
            foreach($players as $player) {
                $stamen[$player->id] = [
                    'dajun' => $dajun,
                    'position' => $dajun,
                    'player' => $player
                ];
                if ($dajun == 9) {
                    break;
                }
                $dajun++;
            }
        } else {
            //前回のゲームがある場合は前回のゲームのスタメン
            $gameMemberInfos = $this->GameMembers->find('all')
                ->where(['GameMembers.game_id' => $recentGame->id])
                ->where(['GameMembers.team_id' => $teamId])
                ->where(['GameMembers.stamen_flag' => true])
                ->contain('Players')
                ->order(['GameMembers.dajun' => 'ASC'])
                ;
            foreach ($gameMemberInfos as $gameMemberInfo) {
                $stamen[$gameMemberInfo->player->id] = [
                    'dajun' => $gameMemberInfo->dajun,
                    'position' => $gameMemberInfo->position,
                    'player' => $gameMemberInfo->player
                ];
            }
        }
        $hikae = [];
        foreach($players as $player) {
            if (!empty($stamen[$player->id])) continue;
            $hikae[$player->id] = [
                'player' => $player
            ];
        }
        // ここ10試合のPのリスト
        // ここ10試合のゲームID
        $recentGameIds = $this->Games->find('all')
            ->where([
                'OR' => [
                    ['Games.home_team_id' => $teamId],
                    ['Games.visitor_team_id' => $teamId],
                ]
            ])
            ->where(['Games.status' => 99])
            ->order(['Games.date' => 'DESC'])
            ->order(['Games.id' => 'DESC'])
            ->find('list', [ 'keyField' => 'id', 'valueField' => 'id' ])
            ->limit(10)
            ->toArray();
        if (empty($recentGameIds)) {
            $recentGameIds[] = 0;
        }
        $this->loadModel('GameResults');
        $pitcherDatas = $this->GameResults->find('all')
            ->select('GameResults.pitcher_id')
            ->select(['out_num' => 'sum(GameResults.out_num)'])
            ->select(['Pitchers.name_short'])
            ->select(['Pitchers.type_p'])
            ->select(['Pitchers.type_c'])
            ->select(['Pitchers.type_i'])
            ->select(['Pitchers.type_o'])
            ->select(['Games.date'])
            ->contain('Games')
            ->contain('Pitchers')
            ->group('GameResults.pitcher_id')
            ->group('Pitchers.name_short')
            ->group('Pitchers.type_p')
            ->group('Pitchers.type_c')
            ->group('Pitchers.type_i')
            ->group('Pitchers.type_o')
            ->group('GameResults.game_id')
            ->group('Games.date')
            ->where(['GameResults.game_id IN' => $recentGameIds])
            ->where(['Pitchers.team_id' => $teamId])
            ->where(['GameResults.type IN' => [2,3]])
            ->order(['Games.date' => 'DESC'])
            ->order(['GameResults.game_id' => 'DESC'])
            ->order(['(SELECT OrderGameResults.id FROM game_results AS OrderGameResults WHERE OrderGameResults.pitcher_id = GameResults.pitcher_id AND OrderGameResults.game_id = GameResults.game_id LIMIT 1)' => 'ASC'])
        ;
        $this->set('checkTeam', $checkTeam);
        $this->set('stamen', $stamen);
        $this->set('hikae', $hikae);
        $this->set('pitcherDatas', $pitcherDatas);
        $this->set('positionLists', Configure::read('positionLists'));
        $this->set('positionColors', Configure::read('positionColors'));
        $this->render('stamen_setting_demo');
    }
    
    private function stamenDemoPlay($gameId = null)
    {
        $this->loadModel('Teams');
        $this->loadModel('Players');
        if (!$gameId) {
            $StamenDemo = $this->request->session()->read('StamenDemo');
        } else {
            $game = $this->Games->get($gameId, [
                'contain' => [
                    // 'HomeTeams',
                    // 'VisitorTeams',
                    'GameMembers' => [
                        'Players'
                    ],
                ]
            ]);

            $StamenDemo = [];
            $StamenDemo['visitor']['team_id'] = $game->visitor_team_id;
            $StamenDemo['home']['team_id'] = $game->home_team_id;
            // memberset
            foreach ($game->game_members as $gameMember) {
                if ($gameMember->stamen_flag == true) {
                    if ($gameMember->team_id == $game->visitor_team_id) {
                        $type = 'visitor';
                    } else {
                        $type = 'home';
                    }
                    $StamenDemo[$type][$gameMember->dajun] = [
                        'dajun' => $gameMember->dajun,
                        'position' => $gameMember->position,
                        'player_id' => $gameMember->player_id,
                    ];
                }
            }
            // debug($game);
            // exit;
        }
        
        $visitorTeamInfo = $this->Teams->get($StamenDemo['visitor']['team_id']);
        $homeTeamInfo = $this->Teams->get($StamenDemo['home']['team_id']);
        // member取得
        // visitor
        
        $visitorMembers = [];
        $visitorPitcherId = null;
        for ($i = 1;$i <= 9;$i++) {
            $visitorMembers[$i]['dajun'] = $StamenDemo['visitor'][$i]['dajun'];
            $visitorMembers[$i]['position'] = $StamenDemo['visitor'][$i]['position'];
            $visitorMembers[$i]['player'] = $this->Players->get($StamenDemo['visitor'][$i]['player_id'], ['contain' => ['Teams']]);
            if ($StamenDemo['visitor'][$i]['position'] == 1) {
                $visitorPitcherId = $StamenDemo['visitor'][$i]['player_id'];
            }
        }
        for ($i = 1;$i <= 9;$i++) {
            $homeMembers[$i]['dajun'] = $StamenDemo['home'][$i]['dajun'];
            $homeMembers[$i]['position'] = $StamenDemo['home'][$i]['position'];
            $homeMembers[$i]['player'] = $this->Players->get($StamenDemo['home'][$i]['player_id'], ['contain' => ['Teams']]);
            if ($StamenDemo['home'][$i]['position'] == 1) {
                $homePitcherId = $StamenDemo['home'][$i]['player_id'];
            }
        }
        
        $playerData = [];
        foreach ($visitorMembers as $visitorMember) {
            $playerData[$visitorMember['player']->id] = $this->stamenPlayerSeiri($visitorMember['dajun'], $visitorMember['player'], $visitorMember['position'], $gameId);
        }
        foreach ($homeMembers as $homeMember) {
            $playerData[$homeMember['player']->id] = $this->stamenPlayerSeiri($homeMember['dajun'], $homeMember['player'], $homeMember['position'], $gameId);
        }
        
        $this->set('visitorTeamInfo', $visitorTeamInfo);
        $this->set('homeTeamInfo', $homeTeamInfo);
        $this->set('visitorMembers', $visitorMembers);
        $this->set('homeMembers', $homeMembers);
        $this->set('positionLists', Configure::read('positionLists'));
        $this->set('positionColors', Configure::read('positionColors'));
        $this->set('playerData', $playerData);
        $this->set('gameId', $gameId);
        $this->render('play_display_demo');
    }

    public function stamenDemoSetAjax()
    {
        $this->autoRender=false;
        $type = key($this->request->data);
        $this->request->session()->write('StamenDemo.' . $type, $this->request->data[$type]);
    }
    
    private function stamenPlayerSeiri($dajun, $playerInfo, $position, $gameId)
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
        if (!$gameId) {
            if ($position != 1) {
                $displayPlayerInfo = $playerInfo->batter_player_info;
            } else {
                $displayPlayerInfo = $playerInfo->pitcher_player_info;
            }
        } else {
            $batterInfo = $this->GameMembers->find('all')
                ->contain(['Players' => ['Teams']])
                // ->where(['GameMembers.game_id' => $gameId])
                ->where(['GameMembers.player_id' => $playerInfo->id])
                ->select($this->GameMembers)
                ->select($this->GameMembers->Players)
                ->select(['dasu_count' => '(SELECT sum(Results.dasu_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])
                ->select(['hit_count' => '(SELECT sum(Results.hit_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])
                ->select(['hr_count' => '(SELECT sum(Results.hr_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])
                ->select(['rbi_count' => '(SELECT sum(GameResults.point) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.game_id <= ' . $gameId . ' AND GameResults.target_player_id = GameMembers.player_id)'])
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
            $displayPlayerInfoHr = $batterInfo->hr_count ;
            if ($position != 1) {
                $displayPlayerInfo = $avg . ' (' . (int) $batterInfo->dasu_count . '-' . (int) $batterInfo->hit_count . ') ' . (int) $batterInfo->hr_count . '本' . (int) $batterInfo->rbi_count . '点';
                // $displayPlayerInfo = $playerInfo->batter_player_info;
            } else {
                // 試合勝ち負けセーブ
                $this->loadModel('GamePitcherResults');
                $this->loadModel('GameResults');
                $performData = $this->GamePitcherResults->find('all')
                    ->select(['game_sum' => 'count(GamePitcherResults.id)'])
                    ->select(['win_sum' => 'count(CASE WHEN GamePitcherResults.win = TRUE THEN 1 ELSE NULL END)'])
                    ->select(['lose_sum' => 'count(CASE WHEN GamePitcherResults.lose = TRUE THEN 1 ELSE NULL END)'])
                    ->select(['save_sum' => 'count(CASE WHEN GamePitcherResults.save = TRUE THEN 1 ELSE NULL END)'])
                    ->contain('Games')
                    ->where(['GamePitcherResults.pitcher_id' => $playerInfo->id])
                    ->where(['Games.id <=' => $gameId])
                    ->group('GamePitcherResults.pitcher_id')
                    ->first()
                ;
                
                // 防御率
                $pitcherData = $this->GameResults->find('all')
                    ->select('GameResults.pitcher_id')
                    ->select(['total_inning' => '(SELECT sum(InningGameResults.out_num) FROM game_results AS InningGameResults WHERE InningGameResults.pitcher_id = GameResults.pitcher_id AND InningGameResults.game_id <= ' . $gameId . ')'])
                    ->select(['total_jiseki' => '(SELECT sum(JisekiGamePithcerResults.jiseki) FROM game_pitcher_results AS JisekiGamePithcerResults WHERE JisekiGamePithcerResults.pitcher_id = GameResults.pitcher_id AND JisekiGamePithcerResults.game_id <= ' . $gameId . ')'])
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
            } else {
               $era = '-';
                $displayPlayerInfo = '0試合';
            }

        // return $era . ' ' . (int) $performData->game_sum . '試' . $performData->win_sum . '勝' . (int) $performData->lose_sum . '敗' . (int) $performData->save_sum . 'S';


                // return $text;
                // $displayPlayerInfo = $playerInfo->pitcher_player_info;
                $this->set('gameInfo', $this->Games->get($gameId, [
                    'contain' => [
                        'HomeTeams',
                        'VisitorTeams',
                        'WinPitchers',
                        'LosePitchers',
                        'SavePitchers',
                    ]
                ]));
            }
        }

        return [
            'dajun' => $dajun,
            'no' => $playerInfo->no,
            'name' => $playerInfo->name,
            'name_eng' => $playerInfo->name_eng,
            'name_read' => $playerInfo->name_read,
            'name_short_read' => $playerInfo->name_short_read,
            'info' => $displayPlayerInfo,
            'avg' => $displayPlayerInfoAvg,
            'hr' => $displayPlayerInfoHr,
            'img_path' => $imgPath,
        ];
    }


    public function playlog($gameId)
    {
        $this->layout = false;
        $game = $this->Games->get($gameId, [
            'contain' => [
                'HomeTeams',
                'VisitorTeams',
                'GameResults',
            ]
        ]);

        // イニングの最初の数字を取っておく
        $inningStart = [];
        foreach ($game->game_results as $gameResultkey => $gameResult) {
            if (!array_key_exists($gameResult->inning, $inningStart) ) {
                $inningStart[$gameResult->inning] = $gameResultkey;
            }
        }

        $this->set('game', $game);
        $this->set('inningStart', $inningStart);
        // debug($game);
        // exit;
    }

    public function playlogInfo($gameId, $targetNumber)
    {
        $this->layout = false;
        $game = $this->Games->get($gameId, [
            'contain' => [
                'HomeTeams',
                'VisitorTeams',
                'GameResults' => function($q) {
                    return $q->order(['GameResults.id' => 'ASC'])
                        ->contain([
                            'Results',
                            'TargetPlayers' => function($q) {
                                return $q
                                    ->select('TargetPlayers.name')
                                    ->select(['dasu_sum' => '(SELECT count(SumResults.id) FROM game_results as SumGameResults LEFT JOIN results AS SumResults ON SumGameResults.result_id = SumResults.id WHERE SumResults.dasu_flag = true AND SumGameResults.target_player_id = GameResults.target_player_id AND SumGameResults.id <= GameResults.id)'])
                                    ->select(['hit_sum' => '(SELECT count(SumResults.id) FROM game_results as SumGameResults LEFT JOIN results AS SumResults ON SumGameResults.result_id = SumResults.id WHERE SumResults.hit_flag = true AND SumGameResults.target_player_id = GameResults.target_player_id AND SumGameResults.id <= GameResults.id)'])
                                    ->select(['hr_sum' => '(SELECT count(SumResults.id) FROM game_results as SumGameResults LEFT JOIN results AS SumResults ON SumGameResults.result_id = SumResults.id WHERE SumResults.hr_flag = true AND SumGameResults.target_player_id = GameResults.target_player_id AND SumGameResults.id <= GameResults.id)'])
                                    ->select(['point_sum' => '(SELECT sum(SumGameResults.point) FROM game_results as SumGameResults WHERE SumGameResults.target_player_id = GameResults.target_player_id AND SumGameResults.id <= GameResults.id)'])
                                    ->select(['win_sum' => '(SELECT count(game_pitcher_results.id) FROM game_pitcher_results WHERE game_pitcher_results.win = true AND game_pitcher_results.pitcher_id = GameResults.target_player_id AND game_pitcher_results.game_id < GameResults.game_id)'])
                                    ->select(['lose_sum' => '(SELECT count(game_pitcher_results.id) FROM game_pitcher_results WHERE game_pitcher_results.lose = true AND game_pitcher_results.pitcher_id = GameResults.target_player_id AND game_pitcher_results.game_id < GameResults.game_id)'])
                                    ->select(['inning_sum' => '(SELECT sum(game_pitcher_results.inning) FROM game_pitcher_results WHERE game_pitcher_results.pitcher_id = GameResults.target_player_id AND game_pitcher_results.game_id < GameResults.game_id)'])
                                    ->select(['jiseki_sum' => '(SELECT sum(game_pitcher_results.jiseki) FROM game_pitcher_results WHERE game_pitcher_results.pitcher_id = GameResults.target_player_id AND game_pitcher_results.game_id < GameResults.game_id)'])
                                    ;
                            },
                        ]);
                },
            ]
        ]);

        $startingMemberFlag = $targetNumber == 0;
        $activeMembers = [];
        $activePositions = [];
        $resultSet = null;
        // 進める場所はtype = 1の最後かそれ以外
        $memberChange = false;
        $result = null;
        while(true) {
            if ($game->game_results[$targetNumber]->type == 1) {
                $memberChange = true;
                if (!$startingMemberFlag) {
                    $activeMembers[] = $game->game_results[$targetNumber]->target_player_id;
                    $resultSet = [
                        'type' => 1,
                        'result' => null,
                        'result_code' => null,
                        'result_hit' => null,
                        'outNum' => null,
                        'point' => null,
                        'activeInning' => $game->game_results[$targetNumber]->inning,
                    ];
                }
            }
            if ($game->game_results[$targetNumber]->type != 1) {
                if (!$memberChange) {
                    $activePositions[] = $game->game_results[$targetNumber]->target_player_id;
                    $resultSet = [
                        'type' => $game->game_results[$targetNumber]->type,
                        'result' => !is_null($game->game_results[$targetNumber]->result) ? $game->game_results[$targetNumber]->result->name : null,
                        'result_code' => !is_null($game->game_results[$targetNumber]->result) ? $game->game_results[$targetNumber]->result->id : null,
                        'result_hit' => !is_null($game->game_results[$targetNumber]->result) ? $game->game_results[$targetNumber]->result->hit_flag : null,
                        'outNum' => $game->game_results[$targetNumber]->out_num,
                        'point' => $game->game_results[$targetNumber]->point,
                        'activeInning' => $game->game_results[$targetNumber]->inning,
                    ];
                } else {
                    $targetNumber--;
                }
                break;
            }
            $targetNumber++;
        }

        $members = [
            'home' => [],
            'visitor' => [],
        ];

        // スコアボード
        $scoreBoards = [
            'home' => [
                1 => '',
                2 => '',
                3 => '',
                4=> '',
                5 => '',
                6 => '',
                7 => '',
                8 => '',
                9 => '',
                10 => '',
                11 => '',
                12 => '',
                'R' => 0,
                'H' => 0,
            ],
            'visitor' => [
                1 => '',
                2 => '',
                3 => '',
                4 => '',
                5 => '',
                6 => '',
                7 => '',
                8 => '',
                9 => '',
                10 => '',
                11 => '',
                12 => '',
                'R' => 0,
                'H' => 0,
            ],
        ];

        $dajunCheck = [];
        $outCountCheck = [];
        // 得点のみ動作用
        $beforeDajun = null;
        $nowOut = 0;
        for ($i = 0;$i <= $targetNumber;$i++) {
            if ($game->game_results[$i]->team_id == $game->home_team_id) {
                $teamType = 'home';
            } else {
                $teamType = 'visitor';
            }
            // 盗塁には打順が（なぜか）埋まってないので埋める
            if (is_null($game->game_results[$i]->target_player_id)) {
                $game->game_results[$i]->dajun = $beforeDajun;
            } else {
                if (is_null($game->game_results[$i]->dajun)) {
                    $game->game_results[$i]->dajun = $dajunCheck[$game->game_results[$i]->target_player_id];
                }
                $beforeDajun = $game->game_results[$i]->dajun;
            }
            if ($game->game_results[$i]->type == 1) {
                $members[$teamType][$game->game_results[$i]->dajun]['dajun'] = $game->game_results[$i]->dajun;
                $members[$teamType][$game->game_results[$i]->dajun]['position'] = Configure::read('positionListShorts.' . $game->game_results[$i]->position);
                $members[$teamType][$game->game_results[$i]->dajun]['player_id'] = $game->game_results[$i]->target_player_id;
                $members[$teamType][$game->game_results[$i]->dajun]['player'] = $game->game_results[$i]->target_player->name;
                $dajunCheck[$game->game_results[$i]->target_player_id] = $game->game_results[$i]->dajun;
            }
            $members[$teamType][$game->game_results[$i]->dajun]['avg'] = $game->game_results[$i]->dasu_sum == 0 ? '-' : preg_replace('/^0\./', '.', sprintf('%.3f', round($game->game_results[$i]->hit_sum / $game->game_results[$i]->dasu_sum, 3)));
            $members[$teamType][$game->game_results[$i]->dajun]['hr'] = (int) $game->game_results[$i]->hr_sum;
            $members[$teamType][$game->game_results[$i]->dajun]['rbi'] = (int) $game->game_results[$i]->point_sum;
            $members[$teamType][$game->game_results[$i]->dajun]['win'] = (int) $game->game_results[$i]->win_sum;
            $members[$teamType][$game->game_results[$i]->dajun]['lose'] = (int) $game->game_results[$i]->lose_sum;
            $members[$teamType][$game->game_results[$i]->dajun]['era'] = $game->game_results[$i]->inning_sum == 0 ? '-' : sprintf('%.2f', round($game->game_results[$i]->jiseki_sum / $game->game_results[$i]->inning_sum * 27, 2));
            
            if (!is_null($game->game_results[$i]->result) && $game->game_results[$i]->result->hit_flag == true) {
                $scoreBoards[$teamType]['H']++;
            }
            $inning = ceil($game->game_results[$i]->inning / 2);
            if ($game->game_results[$i]->point > 0) {
                $scoreBoards[$teamType][$inning] = (int) $scoreBoards[$teamType][$inning] + $game->game_results[$i]->point;
                $scoreBoards[$teamType]['R'] += (int) $game->game_results[$i]->point;
            }
            if (empty($outCountCheck[$teamType][$inning])) {
                $outCountCheck[$teamType][$inning] = 0;
            }
            $outCountCheck[$teamType][$inning] += $game->game_results[$i]->out_num;
            $nowOut = $outCountCheck[$teamType][$inning];
            if ($outCountCheck[$teamType][$inning] == 3 && $scoreBoards[$teamType][$inning] == '') {
                $scoreBoards[$teamType][$inning] = 0;
            }
        }

        // debug($activePositions);
        // debug($activeMembers);
        // debug($members);
        // exit;

        echo json_encode([
            'member' => $members,
            'nextNumber' => $targetNumber + 1,
            'activePositions' => $activePositions,
            'activeMembers' => $activeMembers,
            'resultSet' => $resultSet,
            'scoreBoards' => $scoreBoards,
            'nowOut' => $nowOut,
        ]);
        exit;

    }

}
