<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
	
	public function beforeRender(\Cake\Event\Event $event) 
	
	{
		$this->set('statusPositionLists', Configure::read('statusPositionLists'));
		$this->set('statusPositionShortLists', Configure::read('statusPositionShortLists'));
	}

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        
        $members = json_decode($user->card_member, true);

        $this->set('user', $user);
        $this->set('members', $members);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Players');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            $user->point = 100000;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                // スターターセット
                $playerInfos = [];
                $pitcherCount = 12;
                $pitcherSenpatsuCount = 5;
                $batterCount = 14;
                $batterCatcherCount = 1;
                $batter1bCount = 1;
                $batter2bCount = 1;
                $batter3bCount = 1;
                $batterSsCount = 1;
                $batterOfCount = 3;
                $costLists = [
                5,4,4,4,3,3,3,3,3,3,3,2,2,2,2,2,2,2,2,1,1,1,1,1,1
                ];
                shuffle($costLists);
                // メインカード
                $mainInfo = $this->Players->find('all')
                    // 6-8の間でランダム
                    ->where(['Players.status_cost IN' => [6,7,8]])
                    ->contain(['Teams' => ['Seasons']])
                    ->where(['Seasons.regular_flag' => true])
                   ->order('random()')
                   ->first()
               ;
               if ($mainInfo->type_p === null) {
                   $batterCount--;
                   // 捕手
                   if ($mainInfo->status_position == 2) {
                       $batterCatcherCount--;
                   } elseif ($mainInfo->status_position == 3) {
                       $batter1bCount--;
                   } elseif ($mainInfo->status_position == 4) {
                       $batter2bCount--;
                   } elseif ($mainInfo->status_position == 5) {
                       $batter3bCount--;
                   } elseif ($mainInfo->status_position == 6) {
                       $batterSsCount--;
                   } elseif ($mainInfo->status_position == 7) {
                       $batterOfCount--;
                   }
                   
               } else {
                   $pitcherCount--;
                   // 先発
                   if ($mainInfo->status_position == 11) {
                       $pitcherSenpatsuCount--;
                   }
               }
               $playerInfos[] = $mainInfo;
               // ピッチャー
               $checkIds = [];
               $checkIds[] = $mainInfo->id;
               // 先発
               for ($i = 1;$i <= $pitcherSenpatsuCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS NOT' => null])
	                    ->where(['Players.status_position' => 11])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
                   $pitcherCount--;
               }
               // その他P
               for ($i = 1;$i <= $pitcherCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS NOT' => null])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                    ->where(['Players.status_position !=' => 11])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
               }
               // バッター
               for ($i = 1;$i <= $batterCatcherCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS' => null])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                    ->where(['Players.status_slider IS NOT' => null])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
                   $batterCount--;
               }
               for ($i = 1;$i <= $batter1bCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS' => null])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                    ->where(['Players.status_hslider IS NOT' => null])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
                   $batterCount--;
               }
               for ($i = 1;$i <= $batter2bCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS' => null])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                    ->where(['Players.status_cut IS NOT' => null])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
                   $batterCount--;
               }
               for ($i = 1;$i <= $batter3bCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS' => null])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                    ->where(['Players.status_curb IS NOT' => null])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
                   $batterCount--;
               }
               for ($i = 1;$i <= $batterSsCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS' => null])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                    ->where(['Players.status_scurb IS NOT' => null])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
                   $batterCount--;
               }
               for ($i = 1;$i <= $batterOfCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS' => null])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                    ->where(['Players.status_folk IS NOT' => null])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
                   $batterCount--;
               }
               for ($i = 1;$i <= $batterCount;$i++) {
	                $player = $this->Players->find('all')
	                    // 1-4の間でランダム
	                    ->where(['Players.status_cost' => array_shift($costLists)])
	                    ->contain(['Teams' => ['Seasons']])
	                    ->where(['Seasons.regular_flag' => true])
	                    ->where(['Players.type_p IS' => null])
	                    ->where(['Players.id NOT IN' => $checkIds])
	                   ->order('random()')
	                   ->first()
	               ;
	                $playerInfos[] = $player;
                   $checkIds = array_merge($checkIds, $this->Players->find('all')
                   	   ->where(['Teams.ryaku_name' => $player->team->ryaku_name])
                   	   ->where(['Players.no' => $player->no])
                   	   ->contain(['Teams'])
                   ->find('list',[
                       'valueField' => 'id'
                   ])->toArray()
                   );
               }
               $this->loadModel('Cards');
               foreach ($playerInfos as $playerInfo) {
                   $entity = $this->Cards->newEntity();
                   $entity->player_id = $playerInfo->id;
                   $entity->user_id = $user->id;
                   $this->Cards->save($entity);
               }
               
               // 自動スタメンセット
               $this->Cards->autoStamen($user->id);

                return $this->redirect(['action' => 'view', $user->id]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function cardlist($userId, $order = null)
    {
        $this->loadModel('Cards');
        $cardLists = $this->Cards->find('all')
        	->where(['Cards.user_id' => $userId])
        	->contain([
        		'Players' => [
        			'Teams' => [
        				'Seasons'
        			]
        		]
        	])
        	;
        if ($order == 'position') {
            $cardLists
            	->order(['Players.status_position' => 'ASC']);
        }
	    $cardLists
	    	->order(['Players.status_cost' => 'DESC'])
	    	->order(['Players.id' => 'DESC']);
        $this->set('cardLists', $cardLists);
        $this->set('userId', $userId);
    }
    public function carddetail($cardId)
    {
        $this->loadModel('Cards');
        $card = $this->Cards->find('all')
        	->where(['Cards.id' => $cardId])
        	->contain([
        		'Players' => [
        			'Teams' => [
        				'Seasons'
        			]
        		]
        	])
        	->first()
        	;
        
        $this->set('card', $card);
    }
    
    public function autostamen($userId)
    {
        $this->loadModel('Cards');
        $this->Cards->autoStamen($userId);
       return $this->redirect(['action' => 'view', $userId]);
    }
}
