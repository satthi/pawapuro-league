<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Teams Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Seasons
 *
 * @method \App\Model\Entity\Team get($primaryKey, $options = [])
 * @method \App\Model\Entity\Team newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Team[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Team|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Team patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Team[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Team findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TeamsTable extends Table
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

        $this->table('teams');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Seasons', [
            'foreignKey' => 'season_id'
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
            ->allowEmpty('ryaku_name');

        $validator
            ->integer('game')
            ->allowEmpty('game');

        $validator
            ->integer('win')
            ->allowEmpty('win');

        $validator
            ->integer('lose')
            ->allowEmpty('lose');

        $validator
            ->integer('draw')
            ->allowEmpty('draw');

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

        return $rules;
    }
    
    public function teamLists($seasonId, $vsTeam)
    {
        $teams = $this->find('all')
            ->where(['Teams.season_id' => $seasonId])
            ->select($this)
            ->select(['dasu' => '(SELECT sum(players.dasu) FROM players WHERE players.team_id = Teams.id)'])
            ->select(['hit' => '(SELECT sum(players.hit) FROM players WHERE players.team_id = Teams.id)'])
            ->select(['inning' => '(SELECT sum(players.inning) FROM players WHERE players.team_id = Teams.id)'])
            ->select(['jiseki' => '(SELECT sum(players.jiseki) FROM players WHERE players.team_id = Teams.id)'])
            ->select(['hr' => '(SELECT sum(players.hr) FROM players WHERE players.team_id = Teams.id)'])
            ->select(['point' => 'COALESCE((SELECT sum(games.home_point) FROM games WHERE games.home_team_id = Teams.id),0) + COALESCE((SELECT sum(games.visitor_point) FROM games WHERE games.visitor_team_id = Teams.id),0)'])
            ->select(['loss' => 'COALESCE((SELECT sum(games.visitor_point) FROM games WHERE games.home_team_id = Teams.id),0) + COALESCE((SELECT sum(games.home_point) FROM games WHERE games.visitor_team_id = Teams.id),0)'])
            ->order(['CASE WHEN Teams.win = 0 OR Teams.win IS NULL THEN 0::numeric ELSE Teams.win::numeric / (Teams.win::numeric + Teams.lose::numeric) END' => 'DESC'])
            ->order(['Teams.win' => 'DESC'])
            ->order(['Teams.lose' => 'ASC'])
        ;
        
        // ここから優勝条件などのチェック
        $checkTeams = [];
        foreach ($teams as $team) {
            $checkTeams[$team->id] = $team;
        }
        foreach ($checkTeams as $checkTeam) {
            $championPossible = true;
            $championFix = true;
            $championOneself = true;
            $magicCheck = true;
            // マジックナンバー算出用
            $maxTargetRatio = 0;
            foreach ($vsTeam as $vsTeamId => $vsTeamParts) {
                if ($vsTeamId == $checkTeam->id) continue;
                $targetTeamInfo = $checkTeam;
                $vsTeamInfo = $checkTeams[$vsTeamId];

                $remainDirectGame = $vsTeamParts[$checkTeam->id]['remain'];
                // 優勝の可能性があるか(自身が全勝して対象が全敗した時に上回れるか
                if (
                    ($targetTeamInfo->win + $targetTeamInfo->remain) / ($targetTeamInfo->win + $targetTeamInfo->lose + $targetTeamInfo->remain)
                    <
                    ($vsTeamInfo->win) / ($vsTeamInfo->win + $vsTeamInfo->lose + $vsTeamInfo->remain)
                ) {
                    $championPossible = false;
                }
                // 優勝しない可能性があるか(自身が全敗して対象が全勝した時に下回るか)
                if (
                    ($targetTeamInfo->win) / ($targetTeamInfo->win + $targetTeamInfo->lose + $targetTeamInfo->remain)
                    <
                    ($vsTeamInfo->win + $vsTeamInfo->remain) / ($vsTeamInfo->win + $vsTeamInfo->lose + $vsTeamInfo->remain)
                ) {
                    $championFix = false;
                }
                // 自力優勝の可能性があるか(自身が全勝して対象が直接対決以外全勝した時に上回れるか
                if (
                    ($targetTeamInfo->win + $targetTeamInfo->remain) / ($targetTeamInfo->win + $targetTeamInfo->lose + $targetTeamInfo->remain)
                    <
                    ($vsTeamInfo->win + $vsTeamInfo->remain - $remainDirectGame) / ($vsTeamInfo->win + $vsTeamInfo->lose + $vsTeamInfo->remain)
                ) {
                    $championOneself = false;
                }
                // マジックが点灯しないか(自身が直接対決以外全勝して対象が全勝した時に下回るか
                if (
                    ($targetTeamInfo->win + $targetTeamInfo->remain - $remainDirectGame) / ($targetTeamInfo->win + $targetTeamInfo->lose + $targetTeamInfo->remain)
                    <=
                    ($vsTeamInfo->win + $vsTeamInfo->remain) / ($vsTeamInfo->win + $vsTeamInfo->lose + $vsTeamInfo->remain)
                ) {
                    $magicCheck = false;
                }
                $targetRatio = ($vsTeamInfo->win + $vsTeamInfo->remain) / ($vsTeamInfo->win + $vsTeamInfo->lose + $vsTeamInfo->remain);
                //他チームの最大勝率
                if ($maxTargetRatio < $targetRatio) {
                    $maxTargetRatio = $targetRatio;
                }
            }
            // マジックナンバー(ついてなくても計算はする)
            $magicNo = 0;
            while(true) {
                //他チームの最大勝率を上回るまで回す
                if (
                    ($targetTeamInfo->win + $magicNo) / ($targetTeamInfo->win + $targetTeamInfo->lose + $targetTeamInfo->remain)
                    >
                    $maxTargetRatio
                ) {
                    break;
                }
                $magicNo++;
            }
            $checkTeam->championPossible = $championPossible;
            $checkTeam->championFix = $championFix;
            $checkTeam->championOneself = $championOneself;
            $checkTeam->magicCheck = $magicCheck;
            $checkTeam->magicNo = $magicNo;
        }
        return $checkTeams;
    }
    
    public function adds($seasonId, $data)
    {
        // Teamの登録
        $saveFlag = true;
        $teamLists = [];
        foreach ($data['Teams'] as $team) {
            if (empty($team['name'])) {
                continue;
            }
            $team['season_id'] = $seasonId;
            $teamEntity = $this->newEntity($team);
            if (!$this->save($teamEntity, ['atomic' => false])) {
                $saveFlag = false;
            }
        }
        return $saveFlag;
    }
    
    public function getTeamLists($seasonId)
    {
        return $this->find('list', [
            'keyField' => 'ryaku_name',
            'valueField' => 'id',
        ])
        ->where(['Teams.season_id' => $seasonId])
        ;
    }
    
    public function teamShukei($seasonId)
    {
        $Games = TableRegistry::get('Games');
        // homegameとvisitorgameでそれぞれ集計して合算する
        $homegameShukeis = $Games->find('all')
            ->select('Games.home_team_id')
            ->select(['game' => 'count(CASE WHEN Games.status = 99 THEN 1 ELSE null END)'])
            ->select(['win' => 'count(CASE WHEN Games.home_point > Games.visitor_point AND Games.status = 99 THEN 1 ELSE null END)'])
            ->select(['lose' => 'count(CASE WHEN Games.home_point < Games.visitor_point AND Games.status = 99 THEN 1 ELSE null END)'])
            ->select(['draw' => 'count(CASE WHEN Games.home_point = Games.visitor_point AND Games.status = 99 THEN 1 ELSE null END)'])
            ->select(['remain' => 'count(CASE WHEN Games.status != 99 THEN 1 ELSE null END)'])
            ->group('Games.home_team_id')
            ;

    	if (!is_null($seasonId)) {
    		$homegameShukeis->where(['Games.season_id' => $seasonId]);
    	}


        $visitorShukeis = $Games->find('all')
            ->select('Games.visitor_team_id')
            ->select(['game' => 'count(CASE WHEN Games.status = 99 THEN 1 ELSE null END)'])
            ->select(['win' => 'count(CASE WHEN Games.home_point < Games.visitor_point AND Games.status = 99 THEN 1 ELSE null END)'])
            ->select(['lose' => 'count(CASE WHEN Games.home_point > Games.visitor_point AND Games.status = 99 THEN 1 ELSE null END)'])
            ->select(['draw' => 'count(CASE WHEN Games.home_point = Games.visitor_point AND Games.status = 99 THEN 1 ELSE null END)'])
            ->select(['remain' => 'count(CASE WHEN Games.status != 99 THEN 1 ELSE null END)'])
            ->group('Games.visitor_team_id')
            ;
    	if (!is_null($seasonId)) {
    		$visitorShukeis->where(['Games.season_id' => $seasonId]);
    	}



        $shukei = [];
        foreach ($homegameShukeis as $homegameShukei) {
            $shukei[$homegameShukei->home_team_id] = [
                'game' => $homegameShukei->game,
                'win' => $homegameShukei->win,
                'lose' => $homegameShukei->lose,
                'draw' => $homegameShukei->draw,
                'remain' => $homegameShukei->remain,
            ];
        }
        foreach ($visitorShukeis as $visitorShukei) {
            if (empty($shukei[$visitorShukei->visitor_team_id])){
                $shukei[$visitorShukei->visitor_team_id] = [
                    'game' => $visitorShukei->game,
                    'win' => $visitorShukei->win,
                    'lose' => $visitorShukei->lose,
                    'draw' => $visitorShukei->draw,
                    'remain' => $visitorShukei->remain,
                ];
            } else {
                $shukei[$visitorShukei->visitor_team_id] = [
                    'game' => $shukei[$visitorShukei->visitor_team_id]['game'] + $visitorShukei->game,
                    'win' => $shukei[$visitorShukei->visitor_team_id]['win'] +$visitorShukei->win,
                    'lose' => $shukei[$visitorShukei->visitor_team_id]['lose'] +$visitorShukei->lose,
                    'draw' => $shukei[$visitorShukei->visitor_team_id]['draw'] +$visitorShukei->draw,
                    'remain' => $shukei[$visitorShukei->visitor_team_id]['remain'] +$visitorShukei->remain,
                ];
            }
        }
        
        $saveFlag = true;
        foreach ($shukei as $a => $b) {
            $teamInfo = $this->findById($a)->first();
            if (is_null($teamInfo)) {
                continue;
            }
            $teamInfo->game = $b['game'];
            $teamInfo->win = $b['win'];
            $teamInfo->lose = $b['lose'];
            $teamInfo->draw = $b['draw'];
            $teamInfo->remain = $b['remain'];
            if (!$this->save($teamInfo, ['atomic' => false])) {
                $saveFlag =false;
            }
        }
        return $saveFlag;
    }
}
