<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class StampcardForm extends Form 
{
    protected function _buildSchema(Schema $schema)
  {
    // フィールドの設定
    return $schema
      ->addField('title', 'string')
      ->addField('content', 'string')
      ->addField('reword', 'string')
      ->addField('max_limit', 'string')
      ->addField('before_expiry_date', 'string')
      ->addField('after_expiry_date', 'string')
      ->addField('relevant_text', 'string');
  }

  protected function _buildValidator(Validator $validator)
  {
    // バリデーション
    $validator
      ->notEmptyString('title', 'このフィールドを入力してください')
      // confirmとの一致判定
      ->maxLength('title', 20, '半角英数字20字以内にしてください');

    $validator
      ->notEmptyString('content', 'このフィールドを入力してください')
      // 値比較
      ->maxLength('content', 60, '記載する内容は60文字以内にしてください');

    $validator
      ->notEmptyString('reword', 'このフィールドを入力してください')
      ->maxLength('benefits', 20, '半角英数字20字以内にしてください');

    $validator
      ->notEmptyString('max_limit', 'リスト内の項目を選択してください');

    $validator
      ->notEmptyString('before_expiry_date', 'リスト内の項目を選択してください')
      ->add('before_expiry_date', [
        'date_future' => [
          'rule' => function($value) {
            $today = date("Y/m/d");
            $target = date("Y/m/d", strtotime($value));
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
            $target = date("Y/m/d", strtotime($value));
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
       ->allowEmptyString('relevant_text')
      ->maxLength('relevant_text', 50, '半角英数字50字以内にしてください');

    return $validator;
  }

  protected function _execute(array $data)
  {
    // バリデーションが通った時
    // ここでは単にtrueを返すだけ。
    return true;
  }
}
