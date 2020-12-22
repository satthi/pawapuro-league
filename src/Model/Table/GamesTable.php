<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use PhpExcelWrapper\PhpExcelWrapper;
use Cake\I18n\FrozenDate;

/**
 * Games Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Seasons
 * @property \Cake\ORM\Association\BelongsTo $HomeTeams
 * @property \Cake\ORM\Association\BelongsTo $VisitorTeams
 * @property \Cake\ORM\Association\HasMany $GameInnings
 * @property \Cake\ORM\Association\HasMany $GameMembers
 * @property \Cake\ORM\Association\HasMany $GameResults
 *
 * @method \App\Model\Entity\Game get($primaryKey, $options = [])
 * @method \App\Model\Entity\Game newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Game[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Game|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Game patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Game[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Game findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GamesTable extends Table
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

        $this->table('games');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Seasons', [
            'foreignKey' => 'season_id'
        ]);
        $this->belongsTo('HomeTeams', [
            'className' => 'Teams',
            'foreignKey' => 'home_team_id'
        ]);
        $this->belongsTo('VisitorTeams', [
            'className' => 'Teams',
            'foreignKey' => 'visitor_team_id'
        ]);
        $this->hasMany('GameInnings', [
            'foreignKey' => 'game_id'
        ]);
        $this->hasMany('GameMembers', [
            'foreignKey' => 'game_id'
        ]);
        $this->hasMany('GameResults', [
            'foreignKey' => 'game_id'
        ]);
        $this->belongsTo('WinPitchers', [
            'className' => 'Players',
            'foreignKey' => 'win_pitcher_id'
        ]);
        $this->belongsTo('LosePitchers', [
            'className' => 'Players',
            'foreignKey' => 'lose_pitcher_id'
        ]);
        $this->belongsTo('SavePitchers', [
            'className' => 'Players',
            'foreignKey' => 'save_pitcher_id'
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
            ->date('date')
            ->allowEmpty('date');

        $validator
            ->integer('home_point')
            ->allowEmpty('home_point');

        $validator
            ->integer('visitor_point')
            ->allowEmpty('visitor_point');

        $validator
            ->boolean('deleted')
            ->allowEmpty('deleted');

        $validator
            ->dateTime('deleted_date')
            ->allowEmpty('deleted_date');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['season_id'], 'Seasons'));
        $rules->add($rules->existsIn(['home_team_id'], 'HomeTeams'));
        $rules->add($rules->existsIn(['visitor_team_id'], 'VisitorTeams'));

        return $rules;
    }
    
    public function adds($seasonId, $teamLists, $data)
    {
        $baseDate = new FrozenDate('1900-01-01');
        $PhpExcelWrapper = new PhpExcelWrapper($data['game_excel']['tmp_name']);
        $saveFlag = true;
        $row = 2;
        while(true) {
            $date = $PhpExcelWrapper->getVal(0, $row);
            if ($date == '') {
                break;
            }
            $checkDate = $baseDate->addDays($date - 2);
            for ($i =1; $i <= 3;$i++){
                $home_team = $PhpExcelWrapper->getVal(3 * $i - 2, $row);
                $visitor_team = $PhpExcelWrapper->getVal(3 * $i - 1, $row);
                $dh_flag = $PhpExcelWrapper->getVal(3 * $i, $row);
                if ($home_team == '' || $visitor_team == '') {
                    continue;
                }
                $gameInfo = [];
                $gameInfo['season_id'] = $seasonId;
                $gameInfo['date'] = $checkDate;
                $gameInfo['home_team_id'] = $teamLists[$home_team];
                $gameInfo['visitor_team_id'] = $teamLists[$visitor_team];
                $gameInfo['dh_flag'] = $dh_flag == '○';
                $gameInfo['status'] = 0;
                $gameEntity = $this->newEntity($gameInfo);
                if (!$this->Save($gameEntity, ['atomic' => false])) {
                    $saveFlag = false;
                }
            }
            $row++;
        }
        return $saveFlag;
    }
    
    public function vsTeam($id)
    {
        $shukei = $this->find('all')
        	->select('Games.home_team_id')
        	->select('Games.visitor_team_id')
        	->select(['home_win' => 'count(CASE WHEN home_point > visitor_point AND status = 99 THEN 1 ELSE null END)'])
        	->select(['visitor_win' => 'count(CASE WHEN home_point < visitor_point AND status = 99 THEN 1 ELSE null END)'])
        	->select(['draw' => 'count(CASE WHEN home_point = visitor_point AND status = 99 THEN 1 ELSE null END)'])
        	->select(['remain' => 'count(CASE WHEN status != 99 THEN 1 ELSE null END)'])
        	->where(['Games.season_id' => $id])
        	->group('Games.home_team_id')
        	->group('Games.visitor_team_id')
        	;
        // ®Œ`
        $shukei_seikei = [];
        foreach ($shukei as $a) {
            // home
            if (empty($shukei_seikei[$a->home_team_id][$a->visitor_team_id])) {
            	$shukei_seikei[$a->home_team_id][$a->visitor_team_id] = [
            		'win' => 0,
            		'lose' => 0,
            		'draw' => 0,
            		'remain' => 0,
            	];
            }
            $shukei_seikei[$a->home_team_id][$a->visitor_team_id]['win'] += $a->home_win;
            $shukei_seikei[$a->home_team_id][$a->visitor_team_id]['lose'] += $a->visitor_win;
            $shukei_seikei[$a->home_team_id][$a->visitor_team_id]['draw'] += $a->draw;
            $shukei_seikei[$a->home_team_id][$a->visitor_team_id]['remain'] += $a->remain;
            
            if (empty($shukei_seikei[$a->visitor_team_id][$a->home_team_id])) {
            	$shukei_seikei[$a->visitor_team_id][$a->home_team_id] = [
            		'win' => 0,
            		'lose' => 0,
            		'draw' => 0,
            		'remain' => 0,
            	];
            }
            $shukei_seikei[$a->visitor_team_id][$a->home_team_id]['win'] += $a->visitor_win;
            $shukei_seikei[$a->visitor_team_id][$a->home_team_id]['lose'] += $a->home_win;
            $shukei_seikei[$a->visitor_team_id][$a->home_team_id]['draw'] += $a->draw;
            $shukei_seikei[$a->visitor_team_id][$a->home_team_id]['remain'] += $a->remain;
        }
        return $shukei_seikei;
    }

    public function vsTeamDetail($rowTeamId, $colTeamId)
    {
        $gameResults = $this->find()
            ->where([
                'OR' => [
                    [
                        'home_team_id' => $rowTeamId,
                        'visitor_team_id' => $colTeamId,
                    ],
                    [
                        'home_team_id' => $colTeamId,
                        'visitor_team_id' => $rowTeamId,
                    ],
                ],
            ])
            ->order(['date' => 'ASC'])
            ->contain([
                'WinPitchers',
                'LosePitchers',
                'SavePitchers',
            ]);
        // ここから集計
        $shukei = [
            'game' => 0,
            'win' => 0,
            'lose' => 0,
            'draw' => 0,
            'tokuten' => 0,
            'shitten' => 0,
        ];
        
        foreach ($gameResults as $gameResult) {
            if ($gameResult->status != 99) {
                continue;
            }
            $shukei['game']++;
			if (
				(
					$rowTeamId == $gameResult->home_team_id &&
					$gameResult->home_point > $gameResult->visitor_point 
				) ||
				(
					$rowTeamId == $gameResult->visitor_team_id &&
					$gameResult->home_point < $gameResult->visitor_point 
				)
				
			){
            	$shukei['win']++;
			} elseif (
				(
					$rowTeamId == $gameResult->home_team_id &&
					$gameResult->home_point < $gameResult->visitor_point 
				) ||
				(
					$rowTeamId == $gameResult->visitor_team_id &&
					$gameResult->home_point > $gameResult->visitor_point 
				)
				
			) {
            	$shukei['lose']++;
            } else {
            	$shukei['draw']++;
            }
            
            if ($rowTeamId == $gameResult->home_team_id) {
                $shukei['tokuten'] += $gameResult->home_point;
                $shukei['shitten'] += $gameResult->visitor_point;
            } else {
                $shukei['tokuten'] += $gameResult->visitor_point;
                $shukei['shitten'] += $gameResult->home_point;
            }
        }
        
        $shukei2 = TableRegistry::get('GameResults')->find()
    		->contain('Results')
    		->contain('Batters')
    		->contain('Pitchers')
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
    		->group('Batters.team_id')
    		->where(['GameResults.target_player_id IS NOT' => null])
    		->where(['Batters.team_id' => $rowTeamId])
    		->where(['Pitchers.team_id' => $colTeamId])
    		->firstOrFail()
    	;

        $shukei3 = TableRegistry::get('GameResults')->find()
    		->contain('Results')
    		->contain('Batters')
    		->contain('Pitchers')
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
    		->select(['inning_count' => 'sum(GameResults.out_num)'])
    		->group('Batters.team_id')
    		->where(['GameResults.target_player_id IS NOT' => null])
    		->where(['Batters.team_id' => $colTeamId])
    		->where(['Pitchers.team_id' => $rowTeamId])
    		->firstOrFail()
    	;

    	$shukei4 = TableRegistry::get('GamePitcherResults')->find('all')
    		->contain('Games')
    		->contain('Pitchers')
    		->select(['jiseki_count' => 'sum(GamePitcherResults.jiseki)'])
    		->where(['OR' => [
    				'Games.home_team_id' => $colTeamId,
    				'Games.visitor_team_id' => $colTeamId,
    		]])
    		->where(['Pitchers.team_id' => $rowTeamId])
    		->group('Pitchers.team_id')
    		->first()
    		;

        return [
            'gameResults' => $gameResults,
            'shukei' => $shukei,
            'shukei2' => $shukei2,
            'shukei3' => $shukei3,
            'shukei4' => $shukei4,
        ];
//        debug($gameResults->all());
//        exit;
        
    }
    
    public function gameNextInning($gameId)
    {
        // ƒQ[ƒ€î•ñ‚ÌŽæ“¾
        $gameInfo = $this->get($gameId, [
            'contain' => [
                'HomeTeams', 'VisitorTeams'
            ]
        ]);
        // ‚±‚Ìó‘Ô‚Å‚±‚±‚Í—ˆ‚Ä‚Í‚¢‚¯‚È‚¢
        if ($gameInfo->status != 0 && $gameInfo->out_num != 3) {
            exit;
        }
        $inning = $gameInfo->status;
        // ƒCƒjƒ“ƒO‚ÌXV
        $gameInfo->status++;
        $gameInfo->out_num = 0;;
        $this->save($gameInfo);
        
        $this->GameInnings = TableRegistry::get('GameInnings');
        // ƒCƒjƒ“ƒOî•ñ‚ÌXV(‚Í‚¶‚ßˆÈŠO)
        if ($inning != 0) {
	        $inningInfo = $this->GameInnings->find('all')
	            ->where(['GameInnings.game_id' => $gameId])
	            ->where(['GameInnings.inning' => $inning])
	            ->first()
	            ;
	        if (is_null($inningInfo->point)) {
	            $inningInfo->point = 0;
	            $this->GameInnings->save($inningInfo);
	        }
        }
    }
    
    public function gameInfoUpdate($gameId, $point, $outNum, $inning, $hitFlag)
    {
        // ƒQ[ƒ€î•ñ‚ÌŽæ“¾
        $gameInfo = $this->get($gameId, [
            'contain' => [
                'HomeTeams', 'VisitorTeams'
            ]
        ]);
        
        // ¡‚ÌƒCƒjƒ“ƒO‚ðŽæ“¾
        // ŽŽ‡ŠJŽn‘O‚Í1‰ñ•\‚ÆŽæ‚èˆµ‚¤
        
        if ($gameInfo->status % 2 == 1) {
            $gameInfo->visitor_point += $point;
        } else {
            $gameInfo->home_point += $point;
        }
        
        $gameInfo->out_num += $outNum;
        
        $this->save($gameInfo);
        $this->GameInnings = TableRegistry::get('GameInnings');
        // ƒCƒjƒ“ƒOî•ñ‚ÌXV
        $inningInfo = $this->GameInnings->find('all')
            ->where(['GameInnings.game_id' => $gameId])
            ->where(['GameInnings.inning' => $inning])
            ->first()
            ;
        
        if (empty($inningInfo)) {
            $inningInfo = $this->GameInnings->newEntity();
            $inningInfo->game_id = $gameId;
            $inningInfo->inning = $inning;
        }
        
        if ($hitFlag == true) {
            $inningInfo->hit++;
        }
        
        if ($point > 0) {
            $inningInfo->point += $point;
        }
        
        if ($gameInfo->out_num == 3 && is_null($inningInfo->point)) {
            $inningInfo->point = 0;
        }
        $this->GameInnings->save($inningInfo);
    }
}
