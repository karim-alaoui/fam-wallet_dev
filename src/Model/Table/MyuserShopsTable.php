<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MyuserShops Model
 *
 * @property &\Cake\ORM\Association\BelongsTo $Myusers
 * @property \App\Model\Table\ShopsTable&\Cake\ORM\Association\BelongsTo $Shops
 *
 * @method \App\Model\Entity\MyuserShop get($primaryKey, $options = [])
 * @method \App\Model\Entity\MyuserShop newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MyuserShop[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MyuserShop|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MyuserShop saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MyuserShop patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MyuserShop[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MyuserShop findOrCreate($search, callable $callback = null, $options = [])
 */
class MyuserShopsTable extends Table
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

        $this->setTable('myuser_shops');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Myusers', [
            'foreignKey' => 'myuser_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Shops', [
            'foreignKey' => 'shop_id',
            'joinType' => 'INNER',
        ]);
    }

    /*
    * controller: Myusers
    * action: Leaders/Members edit
    * info: 既存レコードを配列で返す
    */
    public function my_user_list($user_id)
    {
      $result_record_array = $this->find('all')
        ->contain(['Shops'])
        ->where(['myuser_id' => $user_id])
        ->toArray();

      return $result_record_array;
    }

    /*
    * controller:Myusers
    * action: Leaders/Members edit
    * info: 既存レコードからIDだけを配列で返す
    */
    public function edit_record($user_id)
    {
      $result_record_array = $this->find('all')
        ->where(['myuser_id' => $user_id])
        ->extract('id')->toArray();

      return $result_record_array;
    }

    /*
    * controller:Shops
    * action:index
    * info:対象ユーザIDだけ返す
    */
    public function shop_index($login_user_id)
    {
      $result_record = $this->find('all')
        ->contain(['Shops'])
        ->where(['myuser_id' => $login_user_id]);
 
      return $result_record;
    }

    public function shop_list($user_id)
    {
      $result_record = $this->find('all')
        ->contain(['Shops'])
        ->where(['myuser_id' => $user_id]);

      $result_array = [];
      foreach ($result_record as $key) {
        $result_array[$key->shop->id] = $key->shop->name;
      }

      return $result_array;
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
        $rules->add($rules->existsIn(['myuser_id'], 'Myusers'));
        $rules->add($rules->existsIn(['shop_id'], 'Shops'));

        return $rules;
    }
}
