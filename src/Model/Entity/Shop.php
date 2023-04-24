<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Shop Entity
 *
 * @property int $id
 * @property string $name
 * @property int $company_id
 * @property string|null $introdaction
 * @property string|null $tel
 * @property string|null $address
 * @property string|null $homepage
 * @property string|null $line
 * @property string|null $twitter
 * @property string|null $facebook
 * @property string|null $instagram
 * @property string|resource|null $image
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\CouponShop[] $coupon_shops
 * @property \App\Model\Entity\MyuserShop[] $myuser_shops
 * @property \App\Model\Entity\StampcardShop[] $stampcard_shops
 */
class Shop extends Entity
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
        'name' => true,
        'company_id' => true,
        'introdaction' => true,
        'tel' => true,
        'address' => true,
        'homepage' => true,
        'line' => true,
        'twitter' => true,
        'facebook' => true,
        'instagram' => true,
        'image' => true,
        'create_at' => true,
        'update_at' => true,
        'company' => true,
        'coupon_shops' => true,
        'myuser_shops' => true,
        'stampcard_shops' => true,
    ];
}
