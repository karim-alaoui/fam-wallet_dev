<?php
namespace App\Model\Entity;

use App\Interfaces\IChildCoupon;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * AffiliaterChildCoupon Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property string $serial_number
 * @property string $authentication_token
 * @property int $download_count
 * @property int $used_count
 * @property string $dir_path
 * @property string $token
 * @property string $pass_type_id
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\ParentAffiliaterChildCoupon $parent_affiliater_child_coupon
 * @property \App\Model\Entity\ChildAffiliaterChildCoupon[] $child_affiliater_child_coupons
 * @property AffiliaterCoupon $affiliater_coupon
 */
class AffiliaterChildCoupon extends Entity implements IChildCoupon
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
        'affiliater_coupon_id' => true,
        'serial_number' => true,
        'authentication_token' => true,
        'download_count' => true,
        'used_count' => true,
        'dir_path' => true,
        'token' => true,
        'pass_type_id' => true,
        'create_at' => true,
        'update_at' => true,
        'parent_affiliater_child_coupon' => true,
        'child_affiliater_child_coupons' => true,
    ];

    public function getId()
    {
        return $this->id;
    }

    public function getParentId()
    {
        return $this->parent_id;
    }

    public function getSerialNumber(): string
    {
        return $this->serial_number;
    }

    public function getAuthenticationToken(): string
    {
        return $this->authentication_token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getModel(): Table
    {
        return TableRegistry::getTableLocator()->get('AffiliaterChildCoupons');
    }

}
