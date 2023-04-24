<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AffiliaterPoint Entity
 *
 * @property int $id
 * @property int $myuser_id
 * @property int $coupon_id
 * @property int $point
 * @property bool $is_applied
 * @property bool $is_transferred
 *
 * @property \App\Model\Entity\Myuser $myuser
 * @property \App\Model\Entity\Coupon $coupon
 */
class AffiliaterPoint extends Entity
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
        'affiliater_coupon_id' => true,
        'point' => true,
        'is_applied' => true,
        'is_transferred' => true,
        'myuser' => true,
        'coupon' => true,
        'affliater_coupon' => true,
        'create_at' => true,
        'update_at' => true
    ];
}
