<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Myuser;
use App\Services\AuthService;
use Cake\ORM\TableRegistry;
use Imagick;
/**
 * Shops Controller
 *
 * @property \App\Model\Table\ShopsTable $Shops
 *
 * @method \App\Model\Entity\Shop[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ShopsController extends AppController
{


    public function initialize()
    {
      parent::initialize();
      // login中のrole_id
      $this->login_user_role = $this->Auth->User('role_id');
      // login中のcompany_id
      $this->login_user_company = $this->Auth->User('company_id');
      $this->login_user_id = $this->Auth->User('role_id');
      $this->MyuserShops = TableRegistry::get('MyuserShops');
      $this->CouponShops = TableRegistry::get('CouponShops');
      $this->StampcardShops = TableRegistry::get('StampcardShops');

      // ディレクトリがなければ作成
      $shopImagesDir = WWW_ROOT.'img/shop_images';
      if (!is_dir($shopImagesDir)) {
        mkdir($shopImagesDir,0777, true);
      }
    }

    // 店舗一覧
    public function index()
    {
      if ($this->login_user_role == 1) {
        $shops = $this->Shops->shop_index($this->login_user_company);
        $shop_count = $shops->toArray();
      } else { //リーダー,メンバー
        // 対象ユーザIDのレコードを取得
        $shops = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->contain(['Shops']);
        // レコード数をカウント
        $shop_count = $this->MyuserShops->find('all')->where(['myuser_id' => $this->Auth->User('id')])->contain(['Shops'])->count();
      }

        $shops = $this->paginate($shops, ['limit' => 100]);
        $this->set(compact('shops', 'shop_count'));
    }

    public function new()
    {
      $shop = $this->Shops->newEntity();
      $myuser_shop = $this->MyuserShops->newEntity();
      if ($this->request->is('post')) {
        $shop = $this->Shops->patchEntity($shop, $this->request->getData());
        if ($this->Shops->save($shop)) {
          $data = [
            'myuser_id' => $this->login_user_id,
            'shop_id' => $shop->id
          ];
          $myuser_shop = $this->MyuserShops->patchEntity($myuser_shop, $data);
          $this->MyuserShops->save($myuser_shop);
          $this->Flash->success(__('店舗を追加しました。'));
          return $this->redirect(['action' => 'view', $shop->id]);
        }
        $this->Flash->error(__('店舗を追加出来ませんでした。'));
      }
      $companies = $this->Shops->Companies->find('list', ['limit' => 200]);
      $this->set(compact('shop', 'companies'));
    }

    // 詳細
    public function view($id = null)
    {
      $shop = $this->Shops->get($id, [
        'contain' => ['Companies', 'CouponShops', 'MyuserShops', 'StampcardShops'],
      ]);

      if (file_exists(WWW_ROOT.'img/shop_images/'.$shop->id.'/'.$shop->image)) {
        $file_check = 'true';
        $this->set(compact('file_check'));
      }

      $this->set('shop', $shop);
    }

    // 編集
    public function edit($id = null)
    {
      if (!AuthService::authCheck($this, [Myuser::ROLE_OWNER])) {
        return null;
      }

      $shop = $this->Shops->get($id);

      if (!AuthService::sameAuthUserCompanyCheck($this, $shop->company_id)) {
        return null;
      }

      // スタンプカード、クーポン、ユーザ所属店舗が一つ以上する場合のみ削除
      // 対象店舗に紐づいてるstamp_id
      $stampcard_record = $this->StampcardShops->find('all')->where(['shop_id' => $id])->select(['stamp_id']);
      // 対象店舗に紐づいているstamp_iaを全て抽出
      $stampcard_select = $this->StampcardShops->find('all')->where(['stamp_id IN' => $stampcard_record])->select(['stamp_id'])->extract('stamp_id')->toArray();
      // stamp_id数をカウント
      $stampcard_check = array_count_values($stampcard_select);

      // クーポン
      $coupon_record = $this->CouponShops->find('all')->where(['shop_id' => $id])->select(['coupon_id']);
      $coupon_select = $this->CouponShops->find('all')->where(['coupon_id IN' => $coupon_record])->select(['coupon_id'])->extract('coupon_id')->toArray();
      $coupon_check = array_count_values($coupon_select);

      // myusershops
      $myuser_shop_record = $this->MyuserShops->find('all')->where(['shop_id' => $id])->select(['myuser_id']);
      $myuser_shop_select = $this->MyuserShops->find('all')->where(['myuser_id IN' => $myuser_shop_record])->select(['myuser_id'])->extract('myuser_id')->toArray();
      $myuser_shop_check = array_count_values($myuser_shop_select);

      // スタンプカード、クーポン、ユーザ所属店舗が一つ以上存在
      if (!in_array(1, $stampcard_check) && !in_array(1, $coupon_check) && !in_array(1, $myuser_shop_check)) {
        $shop_result = 'true';
        $this->set(compact('shop_result'));
      }

      // オーナー以外でのアクセス禁
      if ($this->login_user_id != 1) {
        $this->redirect(['action' => 'index']);
      }


      if (file_exists(WWW_ROOT.'img/shop_images/'.$shop->id.'/'.$shop->image)) {
        $file_check = 'true';
        $this->set(compact('file_check'));
      }

      $data = $this->request->getData();

      if ($this->request->is(['patch', 'post', 'put'])) {
        if (!empty($data['image']['tmp_name'])) {
          if ($data['image']['type'] == 'image/jpg'
            || $data['image']['type'] == 'image/jpeg'
            || $data['image']['type'] == 'image/png'
            &&  $data['image']['size'] < 10000000) {

            $file = $data['image']['tmp_name'];
            $upload_path = WWW_ROOT.'img/shop_images/'.$id.'/';
            $save_file_name = uniqid('shop_', true).'.png';

            if (!is_dir($upload_path)) {
              mkdir($upload_path, true);
              chmod($upload_path, 0777);
            }

            // png形式
            if ($this->request->data['image']['type'] == 'image/png') {
              move_uploaded_file($file, $upload_path.'icon.png');
              move_uploaded_file($file, $upload_path.$save_file_name.'.png');
              if (file_exists($upload_path.'icon.png')) {
                chmod($upload_path.'icon.png', 0777);
              }
              //元の画像
              $original = imagecreatefrompng($upload_path.'icon.png');

            } else { // jpg形式
              move_uploaded_file($file, $upload_path.'icon.jpg');
              move_uploaded_file($file, $upload_path.$save_file_name.'.jpg');
              if (file_exists($upload_path.'icon.png')) {
                chmod($upload_path.'icon.png', 0777);
              }
              $original = imagecreatefromjpeg($upload_path.'icon.jpg');
            }

            $x = imagesx($original);
            $y = imagesy($original);

            //縮小先
            $resize = imagecreatetruecolor(60, 60);
            //ブレンドモードを無効
            imagealphablending($resize, false);
            //完全なアルファチャネル情報を保存するフラグをon
            imagesavealpha($resize, true);
            //縮小
            imagecopyresampled($resize, $original, 0, 0, 0, 0, 60, 60, $x, $y);
            //保存
            imagepng($resize, $upload_path.'icon.png');
            //片付け
            imagedestroy($original);
            imagedestroy($resize);

            copy($upload_path.'icon.png', $upload_path.'logo.png');
            chmod($upload_path.'logo.png', 0777);

            $before_image_name = $shop->image;
            $this->request = $this->request->withData('image', $save_file_name);
            $shop = $this->Shops->patchEntity($shop, $this->request->getData());
            if ($this->Shops->save($shop)) {
              copy($upload_path.'icon.png', $upload_path.$shop->image);
              chmod($upload_path.$shop->image, 0777);

//              if (file_exists($upload_path.$before_image_name)) {
//                unlink($upload_path.$before_image_name);
//              }
              $this->Flash->success(__('店舗情報を更新しました'));
              return $this->redirect(['action' => 'view', $shop->id]);
            } else {
              $this->Flash->success(__('店舗情報を更新できませんでした。'));
            }
          } else {
            $size_error = '10MB以下のpng、jpg画像を選択してください。';
            $this->set(compact('size_error'));
          }
        } else {
          $shop = $this->Shops->patchEntity($shop, $this->request->getData());
          if ($this->Shops->save($shop)) {
            $this->Flash->success(__('店舗情報を更新しました'));
            return $this->redirect(['action' => 'view', $shop->id]);
          } else {
            $this->Flash->success(__('店舗情報を更新できませんでした。'));
          }
        }
      }
      $this->set(compact('shop'));
    }

    // 削除
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shop = $this->Shops->get($id);
        if ($this->Shops->delete($shop)) {
            $this->Flash->success(__('店舗情報を削除しました。'));
        } else {
            $this->Flash->error(__('店舗情報を削除出来ませんでした。'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
