<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GameResults Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Games
 * @property \Cake\ORM\Association\BelongsTo $Batters
 * @property \Cake\ORM\Association\BelongsTo $Pitchers
 *
 * @method \App\Model\Entity\GameResult get($primaryKey, $options = [])
 * @method \App\Model\Entity\GameResult newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GameResult[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GameResult|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GameResult patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GameResult[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GameResult findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GameResultsTable extends Table
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

        $this->table('game_results');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id'
        ]);
        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id'
        ]);
        $this->belongsTo('Results', [
            'foreignKey' => 'result_id'
        ]);
        $this->belongsTo('Batters', [
            'className' => 'Players',
            'foreignKey' => 'target_player_id'
        ]);
        $this->belongsTo('TargetPlayers', [
            'className' => 'Players',
            'foreignKey' => 'target_player_id'
        ]);
        $this->belongsTo('Pitchers', [
            'className' => 'Players',
            'foreignKey' => 'pitcher_id'
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

        return $rules;
    }
}
