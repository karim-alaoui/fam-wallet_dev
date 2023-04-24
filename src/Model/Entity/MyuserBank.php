<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class MyuserBank extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'myuser_id' => true,
        'account_holder_name' => true,
        'bank_name' => true,
        'branch' => true,
        'deposit_type' => true,
        'account_number' => true,
        'created' => true,
        'modified' => true,
    ];

    const DEPOSIT_TYPE_FUTUU = 1;
    const DEPOSIT_TYPE_TOUZA = 2;
    const DEPOSIT_TYPE_TYOTIKU = 3;

    const DEPOSIT_TYPE_LIST = [
        self::DEPOSIT_TYPE_FUTUU => '普通',
        self::DEPOSIT_TYPE_TOUZA => '当座',
        self::DEPOSIT_TYPE_TYOTIKU => '貯蓄'
    ];

    public function getDepositTypeName($type) {
        return $type ? self::DEPOSIT_TYPE_LIST[$type] : '';
    }
}
