<?php

namespace App\Interfaces;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\Table;

Interface IChildCoupon extends EntityInterface {

    public function getId();

    public function getParentId();

    public function getSerialNumber():string ;

    public function getAuthenticationToken(): string ;

    public function getToken(): string ;

    public function getModel(): Table;
}