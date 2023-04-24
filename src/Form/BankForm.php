<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class BankForm extends Form
{
  protected function _buildSchema(Schema $schema)
  {
    // フィールドの設定
    return $schema
        ->addField('myuser_id', ['type' => 'integer'])
        ->addField('account_holder_name', ['type' => 'string'])
        ->addField('bank_name', ['type' => 'string'])
        ->addField('branch', ['type' => 'string'])
        ->addField('account_number', ['type' => 'string'])
        ->addField('deposit_type', ['type' => 'integer']);
  }

  protected function _buildValidator(Validator $validator)
  {
      // バリデーション
      $validator
          ->scalar('account_holder_name')
          ->maxLength('account_holder_name', 255)
          ->requirePresence('account_holder_name', 'create', '口座名義人を入力して下さい')
          ->notEmptyString('account_holder_name', '口座名義人を入力して下さい');

      $validator
          ->scalar('bank_name')
          ->maxLength('bank_name', 255)
          ->requirePresence('bank_name', 'create', '金融機関を入力して下さい')
          ->notEmptyString('bank_name', '金融機関を入力して下さい');

      $validator
          ->scalar('branch')
          ->maxLength('branch', 255)
          ->requirePresence('branch', 'create', '支店名を入力して下さい')
          ->notEmptyString('branch', '支店名を入力して下さい');

      $validator
          ->scalar('account_number')
          ->maxLength('account_number', 255)
          ->requirePresence('account_number', 'create', '口座番号を入力して下さい')
          ->notEmptyString('account_number', '口座番号を入力して下さい');

    return $validator;
  }

  protected function _execute(array $data)
  {
    // バリデーションが通った時
    // ここでは単にtrueを返すだけ。
    return true;
  }
}
