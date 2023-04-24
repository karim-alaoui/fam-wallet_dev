<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AffiliaterApplications Model
 *
 * @property \App\Model\Table\MyusersTable&\Cake\ORM\Association\BelongsTo $Myusers
 *
 * @method \App\Model\Entity\AffiliaterApplication get($primaryKey, $options = [])
 * @method \App\Model\Entity\AffiliaterApplication newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AffiliaterApplication[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterApplication|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliaterApplication saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliaterApplication patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterApplication[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliaterApplication findOrCreate($search, callable $callback = null, $options = [])
 */
class AffiliaterApplicationsTable extends Table
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

        $this->setTable('affiliater_applications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Myusers', [
            'foreignKey' => 'myuser_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Applications', [
            'foreignKey' => 'application_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsToMany('AffliaterPoints', [
            'joinTable' => 'application_point',
            'foreignKey' => 'affiliater_point_id'
        ]);

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
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
            ->boolean('is_transferred')
            ->notEmptyString('is_transferred');

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
        $rules->add($rules->existsIn(['myuser_id'], 'Myusers'));
        $rules->add($rules->existsIn(['application_id'], 'Applications'));

        return $rules;
    }
}
