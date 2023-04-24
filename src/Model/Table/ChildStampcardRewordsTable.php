<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChildStampcardRewords Model
 *
 * @property \App\Model\Table\ChildStampcardRewordsTable&\Cake\ORM\Association\BelongsTo $ParentChildStampcardRewords
 * @property \App\Model\Table\ChildStampcardsTable&\Cake\ORM\Association\BelongsTo $ChildStampcards
 * @property \App\Model\Table\RewordStatesTable&\Cake\ORM\Association\BelongsTo $RewordStates
 * @property \App\Model\Table\ChildStampcardRewordsTable&\Cake\ORM\Association\HasMany $ChildChildStampcardRewords
 *
 * @method \App\Model\Entity\ChildStampcardReword get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChildStampcardReword newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChildStampcardReword[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChildStampcardReword|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChildStampcardReword saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChildStampcardReword patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChildStampcardReword[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChildStampcardReword findOrCreate($search, callable $callback = null, $options = [])
 */
class ChildStampcardRewordsTable extends Table
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

        $this->setTable('child_stampcard_rewords');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ParentChildStampcardRewords', [
            'className' => 'ChildStampcardRewords',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsTo('ChildStampcards', [
            'foreignKey' => 'child_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('RewordStates', [
            'foreignKey' => 'state_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('ChildChildStampcardRewords', [
            'className' => 'ChildStampcardRewords',
            'foreignKey' => 'parent_id',
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
            ->integer('reword_point')
            ->requirePresence('reword_point', 'create')
            ->notEmptyString('reword_point');

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
        $rules->add($rules->existsIn(['child_id'], 'ChildStampcards'));
        $rules->add($rules->existsIn(['state_id'], 'RewordStates'));

        return $rules;
    }
}
