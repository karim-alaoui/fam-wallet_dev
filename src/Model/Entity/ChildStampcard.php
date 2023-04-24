<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChildStampcard Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property string $serial_number
 * @property string $authentication_token
 * @property int $limit_count
 * @property string $dir_path
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\ChildStampcard $parent_child_stampcard
 * @property \App\Model\Entity\ChildStampcard[] $child_child_stampcards
 */
class ChildStampcard extends Entity
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
        'parent_id' => true,
        'serial_number' => true,
        'authentication_token' => true,
        'limit_count' => true,
        'dir_path' => true,
        'token' => true,
        'create_at' => true,
        'update_at' => true,
        'parent_child_stampcard' => true,
        'child_child_stampcards' => true,
    ];
}
