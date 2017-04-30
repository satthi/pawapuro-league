<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Core\Configure;

/**
 * MonthInfo cell
 */
class MonthInfoCell extends Cell
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
    public function display($seasonId)
    {
    	$this->loadModel('Games');
    	$monthLists = $this->Games->find('all')
    		->select(['year' => 'date_part(\'YEAR\'::text, Games.date)'])
    		->select(['month' => 'date_part(\'MONTH\'::text, Games.date)'])
    		->where(['Games.season_id' => $seasonId])
    		->where(['Games.status' => 99])
    		->group('date_part(\'YEAR\'::text, Games.date)')
    		->group('date_part(\'MONTH\'::text, Games.date)')
    		->order(['date_part(\'YEAR\'::text, Games.date)' => 'ASC'])
    		->order(['date_part(\'MONTH\'::text, Games.date)' => 'ASC'])
    		;
    	$this->set('seasonId', $seasonId);
    	$this->set('monthLists', $monthLists);
    }
}
