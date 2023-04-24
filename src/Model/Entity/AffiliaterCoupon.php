<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AffiliaterCoupon Entity
 *
 * @property int $id
 * @property int $affiliater_id
 * @property int $coupon_id
 * @property int $type
 * @property int $rate
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\Affiliater $affiliater
 * @property \App\Model\Entity\Coupon $coupon
 */
class AffiliaterCoupon extends Entity
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
        'coupon_id' => true,
        'rate' => true,
        'is_use' => true,
        'create_at' => true,
        'update_at' => true,
        'affiliater' => true,
        'coupon' => true,
        'type' => true,
    ];

    const TYPE_RATE = 1;
    const TYPE_FIXED = 2;
}
