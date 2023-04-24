<?php
namespace App\View\Helper;

use App\Model\Entity\Coupon;
use Cake\I18n\Time;
use Cake\ORM\ResultSet;
use Cake\View\Helper;
use Cake\View\View;

/**
 * AffiliaterCoupons helper
 */
class AffiliaterCouponsHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function getRewordTypeString($type) {
        return $type == 1 ? '料率方式': '固定金額方式';
    }

    public function getRateString($type, $rate) {
        return $this->isRate($type) ? "{$rate}%" : "{$rate}P";
    }

    public function isRate($type) {
        return $type == 1;
    }

    public function getIsActive($showKey, $hideKey, $defaultShow = false) {
        $view = $this->getView();
        if(!$view->getRequest()->getQuery($showKey) &&
            !$view->getRequest()->getQuery($hideKey) &&
            $view->getRequest()->getQuery('public') == 'false' && !$defaultShow) {
            return 'is-active';
        }

        if(!$view->getRequest()->getQuery($showKey) &&
            !$view->getRequest()->getQuery($hideKey) &&
            $view->getRequest()->getQuery('public') == 'true' && $defaultShow) {
            return 'is-active';
        }

        if(!$view->getRequest()->getQuery($showKey)
            && !$view->getRequest()->getQuery($hideKey) && !$view->getRequest()->getQuery('public')) {
            return $defaultShow ? 'is-active' : '';
        }

        if($view->getRequest()->getQuery('public') == 'true' && $defaultShow) {
            return 'is-active';
        }

        if($view->getRequest()->getQuery('public') == 'false' && !$defaultShow) {
            return 'is-active';
        }
    }

    public function infoMessage(Coupon $coupon) {
        $usedSum = collection($coupon->affiliater_child_coupons)
            ->sumOf('used_count');

        if($coupon->limit != '無制限' && $coupon->limit <= $usedSum) {
            return [
                'text' => '特典を使用済みのため利用できません',
                'class' => 'info-red'
            ];
        }

        if(Time::now()->lt($coupon->before_expiry_date)) {
            $t = date_format($coupon->before_expiry_date, 'Y-m-d');
            return [
                'text' => 'このクーポンは'. $t. 'から使用可能です',
                'class' => 'info-red'
            ];
        }

        if(Time::now()->gt($coupon->after_expiry_date)) {
            return [
                'text' => '期限切れのため利用できません',
                'class' => 'info-red'
            ];
        }

        return [
            'text' => '利用できるクーポンです',
            'class' => 'info-orange'
        ];
    }

}
