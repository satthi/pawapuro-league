<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Team Entity
 *
 * @property int $id
 * @property int $season_id
 * @property string $name
 * @property string $ryaku_name
 * @property int $game
 * @property int $win
 * @property int $lose
 * @property int $draw
 * @property bool $deleted
 * @property \Cake\I18n\Time $deleted_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Season $season
 */
class Team extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
    
    public function getBestMember()
    {
        $gameMembersTable = TableRegistry::get('GameMembers');
        $playersTable = TableRegistry::get('Players');
        $gameMembers = $gameMembersTable->find()
            ->select(['count' => 'count(GameMembers.id)'])
            ->select('GameMembers.position')
            ->select('GameMembers.player_id')
            ->where(['GameMembers.team_id' => $this->_properties['id']])
            ->where(['GameMembers.stamen_flag' => true])
            ->group('GameMembers.player_id')
            ->group('GameMembers.position')
            ->order(['count(GameMembers.id)' => 'DESC']);
        // positionの設定
        $positionArray = [
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
            7 => null,
            8 => null,
            9 => null,
        ];
        
        foreach ($gameMembers as $gameMember) {
            // 範囲外は無視
            if (!array_key_exists($gameMember->position, $positionArray)) {
                continue;
            }
            // position設定済みは無視
            if (!is_null($positionArray[$gameMember->position])) {
                continue;
            }
            // 別のポジションで設定済み
            if (in_array($gameMember->player_id, $positionArray, true)) {
                continue;
            }
            
            $positionArray[$gameMember->position] = $gameMember->player_id;
        }
        
        $positionArrayCopy = $positionArray;
        
        // 打順の設定
        $gameMemberDajuns = $gameMembersTable->find()
            ->select(['count' => 'count(GameMembers.id)'])
            ->select('GameMembers.dajun')
            ->select('GameMembers.player_id')
            ->where(['GameMembers.team_id' => $this->_properties['id']])
            ->where(['GameMembers.stamen_flag' => true])
            ->where(['GameMembers.player_id IN' => $positionArray])
            ->group('GameMembers.player_id')
            ->group('GameMembers.dajun')
            ->order(['count(GameMembers.id)' => 'DESC']);

        $playerDaJuns = [
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
            7 => null,
            8 => null,
        ];

        foreach ($gameMemberDajuns as $gameMember) {
            // 範囲外は無視
            if (!array_key_exists($gameMember->dajun, $playerDaJuns)) {
                continue;
            }
            // position設定済みは無視
            if (!is_null($playerDaJuns[$gameMember->dajun])) {
                continue;
            }
            // 別のポジションで設定済み
            if (in_array($gameMember->player_id, $playerDaJuns, true)) {
                continue;
            }
            
            $playerDaJuns[$gameMember->dajun] = $gameMember->player_id;
            
            $unsetKey = array_search($gameMember->player_id, $positionArrayCopy);
            unset($positionArrayCopy[$unsetKey]);
        }
        
        // nullがあったときは埋める
        foreach ($playerDaJuns as $playerDaJunKey => $playerDaJun) {
            if (is_null($playerDaJun)) {
                $positionArrayCopyNewKey = key($positionArrayCopy);
                $playerDaJuns[$playerDaJunKey] = $positionArrayCopy[$positionArrayCopyNewKey];
                unset($positionArrayCopy[$positionArrayCopyNewKey]);
            }
        }
        
        $players = $playersTable->find()
            ->where(['Players.id IN' => $playerDaJuns]);
        
        $playersLists = [];
        foreach ($players as $player) {
            $playersLists[$player->id] = $player;
        }
        
        $summary = [];
        foreach ($playerDaJuns as $dajun => $playerDaJunId) {
            $summary[$dajun] = [
                'position' => Configure::read('positionLists.' . array_search($playerDaJunId, $positionArray)),
                'player' => $playersLists[$playerDaJunId],
            ];
        }
        
        return $summary;
    }

    public function getMainPitchers()
    {
        $playersTable = TableRegistry::get('Players');
        // 先発
        $starter = $playersTable->find()
            ->contain('Teams')
            ->where(['Players.team_id' => $this->_properties['id']])
            ->where(['Players.inning >= Teams.game * 3'])
            ->order(['Players.era' => 'ASC']);
        // 中継ぎ/抑え
        $nakatsugi = $playersTable->find()
            ->contain('Teams')
            ->where(['Players.team_id' => $this->_properties['id']])
            ->where(['Players.game >= 40'])
            ->order(['Players.era' => 'ASC']);
        
        return [
            'starter' => $starter,
            'nakatsugi' => $nakatsugi,
        ];
    }


}
