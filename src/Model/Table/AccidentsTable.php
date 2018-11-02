<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Accidents Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Players
 *
 * @method \App\Model\Entity\Accident get($primaryKey, $options = [])
 * @method \App\Model\Entity\Accident newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Accident[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Accident|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Accident patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Accident[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Accident findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccidentsTable extends Table
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

        $this->table('accidents');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Players', [
            'foreignKey' => 'player_id'
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
            ->date('start_date')
            ->allowEmpty('start_date');

        $validator
            ->date('end_date')
            ->allowEmpty('end_date');

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
        $rules->add($rules->existsIn(['player_id'], 'Players'));

        return $rules;
    }


    public function accidentCheck($gameId)
    {
        // 設定値
        $basePoint = 0.3;
        // 打席数に応じたポイント
        $dasekiPoint = 0.2;
        // イニングに応じたポイント
        $inningPoint = 0.2;

        $rentoCheckDay = 20;
        // 連投に関するポイント
        $rentoPoint = [
            0 => 1,
            1 => 1.1,
            2 => 1.3,
            3 => 1.6,
            4 => 2,
            5 => 2.5,
            6 => 3,
            7 => 4.5,
            8 => 6,
            9 => 8,
            10 => 10,
            11 => 13,
            12 => 16,
            13 => 20,
            14 => 25,
            15 => 30,
            16 => 40,
            17 => 50,
            18 => 70,
            19 => 100,
            20 => 150,
        ];
        // acciednt_type 倍率
        $accidentType = [
            1 => 5,
            2 => 4,
            3 => 3,
            4 => 2,
            5 => 1,
            6 => 0.9,
            7 => 0.7,
            8 => 0.5,
            9 => 0.3,
            10 => 0.1,
        ];


        $accidentLists = [];
        $accidentTypeLists = [];
        $GamesTable = TableRegistry::get('Games');
        $GameMembersTable = TableRegistry::get('GameMembers');
        $GameResultsTable = TableRegistry::get('GameResults');
        $GamePitcherResultsTable = TableRegistry::get('GamePitcherResults');

        $game = $GamesTable->get($gameId);
        $members = $GameMembersTable->findByGameId($gameId)
            ->contain('Players');

        foreach ($members as $member) {
            $accidentLists[$member->player_id] = $basePoint;
            $accidentTypeLists[$member->player_id] = $accidentType[$member->player->accident_type];
        }
        // 打席数
        $gameResults = $GameResultsTable->find()
            ->where(['game_id' => $gameId])
            ->where(['type' => 2]);
        foreach ($gameResults as $gameResult) {
            $accidentLists[$gameResult->target_player_id] += $dasekiPoint;
        }

        $todayGamePitcherResults = $GamePitcherResultsTable->find()
            ->where(['game_id' => $gameId]);

        foreach ($todayGamePitcherResults as $todayGamePitcherResult) {
            // 過去の規定日数内に何日投げていたかのチェック
            $rentoCheck = $GamePitcherResultsTable->find()
                ->contain('Games')
                ->where(['Games.date >=' => $game->date->subday($rentoCheckDay)])
                ->where(['Games.date <' => $game->date])
                ->where(['pitcher_id' => $todayGamePitcherResult->pitcher_id])
                ->count();

            $accidentLists[$todayGamePitcherResult->pitcher_id] += $todayGamePitcherResult->inning * $inningPoint * $rentoPoint[$rentoCheck];
        }


        // 怪我ちぇーっく

        foreach ($accidentLists as $playerId => $accidentList) {
            $rand = rand(0, 10000)/100;
            if ($accidentList >= $rand) {
                $accidentEntity = $this->newEntity();
                $accidentEntity->player_id = $playerId;
                $accidentEntity->start_date = $game->date;
                $accidentEntity->end_date = $game->date->addDay(rand(3, 60));
                $this->save($accidentEntity);
            }
        }

        // return $accidentResults;

        // 投球回数
        // debug($accidentLists);
        // exit;
    }
}
