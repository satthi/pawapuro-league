<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Core\Configure;

/**
 * BatterInfo cell
 */
class BatterInfoCell extends Cell
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
    public function display($id, $gameId, $dajun)
    {
    	$this->loadModel('Players');
    	$playerInfo = $this->Players->find('all')
    		->where(['Players.id' => $id])
    		->contain(['Teams'])
    		->first()
    		;
    	$this->set('playerInfo', $playerInfo);
    	
    	// 今日の結果を表示
    	$this->loadModel('GameResults');
    	
    	$todayResults = $this->GameResults->find('all')
    		->contain('Pitchers')
    		->contain('Results')
    		->where(['GameResults.type' => 2])
    		->where(['GameResults.target_player_id' => $id])
    		->where(['GameResults.game_id' => $gameId])
    		->order(['GameResults.id' => 'ASC'])
    	;
    	$this->set('gameId', $gameId);
    	$this->set('dajun', $dajun);
    	$this->set('todayResults', $todayResults);
    	$this->set('resultSet', Configure::read('resultSet'));
    }
}
