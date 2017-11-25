<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Players Controller
 *
 * @property \App\Model\Table\PlayersTable $Players
 */
class PlayersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($teamID = null)
    {
        $players = $this->Players->find('all')
            ->where(['Players.team_id' => $teamID])
            ->order(['Players.type_p is null' => 'DESC'])
            ->order(['Players.no::integer' => 'ASC']);
        
        // 月間の試合結果との行き来のため
        $this->loadModel('Games');
        $monthSets = $this->Games->find('all')
        	->where(['OR' => 
        		[
        			'Games.home_team_id' => $teamID,
        			'Games.visitor_team_id' => $teamID
        		],
        	])
        	->select(['year' => 'DATE_PART(\'YEAR\', Games.date)'])
        	->select(['month' => 'DATE_PART(\'MONTH\', Games.date)'])
        	->group('DATE_PART(\'YEAR\', Games.date)')
        	->group('DATE_PART(\'MONTH\', Games.date)')
        	->order(['DATE_PART(\'YEAR\', Games.date)' => 'ASC'])
        	->order(['DATE_PART(\'MONTH\', Games.date)' => 'ASC'])
        	;

        $this->set(compact('players'));
        $this->set(compact('monthSets'));
        $this->set(compact('teamID'));
        $this->set('_serialize', ['players']);
    }

    /**
     * View method
     *
     * @param string|null $id Player id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
    	$this->loadModel('GameResults');
    	$this->loadModel('GamePitcherResults');
    	$this->loadModel('VMonthBatterInfos');
    	$this->loadModel('VMonthPitcherInfos');
        $player = $this->Players->get($id, [
            'contain' => ['GameMembers', 'Teams']
        ]);
        // 該当者の全履歴取得
        $batterResults = $this->GameResults->find('all')
        	->where(['GameResults.target_player_id' => $id])
        	->where(['Games.status' => 99])
        	->order(['GameResults.id' => 'ASC'])
        	->contain(['Games' => [
        		'HomeTeams',
        		'VisitorTeams',
        	]])
        	->contain('Results')
        ;
        $resultSet = Configure::read('resultSet');
        $batterResultSets = [];
        foreach ($batterResults as $batterResult) {
            if (empty($batterResultSets[$batterResult->game_id])) {
            	$vsTeam = null;
            	if ($player->team_id == $batterResult->game->home_team_id) {
            		$vsTeam = $batterResult->game->visitor_team->ryaku_name;
            	} else {
            		$vsTeam = $batterResult->game->home_team->ryaku_name;
            	}
            	$batterResultSets[$batterResult->game_id] = [
            		'date' => $batterResult->game->date,
            		'vsTeam' => $vsTeam,
            		'daseki' => 0,
            		'dasu' => 0,
            		'hit' => 0,
            		'hr' => 0,
            		'rbi' => 0,
            		'steal' => 0,
            		'results' => [],
            	];
            }
            // 打撃成績
            if ($batterResult->type == 2) {
            	$batterResultSets[$batterResult->game_id]['daseki'] += 1;
            	if ($batterResult->result->dasu_flag == true) {
            		$batterResultSets[$batterResult->game_id]['dasu'] += 1;
            	}
            	if ($batterResult->result->hit_flag == true) {
            		$batterResultSets[$batterResult->game_id]['hit'] += 1;
            	}
            	if ($batterResult->result->hr_flag == true) {
            		$batterResultSets[$batterResult->game_id]['hr'] += 1;
            	}
            	$batterResultSets[$batterResult->game_id]['rbi'] += $batterResult->point;
            	$batterResultSets[$batterResult->game_id]['results'][] = '<span class="type_' . (int) $batterResult->result->hit_flag . '">' . $batterResult->result->name . '</span>';
            }
            // 盗塁成績
            if ($batterResult->type == 3) {
            	if ($batterResult->out_num == 0) {
            		$batterResultSets[$batterResult->game_id]['steal'] += 1;
            	}
            }
        }
        
        
        // 投手履歴
        $pitcherResults = $this->GamePitcherResults->find('all')
        	->where(['GamePitcherResults.pitcher_id' => $id])
        	->order(['GamePitcherResults.id' => 'ASC'])
        	->contain(['Games' => [
        		'HomeTeams',
        		'VisitorTeams',
        	]])
        	->select($this->GamePitcherResults)
        	->select($this->GamePitcherResults->Games)
        	->select($this->GamePitcherResults->Games->HomeTeams)
        	->select($this->GamePitcherResults->Games->VisitorTeams)
        	->select(['inning' => '(SELECT sum(out_num) FROM game_results WHERE game_results.pitcher_id = ' . $id . ' AND game_results.game_id = GamePitcherResults.game_id)'])
        	->select(['sansin' => '(SELECT sum(Results.sansin_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.pitcher_id = ' . $id . ' AND GameResults.game_id = GamePitcherResults.game_id)'])
        	->select(['hit' => '(SELECT sum(Results.hit_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.pitcher_id = ' . $id . ' AND GameResults.game_id = GamePitcherResults.game_id)'])
        	->select(['hr' => '(SELECT sum(Results.hr_flag::integer) FROM game_results AS GameResults LEFT JOIN results AS Results ON GameResults.result_id = Results.id WHERE GameResults.pitcher_id = ' . $id . ' AND GameResults.game_id = GamePitcherResults.game_id)'])
        	;
        $pitcherResultSets = [];
        foreach ($pitcherResults as $pitcherResult) {
            	$vsTeam = null;
            	if ($player->team_id == $pitcherResult->game->home_team_id) {
            		$vsTeam = $pitcherResult->game->visitor_team->ryaku_name;
            	} else {
            		$vsTeam = $pitcherResult->game->home_team->ryaku_name;
            	}
            	$result = '';
            	if ($pitcherResult->win) {
            		$result = '○';
            	} elseif ($pitcherResult->lose) {
            		$result = '●';
            	} elseif ($pitcherResult->hold) {
            		$result = 'H';
            	} elseif ($pitcherResult->save) {
            		$result = 'S';
            	}
            	$pitcherResultSets[$pitcherResult->game_id] = [
            		'date' => $pitcherResult->game->date,
            		'vsTeam' => $vsTeam,
            		'inning' => $pitcherResult->inning,
            		'jiseki' => $pitcherResult->jiseki,
            		'hit' => $pitcherResult->hit,
            		'hr' => $pitcherResult->hr,
            		'sansin' => $pitcherResult->sansin,
            		'result' => $result,
            	];

        }
        
        //月間野手成績
        $monthBatterInfos = $this->VMonthBatterInfos->find('all')
        	->where(['VMonthBatterInfos.player_id' => $id])
        	->order(['VMonthBatterInfos.year' => 'ASC'])
        	->order(['VMonthBatterInfos.month' => 'ASC'])
        	;
        // 月間投手成績
        $monthPitcherInfos = $this->VMonthPitcherInfos->find('all')
        	->where(['VMonthPitcherInfos.player_id' => $id])
        	->order(['VMonthPitcherInfos.year' => 'ASC'])
        	->order(['VMonthPitcherInfos.month' => 'ASC'])
        	;
        
        // 対戦チームごとの野手成績
        $vsTeamBatterInfos = $this->GameResults->find('all')
        	->select('Pitchers.team_id')
        	->select('Teams.ryaku_name')
    		->select(['game' => 'count(DISTINCT GameResults.game_id)'])
    		->select(['daseki' => 'count(CASE WHEN GameResults.type = 2 THEN 1 ELSE null END)'])
    		->select(['dasu' => 'sum(Results.dasu_flag::integer)'])
    		->select(['hit' => 'sum(Results.hit_flag::integer)'])
    		->select(['hr' => 'sum(Results.hr_flag::integer)'])
    		->select(['rbi' => 'sum(GameResults.point)'])
    		->select(['steal' => 'count(CASE WHEN GameResults.type = 3 AND GameResults.out_num = 0 THEN 1 ELSE null END)'])
        	->contain(['Pitchers' => ['Teams']])
        	->contain('Results')
        	->where(['GameResults.target_player_id' => $id])
        	->where(['GameResults.pitcher_id IS NOT' => null])
        	->group('Pitchers.team_id')
        	->group('Teams.ryaku_name')
        	->order(['Pitchers.team_id' => 'ASC'])
        	;
        // ピッチャーごとの対戦成績
        $vsPitcherBatterInfos = $this->GameResults->find('all')
            ->select('Pitchers.name')
            ->select('Teams.ryaku_name')
            ->select(['game' => 'count(DISTINCT GameResults.game_id)'])
            ->select(['daseki' => 'count(CASE WHEN GameResults.type = 2 THEN 1 ELSE null END)'])
            ->select(['dasu' => 'sum(Results.dasu_flag::integer)'])
            ->select(['hit' => 'sum(Results.hit_flag::integer)'])
            ->select(['hr' => 'sum(Results.hr_flag::integer)'])
            ->select(['rbi' => 'sum(GameResults.point)'])
            ->select(['steal' => 'count(CASE WHEN GameResults.type = 3 AND GameResults.out_num = 0 THEN 1 ELSE null END)'])
            ->contain(['Pitchers' => ['Teams']])
            ->contain('Results')
            ->where(['GameResults.target_player_id' => $id])
            ->where(['GameResults.pitcher_id IS NOT' => null])
            ->group('Pitchers.name')
            ->group('Pitchers.team_id')
            ->group('Pitchers.id')
            ->group('Teams.ryaku_name')
            ->order(['Pitchers.team_id' => 'ASC'])
            ;

        // 利き腕ごとの野手成績
        $vsHandBatterInfos = $this->GameResults->find('all')
        	->select('Pitchers.throw')
    		->select(['daseki' => 'count(CASE WHEN GameResults.type = 2 THEN 1 ELSE null END)'])
    		->select(['dasu' => 'sum(Results.dasu_flag::integer)'])
    		->select(['hit' => 'sum(Results.hit_flag::integer)'])
    		->select(['hr' => 'sum(Results.hr_flag::integer)'])
    		->select(['rbi' => 'sum(GameResults.point)'])
        	->contain(['Pitchers'])
        	->contain('Results')
        	->where(['GameResults.target_player_id' => $id])
        	->where(['GameResults.type' => 2])
        	->group('Pitchers.throw')
        	->order(['Pitchers.throw' => 'ASC'])
        	;
        
        if ($player->throw == 1) {
            $both = 2;
        } else {
            $both = 1;
        }
        // 利き腕ごとの投手成績
        $vsHandPitcherInfos = $this->GameResults->find('all')
        	->select(['bat' => 'CASE WHEN Batters.bat = 3 THEN ' . $both . ' ELSE Batters.bat END'])
    		->select(['daseki' => 'count(CASE WHEN GameResults.type = 2 THEN 1 ELSE null END)'])
    		->select(['dasu' => 'sum(Results.dasu_flag::integer)'])
    		->select(['hit' => 'sum(Results.hit_flag::integer)'])
    		->select(['hr' => 'sum(Results.hr_flag::integer)'])
        	->contain(['Batters'])
        	->contain('Results')
        	->where(['GameResults.pitcher_id' => $id])
        	->where(['GameResults.type' => 2])
        	->group(['CASE WHEN Batters.bat = 3 THEN ' . $both . ' ELSE Batters.bat END'])
        	->order(['CASE WHEN Batters.bat = 3 THEN ' . $both . ' ELSE Batters.bat END' => 'ASC'])
        	;
        
        // 対戦チームごとの投手成績
        $vsTeamPitcherInfos = $this->GamePitcherResults->find('all')
        	->select(['team' => 'CASE when GamePitcherResults.team_id = HomeTeams.id THEN VisitorTeams.ryaku_name ELSE HomeTeams.ryaku_name END'])
    		->select(['game_count' => 'count(GamePitcherResults.game_id)'])
    		->select(['win_count' => 'sum(GamePitcherResults.win::integer)'])
    		->select(['lose_count' => 'sum(GamePitcherResults.lose::integer)'])
    		->select(['hold_count' => 'sum(GamePitcherResults.hold::integer)'])
    		->select(['save_count' => 'sum(GamePitcherResults.save::integer)'])
    		->select(['jiseki_count' => 'sum(GamePitcherResults.jiseki)'])
    		->select(['inning_count' => 'sum(GamePitcherResults.inning)'])
        	->contain(['Games' => ['HomeTeams','VisitorTeams',]])
        	->where(['GamePitcherResults.pitcher_id' => $id])
        	->group('CASE when GamePitcherResults.team_id = HomeTeams.id THEN VisitorTeams.ryaku_name ELSE HomeTeams.ryaku_name END')
        	->group('CASE when GamePitcherResults.team_id = HomeTeams.id THEN VisitorTeams.id ELSE HomeTeams.id END')
        	->order(['CASE when GamePitcherResults.team_id = HomeTeams.id THEN VisitorTeams.id ELSE HomeTeams.id END' => 'ASC'])
        	;

        // ピッチャーごとの対戦成績
        $vsBatterPitcherInfos = $this->GameResults->find('all')
            ->select('Batters.name')
            ->select('Teams.ryaku_name')
            ->select(['game' => 'count(DISTINCT GameResults.game_id)'])
            ->select(['daseki' => 'count(CASE WHEN GameResults.type = 2 THEN 1 ELSE null END)'])
            ->select(['dasu' => 'sum(Results.dasu_flag::integer)'])
            ->select(['hit' => 'sum(Results.hit_flag::integer)'])
            ->select(['hr' => 'sum(Results.hr_flag::integer)'])
            ->select(['rbi' => 'sum(GameResults.point)'])
            ->contain(['Batters' => ['Teams']])
            ->contain('Results')
            ->where(['GameResults.pitcher_id' => $id])
            ->group('Batters.name')
            ->group('Batters.id')
            ->group('Teams.ryaku_name')
            // ->order(['Pitchers.team_id' => 'ASC'])
            ;
        // 歴代成績
        $histories = $this->Players->find('all')
        	->where(['Teams.ryaku_name' => $player->team->ryaku_name])
        	->where(['Players.no' => $player->no])
        	->contain(['Teams' => ['Seasons']])
        	->order(['Teams.season_id' => 'ASC'])
        	;
        
        $this->set('player', $player);
        $this->set('batterResultSets', $batterResultSets);
        $this->set('monthBatterInfos', $monthBatterInfos);
        $this->set('pitcherResultSets', $pitcherResultSets);
        $this->set('monthPitcherInfos', $monthPitcherInfos);
        $this->set('vsTeamBatterInfos', $vsTeamBatterInfos);
        $this->set('vsPitcherBatterInfos', $vsPitcherBatterInfos);
        $this->set('vsTeamPitcherInfos', $vsTeamPitcherInfos);
        $this->set('vsBatterPitcherInfos', $vsBatterPitcherInfos);
        $this->set('vsHandBatterInfos', $vsHandBatterInfos);
        $this->set('vsHandPitcherInfos', $vsHandPitcherInfos);
        $this->set('histories', $histories);
        $this->set('_serialize', ['player']);
    }

    private function resultCase($conditions)
    {
    	foreach ($conditions as $k => $v) {
    		$conditions[$k] = '\'' . $v . '\'';
    	}
        return 'count(CASE WHEN (GameResults.type = 2 AND GameResults.result IN (' . implode(',' , $conditions) . ')) THEN 1 ELSE null END)';
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $player = $this->Players->newEntity();
        if ($this->request->is('post')) {
            $player = $this->Players->patchEntity($player, $this->request->data);
            if ($this->Players->save($player)) {
                $this->Flash->success(__('The player has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The player could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('player'));
        $this->set('_serialize', ['player']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Player id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $player = $this->Players->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $player = $this->Players->patchEntity($player, $this->request->data);
            if ($this->Players->save($player)) {
                $this->Flash->success(__('The player has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The player could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('player'));
        $this->set('_serialize', ['player']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Player id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $player = $this->Players->get($id);
        if ($this->Players->delete($player)) {
            $this->Flash->success(__('The player has been deleted.'));
        } else {
            $this->Flash->error(__('The player could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
