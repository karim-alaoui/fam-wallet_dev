<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AffiliaterApplication Entity
 *
 * @property int $id
 * @property int $affiliater_application_id
 * @property int $affiliater_point_id

 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\Myuser $myuser
 */
class ApplicationPoint extends Entity
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
        'affiliater_application_id' => true,
        'affiliater_point_id' => true,
        'created' => true,
        'modified' => true,
    ];
}
