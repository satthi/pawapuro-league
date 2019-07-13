<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * Player Entity
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $daseki
 * @property int $dasu
 * @property int $hit
 * @property int $hr
 * @property int $rbi
 * @property int $inning
 * @property int $jiseki
 * @property int $win
 * @property int $lose
 * @property int $hold
 * @property int $save
 * @property bool $deleted
 * @property \Cake\I18n\Time $deleted_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\GameMember[] $game_members
 */
class Player extends Entity
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
    
    protected function _getPlayerColor()
    {
    	$type_p = $this->_properties['type_p'];
    	$type_c = $this->_properties['type_c'];
    	$type_i = $this->_properties['type_i'];
    	$type_o = $this->_properties['type_o'];
    	
    	if ($type_p == 2 && $type_c == null && $type_i == null && $type_o == null) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/p.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == 2 && $type_c == null && $type_i == 1 && $type_o == null) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/p_i.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == 2 && $type_c == null && $type_i == null && $type_o == 1) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/p_o.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == 2 && $type_i == null && $type_o == null) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/c.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == 2 && $type_i == 1 && $type_o == null) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/c_i.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == 2 && $type_i == null && $type_o == 1) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/c_o.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == 2 && $type_i == 1 && $type_o == 1) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/c_i_o.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == null && $type_i == 2 && $type_o == null) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/i.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == 1 && $type_i == 2 && $type_o == null) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/i_c.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == null && $type_i == 2 && $type_o == 1) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/i_o.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == 1 && $type_i == 2 && $type_o == 1) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/i_c_o.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == null && $type_i == null && $type_o == 2) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/o.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == 1 && $type_i == null && $type_o == 2) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/o_c.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == null && $type_i == 1 && $type_o == 2) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/o_i.png') . '\');background-size:100% 100%;"';
    	}
    	if ($type_p == null && $type_c == 1 && $type_i == 1 && $type_o == 2) {
    	    return ' style="background-image: url(\'' . Router::url('/img/p_color/o_c_i.png') . '\');background-size:100% 100%;"';
    	}
    }
    
    
    protected function _getDisplayAvg()
    {
        return preg_replace('/^0/', '', sprintf('%0.3f', round($this->_properties['avg'], 3)));
    }
    
    protected function _getPAvg()
    {
        if($this->_properties['p_dasu'] == 0) {
           $ratio = sprintf('%0.3f', round(0, 3));
        } else {
           $ratio = sprintf('%0.3f', round($this->_properties['p_hit'] / ($this->_properties['p_dasu']), 3));
        }
        $ratio = preg_replace('/^0/', '', $ratio);
        
        return $ratio;
    }
    
    protected function _getObp()
    {
        if(
            $this->_properties['dasu'] == 0 &&
            $this->_properties['walk'] == 0 &&
            $this->_properties['deadball'] == 0 &&
            $this->_properties['sacrifice_fly'] == 0
         ) {
           $ratio = sprintf('%0.3f', round(0, 3));
        } else {
           $ratio = sprintf('%0.3f', round(($this->_properties['hit'] + $this->_properties['walk'] + $this->_properties['deadball']) / ($this->_properties['dasu'] + $this->_properties['walk'] + $this->_properties['deadball'] + $this->_properties['sacrifice_fly']), 3));
        }
        $ratio = preg_replace('/^0/', '', $ratio);
        
        return $ratio;
    }
    
    protected function _getSlg()
    {
        if(
            $this->_properties['dasu'] == 0
         ) {
           $ratio = sprintf('%0.3f', round(0, 3));
        } else {
           $ratio = sprintf('%0.3f', round(($this->_properties['hit'] + $this->_properties['base2'] + $this->_properties['base3'] * 2 + $this->_properties['hr']* 3 ) / ($this->_properties['dasu']), 3));
        }
        $ratio = preg_replace('/^0/', '', $ratio);
        
        return $ratio;
    }
    
    protected function _getOps()
    {
        return preg_replace('/^0/', '', sprintf('%0.3f', $this->_getObp() + $this->_getSlg()));
    }
    
    protected function _getDisplayEra()
    {
        return sprintf('%0.2f', round($this->_properties['era'], 2));
    }
        protected function _getDisplayWinRatio()
    {
        return sprintf('%0.3f', round($this->_properties['win_ratio'], 3));
    }

    protected function _getPlayerInfo()
    {
    	if ($this->_properties['type_p'] != 2) {
        	return $this->_getBatterPlayerInfo();
        } else {
        	return $this->_getPitcherPlayerInfo();
        }
    }
    protected function _getBatterPlayerInfo()
    {
        return $this->display_avg . ' (' . (int) $this->_properties['dasu'] . '-' . (int) $this->_properties['hit'] . ') ' . (int) $this->_properties['hr'] . '本' . (int) $this->_properties['rbi'] . '点';
    }
    
    protected function _getPitcherPlayerInfo()
    {
        return $this->display_era . ' ' . (int) $this->_properties['game'] . '試' . $this->_properties['win'] . '勝' . (int) $this->_properties['lose'] . '敗' . (int) $this->_properties['save'] . 'S';
    }

    protected function _getRealAvg()
    {
		$playerId = $this->_properties['id'];

    	$GameResults = TableRegistry::get('GameResults');
    	$shukeiData = $GameResults->find('all')
    		->contain('Results')
    		->select('GameResults.target_player_id')
    		->select(['dasu_count' => 'sum(Results.dasu_flag::integer)'])
    		->select(['hit_count' => 'sum(Results.hit_flag::integer)'])
    		->group('GameResults.target_player_id')
    		->where(['GameResults.target_player_id' => $playerId])
    		->first()
    	;
    	
        if(empty($shukeiData) || $shukeiData->dasu_count == 0) {
           $avg = sprintf('%0.3f', round(0, 3));
        } else {
           $avg = sprintf('%0.3f', round($shukeiData->hit_count / ($shukeiData->dasu_count), 3));
        }
        return preg_replace('/^0/', '', $avg);
    }

    protected function _getRealHr()
    {
		//
		$playerId = $this->_properties['id'];
    	$resultSet = Configure::read('resultSet');

    	$GameResults = TableRegistry::get('GameResults');
    	$shukeiData = $GameResults->find('all')
    		->contain('Results')
    		->select('GameResults.target_player_id')
    		->select(['hr_count' => 'sum(Results.hr_flag::integer)'])
    		->group('GameResults.target_player_id')
    		->where(['GameResults.target_player_id' => $playerId])
    		->first()
    	;
    	
    	if (empty($shukeiData)) {
        	return 0;
    	}
        
        return (int) $shukeiData->hr_count;
    }
    protected function _getDisplayName()
    {
        return $this->team->ryaku_name . ': (' . $this->_properties['no'] . ')' . $this->_properties['name'];
    }

    protected function _getRealBatterPlayerInfo()
    {
		//
		$playerId = $this->_properties['id'];
    	$resultSet = Configure::read('resultSet');

    	$GameResults = TableRegistry::get('GameResults');
    	$shukeiData = $GameResults->find('all')
    		->contain('Results')
    		->select('GameResults.target_player_id')
    		->select(['dasu_count' => 'sum(Results.dasu_flag::integer)'])
    		->select(['hit_count' => 'sum(Results.hit_flag::integer)'])
    		->select(['hr_count' => 'sum(Results.hr_flag::integer)'])
    		->select(['rbi_count' => 'sum(GameResults.point)'])
    		->group('GameResults.target_player_id')
    		->where(['GameResults.target_player_id' => $playerId])
    		->first()
    	;
    	
    	if (empty($shukeiData)) {
        	return '.000 (0-0) 0本0点';
    	}
    	
        if($shukeiData->dasu_count == 0) {
           $avg = sprintf('%0.3f', round(0, 3));
        } else {
           $avg = sprintf('%0.3f', round($shukeiData->hit_count / ($shukeiData->dasu_count), 3));
        }
        $avg = preg_replace('/^0/', '', $avg);
        
        return $avg . ' (' . (int) $shukeiData->dasu_count . '-' . (int) $shukeiData->hit_count . ') ' . (int) $shukeiData->hr_count . '本' . (int) $shukeiData->rbi_count . '点';
    }
    

    public function getRecentBatterPlayerInfo($game = 10)
    {
    	$Games = TableRegistry::get('Games');
    	$gameLists = $Games->find('list', [
    			'valueField' => 'id'
    		])
    		->where([
    			'OR' => [
    				'Games.home_team_id' => $this->_properties['team_id'],
    				'Games.visitor_team_id' => $this->_properties['team_id'],
    			]
    		])
    		->where(['Games.status' => 99])
    		->order(['Games.id' => 'DESC'])
    		->limit(10)
    		->toArray()
    		;
		//
		if (empty($gameLists)) {
			$gameLists[] = 0;
		}
		$playerId = $this->_properties['id'];
    	$resultSet = Configure::read('resultSet');

    	$GameResults = TableRegistry::get('GameResults');
    	$shukeiData = $GameResults->find('all')
    		->select('GameResults.target_player_id')
    		->select(['dasu_count' => 'sum(Results.dasu_flag::integer)'])
    		->select(['hit_count' => 'sum(Results.hit_flag::integer)'])
    		->select(['hr_count' => 'sum(Results.hr_flag::integer)'])
    		->select(['rbi_count' => 'sum(GameResults.point)'])
    		->contain('Results')
    		->group('GameResults.target_player_id')
    		->where(['GameResults.target_player_id' => $playerId])
    		->where(['GameResults.game_id IN' => $gameLists])
    		->first()
    	;
    	
        if(empty($shukeiData) || $shukeiData->dasu_count == 0) {
           $avg = sprintf('%0.3f', round(0, 3));
        $avg = preg_replace('/^0/', '', $avg);
        return $avg . ' (' . (int) 0 . '-' . (int) 0 . ') ' . (int) 0 . '本' . (int) 0 . '点';
        } else {
           $avg = sprintf('%0.3f', round($shukeiData->hit_count / ($shukeiData->dasu_count), 3));
        $avg = preg_replace('/^0/', '', $avg);
        return $avg . ' (' . (int) $shukeiData->dasu_count . '-' . (int) $shukeiData->hit_count . ') ' . (int) $shukeiData->hr_count . '本' . (int) $shukeiData->rbi_count . '点';
        }
        
    }
    
    private function resultCase($conditions)
    {
    	foreach ($conditions as $k => $v) {
    		$conditions[$k] = '\'' . $v . '\'';
    	}
        return 'count(CASE WHEN (GameResults.type = 2 AND GameResults.result IN (' . implode(',' , $conditions) . ')) THEN 1 ELSE null END)';
    }
    
    protected function _getAvgIsTop()
    {
    	if (empty($this->_properties['avg']) || $this->_properties['daseki'] < $this->_properties['team']->game * 3.1) {
    		return false;
    	}
    	
        return TableRegistry::get('Players')->find()
        	->contain('Teams')
        	->where(['Teams.season_id' => $this->_properties['team']->season_id])
        	->where(['Players.avg >' => $this->_properties['avg']])
        	->where('Players.daseki::numeric >= (Teams.game::numeric * 3.1)')
        	->count() == 0;
    }
    
    protected function _getHrIsTop()
    {
        return $this->simpleIsTop('hr');
    }
    
    protected function _getRbiIsTop()
    {
        return $this->simpleIsTop('rbi');
    }
    
    protected function _getDasekiIsTop()
    {
        return $this->simpleIsTop('daseki');
    }
    
    protected function _getDasuIsTop()
    {
        return $this->simpleIsTop('dasu');
    }
    
    protected function _getHitIsTop()
    {
        return $this->simpleIsTop('hit');
    }
    
    protected function _getBase2IsTop()
    {
        return $this->simpleIsTop('base2');
    }
    
    protected function _getBase3IsTop()
    {
        return $this->simpleIsTop('base3');
    }
    
    protected function _getWalkIsTop()
    {
        return $this->simpleIsTop('walk');
    }
    
    protected function _getDeadballIsTop()
    {
        return $this->simpleIsTop('deadball');
    }
    
    protected function _getBantIsTop()
    {
        return $this->simpleIsTop('bant');
    }
    
    protected function _getSacrificeFlyIsTop()
    {
        return $this->simpleIsTop('sacrifice_fly');
    }
    
    protected function _getSansinIsTop()
    {
        return $this->simpleIsTop('sansin');
    }
    
    protected function _getHeisatsuIsTop()
    {
        return $this->simpleIsTop('heisatsu');
    }

    
    protected function _getStealIsTop()
    {
        return $this->simpleIsTop('steal');
    }
    
    protected function _getEraIsTop()
    {
    	if (is_null($this->_properties['era']) || $this->_properties['inning'] < $this->_properties['team']->game * 3) {
    		return false;
    	}
    	
        return TableRegistry::get('Players')->find()
        	->contain('Teams')
        	->where(['Teams.season_id' => $this->_properties['team']->season_id])
        	->where(['Players.era <' => $this->_properties['era']])
        	->where('Players.inning >= (Teams.game * 3)')
        	->count() == 0;
    }
    
    protected function _getWinRatioIsTop()
    {
    	if (is_null($this->_properties['win_ratio']) || $this->_properties['win'] < 13) {
    		return false;
    	}
    	
        return TableRegistry::get('Players')->find()
        	->contain('Teams')
        	->where(['Teams.season_id' => $this->_properties['team']->season_id])
        	->where(['Players.win_ratio >' => $this->_properties['win_ratio']])
        	->where('Players.win >= 13')
        	->count() == 0;
    }
    protected function _getInningIsTop()
    {
        return $this->simpleIsTop('inning');
    }    
    
    protected function _getJisekiIsTop()
    {
        return $this->simpleIsTop('jiseki');
    }    
    
    protected function _getGameIsTop()
    {
        return $this->simpleIsTop('game');
    }    
    
    protected function _getWinIsTop()
    {
        return $this->simpleIsTop('win');
    }    
    
    protected function _getLoseIsTop()
    {
        return $this->simpleIsTop('lose');
    }    
    
    protected function _getHoldIsTop()
    {
        return $this->simpleIsTop('hold');
    }    
    
    protected function _getKantoIsTop()
    {
        return $this->simpleIsTop('kanto');
    }    
    
    protected function _getKanpuIsTop()
    {
        return $this->simpleIsTop('kanpu');
    }    
    
    protected function _getSaveIsTop()
    {
        return $this->simpleIsTop('save');
    }    
    
    protected function _getPHitIsTop()
    {
        return $this->simpleIsTop('p_hit');
    }    
    
    protected function _getPHrIsTop()
    {
        return $this->simpleIsTop('p_hr');
    }    
    
    protected function _getGetSansinIsTop()
    {
        return $this->simpleIsTop('get_sansin');
    }    
    
    private function simpleIsTop($field)
    {
    	if (is_null($this->_properties[$field])) {
    		return false;
    	}
        return TableRegistry::get('Players')->find()
        	->contain('Teams')
        	->where(['Teams.season_id' => $this->_properties['team']->season_id])
        	->where(['Players.' . $field . ' >' => $this->_properties[$field]])
        	->count() == 0;
    }
    

}
