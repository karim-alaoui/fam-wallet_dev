<?php
namespace App\Form;

use App\Model\Entity\AffiliaterApplication;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class AffiliaterApplicationForm extends Form
{
    protected function _buildSchema(Schema $schema)
    {
        // フィールドの設定
        return $schema
            ->addField('point', ['type' => 'text'])
            ->addField('myuser_id', 'integer')
            ->addField('affiliate_coupons', ['type' => 'array'])
            ->addField('consent', ['type' => 'checkbox']);
    }

    protected function _buildValidator(Validator $validator)
    {

        $validator
            // 値比較
            ->equals('consent', 1, '利用規約にご同意ください');

        $validator
            ->requirePresence('point', 'create', '換金額を入力して下さい')
            ->notEmptyString('point', '換金額を入力して下さい')
            ->greaterThanOrEqual('point', 1,'換金額を入力してください')
            ->greaterThanOrEqual('point', AffiliaterApplication::TRANSFER_FEE + 1, '申請金額は'.(AffiliaterApplication::TRANSFER_FEE + 1).'円以上を設定してください')
            ->add('point', 'maxPoint', [
                'rule' => function($value, $context) {
                    $myusers = TableRegistry::getTableLocator()->get('Myusers');
                    $user = $myusers->get($context['data']['myuser_id']);

                    if($value > $user->point) {
                        return false;
                    }
                    return true;
                },
                'message' => '換金額が保有ポイントを超えています'
            ])
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
