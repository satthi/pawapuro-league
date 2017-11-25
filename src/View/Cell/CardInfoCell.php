<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Core\Configure;

/**
 * PitcherInfo cell
 */
class CardInfoCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($cardId, $position = null, $dajun = null)
    {
    	$this->loadModel('Cards');
    	$card = $this->Cards->get($cardId, [
    		'contain' => [
    			'Players' => [
    				'Teams' => [
    					'Seasons'
    				]
    			]
    		]
    	]);
    	
    	$this->set('card', $card);
    	$this->set('position', $position);
    	$this->set('dajun', $dajun);
		$this->set('statusPositionShortLists', Configure::read('statusPositionShortLists'));

    }
    

}
