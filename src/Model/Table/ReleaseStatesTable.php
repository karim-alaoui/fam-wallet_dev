<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReleaseStates Model
 *
 * @method \App\Model\Entity\ReleaseState get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReleaseState newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ReleaseState[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReleaseState|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReleaseState saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReleaseState patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReleaseState[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReleaseState findOrCreate($search, callable $callback = null, $options = [])
 */
class ReleaseStatesTable extends Table
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

        $this->setTable('release_states');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Stampcards', [
            'foreignKey' => 'release_id',
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
            ->scalar('status')
            ->maxLength('status', 255)
            ->notEmptyString('status');

        $validator
            ->dateTime('create_at')
            ->notEmptyDateTime('create_at');

        $validator
            ->dateTime('update_at')
            ->notEmptyDateTime('update_at');

        return $validator;
    }
}
