<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * MyuserShops Controller
 *
 * @property \App\Model\Table\MyuserShopsTable $MyuserShops
 *
 * @method \App\Model\Entity\MyuserShop[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MyuserShopsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Shops'],
        ];
        $myuserShops = $this->paginate($this->MyuserShops);

        $this->set(compact('myuserShops'));
    }

    /**
     * View method
     *
     * @param string|null $id Myuser Shop id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $myuserShop = $this->MyuserShops->get($id, [
            'contain' => ['Shops'],
        ]);

        $this->set('myuserShop', $myuserShop);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $myuserShop = $this->MyuserShops->newEntity();
        if ($this->request->is('post')) {
            $myuserShop = $this->MyuserShops->patchEntity($myuserShop, $this->request->getData());
            if ($this->MyuserShops->save($myuserShop)) {
                $this->Flash->success(__('The myuser shop has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The myuser shop could not be saved. Please, try again.'));
        }
        $shops = $this->MyuserShops->Shops->find('list', ['limit' => 200]);
        $this->set(compact('myuserShop', 'shops'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Myuser Shop id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $myuserShop = $this->MyuserShops->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $myuserShop = $this->MyuserShops->patchEntity($myuserShop, $this->request->getData());
            if ($this->MyuserShops->save($myuserShop)) {
                $this->Flash->success(__('The myuser shop has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The myuser shop could not be saved. Please, try again.'));
        }
        $shops = $this->MyuserShops->Shops->find('list', ['limit' => 200]);
        $this->set(compact('myuserShop', 'shops'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Myuser Shop id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $myuserShop = $this->MyuserShops->get($id);
        if ($this->MyuserShops->delete($myuserShop)) {
            $this->Flash->success(__('The myuser shop has been deleted.'));
        } else {
            $this->Flash->error(__('The myuser shop could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
