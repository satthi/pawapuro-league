<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GameInnings Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Games
 *
 * @method \App\Model\Entity\GameInning get($primaryKey, $options = [])
 * @method \App\Model\Entity\GameInning newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GameInning[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GameInning|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GameInning patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GameInning[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GameInning findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GameInningsTable extends Table
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

        $this->table('game_innings');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id'
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
            ->integer('inning')
            ->allowEmpty('inning');

        $validator
            ->integer('omote_ura')
            ->allowEmpty('omote_ura');

        $validator
            ->integer('hit')
            ->allowEmpty('hit');

        $validator
            ->integer('point')
            ->allowEmpty('point');

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
        $rules->add($rules->existsIn(['game_id'], 'Games'));

        return $rules;
    }
}
