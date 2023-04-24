<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class SearchAppForm extends Form
{
  protected function _buildSchema(Schema $schema)
  {
    // フィールドの設定
    return $schema
        ->addField('before_date', ['type' => 'string'])
        ->addField('after_date', ['type' => 'string'])
      ->addField('status', ['type' => 'integer']);
  }

  protected function _buildValidator(Validator $validator)
  {
    return $validator;
  }

  protected function _execute(array $data)
  {
    // バリデーションが通った時
    // ここでは単にtrueを返すだけ。
    return true;
  }
}
