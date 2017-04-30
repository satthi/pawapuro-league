<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Core\Configure;

/**
 * PitcherInfo cell
 */
class PitcherInfoCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($id, $gameId)
    {
    	$this->loadModel('Players');
    	$playerInfo = $this->Players->find('all')
    		->where(['Players.id' => $id])
    		->contain(['Teams'])
    		->first()
    		;
    	$this->set('playerInfo', $playerInfo);
    	
    	//‚±‚±‚Ü‚Å‚Ì“o”Âî•ñ
    	$this->loadModel('GameResults');
    	
    	$shukeiData = $this->GameResults->find('all')
    		->contain('Results')
    		->select('GameResults.pitcher_id')
    		->select(['out_num' => 'sum(GameResults.out_num)'])
    		->select(['sansin_count' => 'sum(Results.sansin_flag::integer)'])
    		->select(['yontama_count' => 'sum(Results.walk_flag::integer)'])
    		->select(['hit_count' => 'sum(Results.hit_flag::integer)'])
    		->select(['hr_count' => 'sum(Results.hr_flag::integer)'])
    		->select(['rbi_count' => 'sum(GameResults.point)'])
    		->group('GameResults.pitcher_id')
    		->where(['GameResults.game_id' => $gameId])
    		->where(['GameResults.pitcher_id' => $id])
    		->first()
    	;
    	
    	$todeyResult = [
    		'out' => 0,
    		'hit' => 0,
    		'sansin' => 0,
    		'yontama' => 0,
    		'hr' => 0,
    		'rbi' => 0,
    	];
    	if ($shukeiData !== null) {
	    	$todeyResult = [
	    		'out' => $shukeiData->out_num,
	    		'hit' => $shukeiData->hit_count,
	    		'sansin' => $shukeiData->sansin_count,
	    		'yontama' => $shukeiData->yontama_count,
	    		'hr' => $shukeiData->hr_count,
	    		'rbi' => $shukeiData->rbi_count,
	    	];
    	}
    	$this->set('todeyResult', $todeyResult);
    	$this->set('gameId', $gameId);

    }
    
    
    
    private function resultCase($conditions)
    {
    	foreach ($conditions as $k => $v) {
    		$conditions[$k] = '\'' . $v . '\'';
    	}
        return 'count(CASE WHEN (GameResults.type = 2 AND GameResults.result IN (' . implode(',' , $conditions) . ')) THEN 1 ELSE null END)';
    }
    
}
