<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class UserForm extends Form
{
  protected function _buildSchema(Schema $schema)
  {
    // フィールドの設定
    return $schema
      ->addField('username', ['type' => 'text'])
//      ->addField('name', ['type' => 'text'])
      ->addField('password', ['type' => 'password'])
      ->addField('password_confirm', ['type' => 'password'])
      ->addField('email', ['type' => 'email'])
      ->addField('consent', ['type' => 'checkbox']);
  }

  protected function _buildValidator(Validator $validator)
  {

    // バリデーション
    $validator
       // confirmとの一致判定
      ->add('password', [
        'password_confirm' => [
          'rule' => ['compareWith', 'password_confirm'],
          'message' => 'パスワードが一致しません。',
        ]
      ])
      ->add('password', 'validFormat' ,[
          'rule' => array('custom', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/'),
          'message' => 'パスワードは英数字で、大文字と小文字を使用する必要があります',
        ]
      )
      ->minLength('password', 8, '半角英数字8文字以上にして下さい。')
      ->notEmptyString('password', '半角英数字8文字以上で入力してください')
      ->requirePresence('password', 'create', '半角英数字8文字以上で入力してください')
      ->alphaNumeric('password', '半角英数字で入力してください')
    ;

    $validator
      // 値比較
      ->equals('consent', 'consent' == 0, '利用規約にご同意ください');

    $validator
      ->scalar('username')
      ->maxLength('username', 255)
      ->requirePresence('username', 'create', '名前を入力して下さい')
      ->notEmptyString('username', '名前を入力して下さい')
      ->add('username', [
        'unique' => [
          'rule' => function($value) {
            $myusers = TableRegistry::getTableLocator()->get('Myusers');
            $myuser = $myusers->find()->where(['username' => $value])->first();

            if ($myuser) {
              return false;
            }
            return true;
          },
          'message' => 'ユーザー名はすでに登録されています'
        ]
      ]);

//    $validator
//      ->scalar('name')
//      ->maxLength('name', 255)
//      ->requirePresence('name', 'create', '会社名を入力して下さい')
//      ->notEmptyString('name', '会社名を入力して下さい');

    $validator
      ->email('email', false, '正しいメールアドレスを入力してください')
      ->requirePresence('email', 'メールアドレスを入力してください')
      ->notEmptyString('email', 'メールアドレスを入力してください')
    ;

    return $validator;
  }

  protected function _execute(array $data)
  {
    // バリデーションが通った時
    // ここでは単にtrueを返すだけ。
    return true;
  }
}
