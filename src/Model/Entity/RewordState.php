<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RewordState Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 */
class RewordState extends Entity
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
        'create_at' => true,
        'update_at' => true,
    ];
}
