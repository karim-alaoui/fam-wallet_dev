<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StampcardShops Controller
 *
 * @property \App\Model\Table\StampcardShopsTable $StampcardShops
 *
 * @method \App\Model\Entity\StampcardShop[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StampcardShopsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Shops', 'Stampcards'],
        ];
        $stampcardShops = $this->paginate($this->StampcardShops);

        $this->set(compact('stampcardShops'));
    }

    /**
     * View method
     *
     * @param string|null $id Stampcard Shop id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stampcardShop = $this->StampcardShops->get($id, [
            'contain' => ['Shops', 'Stampcards'],
        ]);

        $this->set('stampcardShop', $stampcardShop);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stampcardShop = $this->StampcardShops->newEntity();
        if ($this->request->is('post')) {
            $stampcardShop = $this->StampcardShops->patchEntity($stampcardShop, $this->request->getData());
            if ($this->StampcardShops->save($stampcardShop)) {
                $this->Flash->success(__('The stampcard shop has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The stampcard shop could not be saved. Please, try again.'));
        }
        $shops = $this->StampcardShops->Shops->find('list', ['limit' => 200]);
        $stamps = $this->StampcardShops->Stampcards->find('list', ['limit' => 200]);
        $this->set(compact('stampcardShop', 'shops', 'stamps'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Stampcard Shop id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $stampcardShop = $this->StampcardShops->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stampcardShop = $this->StampcardShops->patchEntity($stampcardShop, $this->request->getData());
            if ($this->StampcardShops->save($stampcardShop)) {
                $this->Flash->success(__('The stampcard shop has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The stampcard shop could not be saved. Please, try again.'));
        }
        $shops = $this->StampcardShops->Shops->find('list', ['limit' => 200]);
        $stamps = $this->StampcardShops->Stampcards->find('list', ['limit' => 200]);
        $this->set(compact('stampcardShop', 'shops', 'stamps'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Stampcard Shop id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stampcardShop = $this->StampcardShops->get($id);
        if ($this->StampcardShops->delete($stampcardShop)) {
            $this->Flash->success(__('The stampcard shop has been deleted.'));
        } else {
            $this->Flash->error(__('The stampcard shop could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
