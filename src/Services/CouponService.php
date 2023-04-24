<?php

namespace App\Services;

use App\Model\Entity\AffiliaterChildCoupon;
use App\Model\Entity\AffiliaterCoupon;
use App\Model\Entity\ChildCoupon;
use App\Model\Entity\Coupon;
use App\Model\Entity\Myuser;
use App\Traits\Transactionable;
use Cake\ORM\TableRegistry;

class CouponService {

    use Transactionable;

    public function __construct()
    {
    }

    /**
     * @param AffiliaterChildCoupon $childCoupon
     * @param null $price
     * @return bool
     */
    public function useAffiliaterCoupon($useCouponUserId, AffiliaterChildCoupon $childCoupon, $price = null) {
        $point = $this->getPoint($childCoupon->affiliater_coupon, $price);
//        $data = ['used_count' => $childCoupon->used_count + 1];

        $success = true;
        $this->callTransaction(function() use($childCoupon, $point, $useCouponUserId){
//            $affiliaterChildCouponsTable = TableRegistry::getTableLocator()->get('AffiliaterChildCoupons');
//            $affiliaterChildCoupon = $affiliaterChildCouponsTable->patchEntity($childCoupon, $data);
//            $affiliaterChildCouponsTable->saveOrFail($affiliaterChildCoupon);

//            $myusersTable = TableRegistry::getTableLocator()->get('Myusers');
//            $data = ['point' => $childCoupon->affiliater_coupon->myuser->point + $point];
//            $myuser = $myusersTable->patchEntity($childCoupon->affiliater_coupon->myuser, $data);
//            $myusersTable->saveOrFail($myuser);

            $this->addUseCountAffiaterChildCoupon($childCoupon);
            $this->addPoint($childCoupon, $point);

            $this->setIsUseAffiaterCoupon($childCoupon, $useCouponUserId);

        }, function(\Exception $exception) use(&$success){
            $success = false;
        });

        return $success;
    }

    /**
     * @param AffiliaterChildCoupon $childCoupon
     * @param null $price
     * @return bool
     */
    public function useCoupon($useCouponUserId, Coupon $coupon) {

        $success = true;
        $this->callTransaction(function() use($coupon, $useCouponUserId){
            $this->setIsUseAffiaterCouponFromCoupon($coupon, $useCouponUserId);

        }, function(\Exception $exception) use(&$success){
            dd($exception);
            $success = false;
        });

        return $success;
    }

    private function addUseCountAffiaterChildCoupon($childCoupon) {
        $data = ['used_count' => $childCoupon->used_count + 1];
        $affiliaterChildCouponsTable = TableRegistry::getTableLocator()->get('AffiliaterChildCoupons');
        $affiliaterChildCoupon = $affiliaterChildCouponsTable->patchEntity($childCoupon, $data);
        $affiliaterChildCouponsTable->saveOrFail($affiliaterChildCoupon);
    }

    private function addPoint($childCoupon, $point) {
        if (!$point) {
            return;
        }
        $this->addPointMyUser($childCoupon, $point);
        $this->addAffiliaterPoint($childCoupon, $point);
    }

    private function addPointMyUser($childCoupon, $point) {
        $myusersTable = TableRegistry::getTableLocator()->get('Myusers');
        $data = ['point' => $childCoupon->affiliater_coupon->myuser->point + $point];
        $myuser = $myusersTable->patchEntity($childCoupon->affiliater_coupon->myuser, $data);
        $myusersTable->saveOrFail($myuser);
    }

    /**
     * @param AffiliaterCoupon $coupon
     * @param null $price
     * @return int|void
     */
    private function getPoint(AffiliaterCoupon $coupon, $price = null) {
        if (!$price) {
            return;
        }

        if($coupon->type === AffiliaterCoupon::TYPE_RATE) {
            return (int)floor(($price / 100) * $coupon->rate);
        }

        if($coupon->type === AffiliaterCoupon::TYPE_FIXED) {
            return $coupon->rate;
        }
    }

    private function addAffiliaterPoint($childCoupon, $point) {
        $pointsTable = TableRegistry::getTableLocator()->get('AffiliaterPoints');
        $points = $pointsTable->newEntity();

        $coupon = $childCoupon->affiliater_coupon;

        $data = [
            'myuser_id' => $coupon->myuser_id,
            'affiliater_coupon_id' => $coupon->id,
            'point' => $point
        ];

        $entity = $pointsTable->patchEntity($points, $data);
        $pointsTable->saveOrFail($entity);
    }

    private function setIsUseAffiaterCoupon($childCoupon, $useCouponUserId) {
        $coupon = $childCoupon->affiliater_coupon;

        $affiliaterId = $coupon->myuser_id;

        if ($useCouponUserId != $affiliaterId) {
            return;
        }

        $data = ['is_use' => true];
        $affiliaterCouponsTable = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
        $affiliaterCoupon = $affiliaterCouponsTable->patchEntity($childCoupon->affiliater_coupon, $data);
        $affiliaterCouponsTable->saveOrFail($affiliaterCoupon);

    }

    private function setIsUseAffiaterCouponFromCoupon($coupon, $useCouponUserId) {
        $AffiliaterCoupons = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
        $affliaterCoupon = $AffiliaterCoupons->find()
            ->where(['myuser_id' => $useCouponUserId, 'coupon_id' => $coupon->id])->first();

        $data = ['is_use' => true];
        $affiliaterCouponsTable = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
        $affiliaterCoupon = $affiliaterCouponsTable->patchEntity($affliaterCoupon, $data);
        $affiliaterCouponsTable->saveOrFail($affiliaterCoupon);

    }
}
