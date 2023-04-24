<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StampcardShops Model
 *
 * @property \App\Model\Table\ShopsTable&\Cake\ORM\Association\BelongsTo $Shops
 * @property \App\Model\Table\StampcardsTable&\Cake\ORM\Association\BelongsTo $Stampcards
 *
 * @method \App\Model\Entity\StampcardShop get($primaryKey, $options = [])
 * @method \App\Model\Entity\StampcardShop newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StampcardShop[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StampcardShop|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StampcardShop saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StampcardShop patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StampcardShop[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StampcardShop findOrCreate($search, callable $callback = null, $options = [])
 */
class StampcardShopsTable extends Table
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

        $this->setTable('stampcard_shops');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Shops', [
            'foreignKey' => 'shop_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('shop_id')
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
        $rules->add($rules->existsIn(['shop_id'], 'Shops'));
        $rules->add($rules->existsIn(['stamp_id'], 'Stampcards'));

        return $rules;
    }
}
