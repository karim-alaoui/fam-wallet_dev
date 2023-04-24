<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChildStampcards Model
 *
 * @property \App\Model\Table\ChildStampcardsTable&\Cake\ORM\Association\BelongsTo $ParentChildStampcards
 * @property \App\Model\Table\ChildStampcardsTable&\Cake\ORM\Association\HasMany $ChildChildStampcards
 *
 * @method \App\Model\Entity\ChildStampcard get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChildStampcard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChildStampcard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChildStampcard|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChildStampcard saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChildStampcard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChildStampcard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChildStampcard findOrCreate($search, callable $callback = null, $options = [])
 */
class ChildStampcardsTable extends Table
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

        $this->setTable('child_stampcards');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Stampcards', [
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildStampcardRewords', [
            'foreignKey' => 'child_id',
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
            ->integer('limit_count')
            ->requirePresence('limit_count', 'create')
            ->notEmptyString('limit_count');

        $validator
            ->scalar('dir_path')
            ->maxLength('dir_path', 255)
            ->requirePresence('dir_path', 'create')
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
        return $rules;
    }
}
