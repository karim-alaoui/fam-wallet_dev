<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AffiliaterApplication Entity
 *
 * @property int $id
 * @property int $application_id
 * @property int $myuser_id
 * @property int $point
 * @property bool $is_transferred
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\Myuser $myuser
 */
class AffiliaterApplication extends Entity
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
        'application_id' => true,
        'myuser_id' => true,
        'point' => true,
        'company_id' => true,
        'status_id' => true,
        'is_transferred' => true,
        'create_at' => true,
        'update_at' => true,
        'myuser' => true,
    ];

    const STATUS_ID_APPLYING = 0;
    const STATUS_ID_APPROVED = 1;
    const STATUS_ID_PAID = 2;
    const STATUS_ID_ERROR = 3;

    const STATUS_LIST = [
        self::STATUS_ID_APPLYING => '申請中',
        self::STATUS_ID_APPROVED => '承認済み',
        self::STATUS_ID_PAID => '支払い済み',
        self::STATUS_ID_ERROR => '支払いエラー',
    ];

    //振り込み手数料
    const TRANSFER_FEE = 210;

    // Stripe手数料
    const STRIPE_COMMISSION_RATE = 10;
}
