<?php

namespace App\Services;

use App\Model\Entity\Myuser;
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;

class AuthService {

    public static function authCheck(Controller $controller, array $roles = []) {
      $user = self::getFreshUser($controller);

      // ユーザー存在チェック
      if (!$user) {
        $logoutUrl = $controller->Auth->logout();
        $controller->redirect($logoutUrl);
        $controller->Flash->error(__('ユーザーが存在しません。再ログインしてください。'));
        return false;
      }

      // 権限チェック
      if (count($roles)) {
        foreach ($roles as $role) {
          if ($user['role_id'] == $role) {
            return true;
          }
        }
        self::redirect($controller, $user);
        $controller->Flash->error(__('アクセス権限がありません。'));
        return false;
      }

      return true;
    }

    public static function sameAuthUserCompanyCheck(Controller $controller, $companyId) {
      $user = self::getFreshUser($controller);

      if ($user['company_id'] != $companyId) {
        self::redirect($controller, $user);
        $controller->Flash->error(__('アクセス権限がありません。'));
      }
      return true;
    }

    public static function redirect(Controller $controller, $user) {
      $roleId = $user['role_id'];
      if ($roleId == Myuser::ROLE_ADMIN) {
        $controller->redirect('/admin');
        return;
      }

      if ($roleId == Myuser::ROLE_OWNER) {
        $controller->redirect('/'.$user['id']);
      }

      if ($roleId == Myuser::ROLE_LEADER) {
        $controller->redirect('/'.$user['id']);
      }

      if ($roleId == Myuser::ROLE_MEMBER) {
        $controller->redirect('/'.$user['id']);
      }

      if ($roleId == Myuser::ROLE_AFFILIATER) {
        $controller->redirect('/affiliaters/index/'.$user['id']);
      }
    }

    private static function getFreshUser(Controller $controller) {
      $user = $controller->Auth->user();
      $Myusers = TableRegistry::getTableLocator()->get('Myusers');
      $user = $Myusers->find()->where(['id' => $user['id']])->first();
      return $user;
    }
}
