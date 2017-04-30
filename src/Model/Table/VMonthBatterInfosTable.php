<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;



/**
 * Players Model
 *
 * @property \Cake\ORM\Association\HasMany $GameMembers
 *
 * @method \App\Model\Entity\Player get($primaryKey, $options = [])
 * @method \App\Model\Entity\Player newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Player[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Player|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Player patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Player[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Player findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VMonthBatterInfosTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('v_month_batter_infos');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->belongsTo('VMonthTeamInfos', [
            'foreignKey' => 'team_id',
            'bindingKey' => 'team_id',
            'conditions' => ['VMonthTeamInfos.month = VMonthBatterInfos.month']
        ]);
    }

    
    public function rankingAvg($seasonId, $year, $month, $limit = 5)
    {
        return $this->find('all')
            ->contain('VMonthTeamInfos')
            ->where(['VMonthBatterInfos.season_id' => $seasonId])
            ->where(['VMonthBatterInfos.month' => $month])
            ->where(['VMonthBatterInfos.month' => $month])
            // 規定打席
            ->where(['VMonthBatterInfos.dasu >' => 0])
            ->where('VMonthTeamInfos.game::numeric * 3.1 <= VMonthBatterInfos.daseki')
            ->order(['CASE WHEN VMonthBatterInfos.dasu = 0 OR VMonthBatterInfos.dasu IS NULL THEN 0::numeric ELSE VMonthBatterInfos.hit::numeric / VMonthBatterInfos.dasu::numeric END' => 'DESC'])
            ->limit($limit);
    }
    
    public function rankingHr($seasonId, $year, $month, $limit = 5)
    {
        return $this->find('all')
            ->where(['VMonthBatterInfos.season_id' => $seasonId])
            ->where(['VMonthBatterInfos.year' => $year])
            ->where(['VMonthBatterInfos.month' => $month])
            // nullは省かないとめんどくさい
            ->where(['VMonthBatterInfos.hr IS NOT' => null])
            ->where(['VMonthBatterInfos.hr >' => 0])
            ->order(['VMonthBatterInfos.hr' => 'DESC'])
            ->limit($limit);
    }
    
    public function rankingRbi($seasonId, $year, $month, $limit =5)
    {
        return $this->find('all')
            ->where(['VMonthBatterInfos.season_id' => $seasonId])
            ->where(['VMonthBatterInfos.year' => $year])
            ->where(['VMonthBatterInfos.month' => $month])
            // nullは省かないとめんどくさい
            ->where(['VMonthBatterInfos.rbi IS NOT' => null])
            ->where(['VMonthBatterInfos.rbi >' => 0])
            ->order(['VMonthBatterInfos.rbi' => 'DESC'])
            ->limit($limit);
    }
    
    public function rankingHit($seasonId, $year, $month, $limit =5)
    {
        return $this->find('all')
            ->where(['VMonthBatterInfos.season_id' => $seasonId])
            ->where(['VMonthBatterInfos.year' => $year])
            ->where(['VMonthBatterInfos.month' => $month])
            // nullは省かないとめんどくさい
            ->where(['VMonthBatterInfos.hit IS NOT' => null])
            ->where(['VMonthBatterInfos.hit >' => 0])
            ->order(['VMonthBatterInfos.hit' => 'DESC'])
            ->limit($limit);
    }
    
}
