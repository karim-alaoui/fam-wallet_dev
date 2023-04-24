<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Device Entity
 *
 * @property int $id
 * @property int $wallet_id
 * @property string $device_id
 * @property string|null $push_token
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\Wallet $wallet
 * @property \App\Model\Entity\Device[] $devices
 */
class Device extends Entity
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
        'wallet_id' => true,
        'device_id' => true,
        'push_token' => true,
        'create_at' => true,
        'update_at' => true,
        'wallet' => true,
        'devices' => true,
    ];
}
