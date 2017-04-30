<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Result Entity
 *
 * @property int $id
 * @property string $name
 * @property int $color_type
 * @property int $position
 * @property int $out
 * @property bool $dasu_flag
 * @property bool $hit_flag
 * @property bool $base2_flag
 * @property bool $base3_flag
 * @property bool $hr_flag
 * @property bool $point_flag
 * @property bool $sansin_flag
 * @property bool $walk_flag
 * @property bool $deadball_flag
 * @property bool $bant_flag
 * @property bool $sacrifice_fly_flag
 * @property bool $heisatsu_flag
 * @property bool $deleted
 * @property \Cake\I18n\Time $deleted_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\GameResult[] $game_results
 */
class Result extends Entity
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
