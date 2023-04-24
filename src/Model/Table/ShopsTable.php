<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Shops Model
 *
 * @property \App\Model\Table\CompaniesTable&\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\CouponShopsTable&\Cake\ORM\Association\HasMany $CouponShops
 * @property \App\Model\Table\MyuserShopsTable&\Cake\ORM\Association\HasMany $MyuserShops
 * @property \App\Model\Table\StampcardShopsTable&\Cake\ORM\Association\HasMany $StampcardShops
 *
 * @method \App\Model\Entity\Shop get($primaryKey, $options = [])
 * @method \App\Model\Entity\Shop newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Shop[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Shop|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shop saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shop patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Shop[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Shop findOrCreate($search, callable $callback = null, $options = [])
 */
class ShopsTable extends Table
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

        $this->setTable('shops');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('CouponShops', [
            'foreignKey' => 'shop_id',
        ]);
        $this->hasMany('MyuserShops', [
            'foreignKey' => 'shop_id',
        ]);
        $this->hasMany('StampcardShops', [
            'foreignKey' => 'shop_id',
        ]);
    }

    /*
    * controller:Coupons
    * action: Coupons new
    * info: wallet裏面に使うレコード
    */
    public function all_shop_record($ids)
    {
      $result_record = $this->find('all')
        ->where(['id IN' => $ids])->toArray();

      return $result_record;
    }
    /*
    * controller:Myusers/Coupons
    * action: Leaders/Members/Coupons new
    * info:対象会社の店舗レコードを配列で返す
    */
    public function shop_record_array($company_id)
    {
      $result_record = $this->find('all')
        ->where(['company_id' => $company_id])
        ->toArray();

      $result_array = [];
      foreach ($result_record as $key) {
        $result_array[$key->id] = $key->name;
      }
      return $result_array;
    }

    /*
    * controller:Myusers/Coupons
    * action:Leader/Cpupons new
    * info:確認画面用
    */
    public function add_record_array($requestdata, $select_colum1, $select_colum2)
    {
      $result_record = $this->find('all')
        ->where(['id IN' => $requestdata])
        ->select([$select_colum1, $select_colum2]);

      $result_array = [];
      foreach ($result_record as $key) {
        $result_array[$key->id] = $key->name;
      }
      return $result_array;
    }

    /*
    * controller:Shops
    * action:index
    * info:店舗一覧
    */
    public function shop_index($company_id)
    {
      $result_record = $this->find('all')
        ->where(['company_id' => $company_id]);

      return $result_record;
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('introdaction')
            ->allowEmptyString('introdaction');

        $validator
            ->scalar('tel')
            ->maxLength('tel', 12)
            ->allowEmptyString('tel');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->allowEmptyString('address');

        $validator
            ->scalar('homepage')
            ->maxLength('homepage', 255)
            ->allowEmptyString('homepage');

        $validator
            ->scalar('line')
            ->maxLength('line', 255)
            ->allowEmptyString('line');

        $validator
            ->scalar('twitter')
            ->maxLength('twitter', 255)
            ->allowEmptyString('twitter');

        $validator
            ->scalar('facebook')
            ->maxLength('facebook', 255)
            ->allowEmptyString('facebook');

        $validator
            ->scalar('instagram')
            ->maxLength('instagram', 255)
            ->allowEmptyString('instagram');

        $validator
            ->allowEmptyFile('image');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
