<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StampcardReword Entity
 *
 * @property int $id
 * @property int $stamp_id
 * @property string $reword
 * @property int $reword_point
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\Stampcard $stampcard
 */
class StampcardReword extends Entity
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
        'stamp_id' => true,
        'reword' => true,
        'reword_point' => true,
        'create_at' => true,
        'update_at' => true,
        'stampcard' => true,
    ];
}
