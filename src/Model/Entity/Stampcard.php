<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Stampcard Entity
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $serial_number
 * @property string|null $authentication_token
 * @property string|null $server_url
 * @property string|null $longitude
 * @property string|null $latitude
 * @property string|null $relevant_text
 * @property string $background_color
 * @property string $foreground_color
 * @property \Cake\I18n\FrozenTime $before_expiry_date
 * @property \Cake\I18n\FrozenTime $after_expiry_date
 * @property int $release_id
 * @property string|null $address
 * @property int $max_limit
 * @property int $company_id
 * @property \Cake\I18n\FrozenTime $create_at
 * @property \Cake\I18n\FrozenTime $update_at
 *
 * @property \App\Model\Entity\ReleaseState $release_state
 * @property \App\Model\Entity\StampcardReword[] $stampcard_rewords
 * @property \App\Model\Entity\StampcardShop[] $stampcard_shops
 */
class Stampcard extends Entity
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
        'title' => true,
        'content' => true,
        'reword' => true,
        'longitude' => true,
        'latitude' => true,
        'relevant_text' => true,
        'background_color' => true,
        'foreground_color' => true,
        'before_expiry_date' => true,
        'after_expiry_date' => true,
        'release_id' => true,
        'address' => true,
        'max_limit' => true,
        'company_id' => true,
        'token' => true,
        'create_at' => true,
        'update_at' => true,
        'release_state' => true,
        'stampcard_rewords' => true,
        'stampcard_shops' => true,
    ];
}
