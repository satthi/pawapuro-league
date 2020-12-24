<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use PhpExcelWrapper\PhpExcelWrapper;



/**
 * Players Model
 *
 * @property \Cake\ORM\Association\HasMany $GameMembers
 *
 * @method \App\Model\Entity\Player get($primaryKey, $options = [])
 * @method \App\Model\Entity\Player newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Player[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Player|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Player patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Player[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Player findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PlayersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('players');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('GameMembers', [
            'foreignKey' => 'player_id'
        ]);
        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id'
        ]);
        $this->belongsTo('BasePlayers', [
            'foreignKey' => 'base_player_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('name');

        $validator
            ->integer('type')
            ->allowEmpty('type');

        $validator
            ->integer('daseki')
            ->allowEmpty('daseki');

        $validator
            ->integer('dasu')
            ->allowEmpty('dasu');

        $validator
            ->integer('hit')
            ->allowEmpty('hit');

        $validator
            ->integer('hr')
            ->allowEmpty('hr');

        $validator
            ->integer('rbi')
            ->allowEmpty('rbi');

        $validator
            ->integer('inning')
            ->allowEmpty('inning');

        $validator
            ->integer('jiseki')
            ->allowEmpty('jiseki');

        $validator
            ->integer('win')
            ->allowEmpty('win');

        $validator
            ->integer('lose')
            ->allowEmpty('lose');

        $validator
            ->integer('hold')
            ->allowEmpty('hold');

        $validator
            ->integer('save')
            ->allowEmpty('save');

        $validator
            ->boolean('deleted')
            ->allowEmpty('deleted');

        $validator
            ->dateTime('deleted_date')
            ->allowEmpty('deleted_date');

        return $validator;
    }
    
    public function adds($teamLists, $data)
    {
        $saveFlag = true;
        $Hand = Configure::read('Hand');
        $PositionCheck = Configure::read('PositionCheck');
         
        // player登録
        $PhpExcelWrapper = new PhpExcelWrapper($data['player_excel']['tmp_name']);
        $row = 2;
        while(true) {
            $teamCode = $PhpExcelWrapper->getVal(1, $row);
            if (is_object($teamCode)) {
                $teamCode = $teamCode->getPlainText();
            }
            if ($teamCode == '') {
                break;
            }
            if (empty($teamLists[$teamCode])) {
                $row++;
                continue;
            }
            $playerInfo = [];

            $playerInfo['no'] = $PhpExcelWrapper->getVal(2, $row);
            $playerInfo['name'] = $PhpExcelWrapper->getVal(3, $row);
            $playerInfo['name_short'] = $PhpExcelWrapper->getVal(4, $row);
            $playerInfo['name_eng'] = $PhpExcelWrapper->getVal(5, $row);
            $playerInfo['name_read'] = $PhpExcelWrapper->getVal(6, $row);
            $playerInfo['name_short_read'] = $PhpExcelWrapper->getVal(7, $row);
            $playerInfo['throw'] = $Hand[$PhpExcelWrapper->getVal(8, $row)];
            $playerInfo['bat'] = $Hand[$PhpExcelWrapper->getVal(9, $row)];
            $playerInfo['type_p'] = $PositionCheck[$PhpExcelWrapper->getVal(10, $row)];
            $playerInfo['type_c'] = $PositionCheck[$PhpExcelWrapper->getVal(11, $row)];
            $playerInfo['type_i'] = $PositionCheck[$PhpExcelWrapper->getVal(12, $row)];
            $playerInfo['type_o'] = $PositionCheck[$PhpExcelWrapper->getVal(13, $row)];
            $playerInfo['accident_type'] = $PhpExcelWrapper->getVal(14, $row);
            $playerInfo['walk_ritsu'] = $PhpExcelWrapper->getVal(15, $row);
            $playerInfo['p_walk_ritsu'] = $PhpExcelWrapper->getVal(16, $row);

            $playerInfo['base_player_id'] = $PhpExcelWrapper->getVal(0, $row);
            if (empty($playerInfo['base_player_id'])) {
                $basePlayeInfo = $playerInfo;
                $basePlayeInfo['team_ryaku_name'] = $teamCode;
                $basePlayerEntity = $this->BasePlayers->newEntity($basePlayeInfo);
                $this->BasePlayers->save($basePlayerEntity);
                $playerInfo['base_player_id'] = $basePlayerEntity->id;
            } else {
                // 情報の更新
                $basePlayerEntity = $this->BasePlayers->get($playerInfo['base_player_id']);
                $basePlayerEntity = $this->BasePlayers->patchEntity($basePlayerEntity, $playerInfo);
                $basePlayerEntity->team_ryaku_name = $teamCode;
                $this->BasePlayers->save($basePlayerEntity);
            }
            $playerInfo['team_id'] = $teamLists[$teamCode];
            $playerEntity = $this->newEntity($playerInfo);
            if (!$this->Save($playerEntity, ['atomic' => false])) {
                $saveFlag = false;
            }
            $row++;
        }
        return $saveFlag;
    }
    
    public function batterShukei($seasonId)
    {
    	$resultSet = Configure::read('resultSet');

    	$GameResults = TableRegistry::get('GameResults');
    	$shukeiDatas = $GameResults->find('all')
    		->contain('Results')
    		->contain('Games')
    		->select('GameResults.target_player_id')
    		->select(['game_count' => 'count(DISTINCT GameResults.game_id)'])
    		->select(['daseki_count' => 'count(CASE WHEN GameResults.type = 2 THEN 1 ELSE null END)'])
    		->select(['yontama_count' => 'sum(Results.walk_flag::integer)'])
    		->select(['deadball_count' => 'sum(Results.deadball_flag::integer)'])
    		->select(['dasu_count' => 'sum(Results.dasu_flag::integer)'])
    		->select(['hit_count' => 'sum(Results.hit_flag::integer)'])
    		->select(['base2_count' => 'sum(Results.base2_flag::integer)'])
    		->select(['base3_count' => 'sum(Results.base3_flag::integer)'])
    		->select(['hr_count' => 'sum(Results.hr_flag::integer)'])
    		->select(['sansin_count' => 'sum(Results.sansin_flag::integer)'])
    		->select(['bant_count' => 'sum(Results.bant_flag::integer)'])
    		->select(['sacrifice_fly_count' => 'sum(Results.sacrifice_fly_flag::integer)'])
    		->select(['heisatsu_count' => 'sum(Results.heisatsu_flag::integer)'])
    		->select(['rbi_count' => 'sum(GameResults.point)'])
    		->select(['steal_count' => 'count(CASE WHEN GameResults.type = 3 AND GameResults.out_num = 0 THEN 1 ELSE null END)'])
    		->group('GameResults.target_player_id')
    		->where(['GameResults.target_player_id IS NOT' => null])
    	;
    	if (!is_null($seasonId)) {
    		$shukeiDatas->where(['Games.season_id' => $seasonId]);
    	}
    	foreach ($shukeiDatas as $shukeiData) {
    		$playerInfo = $this->get($shukeiData->target_player_id);
    		$playerInfo->yashu_game = $shukeiData->game_count;
    		$playerInfo->daseki = $shukeiData->daseki_count;
    		if ($shukeiData->dasu_count > 0) {
    			$playerInfo->avg = $shukeiData->hit_count / $shukeiData->dasu_count;
    		} else {
    			$playerInfo->avg = 0;
    		}
    		$playerInfo->dasu = $shukeiData->dasu_count;
    		$playerInfo->hit = $shukeiData->hit_count;
    		$playerInfo->base2 = $shukeiData->base2_count;
    		$playerInfo->base3 = $shukeiData->base3_count;
    		$playerInfo->walk = $shukeiData->yontama_count;
    		$playerInfo->deadball = $shukeiData->deadball_count;
    		$playerInfo->hr = $shukeiData->hr_count;
    		$playerInfo->rbi = $shukeiData->rbi_count;
    		$playerInfo->sansin = $shukeiData->sansin_count;
    		$playerInfo->steal = $shukeiData->steal_count;
    		$playerInfo->bant = $shukeiData->bant_count;
    		$playerInfo->heisatsu = $shukeiData->heisatsu_count;
    		$playerInfo->sacrifice_fly = $shukeiData->sacrifice_fly_count;
    		$this->save($playerInfo);
    	}
    }
    private function resultCase($conditions)
    {
    	foreach ($conditions as $k => $v) {
    		$conditions[$k] = '\'' . $v . '\'';
    	}
        return 'count(CASE WHEN (GameResults.type = 2 AND GameResults.result IN (' . implode(',' , $conditions) . ')) THEN 1 ELSE null END)';
    }
    public function pitcherShukei($seasonId)
    {
    	$GamePitcherResults = TableRegistry::get('GamePitcherResults');
    	$GameResults = TableRegistry::get('GameResults');
    	$resultDatas = $GamePitcherResults->find('all')
    		->contain('Games')
    		->select('pitcher_id')
    		->select(['game_count' => 'count(GamePitcherResults.id)'])
    		->select(['win_count' => 'count(CASE WHEN GamePitcherResults.win = true THEN 1 ELSE null END)'])
    		->select(['lose_count' => 'count(CASE WHEN GamePitcherResults.lose = true THEN 1 ELSE null END)'])
    		->select(['save_count' => 'count(CASE WHEN GamePitcherResults.save = true THEN 1 ELSE null END)'])
    		->select(['hold_count' => 'count(CASE WHEN GamePitcherResults.hold = true THEN 1 ELSE null END)'])
    		->select(['kanto_count' => 'count(CASE WHEN 
    			NOT EXISTS(
    				SELECT 1 FROM game_pitcher_results as OtherGamePitcherResults WHERE GamePitcherResults.game_id = OtherGamePitcherResults.game_id AND GamePitcherResults.team_id = OtherGamePitcherResults.team_id AND GamePitcherResults.pitcher_id != OtherGamePitcherResults.pitcher_id
    			)
    		 THEN 1 ELSE null END)'])
    		->select(['kanpu_count' => 'count(CASE WHEN 
    			NOT EXISTS(
    				SELECT 1 FROM game_pitcher_results as OtherGamePitcherResults WHERE GamePitcherResults.game_id = OtherGamePitcherResults.game_id AND GamePitcherResults.team_id = OtherGamePitcherResults.team_id AND GamePitcherResults.pitcher_id != OtherGamePitcherResults.pitcher_id
    			)
    			AND 
    			(
    				(
    				Games.home_team_id = GamePitcherResults.team_id
    					AND
    				Games.home_point > 0
    					AND
    				Games.visitor_point = 0
    				)
    				OR
    				(
    				Games.visitor_team_id = GamePitcherResults.team_id
    					AND
    				Games.home_point = 0
    					AND
    				Games.visitor_point > 0
    				)
    			)
    		 THEN 1 ELSE null END)'])
    		->select(['jiseki_count' => 'sum(GamePitcherResults.jiseki)'])
    		->group('GamePitcherResults.pitcher_id')
    		;
    	
    	if (!is_null($seasonId)) {
    		$resultDatas->where(['Games.season_id' => $seasonId]);
    	}
    	
    	
    	//加工しやすいように
    	$inningDatas = $GameResults->find('all')
    		->select('pitcher_id')
    		->contain('Results')
    		->contain('Games')
    		->select(['inning' => 'sum(GameResults.out_num)'])
    		->select(['sansin_count' => 'sum(Results.sansin_flag::integer)'])
    		->select(['dasu_count' => 'sum(Results.dasu_flag::integer)'])
    		->select(['hit_count' => 'sum(Results.hit_flag::integer)'])
    		->select(['hr_count' => 'sum(Results.hr_flag::integer)'])
    		->select(['walk_count' => 'sum(Results.walk_flag::integer)'])
    		
    		->group('GameResults.pitcher_id')
    		;

    	if (!is_null($seasonId)) {
    		$inningDatas->where(['Games.season_id' => $seasonId]);
    	}


    	$inningInfos = [];
    	foreach ($inningDatas as $inningData) {
    		$inningInfos[$inningData->pitcher_id] = [
    			'inning' => $inningData->inning,
    			'sansin' => $inningData->sansin_count,
    			'p_dasu' => $inningData->dasu_count,
    			'p_hit' => $inningData->hit_count,
    			'p_hr' => $inningData->hr_count,
    			'p_walk' => $inningData->walk_count,
    		];
    	}
    	// データの保存
    	foreach ($resultDatas as $resultData) {
    		$player_id = $resultData->pitcher_id;
    		$playerInfo = $this->get($player_id);
    		$playerInfo->game = $resultData->game_count;
    		$playerInfo->win = $resultData->win_count;
    		if ($inningInfos[$player_id]['inning'] > 0) {
    			$playerInfo->era = $resultData->jiseki_count / $inningInfos[$player_id]['inning'] * 27;
    		} else {
    			$playerInfo->era = 0;
    		}
    		if ($resultData->win_count > 0) {
    			$playerInfo->win_ratio = $resultData->win_count / ($resultData->win_count + $resultData->lose_count);
    		} else {
    			$playerInfo->win_ratio = 0;
    		}
    		$playerInfo->lose = $resultData->lose_count;
    		$playerInfo->save = $resultData->save_count;
    		$playerInfo->hold = $resultData->hold_count;
    		$playerInfo->jiseki = $resultData->jiseki_count;
    		$playerInfo->inning = $inningInfos[$player_id]['inning'];
    		$playerInfo->get_sansin = $inningInfos[$player_id]['sansin'];
    		$playerInfo->p_dasu = $inningInfos[$player_id]['p_dasu'];
    		$playerInfo->p_hit = $inningInfos[$player_id]['p_hit'];
    		$playerInfo->p_hr = $inningInfos[$player_id]['p_hr'];
    		$playerInfo->p_walk = $inningInfos[$player_id]['p_walk'];
    		$playerInfo->kanto = $resultData->kanto_count;
    		$playerInfo->kanpu = $resultData->kanpu_count;
    		
    		$this->save($playerInfo);
    	}
    }
    
    public function rankingAvg($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// 規定打席
    		->where(['Players.dasu >' => 0])
    		->where('Teams.game::numeric * 3.1 <= Players.daseki')
    		->order(['CASE WHEN Players.dasu = 0 OR Players.dasu IS NULL THEN 0::numeric ELSE Players.hit::numeric / Players.dasu::numeric END' => 'DESC'])
    		->limit($limit);
    }
    
    public function rankingHr($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.hr IS NOT' => null])
    		->where(['Players.hr >' => 0])
    		->order(['Players.hr' => 'DESC'])
    		->limit($limit);
    }
    
    public function rankingRbi($seasonId, $limit =5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.rbi IS NOT' => null])
    		->where(['Players.rbi >' => 0])
    		->order(['Players.rbi' => 'DESC'])
    		->limit($limit);
    }
    
    public function rankingHit($seasonId, $limit =5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.hit IS NOT' => null])
    		->where(['Players.hit >' => 0])
    		->order(['Players.hit' => 'DESC'])
    		->limit($limit);
    }
    
    
    public function rankingSteal($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.steal >' => 0])
    		->where(['Players.steal IS NOT' => null])
    		->order(['Players.steal' => 'DESC'])
    		->limit($limit);
    }
    
    public function rankingSansin($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.sansin >' => 0])
    		->where(['Players.sansin IS NOT' => null])
    		->order(['Players.sansin' => 'DESC'])
    		->limit($limit);
    }
    
    public function rankingEra($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// 規定投球回数
    		->where(['Players.inning >' => 0])
    		->where('Teams.game::numeric * 3 <= Players.inning')
    		->order(['CASE WHEN Players.inning = 0 OR Players.inning IS NULL THEN 99::numeric ELSE Players.jiseki::numeric / Players.inning::numeric END' => 'ASC'])
    		->limit($limit);
    }
    
    public function rankingWin($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.win >' => 0])
    		->where(['Players.win IS NOT' => null])
    		->order(['Players.win' => 'DESC'])
    		->limit($limit);
    }
    
    public function rankingSave($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.save >' => 0])
    		->where(['Players.save IS NOT' => null])
    		->order(['Players.save' => 'DESC'])
    		->limit($limit);
    }
    
    public function rankingHold($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.hold >' => 0])
    		->where(['Players.hold IS NOT' => null])
    		->order(['Players.hold' => 'DESC'])
    		->limit($limit);
    }
    
    public function rankingGetSansin($seasonId, $limit = 5)
    {
    	return $this->find('all')
    		->contain('Teams')
    		->where(['Teams.season_id' => $seasonId])
    		// nullは省かないとめんどくさい
    		->where(['Players.get_sansin >' => 0])
    		->where(['Players.get_sansin IS NOT' => null])
    		->order(['Players.get_sansin' => 'DESC'])
    		->limit($limit);
    }
    
    public function changePlayerLists($game_id, $team_id)
    {
    	$this->GameMembers = TableRegistry::get('GameMembers');
        // すでに出場済みの選手
        $notListPlayers = $this->GameMembers->find('list', ['valueField' => 'player_id'])
        	->where(['GameMembers.team_id' => $team_id])
            ->where(['GameMembers.game_id' => $game_id])
            // DH解除を除く
            ->where(['GameMembers.player_id IS NOT' => null])
        	->toArray()
        	;
        // 交代候補選手
        return $pinchHitterLists = $this->find('all')
        	->where(['Players.team_id' => $team_id])
        	->where(['Players.id NOT IN' => $notListPlayers])
        	->order(['Players.id' => 'ASC'])
        ;
    }
    
    public function getKings($seasonId, $field)
    {
        return $this->find()
            ->contain('Teams')
            ->where(['Teams.season_id' => $seasonId])
            ->where($this->aliasField($field) . ' = (SELECT max(compare_players.' . $field . ') FROM players AS compare_players LEFT JOIN teams as compare_teams ON compare_players.team_id = compare_teams.id WHERE compare_teams.season_id = ' . $seasonId . ')');
    }
    
    public function getAvgKings($seasonId)
    {
        return $this->find()
            ->contain('Teams')
            ->where(['Teams.season_id' => $seasonId])
            ->where($this->aliasField('daseki') . ' >= Teams.game * 3.1')
            ->where($this->aliasField('avg') . ' = (SELECT max(compare_players.avg) FROM players AS compare_players LEFT JOIN teams as compare_teams ON compare_players.team_id = compare_teams.id WHERE compare_teams.season_id = ' . $seasonId . ' AND compare_players.daseki >= compare_teams.game * 3.1)');
    }
    
    public function getEraKings($seasonId)
    {
        return $this->find()
            ->contain('Teams')
            ->where(['Teams.season_id' => $seasonId])
            ->where($this->aliasField('inning') . ' >= Teams.game * 3')
            ->where($this->aliasField('era') . ' = (SELECT min(compare_players.era) FROM players AS compare_players LEFT JOIN teams as compare_teams ON compare_players.team_id = compare_teams.id WHERE compare_teams.season_id = ' . $seasonId . ' AND compare_players.inning >= compare_teams.game * 3)');
    }
    
    public function getWinRatioKings($seasonId)
    {
        return $this->find()
            ->contain('Teams')
            ->where(['Teams.season_id' => $seasonId])
            ->where($this->aliasField('win') . ' >= 13')
            ->where($this->aliasField('win_ratio') . ' = (SELECT max(compare_players.win_ratio) FROM players AS compare_players LEFT JOIN teams as compare_teams ON compare_players.team_id = compare_teams.id WHERE compare_teams.season_id = ' . $seasonId . ' AND compare_players.win >= 13)');
    }
}
