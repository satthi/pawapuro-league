<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Players Controller
 *
 * @property \App\Model\Table\PlayersTable $Players
 */
class CardsController extends AppController
{
	
	public function beforeRender(\Cake\Event\Event $event) 
	
	{
		$this->set('statusPositionLists', Configure::read('statusPositionLists'));
	}
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadModel('BasePlayers');
        $players = $this->BasePlayers->find('all')
            ->order(['team_ryaku_name' => 'ASC'])
            ->order(['BasePlayers.no::integer' => 'ASC'])
        ;
        $this->set('players', $players);
    }
    
    public function view($basePlayerId)
    {
        $this->loadModel('Players');
        $playerInfos = $this->Players->find('all')
            ->where(['Players.base_player_id' => $basePlayerId])
            ->contain(['Teams' => ['Seasons']])
            ->where(['Seasons.regular_flag' => true])
            ->order(['Seasons.id' => 'ASC'])
            ;
        $this->set('playerInfos', $playerInfos);
        if ($this->request->is('post') ||$this->request->is('put')) {
        	$entity = $this->Players->get($this->request->data['id']);
        	$entity = $this->Players->patchEntity($entity, $this->request->data);
            $this->Players->save($entity);
            return $this->redirect(['action' => 'view',$basePlayerId]);
        }
    }
    
    public function random($count = 1, $userId = null, $high = false) {
        $this->loadModel('Players');
        for ($i = 1;$i <= $count;$i++) {
            $cost = $this->getRandomCost($high);
            $playerInfos[] = $this->Players->find('all')
            ->where(['Players.status_cost' => $cost])
            ->contain(['Teams' => ['Seasons']])
            ->where(['Seasons.regular_flag' => true])
            ->order('random()')
            ->first()
            ;
        }
        $this->set('playerInfos', $playerInfos);
        
        if ($userId)
        {
            $this->loadModel('Users');
            $this->loadModel('Cards');
            $user = $this->Users->get($userId);
            $user->point -= 1000 * $count;
            // ƒf[ƒ^‚ª‚ ‚éê‡‚Æ‚È‚¢ê‡‚Åˆ—‚ð•Ï‚¦‚é
            foreach ($playerInfos as $playerInfo) {
                $check = $this->Cards->find('all')
                    ->where(['Cards.user_id' => $userId])
                    ->where(['Cards.player_id' => $playerInfo->id])
                    ->first()
                    ;
                if (empty($check)) {
                    $cardEntity = $this->Cards->newEntity();
                    $cardEntity->user_id =$userId;
                    $cardEntity->player_id =$playerInfo->id;
                    $this->Cards->save($cardEntity);
                } else {
                    $update = [
                        'meat_plus',
                        'power_plus',
                        'speed_plus',
                        'bant_plus',
                        'defense_plus',
                        'mental_plus',
                    ];
                    shuffle($update);
                    $check->{$update[0]}++;
                    $this->Cards->save($check);
                }
            }
        }
        if ($count > 300){exit;}
        $this->set('count', $count);
        $this->set('userId', $userId);
        $this->set('high', $high);
        $this->set('playerInfos', $playerInfos);
    }
    
    private function getRandomCost($high)
    {
    	if ($high == false) {
        $costCheck = [
            1 => 1500, //15
            2 => 3000, // 15
            3 => 5000, //25
            4 => 7500, //25
            5 => 9000, //15
            6 => 9700, // 7
            7 => 9920, // 2.2
            8 => 9970, //0.5
            9 => 9995, // 0.25
            10 => 10000 // 0.05
        ];
        } else {
        $costCheck = [
            6 => 6000, // 60
            7 => 8500, // 25
            8 => 9400, //9
            9 => 9800, // 4
            10 => 10000 // 2
        ];
        }
        $random = rand(1, 10000);
        foreach ($costCheck as $k => $v) {
            if ($random <= $v) {
                return $k;
            }
        }
        
        return $k;
//        debug($random);
//        exit;
    }
    
    
    public function lists($cost = null) {
        $this->loadModel('Players');
        $playerInfos = $this->Players->find('all')
        ->contain(['Teams' => ['Seasons']])
        ->where(['Seasons.regular_flag' => true])
        ->order(['Players.id' => 'ASC'])
        ;
        if (!is_null($cost)) {
            $playerInfos->where(['Players.status_cost' => $cost]);
        }
        $this->set('playerInfos', $playerInfos);
        $this->render('random');
    }
    
}
