<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\I18n\FrozenDate;
use phpDocumentor\Reflection\Types\Boolean;

class CouponForm extends Form
{
  protected function _buildSchema(Schema $schema)
  {
    // フィールドの設定
    return $schema
      ->addField('title', 'string')
      ->addField('content', 'string')
      ->addField('reword', 'string')
      ->addField('limit', ['type' => 'select'])
      ->addField('before_expiry_date', 'string')
      ->addField('after_expiry_date', 'string')
      ->addField('affiliater', ['type' => 'select'])
      ->addField('rate', 'integer')
      ->addField('is_affiliate', 'bool');
  }

  protected function _buildValidator(Validator $validator)
  {
    // バリデーション
    $validator
      ->notEmptyString('title', 'このフィールドを入力してください')
      // confirmとの一致判定
      ->maxLength('title', 10, '10字以内にしてください');

    $validator
      ->notEmptyString('content', 'このフィールドを入力してください')
      // 値比較
      ->maxLength('content', 50, '50字以内にして下さい');

    $validator
      ->notEmptyString('reword', 'このフィールドを入力してください')
      ->maxLength('reword', 10, '10字以内にしてください');

    $validator
      ->notEmptyString('limit', 'リスト内の項目を選択してください');

    $validator
      ->notEmptyString('before_expiry_date', 'リスト内の項目を選択してください')
      ->add('before_expiry_date', [
        'date_future' => [
          'rule' => function($value) {
            $today = date("Y/m/d");
//            $target = date("Y/m/d", strtotime($value));
            if (strtotime($today) <= strtotime($value)) {
              return true;
            } else {
              return false;
            }
           },
           'message' => '無効な日付が入力されています。'
         ]
       ]);

    $validator
      ->notEmptyString('after_expiry_date', 'リスト内の項目を選択してください')
      ->add('after_expiry_date', [
        'date_future' => [
          'rule' => function($value) {
            $today = date("Y/m/d");
//            $target = date("Y/m/d", strtotime($value));
            if (strtotime($today) <= strtotime($value)) {
              return true;
            } else {
              return false;
            }
           },
           'message' => '無効な日付が入力されています。'
         ]
       ]);

//      $validator->allowEmptyString('is_affiliate');
//      $validator
//          ->allowEmptyString('affiliater', 'アフィリエイターを選択して下さい', function($context) {
//            $data = $context['data'];
//            if(isset($data['is_affiliate']) && !$data['is_affiliate']) return true;
//
//            if(isset($data['affiliater']) && $data['affiliater']) {
//              return true;
//            }
//            return false;
//          });

      $validator
        ->allowEmptyString('rate', '報酬金額を設定してください', function($context) {
          $data = $context['data'];
          if(isset($data['is_affiliate']) && !$data['is_affiliate']) return true;

          if(isset($data['rate']) && $data['rate']) {
            return true;
          }
          return false;
        });

    return $validator;
  }

  protected function _execute(array $data)
  {
    // バリデーションが通った時
    // ここでは単にtrueを返すだけ。
    return true;
  }
}
