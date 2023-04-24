<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Coupon Entity
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $serialnumber
 * @property string|null $server_url
 * @property string|null $authentication_token
 * @property string $limit
 * @property int $release_id
 * @property string $reword
 * @property string|null $address
 * @property string|null $longitude
 * @property string|null $latitude
 * @property string|null $relevant_text
 * @property string $background_color
 * @property string $foreground_color
 * @property \Cake\I18n\FrozenTime|null $before_expiry_date
 * @property \Cake\I18n\FrozenTime|null $after_expiry_date
 * @property int $company_id
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\ReleaseState $release_state
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\CouponShop[] $coupon_shops
 * @property AffiliaterChildCoupon[] $affiliater_child_coupons
 */
class Coupon extends Entity
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
        'title' => true,
        'content' => true,
        'limit' => true,
        'release_id' => true,
        'reword' => true,
        'address' => true,
        'longitude' => true,
        'latitude' => true,
        'relevant_text' => true,
        'background_color' => true,
        'foreground_color' => true,
        'before_expiry_date' => true,
        'after_expiry_date' => true,
        'company_id' => true,
        'rate' => true,
        'token' => true,
        'create_at' => true,
        'update_at' => true,
        'release_state' => true,
        'company' => true,
        'coupon_shops' => true,
    ];

    const RELEASE_SHOW = 1;
    const RELEASE_HIDE = 2;
}
