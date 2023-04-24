<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MyuserShop Entity
 *
 * @property int $id
 * @property int $myuser_id
 * @property int $shop_id
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\Shop $shop
 */
class MyuserShop extends Entity
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
        'shop_id' => true,
        'create_at' => true,
        'update_at' => true,
        'shop' => true,
    ];
}
