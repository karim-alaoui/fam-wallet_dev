<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StampcardRewords Model
 *
 * @property \App\Model\Table\StampcardsTable&\Cake\ORM\Association\BelongsTo $Stampcards
 *
 * @method \App\Model\Entity\StampcardReword get($primaryKey, $options = [])
 * @method \App\Model\Entity\StampcardReword newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StampcardReword[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StampcardReword|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StampcardReword saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StampcardReword patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StampcardReword[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StampcardReword findOrCreate($search, callable $callback = null, $options = [])
 */
class StampcardRewordsTable extends Table
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

        $this->setTable('stampcard_rewords');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Stampcards', [
            'foreignKey' => 'stamp_id',
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
            ->scalar('reword')
            ->maxLength('reword', 255)
            ->requirePresence('reword', 'create')
            ->notEmptyString('reword');

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
        $rules->add($rules->existsIn(['stamp_id'], 'Stampcards'));

        return $rules;
    }
}
