<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use CakeDC\Users\Model\Entity\User;

/**
 * Myuser Entity
 *
 * @property int $id
 * @property string $username
 * @property string|null $email
 * @property int $company_id
 * @property int $role_id
 * @property string $password
 * @property string|null $token
 * @property \Cake\I18n\FrozenTime|null $token_expires
 * @property \Cake\I18n\FrozenTime|null $activation_date
 * @property bool $active
 * @property int $point
 * @property string $customer
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Role $role
 */
class Myuser extends User
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
        'username' => true,
        'email' => true,
        'company_id' => true,
        'role_id' => true,
        'password' => true,
        'token' => true,
        'token_expires' => true,
        'activation_date' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'company' => true,
        'role' => true,
        'myuser_shops' => true,
        'point' => true,
        'customer' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token',
    ];

    const ROLE_OWNER = 1;
    const ROLE_LEADER = 2;
    const ROLE_MEMBER = 3;
    const ROLE_AFFILIATER = 4;
    const ROLE_ADMIN = 99;
}
