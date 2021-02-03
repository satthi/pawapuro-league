<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager; 

/**
 * Seasons Model
 *
 * @property \Cake\ORM\Association\HasMany $Teams
 *
 * @method \App\Model\Entity\Season get($primaryKey, $options = [])
 * @method \App\Model\Entity\Season newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Season[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Season|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Season patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Season[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Season findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SeasonsTable extends Table
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

        $this->table('seasons');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Teams', [
            'foreignKey' => 'season_id'
        ]);
        $this->hasMany('Games', [
            'foreignKey' => 'season_id'
        ]);
        $this->belongsTo('Mvps', [
            'className' => 'Players',
            'foreignKey' => 'mvp_player_id'
        ]);
        $this->belongsTo('B9ps', [
            'className' => 'Players',
            'foreignKey' => 'b9_p_player_id'
        ]);
        $this->belongsTo('B9cs', [
            'className' => 'Players',
            'foreignKey' => 'b9_c_player_id'
        ]);
        $this->belongsTo('B91bs', [
            'className' => 'Players',
            'foreignKey' => 'b9_1b_player_id'
        ]);
        $this->belongsTo('B92bs', [
            'className' => 'Players',
            'foreignKey' => 'b9_2b_player_id'
        ]);
        $this->belongsTo('B93bs', [
            'className' => 'Players',
            'foreignKey' => 'b9_3b_player_id'
        ]);
        $this->belongsTo('B9sses', [
            'className' => 'Players',
            'foreignKey' => 'b9_ss_player_id'
        ]);
        $this->belongsTo('B9of1s', [
            'className' => 'Players',
            'foreignKey' => 'b9_of1_player_id'
        ]);
        $this->belongsTo('B9of2s', [
            'className' => 'Players',
            'foreignKey' => 'b9_of2_player_id'
        ]);
        $this->belongsTo('B9of3s', [
            'className' => 'Players',
            'foreignKey' => 'b9_of3_player_id'
        ]);
        $this->belongsTo('Ggps', [
            'className' => 'Players',
            'foreignKey' => 'gg_p_player_id'
        ]);
        $this->belongsTo('Ggcs', [
            'className' => 'Players',
            'foreignKey' => 'gg_c_player_id'
        ]);
        $this->belongsTo('Gg1bs', [
            'className' => 'Players',
            'foreignKey' => 'gg_1b_player_id'
        ]);
        $this->belongsTo('Gg2bs', [
            'className' => 'Players',
            'foreignKey' => 'gg_2b_player_id'
        ]);
        $this->belongsTo('Gg3bs', [
            'className' => 'Players',
            'foreignKey' => 'gg_3b_player_id'
        ]);
        $this->belongsTo('Ggsses', [
            'className' => 'Players',
            'foreignKey' => 'gg_ss_player_id'
        ]);
        $this->belongsTo('Ggof1s', [
            'className' => 'Players',
            'foreignKey' => 'gg_of1_player_id'
        ]);
        $this->belongsTo('Ggof2s', [
            'className' => 'Players',
            'foreignKey' => 'gg_of2_player_id'
        ]);
        $this->belongsTo('Ggof3s', [
            'className' => 'Players',
            'foreignKey' => 'gg_of3_player_id'
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
            ->boolean('deleted')
            ->allowEmpty('deleted');

        $validator
            ->dateTime('deleted_date')
            ->allowEmpty('deleted_date');

        return $validator;
    }
    
    public function add($season, $data)
    {
        $connection = ConnectionManager::get('default');
        
        $connection->begin();

        $this->Teams = TableRegistry::get('Teams');
        $this->Players = TableRegistry::get('Players');
        $this->Games = TableRegistry::get('Games');
        
        $saveFlag =true;
        $season = $this->patchEntity($season, $data);
        if (!$this->save($season, ['atomic' => false])) {
            $saveFlag =false;
        }
        
        if ($saveFlag == true) {
            if (!$this->Teams->adds($season->id, $data)) {
                $saveFlag =false;
            } else {
                $teamLists = $this->Teams->getTeamLists($season->id)->toArray();
            }
        }
        if ($saveFlag == true) {
            if (!$this->Players->adds($teamLists, $data)) {
                $saveFlag =false;
            }
        }
            
            // “ú’ö
        if ($saveFlag == true) {
            if (!$this->Games->adds($season->id, $teamLists, $data)) {
                $saveFlag =false;
            }
        }
        if ($saveFlag == true) {
            if (!$this->Teams->teamShukei($season->id)) {
                $saveFlag =false;
            }
        }
        
        if ($saveFlag == true) {
            $connection->commit();
            return true;
        } else {
            $connection->rollback();
            return false;
        }
    }
}
