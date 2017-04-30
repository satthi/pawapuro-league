<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Core\Configure;

/**
 * ChangeOnly cell
 */
class ChangeOnlyCell extends Cell
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
    public function display($gameId)
    {
    	$this->set('gameId', $gameId);
    }
}
