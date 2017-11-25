<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Players
 *
 * @method \App\Model\Entity\Card get($primaryKey, $options = [])
 * @method \App\Model\Entity\Card newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Card[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Card|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Card patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Card[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Card findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CardsTable extends Table
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

        $this->table('cards');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
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
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('meat_plus')
            ->allowEmpty('meat_plus');

        $validator
            ->integer('power_plus')
            ->allowEmpty('power_plus');

        $validator
            ->integer('speed_plus')
            ->allowEmpty('speed_plus');

        $validator
            ->integer('bant_plus')
            ->allowEmpty('bant_plus');

        $validator
            ->integer('defense_plus')
            ->allowEmpty('defense_plus');

        $validator
            ->integer('mental_plus')
            ->allowEmpty('mental_plus');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['player_id'], 'Players'));

        return $rules;
    }
    
    public function autoStamen($userId)
    {
        $cardLists = $this->find('all')
        	->where(['Cards.user_id' => $userId])
        	->contain([
        		'Players' => [
        			'Teams' => [
        				'Seasons'
        			]
        		]
        	])
        	->order(['Players.status_cost' => 'DESC'])
        	->order(['Players.status_meat + Players.status_power + Players.status_speed + Players.status_bant + Players.status_defense + Players.status_mental + Cards.meat_plus + Cards.power_plus + Cards.speed_plus + Cards.bant_plus + Cards.defense_plus + Cards.mental_plus' => 'DESC'])
        	->order(['Cards.id' => 'DESC'])
        	;
        $batterLists = [];
        $pitcherLists = [];
        foreach ($cardLists as $cardList) {
            if ($cardList->player->type_p === null) {
                $batterLists[$cardList->id] = $cardList;
            } else {
                $pitcherLists[$cardList->id] = $cardList;
            }
        }
        //野手
        // 全メインポジションが埋まるかを確認
        $positionSet = [];
        foreach ($batterLists as $cardId => $batterList) {
            $positionSet[$batterList->player->status_position][$cardId] = $batterList;
        }
        $requireNum = [
            2 => 1,
            3 => 1,
            4 => 1,
            5 => 1,
            6 => 1,
            7 => 3,
        ];
        $positionCheckList = [
            2 => 'status_slider',
		    3 => 'status_hslider',
		    4 => 'status_cut',
		    5 => 'status_curb',
		    6 => 'status_scurb',
		    7 => 'status_folk',
        ];

        $lessPosition = [];
        $amariPosition = [];
        foreach ($requireNum as $position => $positionCount) {
            if (empty($positionSet[$position])) {
                $lessPosition[$position] = $positionCount;
            } elseif (count($positionSet[$position]) < $positionCount) {
                // 不足
                $lessPosition[$position] = $positionCount - count($positionSet[$position]);
            } elseif (count($positionSet[$position]) > $positionCount) {
                $amariPosition[$position] = count($positionSet[$position]) - $positionCount;
            }
        }
        $newPositions = [];
        $removeCards = [];
        // 不足ポジションがいれば
        if (!empty($lessPosition)) {
            foreach ($lessPosition as $position => $count) {
                $setThisPositions = [];
                foreach ($amariPosition as $amariPositionKey => $amaricount) {
                    if ($amaricount > 0) {
                        foreach ($positionSet[$amariPositionKey] as $cardId => $batterList) {
                            $setThisPositions[$cardId] = $batterList->player->{$positionCheckList[$position]};
                        }
                    }
                }
                arsort($setThisPositions);
                for ($i = 1;$i <= $count;$i++) {
                	reset($setThisPositions);
                    $thisCardId = key($setThisPositions);
                    $newPositions[$position][$thisCardId] = $batterLists[$thisCardId];
                    $removeCards[] = $thisCardId;
                    unset($batterLists[$thisCardId]);
                }
            }
        }
        // DH以外
        foreach ($positionSet as $position => $positionPlayers) {
            foreach ($positionPlayers as $positionPlayer) {
	            if (in_array($positionPlayer->id, $removeCards)) {
	                continue;
	            }
	            if (!empty($newPositions[$position]) && count($newPositions[$position]) >= $requireNum[$position]) {
	                break;
	            }
	            $newPositions[$position][$positionPlayer->id] = $positionPlayer;
	            unset($batterLists[$positionPlayer->id]);
            }
        }
        // DH
        reset($batterLists);
        $dhCardId = key($batterLists);
        $newPositions[99][$dhCardId] = $batterLists[$dhCardId];
        unset($batterLists[$dhCardId]);
        // 控え
        $hikaeBatters = [];
        for ($i =1;$i <= 5;$i++ ){
        	reset($batterLists);
            $hikaeBatterId = key($batterLists);
            $hikaeBatters[] = $hikaeBatterId;
            unset($batterLists[$hikaeBatterId]);
        }
        // 打順のソートはあとでにしよう
        
        // ピッチャー
        // 先発
        $startPitchers = [];
        foreach ($pitcherLists as $cardId => $pitcherList) {
            if ($pitcherList->player->status_position == 11) {
                $startPitchers[] = $pitcherList->id;
                unset($pitcherLists[$cardId]);
                if (count($startPitchers) >= 5) {
                    break;
                }
            }
        }
        // 抑え
        $osaePitchers = [];
        foreach ($pitcherLists as $cardId => $pitcherList) {
            if ($pitcherList->player->status_position == 14) {
                $osaePitchers[] = $pitcherList->id;
                unset($pitcherLists[$cardId]);
                break;
            }
        }
        // 抑えがいなかったらセットアッパーから
        if (empty($osaePitchers)){
	        foreach ($pitcherLists as $cardId => $pitcherList) {
	            if ($pitcherList->player->status_position == 13) {
	                $osaePitchers[] = $pitcherList->id;
	                unset($pitcherLists[$cardId]);
	                break;
	            }
	        }
        }
        // セットアッパーもいなければ中継ぎから
        if (empty($osaePitchers)){
	        foreach ($pitcherLists as $cardId => $pitcherList) {
	            if ($pitcherList->player->status_position == 12) {
	                $osaePitchers[] = $pitcherList->id;
	                unset($pitcherLists[$cardId]);
	                break;
	            }
            }
        }
        // セットアッパー
        $setupperPitchers = [];
        // 抑えがいなかったらセットアッパーから
        foreach ($pitcherLists as $cardId => $pitcherList) {
	        if ($pitcherList->player->status_position == 13) {
	            $setupperPitchers[] = $pitcherList->id;
	            unset($pitcherLists[$cardId]);
	            break;
	        }
        }
        // セットアッパーもいなければ中継ぎから
        if (empty($setupperPitchers)){
	        foreach ($pitcherLists as $cardId => $pitcherList) {
	            if ($pitcherList->player->status_position == 12) {
	                $setupperPitchers[] = $pitcherList->id;
	                unset($pitcherLists[$cardId]);
	                break;
	            }
            }
        }
        $nakatsugiPitchers = [];
        // 中継ぎは4人
        foreach ($pitcherLists as $cardId => $pitcherList) {
            if ($pitcherList->player->status_position == 12 || $pitcherList->player->status_position == 13 || $pitcherList->player->status_position == 14) {
            $nakatsugiPitchers[] = $pitcherList->id;
            unset($pitcherLists[$cardId]);
                if (count($nakatsugiPitchers) >= 4) {
                    break;
                }
            }
        }
        // ここで本来はちゃんと打順を組むよ
        $dajun = [];
        /*
        
        foreach ($newPositions as $position => $cards) {
        	$count = 0;
        	foreach ($cards as $card) {
	            $dajun[count($dajun) + 1] = [
	                'position' => $position + $count,
	                'card_id' => $card->id,
	            ];
	            $count++;
            }
        }
        */
        // 4番は長打と精神
        $dajun[4] = $this->dajunPickup($newPositions, 'power', 'mental', $dajun);
        $dajun[3] = $this->dajunPickup($newPositions, 'meat', 'power', $dajun);
        $dajun[1] = $this->dajunPickup($newPositions, 'meat', 'speed', $dajun);
        $dajun[5] = $this->dajunPickup($newPositions, 'meat', 'power', $dajun);
        $dajun[2] = $this->dajunPickup($newPositions, 'bant', 'speed', $dajun);
        $dajun[6] = $this->dajunPickup($newPositions, 'meat', 'mental', $dajun);
        $dajun[7] = $this->dajunPickup($newPositions, 'meat', 'mental', $dajun);
        $dajun[8] = $this->dajunPickup($newPositions, 'meat', 'mental', $dajun);
        $dajun[9] = $this->dajunPickup($newPositions, 'meat', 'mental', $dajun);
        // レフトが3人いるので分ける
        $ofCount = 0;
        foreach ($dajun as $dk => $dv) {
            if ($dv['position'] == 7) {
                $dajun[$dk]['position'] += $ofCount;
                $ofCount++;
            }
        }
        ksort($dajun);

        $members = [
        	'dajun' => $dajun,
        	'hikaeBatters' => $hikaeBatters,
        	'startPitcher' => $startPitchers,
        	'nakatsugiPitchers' => $nakatsugiPitchers,
        	'setupperPitchers' => $setupperPitchers,
        	'osaePitchers' => $osaePitchers,
        ];
        $user = $this->Users->get($userId);
        $user->card_member = json_encode($members);
        $this->Users->save($user);
    }
    
    private function dajunPickup($newPositions, $point1, $point2, $selected)
    {
    	$maxPoint = 0;
    	$maxUser = null;
        foreach ($newPositions as $position => $cards) {
        	foreach ($cards as $card) {
        		foreach ($selected as $selectedInfo) {
        		    if ($selectedInfo['card_id'] == $card->id) {
        		       continue 2;
        		    }
        		}
	            $point = $card->player->{'status_' . $point1} + $card->player->{'status_' . $point2};
	            if ($point > $maxPoint) {
	                $maxPoint = $point;
	                $maxUser = [
	                   'position' => $position,
	                   'card_id' => $card->id,
	                ];
	            }
            }
        }
        return $maxUser;
    }
}
