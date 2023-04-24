<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChildStampcardReword Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property int $child_id
 * @property int $reword_point
 * @property int $state_id
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\ParentChildStampcardReword $parent_child_stampcard_reword
 * @property \App\Model\Entity\ChildStampcard $child_stampcard
 * @property \App\Model\Entity\RewordState $reword_state
 * @property \App\Model\Entity\ChildChildStampcardReword[] $child_child_stampcard_rewords
 */
class ChildStampcardReword extends Entity
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
        'child_id' => true,
        'reword_point' => true,
        'state_id' => true,
        'create_at' => true,
        'update_at' => true,
        'parent_child_stampcard_reword' => true,
        'child_stampcard' => true,
        'reword_state' => true,
        'child_child_stampcard_rewords' => true,
    ];
}
