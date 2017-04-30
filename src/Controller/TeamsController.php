<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Teams Controller
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends AppController
{

    public function month($teamId = null, $year = null, $month = null)
    {
        $team = $this->Teams->get($teamId);
        $this->loadModel('Games');
        
        $gameSets = $this->Games->find('all')
        	->where(['OR' => 
        		[
        			'Games.home_team_id' => $teamId,
        			'Games.visitor_team_id' => $teamId
        		],
        	])
        	->where(['DATE_PART(\'YEAR\', Games.date) = ' . $year])
        	->where(['DATE_PART(\'MONTH\', Games.date) = ' . $month])
        	->contain('HomeTeams')
        	->contain('VisitorTeams')
        	->contain('WinPitchers')
        	->contain('LosePitchers')
        	->contain('SavePitchers')
        	
        	->order(['Games.date' => 'ASC']);
        $games = [];
        foreach ($gameSets as $gameSet) {
        	$games[$gameSet->date->format('Ymd')] = $gameSet;
        }

        $monthSets = $this->Games->find('all')
        	->where(['OR' => 
        		[
        			'Games.home_team_id' => $teamId,
        			'Games.visitor_team_id' => $teamId
        		],
        	])
        	->select(['year' => 'DATE_PART(\'YEAR\', Games.date)'])
        	->select(['month' => 'DATE_PART(\'MONTH\', Games.date)'])
        	->group('DATE_PART(\'YEAR\', Games.date)')
        	->group('DATE_PART(\'MONTH\', Games.date)')
        	->order(['DATE_PART(\'YEAR\', Games.date)' => 'ASC'])
        	->order(['DATE_PART(\'MONTH\', Games.date)' => 'ASC'])
        	;

        $this->set(compact('monthSets'));

        $this->set('teamId', $teamId);
        $this->set('team', $team);
        $this->set('games', $games);
        $this->set('year', $year);
        $this->set('month', $month);
    }
    


    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Seasons']
        ];
        $teams = $this->paginate($this->Teams);

        $this->set(compact('teams'));
        $this->set('_serialize', ['teams']);
    }

    /**
     * View method
     *
     * @param string|null $id Team id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $team = $this->Teams->get($id, [
            'contain' => ['Seasons']
        ]);

        $this->set('team', $team);
        $this->set('_serialize', ['team']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $team = $this->Teams->newEntity();
        if ($this->request->is('post')) {
            $team = $this->Teams->patchEntity($team, $this->request->data);
            if ($this->Teams->save($team)) {
                $this->Flash->success(__('The team has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The team could not be saved. Please, try again.'));
            }
        }
        $seasons = $this->Teams->Seasons->find('list', ['limit' => 200]);
        $this->set(compact('team', 'seasons'));
        $this->set('_serialize', ['team']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Team id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $team = $this->Teams->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $team = $this->Teams->patchEntity($team, $this->request->data);
            if ($this->Teams->save($team)) {
                $this->Flash->success(__('The team has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The team could not be saved. Please, try again.'));
            }
        }
        $seasons = $this->Teams->Seasons->find('list', ['limit' => 200]);
        $this->set(compact('team', 'seasons'));
        $this->set('_serialize', ['team']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Team id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $team = $this->Teams->get($id);
        if ($this->Teams->delete($team)) {
            $this->Flash->success(__('The team has been deleted.'));
        } else {
            $this->Flash->error(__('The team could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
