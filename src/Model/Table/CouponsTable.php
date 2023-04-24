<?php
namespace App\Model\Table;

use App\Model\Entity\Coupon;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Coupons Model
 *
 * @property \App\Model\Table\ReleaseStatesTable&\Cake\ORM\Association\BelongsTo $ReleaseStates
 * @property \App\Model\Table\CompaniesTable&\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\CouponShopsTable&\Cake\ORM\Association\HasMany $CouponShops
 *
 * @method \App\Model\Entity\Coupon get($primaryKey, $options = [])
 * @method \App\Model\Entity\Coupon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Coupon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Coupon|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Coupon saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Coupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Coupon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Coupon findOrCreate($search, callable $callback = null, $options = [])
 */
class CouponsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('coupons');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('ReleaseStates', [
            'foreignKey' => 'release_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('CouponShops', [
            'foreignKey' => 'coupon_id',
        ]);
        $this->hasMany('ChildCoupons', [
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('AffiliaterCoupons', [
            'foreignKey' => 'coupon_id',
            'dependent' => true
        ]);
        $this->hasMany('AffiliaterChildCoupons', [
            'foreignKey' => 'parent_id',
        ]);
    }

    public function my_company_coupon($company_id)
    {
      $result_record = $this->find('all')
      ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops'])
      ->order(['id' => 'desc'])
      ->where(['company_id' => $company_id])->toArray();

      return $result_record;
    }

//    public function get_release_record()
//    {
//      $result_record = $this->find('all')
//        ->where(['release_id' => 1]);
//
//      return $result_record;
//    }

    /*
    * controller: Coupons
    * action: Index
    * info: 公開、非公開カウント、全てのレコード抽出
    */
    public function total_release_record($company_id)
    {
      // count
      $release_record = $this->find('all')
        ->where(['company_id' => $company_id, 'release_id' => 1])
        ->count();

      // count
      $private_record = $this->find('all')
        ->where(['company_id' => $company_id, 'release_id' => 2])
        ->count();

      // all
      $result_record = $this->find('all')
        ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops', 'AffiliaterChildCoupons'])
        ->where(['company_id' => $company_id])
        ->order(['id' => 'DESC']);

      // 配列で複数返す
      return [$release_record, $private_record, $result_record];
    }

     /*
    * controller: Coupons
    * action: Index
    * info: 検索
    */
    public function search_record($company_id, $shop_id)
    {
      // count
      $release_record = $this->find('all')
        ->where(['company_id' => $company_id, 'id IN' => $shop_id, 'release_id' => 1])->count();

      // count
      $private_record = $this->find('all')
        ->where(['company_id' => $company_id, 'id IN' => $shop_id, 'release_id' => 2])->count();

      // all
      $result_record = $this->find('all')
        ->contain(['ChildCoupons', 'CouponShops', 'CouponShops.Shops', 'AffiliaterChildCoupons'])
        ->where(['company_id' => $company_id, 'id IN' => $shop_id])
        ->order(['id' => 'DESC']);

      // 配列で複数返す
      return [$release_record, $private_record, $result_record];
    }

//    public function select_shop($shop_id)
//    {
//      $return_record = $this->find('all')
//        ->contain(['CouponShops'])
//        ->where(['CouponShops.shop_id' => $shop_id]);
//      return $result_record;
//    }

    /*
  ¦ * controller: Analytics
  ¦ * action: Index/NarrowDown
  ¦ * info: 対象企業の公開中、非公開中のクーポン、スタンプカードレコードを抽出
    */
    public function analytics_coupon_record($company_id, $before_date, $after_date)
    {
      // 開始時刻のみ
      if ($after_date == null) {
        $result_record = $this->find('all')
          ->contain(['ChildCoupons'])
          ->where(['company_id' => $company_id, 'before_expiry_date >=' => $before_date])->extract('id')->toArray();

      // 終了時刻のみ
      } elseif ($before_date == null) {

        $result_record = $this->find('all')
          ->contain(['ChildCoupons'])
          ->where(['company_id' => $company_id, 'after_expiry_date <=' => $after_date])->extract('id')->toArray();

      // 開始時刻、終了時刻が存在
      } else {
        $result_record = $this->find('all')
          ->contain(['ChildCoupons'])
          ->where(['company_id' => $company_id, 'after_expiry_date >=' => $before_date, 'after_expiry_date <=' => $after_date])->extract('id')->toArray();
      }

      return $result_record;
    }

    /*
    * controller: Analytics
    * action: Index/NarrowDown
    * info: リーダー、メンバー
    */
    public function analytics_coupon_leader_record($company_id, $id_list, $before_date, $after_date)
    {
      // 開始時刻のみ
      if ($after_date == null) {
        $result_record = $this->find('all')
          ->contain(['ChildCoupons'])
          ->where(['company_id' => $company_id, 'id IN' => $id_list, 'before_expiry_date >=' => $before_date])->extract('id')->toArray();

      } elseif ($before_date == null) {
        $result_record = $this->find('all')
          ->contain(['ChildCoupons'])
          ->where(['company_id' => $company_id, 'id IN' => $id_list, 'after_expiry_date <=' => $after_date])->extract('id')->toArray();

      } else {
        $result_record = $this->find('all')
          ->contain(['ChildCoupons'])
          ->where(['company_id' => $company_id, 'id IN' => $id_list, 'after_expiry_date >=' => $before_date, 'after_expiry_date <=' => $after_date])->extract('id')->toArray();
      }

      return $result_record;
    }


    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 20)
            ->notEmptyString('title');

        $validator
            ->scalar('content')
            ->maxLength('content', 60)
            ->notEmptyString('content');

        $validator
            ->scalar('limit')
            ->maxLength('limit', 255)
            ->notEmptyString('limit');

        $validator
            ->scalar('reword')
            ->maxLength('reword', 255)
            ->requirePresence('reword', 'create')
            ->notEmptyString('reword');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->allowEmptyString('address');

        $validator
            ->scalar('longitude')
            ->maxLength('longitude', 255)
            ->allowEmptyString('longitude');

        $validator
            ->scalar('latitude')
            ->maxLength('latitude', 255)
            ->allowEmptyString('latitude');

        $validator
            ->scalar('relevant_text')
            ->maxLength('relevant_text', 60)
            ->allowEmptyString('relevant_text');

        $validator
            ->scalar('background_color')
            ->maxLength('background_color', 255)
            ->requirePresence('background_color', 'create')
            ->notEmptyString('background_color');

        $validator
            ->scalar('foreground_color')
            ->maxLength('foreground_color', 255)
            ->requirePresence('foreground_color', 'create')
            ->notEmptyString('foreground_color');

        /*$validator
            ->dateTime('before_expiry_date')
            ->allowEmptyDateTime('before_expiry_date');*/

        /*$validator
            ->dateTime('after_expiry_date')
            ->allowEmptyDateTime('after_expiry_date');*/

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

        $validator
            ->dateTime('create_at')
            ->notEmptyDateTime('create_at');

        $validator
            ->dateTime('update_at')
            ->notEmptyDateTime('update_at');

        $validator
            ->add('rate', 'custom', [
                'rule' => function($value, $context) {
                    if(!$value || $value < 5) {
                        return '5%から入力可能です';
                    }
                    return true;
                }
            ]);
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['release_id'], 'ReleaseStates'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
