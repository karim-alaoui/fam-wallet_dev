<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AffiliaterCoupons Model
 *
 * @property \App\Model\Table\AffiliatersTable&\Cake\ORM\Association\BelongsTo $Affiliaters
 * @property \App\Model\Table\CouponsTable&\Cake\ORM\Association\BelongsTo $Coupons
 *
 * @method \App\Model\Entity\AffiliaterCoupon get($primaryKey, $options = [])
 * @method \App\Model\Entity\AffiliaterCoupon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AffiliaterCoupon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterCoupon|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliaterCoupon saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliaterCoupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterCoupon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterCoupon findOrCreate($search, callable $callback = null, $options = [])
 */
class AffiliaterCouponsTable extends Table
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

        $this->setTable('affiliater_coupons');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Myusers', [
            'foreignKey' => 'myuser_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Coupons', [
            'foreignKey' => 'coupon_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('AffiliaterChildCoupons', [
           'foreignKey' => 'affiliater_coupon_id'
        ]);
        $this->hasMany('AffiliaterPoints', [
            'foreignKey' => 'affiliater_coupon_id'
        ]);
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
            ->dateTime('create_at')
            ->notEmptyDateTime('create_at');

        $validator
            ->dateTime('update_at')
            ->notEmptyDateTime('update_at');

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
        $rules->add($rules->existsIn(['coupon_id'], 'Coupons'));

        return $rules;
    }
}
