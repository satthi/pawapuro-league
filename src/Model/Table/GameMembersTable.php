<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GameMembers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Games
 * @property \Cake\ORM\Association\BelongsTo $Players
 *
 * @method \App\Model\Entity\GameMember get($primaryKey, $options = [])
 * @method \App\Model\Entity\GameMember newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GameMember[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GameMember|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GameMember patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GameMember[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GameMember findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GameMembersTable extends Table
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

        $this->table('game_members');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Games', [
            'foreignKey' => 'game_id'
        ]);
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
    
    public function getNowMemberLists($gameId, $teamId)
    {
        $memberInfos = $this->find('all')
            ->contain(['Players' => ['Teams']])
            ->where([$this->alias() . '.game_id' => $gameId])
            ->where([$this->alias() . '.team_id' => $teamId])
            ->order([$this->alias() . '.id' => 'ASC'])
        ;
        $members = [];
        $pitcherId = null;
        foreach ($memberInfos as $memberInfo) {
            $members[$memberInfo->dajun] = $memberInfo;
            if ($memberInfo->position == 1) {
                $pitcherId = $memberInfo->player->id;
            }
        }
        ksort($members);
        return [
            'memberInfo' => $members,
            'pitcherId' => $pitcherId,
        ];
    }
    
    public function memberUpdate($game_id, $team_id, $dajun, $change_player_id, $position)
    {
		$this->GameResults = TableRegistry::get('GameResults');
		$this->Games = TableRegistry::get('Games');
    	$game = $this->Games->get($game_id);
		$updateData = $this->newEntity();
		$updateData->game_id = $game_id;
		$updateData->team_id = $team_id;
		$updateData->dajun = $dajun;
		// ‘ã‘Å
		$updateData->position = $position;
		$updateData->player_id = $change_player_id;
		$updateData->stamen_flag = false;
		$this->save($updateData);
		
		$gameResultInfo = $this->GameResults->newEntity();
		$gameResultInfo->game_id = $game_id;
		$gameResultInfo->team_id = $team_id;
		$gameResultInfo->target_player_id = $change_player_id;
		$gameResultInfo->type = 1;
		$gameResultInfo->dajun = $dajun;
		$gameResultInfo->position = $position;
		$gameResultInfo->inning = $game->status;
		return $this->GameResults->save($gameResultInfo);
    }
}
