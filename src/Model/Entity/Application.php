<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Application Entity
 *
 * @property int $id
 * @property int $myuser_id
 * @property int $status_id
 * @property int $point
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\Myuser $myuser
 */
class Application extends Entity
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
        'status_id' => true,
        'point' => true,
        'create_at' => true,
        'update_at' => true,

        'myuser' => true,
    ];

    const STATUS_ID_UNPAID = 0;
    const STATUS_ID_PAID = 1;

    const STATUS_ID_NONE = 99;

    const STATUS_LIST = [
        self::STATUS_ID_UNPAID => '未払い',
        self::STATUS_ID_PAID => '支払い済み',
        self::STATUS_ID_NONE => '指定なし',
    ];
}
