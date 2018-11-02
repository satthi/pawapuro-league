<?php
namespace App\Controller;

use App\Controller\AppController;
use PhpExcelWrapper\PhpExcelWrapper;
use Cake\Core\Configure;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\File;
use Cake\Utility\Security;

/**
 * Seasons Controller
 *
 * @property \App\Model\Table\SeasonsTable $Seasons
 */
class SeasonsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Teams');
        $this->loadModel('Games');
        $this->loadModel('Players');
        $this->loadModel('VMonthTeamInfos');
        $this->loadModel('VMonthBatterInfos');
        $this->loadModel('VMonthPitcherInfos');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $query = $this->Seasons->find()
            ->order(['id' => 'DESC']);
        $seasons = $this->paginate($query);

        $this->set(compact('seasons'));
        $this->set('_serialize', ['seasons']);
    }

    /**
     * View method
     *
     * @param string|null $id Season id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $season = $this->Seasons->get($id);
        // 各チーム対戦成績
        $vsTeam = $this->Games->vsTeam($id);

        // チーム表
        $teams = $this->Teams->teamLists($id, $vsTeam);
        
        // 次のゲーム
        $nextGame = $this->Games->find('all')
            ->where(['Games.season_id' => $id])
            ->where(['not' => ['Games.status' => 99]])
            ->order(['Games.date' => 'ASC'])
            ->order(['Games.id' => 'ASC'])
            ->contain('HomeTeams')
            ->contain('VisitorTeams')
            ->first()
        ;
        
        // 各種ランキング
        $limit = 10;
        $avgRanking = $this->Players->rankingAvg($id, $limit);
        $hrRanking = $this->Players->rankingHr($id, $limit);
        $rbiRanking = $this->Players->rankingRbi($id, $limit);
        $stealRanking = $this->Players->rankingSteal($id, $limit);
        $hitRanking = $this->Players->rankingHit($id, $limit);
        $eraRanking = $this->Players->rankingEra($id, $limit);
        $winRanking = $this->Players->rankingWin($id, $limit);
        $saveRanking = $this->Players->rankingSave($id, $limit);
        $holdRanking = $this->Players->rankingHold($id, $limit);
        $getSansinRanking = $this->Players->rankingGetSansin($id, $limit);
        
        $this->set('season', $season);
        $this->set('teams', $teams);
        $this->set('nextGame', $nextGame);
        $this->set('avgRanking', $avgRanking);
        $this->set('hrRanking', $hrRanking);
        $this->set('rbiRanking', $rbiRanking);
        $this->set('stealRanking', $stealRanking);
        $this->set('hitRanking', $hitRanking);
        $this->set('eraRanking', $eraRanking);
        $this->set('winRanking', $winRanking);
        $this->set('saveRanking', $saveRanking);
        $this->set('holdRanking', $holdRanking);
        $this->set('getSansinRanking', $getSansinRanking);
        $this->set('vsTeam', $vsTeam);
        $this->set('_serialize', ['season']);
    }

    public function viewMonth($id = null, $year = null, $month = null)
    {
        $season = $this->Seasons->get($id);
        $teams = $this->VMonthTeamInfos->find('all')
            ->where(['VMonthTeamInfos.season_id' => $id])
            ->where(['VMonthTeamInfos.year' => $year])
            ->where(['VMonthTeamInfos.month' => $month])
            ->order(['VMonthTeamInfos.win_ratio' => 'DESC'])
            ->order(['VMonthTeamInfos.win' => 'DESC'])
            ->order(['VMonthTeamInfos.lose' => 'ASC'])
        ;
        
        
        // 各種ランキング
        $limit = 10;
        $avgRanking = $this->VMonthBatterInfos->rankingAvg($id, $year, $month, $limit);
        $hrRanking = $this->VMonthBatterInfos->rankingHr($id, $year, $month, $limit);
        $rbiRanking = $this->VMonthBatterInfos->rankingRbi($id, $year, $month, $limit);
        $hitRanking = $this->VMonthBatterInfos->rankingHit($id, $year, $month, $limit);

        $eraRanking = $this->VMonthPitcherInfos->rankingEra($id, $year, $month, $limit);
        $winRanking = $this->VMonthPitcherInfos->rankingWin($id, $year, $month, $limit);
        $saveRanking = $this->VMonthPitcherInfos->rankingSave($id, $year, $month, $limit);
        $holdRanking = $this->VMonthPitcherInfos->rankingHold($id, $year, $month, $limit);
        
        // 各チーム対戦成績
        $this->set('season', $season);
        $this->set('teams', $teams);
        $this->set('avgRanking', $avgRanking);
        $this->set('hrRanking', $hrRanking);
        $this->set('rbiRanking', $rbiRanking);
        $this->set('hitRanking', $hitRanking);
        $this->set('eraRanking', $eraRanking);
        $this->set('winRanking', $winRanking);
        $this->set('saveRanking', $saveRanking);
        $this->set('holdRanking', $holdRanking);
        $this->set('month', $month);
        $this->set('_serialize', ['season']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    	$season = $this->Seasons->newEntity();
        if ($this->request->is('post')) {
            if ($this->Seasons->add($season, $this->request->data)) {
                $this->Flash->success(__('The season has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The season could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('season'));
        $this->set('_serialize', ['season']);
    }
    
    public function reShukei($seasonId = null)
    {
        $this->Players->batterShukei($seasonId);
        $this->Players->pitcherShukei($seasonId);
        $this->Teams->teamShukei($seasonId);
        exit;
    }
    
    public function viewDetail($id = null)
    {
        $season = $this->Seasons->get($id);
        $this->loadModel('Games');
        $games = $this->Games->find('all')
            ->where(['Games.season_id' => $id])
            ->where(['Games.status' => 99])
            ->contain('HomeTeams')
            ->contain('VisitorTeams')
            ->order(['Games.date' => 'ASC'])
            ->order(['Games.id' => 'ASC'])
            ;
        $gameGraphDatas = [];
        $pointCheck = [];
        foreach($games as $game) {
            if (!array_key_exists($game->home_team_id, $pointCheck)) {
                $pointCheck[$game->home_team_id] = 0;
            }
            if (!array_key_exists($game->visitor_team_id, $pointCheck)) {
                $pointCheck[$game->visitor_team_id] = 0;
            }
            if ($game->home_point > $game->visitor_point) {
                $pointCheck[$game->home_team_id]++;
                $pointCheck[$game->visitor_team_id]--;
            } elseif($game->home_point < $game->visitor_point) {
                $pointCheck[$game->visitor_team_id]++;
                $pointCheck[$game->home_team_id]--;
            }
            $gameGraphDatas[$game->home_team->ryaku_name][$game->date->format('Y/m/d')] = $pointCheck[$game->home_team_id];
            $gameGraphDatas[$game->visitor_team->ryaku_name][$game->date->format('Y/m/d')] = $pointCheck[$game->visitor_team_id];
        }
        $firstDate = $games->first()->date->format('Y/m/d');
        $lastDate = $games->last()->date->format('Y/m/d');
        $this->set(compact('season', 'gameGraphDatas', 'firstDate', 'lastDate'));
    }
    
    public function batterDetail($id = null)
    {
        $season = $this->Seasons->get($id);
        //野手成績
        $this->loadModel('Players');
        $query = $this->Players->find('all')
            ->contain('Teams')
            ->where(['Teams.season_id' => $id])
            ;
        if (empty($this->request->query['sort'])) {
            $this->request->query['sort'] = 'avg';
        }
        $this->request->query['direction'] = 'desc';
        $query = $query->where([$this->request->query['sort'] . ' IS NOT' => null]);
        if ($this->request->query['sort'] == 'avg'){
            $query = $query->where('Players.daseki >= Teams.game * 3.1');
        }
        $players = $this->paginate($query, ['limit' => 100]);
        $this->set('players', $players);
        $this->set('id', $id);
    }
    
    public function pitcherDetail($id = null)
    {
        $season = $this->Seasons->get($id);
        //野手成績
        $this->loadModel('Players');
        $query = $this->Players->find('all')
            ->contain('Teams')
            ->where(['Teams.season_id' => $id])
            ;
        if (empty($this->request->query['sort'])) {
            $this->request->query['sort'] = 'era';
        }
        $this->request->query['direction'] = 'desc';
        $query = $query->where(['Players.' . $this->request->query['sort'] . ' IS NOT' => null]);
        if ($this->request->query['sort'] == 'era'){
            $query = $query->where('Players.inning >= Teams.game * 3');
            $this->request->query['direction'] = 'asc';
        }
        if ($this->request->query['sort'] == 'win_ratio'){
            $query = $query->where('Players.inning >= Teams.game * 3');
        }
        $players = $this->paginate($query, ['limit' => 100]);
        $this->set('players', $players);
        $this->set('id', $id);
    }
    
    public function analyze($id = null)
    {
        $season = $this->Seasons->get($id);
        $this->loadModel('Teams');
        $teams = $this->Teams->find('all')
            ->where(['Teams.season_id' => $id])
            ->order(['Teams.id' => 'ASC'])
        ;
        $this->loadModel('GameMembers');
        $stamenMemberAnalyze = $this->GameMembers->find('all')
            ->contain('Games')
            ->contain('Players')
            ->where(['Games.season_id' => $id])
            ->where(['GameMembers.stamen_flag' => true])
            ->select(['count' => 'count(GameMembers.id)'])
            ->select('Players.name_short')
            ->select('Players.type_p')
            ->select('Players.type_c')
            ->select('Players.type_i')
            ->select('Players.type_o')
            ->select('GameMembers.dajun')
            ->select('GameMembers.team_id')
            ->group(['GameMembers.dajun'])
            ->group(['GameMembers.player_id'])
            ->group(['Players.name_short'])
            ->group(['Players.type_p'])
            ->group(['Players.type_c'])
            ->group(['Players.type_i'])
            ->group(['Players.type_o'])
            ->group(['GameMembers.team_id'])
            ->order(['GameMembers.team_id' => 'ASC'])
            ->order(['GameMembers.dajun' => 'ASC'])
            ->order(['count(GameMembers.id)' => 'DESC'])
            ;
        $stamenMemberAnalyzeLists = [];
        foreach ($stamenMemberAnalyze as $stamenMemberAnalyzeList) {
            $stamenMemberAnalyzeLists[$stamenMemberAnalyzeList->team_id][$stamenMemberAnalyzeList->dajun][] = $stamenMemberAnalyzeList;
        }
        $stamenPositionAnalyze = $this->GameMembers->find('all')
            ->contain('Games')
            ->where(['Games.season_id' => $id])
            ->where(['GameMembers.stamen_flag' => true])
            ->select(['count' => 'count(GameMembers.id)'])
            ->select('GameMembers.dajun')
            ->select('GameMembers.team_id')
            ->select('GameMembers.position')
            ->group(['GameMembers.dajun'])
            ->group(['GameMembers.position'])
            ->group(['GameMembers.team_id'])
            ->order(['GameMembers.team_id' => 'ASC'])
            ->order(['GameMembers.dajun' => 'ASC'])
            ->order(['count(GameMembers.id)' => 'DESC'])
            ;
        $stamenPositionAnalyzeLists = [];
        foreach ($stamenPositionAnalyze as $stamenPositionAnalyzeList) {
            $stamenPositionAnalyzeLists[$stamenPositionAnalyzeList->team_id][$stamenPositionAnalyzeList->dajun][] = $stamenPositionAnalyzeList;
        }
        
        $stamenPositionTotalAnalyze = $this->GameMembers->find('all')
            ->contain('Games')
            ->where(['Games.season_id' => $id])
            ->where(['GameMembers.stamen_flag' => true])
            ->select(['count' => 'count(GameMembers.id)'])
            ->select('GameMembers.dajun')
            ->select('GameMembers.position')
            ->group(['GameMembers.dajun'])
            ->group(['GameMembers.position'])
            ->order(['GameMembers.dajun' => 'ASC'])
            ->order(['count(GameMembers.id)' => 'DESC'])
            ;
        $stamenPositionTotalAnalyzeLists = [];
        foreach ($stamenPositionTotalAnalyze as $stamenPositionTotalAnalyzeList) {
            $stamenPositionTotalAnalyzeLists[$stamenPositionTotalAnalyzeList->dajun][] = $stamenPositionTotalAnalyzeList;
        }
        
        $this->set('id', $id);
        $this->set('teams', $teams);
        $this->set('stamenMemberAnalyzeLists', $stamenMemberAnalyzeLists);
        $this->set('stamenPositionAnalyzeLists', $stamenPositionAnalyzeLists);
        $this->set('stamenPositionTotalAnalyzeLists', $stamenPositionTotalAnalyzeLists);
        $this->set('positionLists', Configure::read('positionLists'));
        $this->set('positionColors', Configure::read('positionColors'));
    }



    public function playerExport()
    {
        $this->autoRender = false;
        $basePlayers = TableRegistry::get('BasePlayers')->find()
        ->order(['id' => 'ASC']);

        $PhpExcelWrapper = new PhpExcelWrapper(ROOT . '/webroot/player_template.xlsx');

        $Hand = array_flip(Configure::read('Hand'));
        $PositionCheck = array_flip(Configure::read('PositionCheck'));

        $row = 1;
        foreach ($basePlayers as $basePlayer) {
            $row++;
            $PhpExcelWrapper->setVal($basePlayer->id, 0, $row);
            $PhpExcelWrapper->setVal($basePlayer->team_ryaku_name, 1, $row);
            $PhpExcelWrapper->setVal($basePlayer->no, 2, $row);
            $PhpExcelWrapper->setVal($basePlayer->name, 3, $row);
            $PhpExcelWrapper->setVal($basePlayer->name_short, 4, $row);
            $PhpExcelWrapper->setVal($basePlayer->name_eng, 5, $row);
            $PhpExcelWrapper->setVal($basePlayer->name_read, 6, $row);
            $PhpExcelWrapper->setVal($basePlayer->name_short_read, 7, $row);
            $PhpExcelWrapper->setVal($Hand[$basePlayer->throw], 8, $row);
            $PhpExcelWrapper->setVal($Hand[$basePlayer->bat], 9, $row);
            $PhpExcelWrapper->setVal($PositionCheck[$basePlayer->type_p], 10, $row);
            $PhpExcelWrapper->setVal($PositionCheck[$basePlayer->type_c], 11, $row);
            $PhpExcelWrapper->setVal($PositionCheck[$basePlayer->type_i], 12, $row);
            $PhpExcelWrapper->setVal($PositionCheck[$basePlayer->type_o], 13, $row);
        }

        $this->response->type('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $filename = 'export.xlsx';
        if (strstr(env('HTTP_USER_AGENT'), 'MSIE') || strstr(env('HTTP_USER_AGENT'), 'Trident') || strstr(env('HTTP_USER_AGENT'), 'Edge')) {
            $filename = mb_convert_encoding($filename, "SJIS", "UTF-8");
        }
        $this->response->header('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->header('Cache-Control', 'max-age=0');
        $tmpFile = TMP . Security::hash(time() . rand()) . '.xlsx';
        $PhpExcelWrapper->write($tmpFile);
        // ファイルの内容読み込み
        $fp = new File($tmpFile);
        $body = $fp->read();
        // 一時ファイルの削除
        $fp->delete();
        $this->response->body($body);
    } 

}
