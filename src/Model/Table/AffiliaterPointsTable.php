<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AffiliaterPoints Model
 *
 * @property \App\Model\Table\MyusersTable&\Cake\ORM\Association\BelongsTo $Myusers
 * @property \App\Model\Table\CouponsTable&\Cake\ORM\Association\BelongsTo $Coupons
 *
 * @method \App\Model\Entity\AffiliaterPoint get($primaryKey, $options = [])
 * @method \App\Model\Entity\AffiliaterPoint newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AffiliaterPoint[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterPoint|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliaterPoint saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliaterPoint patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterPoint[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterPoint findOrCreate($search, callable $callback = null, $options = [])
 */
class AffiliaterPointsTable extends Table
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

        $this->setTable('affiliater_points');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Myusers', [
            'foreignKey' => 'myuser_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('AffiliaterCoupons', [
            'foreignKey' => 'affiliater_coupon_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsToMany('AffiliaterApplications', [
            'joinTable' => 'application_point',
            'foreignKey' => 'affiliater_application_id'
        ]);

        $this->hasMany('ApplicationPoint', [
            'foreignKey' => 'affiliater_point_id'
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
            ->integer('point')
            ->requirePresence('point', 'create')
            ->notEmptyString('point');

        $validator
            ->boolean('is_applied')
            ->notEmptyString('is_applied');

        $validator
            ->boolean('is_transferred')
            ->notEmptyString('is_transferred');

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
        $rules->add($rules->existsIn(['myuser_id'], 'Myusers'));
        $rules->add($rules->existsIn(['affiliater_coupon_id'], 'AffiliaterCoupons'));

        return $rules;
    }
}
