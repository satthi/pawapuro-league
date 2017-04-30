<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Game Entity
 *
 * @property int $id
 * @property int $season_id
 * @property \Cake\I18n\Time $date
 * @property int $home_team_id
 * @property int $visitor_team_id
 * @property int $home_point
 * @property int $visitor_point
 * @property bool $deleted
 * @property \Cake\I18n\Time $deleted_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Season $season
 * @property \App\Model\Entity\HomeTeam $home_team
 * @property \App\Model\Entity\VisitorTeam $visitor_team
 * @property \App\Model\Entity\GameInning[] $game_innings
 * @property \App\Model\Entity\GameMember[] $game_members
 * @property \App\Model\Entity\GameResult[] $game_results
 */
class Game extends Entity
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
    
    public function pitcherPerform($pitcherId)
    {
    	$GamePitcherResults = TableRegistry::get('GamePitcherResults');
    	$performData = $GamePitcherResults->find('all')
    		->select(['game_sum' => 'count(GamePitcherResults.id)'])
    		->select(['win_sum' => 'count(CASE WHEN GamePitcherResults.win = TRUE THEN 1 ELSE NULL END)'])
    		->select(['lose_sum' => 'count(CASE WHEN GamePitcherResults.lose = TRUE THEN 1 ELSE NULL END)'])
    		->select(['save_sum' => 'count(CASE WHEN GamePitcherResults.save = TRUE THEN 1 ELSE NULL END)'])
    		->contain('Games')
    		->where(['GamePitcherResults.pitcher_id' => $pitcherId])
    		->where(['Games.date <=' => $this->date])
    		->group('GamePitcherResults.pitcher_id')
    		->first()
    	;
    	
    	$text = '';
    	$text .= $performData->game_sum . '試合';
    	if ($performData->win_sum > 0) {
    		$text .= $performData->win_sum . '勝';
    	}
    	if ($performData->lose_sum > 0) {
    		$text .= $performData->lose_sum . '敗';
    	}
    	if ($performData->save_sum > 0) {
    		$text .= $performData->save_sum . 'S';
    	}
    	return $text;
    }
}
