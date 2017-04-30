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
class VMonthPitcherInfosTable extends Table
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

        $this->table('v_month_pitcher_infos');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->belongsTo('VMonthTeamInfos', [
            'foreignKey' => 'team_id',
            'bindingKey' => 'team_id',
            'conditions' => ['VMonthTeamInfos.month = VMonthPitcherInfos.month']
        ]);
    }

    
    public function rankingEra($seasonId, $year, $month, $limit = 5)
    {
        return $this->find('all')
            ->contain('VMonthTeamInfos')
            ->where(['VMonthPitcherInfos.season_id' => $seasonId])
            ->where(['VMonthPitcherInfos.year' => $year])
            ->where(['VMonthPitcherInfos.month' => $month])
            // 規定投球回数
            ->where(['VMonthPitcherInfos.inning >' => 0])
            ->where('VMonthTeamInfos.game::numeric * 3 <= VMonthPitcherInfos.inning')
            ->order(['CASE WHEN VMonthPitcherInfos.inning = 0 OR VMonthPitcherInfos.inning IS NULL THEN 99::numeric ELSE VMonthPitcherInfos.jiseki::numeric / VMonthPitcherInfos.inning::numeric END' => 'ASC'])
            ->limit($limit);
    }
    
    public function rankingWin($seasonId, $year, $month, $limit = 5)
    {
        return $this->find('all')
            ->where(['VMonthPitcherInfos.season_id' => $seasonId])
            ->where(['VMonthPitcherInfos.year' => $year])
            ->where(['VMonthPitcherInfos.month' => $month])
            // nullは省かないとめんどくさい
            ->where(['VMonthPitcherInfos.win >' => 0])
            ->where(['VMonthPitcherInfos.win IS NOT' => null])
            ->order(['VMonthPitcherInfos.win' => 'DESC'])
            ->limit($limit);
    }
    
    public function rankingSave($seasonId, $year, $month, $limit = 5)
    {
        return $this->find('all')
            ->where(['VMonthPitcherInfos.season_id' => $seasonId])
            ->where(['VMonthPitcherInfos.year' => $year])
            ->where(['VMonthPitcherInfos.month' => $month])
            // nullは省かないとめんどくさい
            ->where(['VMonthPitcherInfos.save >' => 0])
            ->where(['VMonthPitcherInfos.save IS NOT' => null])
            ->order(['VMonthPitcherInfos.save' => 'DESC'])
            ->limit($limit);
    }
    
    public function rankingHold($seasonId, $year, $month, $limit = 5)
    {
        return $this->find('all')
            ->where(['VMonthPitcherInfos.season_id' => $seasonId])
            ->where(['VMonthPitcherInfos.year' => $year])
            ->where(['VMonthPitcherInfos.month' => $month])
            // nullは省かないとめんどくさい
            ->where(['VMonthPitcherInfos.hold >' => 0])
            ->where(['VMonthPitcherInfos.hold IS NOT' => null])
            ->order(['VMonthPitcherInfos.hold' => 'DESC'])
            ->limit($limit);
    }
    
}
