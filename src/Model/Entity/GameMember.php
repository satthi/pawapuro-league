<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GameMember Entity
 *
 * @property int $id
 * @property int $game_id
 * @property int $type
 * @property int $dajun
 * @property int $position
 * @property int $player_id
 * @property bool $stamen_flag
 * @property bool $deleted
 * @property \Cake\I18n\Time $deleted_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Game $game
 * @property \App\Model\Entity\Player $player
 */
class GameMember extends Entity
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
}
