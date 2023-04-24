<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AffiliaterChildCoupons Model
 *
 * @property \App\Model\Table\AffiliaterChildCouponsTable&\Cake\ORM\Association\BelongsTo $ParentAffiliaterChildCoupons
 * @property \App\Model\Table\AffiliaterChildCouponsTable&\Cake\ORM\Association\HasMany $ChildAffiliaterChildCoupons
 *
 * @method \App\Model\Entity\AffiliaterChildCoupon get($primaryKey, $options = [])
 * @method \App\Model\Entity\AffiliaterChildCoupon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AffiliaterChildCoupon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterChildCoupon|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliaterChildCoupon saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliaterChildCoupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterChildCoupon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterChildCoupon findOrCreate($search, callable $callback = null, $options = [])
 */
class AffiliaterChildCouponsTable extends Table
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

        $this->setTable('affiliater_child_coupons');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Coupons', [
            'foreignKey' => 'parent_id',
        ]);

        $this->belongsTo('AffiliaterCoupons', [
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
            ->scalar('serial_number')
            ->maxLength('serial_number', 255)
            ->requirePresence('serial_number', 'create')
            ->notEmptyString('serial_number');

        $validator
            ->scalar('authentication_token')
            ->maxLength('authentication_token', 255)
            ->requirePresence('authentication_token', 'create')
            ->notEmptyString('authentication_token');

        $validator
            ->integer('download_count')
            ->notEmptyString('download_count');

        $validator
            ->integer('used_count')
            ->notEmptyString('used_count');

        $validator
            ->scalar('dir_path')
            ->maxLength('dir_path', 255)
            ->notEmptyString('dir_path');

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
        $rules->add($rules->existsIn(['parent_id'], 'Coupons'));
        $rules->add($rules->existsIn(['affiliater_coupon_id'], 'AffiliaterCoupons'));

        return $rules;
    }
}
