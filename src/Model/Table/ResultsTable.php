<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Results Model
 *
 * @property \Cake\ORM\Association\HasMany $GameResults
 *
 * @method \App\Model\Entity\Result get($primaryKey, $options = [])
 * @method \App\Model\Entity\Result newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Result[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Result|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Result patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Result[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Result findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResultsTable extends Table
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

        $this->table('results');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('GameResults', [
            'foreignKey' => 'result_id'
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
            ->integer('color_type')
            ->allowEmpty('color_type');

        $validator
            ->integer('position')
            ->allowEmpty('position');

        $validator
            ->integer('out')
            ->allowEmpty('out');

        $validator
            ->boolean('dasu_flag')
            ->allowEmpty('dasu_flag');

        $validator
            ->boolean('hit_flag')
            ->allowEmpty('hit_flag');

        $validator
            ->boolean('base2_flag')
            ->allowEmpty('base2_flag');

        $validator
            ->boolean('base3_flag')
            ->allowEmpty('base3_flag');

        $validator
            ->boolean('hr_flag')
            ->allowEmpty('hr_flag');

        $validator
            ->boolean('point_flag')
            ->allowEmpty('point_flag');

        $validator
            ->boolean('sansin_flag')
            ->allowEmpty('sansin_flag');

        $validator
            ->boolean('walk_flag')
            ->allowEmpty('walk_flag');

        $validator
            ->boolean('deadball_flag')
            ->allowEmpty('deadball_flag');

        $validator
            ->boolean('bant_flag')
            ->allowEmpty('bant_flag');

        $validator
            ->boolean('sacrifice_fly_flag')
            ->allowEmpty('sacrifice_fly_flag');

        $validator
            ->boolean('heisatsu_flag')
            ->allowEmpty('heisatsu_flag');

        $validator
            ->boolean('deleted')
            ->allowEmpty('deleted');

        $validator
            ->dateTime('deleted_date')
            ->allowEmpty('deleted_date');

        return $validator;
    }
    
    public function displaySet()
    {
        $datas = $this->find('all')->order(['id' => 'ASC']);
        $returnLists = [];
        foreach ($datas as $data) {
            $returnLists[$data->position][] = $data;
        }
        return $returnLists;
    }
}
