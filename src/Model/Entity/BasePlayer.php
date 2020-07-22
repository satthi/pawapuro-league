<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BasePlayer Entity
 *
 * @property int $id
 * @property string $name
 * @property string $name_short
 * @property bool $deleted
 * @property \Cake\I18n\Time $deleted_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Player[] $players
 */
class BasePlayer extends Entity
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

    protected function _getDisplayAvg()
    {
        if (!$this->_properties['dasu']) {
            return '-';
        }
        return preg_replace('/^0/', '', sprintf('%0.3f', round($this->_properties['hit'] / $this->_properties['dasu'], 3)));
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
        if (!$this->_properties['inning']) {
            return '-';
        }
        return sprintf('%0.2f', round($this->_properties['jiseki'] / $this->_properties['inning'] * 27, 2));
    }

    protected function _getDisplayWinRatio()
    {
        if (!$this->_properties['win'] && !$this->_properties['lose']) {
            return '-';
        }
        return sprintf('%0.3f', round($this->_properties['win'] / ($this->_properties['win'] + $this->_properties['lose']), 3));
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

    protected function _getSansinRitsu()
    {
    	if (!$this->_properties['inning']) {
    		return '-';
    	} 
        return sprintf('%0.2f', round($this->_properties['get_sansin'] / $this->_properties['inning'] * 27, 2));
    }

}
