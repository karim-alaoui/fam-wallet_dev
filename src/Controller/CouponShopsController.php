<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CouponShops Controller
 *
 * @property \App\Model\Table\CouponShopsTable $CouponShops
 *
 * @method \App\Model\Entity\CouponShop[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CouponShopsController extends AppController
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
        $couponShops = $this->paginate($this->CouponShops);

        $this->set(compact('couponShops'));
    }

    /**
     * View method
     *
     * @param string|null $id Coupon Shop id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $couponShop = $this->CouponShops->get($id, [
            'contain' => ['Shops'],
        ]);

        $this->set('couponShop', $couponShop);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $couponShop = $this->CouponShops->newEntity();
        if ($this->request->is('post')) {
            $couponShop = $this->CouponShops->patchEntity($couponShop, $this->request->getData());
            if ($this->CouponShops->save($couponShop)) {
                $this->Flash->success(__('The coupon shop has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The coupon shop could not be saved. Please, try again.'));
        }
        $shops = $this->CouponShops->Shops->find('list', ['limit' => 200]);
        $this->set(compact('couponShop', 'shops'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Coupon Shop id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $couponShop = $this->CouponShops->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $couponShop = $this->CouponShops->patchEntity($couponShop, $this->request->getData());
            if ($this->CouponShops->save($couponShop)) {
                $this->Flash->success(__('The coupon shop has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The coupon shop could not be saved. Please, try again.'));
        }
        $shops = $this->CouponShops->Shops->find('list', ['limit' => 200]);
        $this->set(compact('couponShop', 'shops'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Coupon Shop id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $couponShop = $this->CouponShops->get($id);
        if ($this->CouponShops->delete($couponShop)) {
            $this->Flash->success(__('The coupon shop has been deleted.'));
        } else {
            $this->Flash->error(__('The coupon shop could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
