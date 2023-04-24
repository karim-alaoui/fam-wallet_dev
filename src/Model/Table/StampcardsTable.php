<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Stampcards Model
 *
 * @property \App\Model\Table\ReleaseStatesTable&\Cake\ORM\Association\BelongsTo $ReleaseStates
 * @property &\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\Stampcard get($primaryKey, $options = [])
 * @method \App\Model\Entity\Stampcard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Stampcard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Stampcard|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Stampcard saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Stampcard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Stampcard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Stampcard findOrCreate($search, callable $callback = null, $options = [])
 */
class StampcardsTable extends Table
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

        $this->setTable('stampcards');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('ReleaseStates', [
            'foreignKey' => 'release_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('StampcardShops', [
            'foreignKey' => 'stamp_id',
        ]);
        $this->hasMany('StampcardRewords', [
            'foreignKey' => 'stamp_id',
        ]);
        $this->hasMany('ChildStampcards', [
            'foreignKey' => 'parent_id'
        ]);
    }

    /*
    * controller: Stampcards
    * action: Index
    * info: 公開、非公開カウント、全てのレコード抽出 
    */
    public function total_release_record($company_id)
    {
      $release_record = $this->find('all')
        ->where(['company_id' => $company_id, 'release_id' => 1])
        ->count();

      $private_record = $this->find('all')
        ->where(['company_id' => $company_id, 'release_id' => 2])
        ->count();

      $result_record = $this->find('all')
        ->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'])
        ->where(['company_id' => $company_id])
        ->order(['id' => 'DESC']);

      return [$release_record, $private_record, $result_record];
    }

    /*
    * controller: Stampcards
    * action: Index
    * info: 検索
    */
    public function search_record($company_id, $shop_id)
    {
      // count
      $release_record = $this->find('all')
        ->where(['company_id' => $company_id, 'id IN' => $shop_id, 'release_id' => 1])->count();

      $private_record = $this->find('all')
        ->where(['company_id' => $company_id, 'id IN' => $shop_id, 'release_id' => 2])->count();

      $result_record = $this->find('all')
        ->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'])
        ->where(['company_id' => $company_id, 'id IN' => $shop_id])
        ->order(['id' => 'DESC']);

      return [$release_record, $private_record, $result_record];
    }


    public function my_company_stampcard($company_id)
    {
      $result_record = $this->find('all')
       ->contain(['ChildStampcards', 'StampcardShops', 'StampcardShops.Shops'])
       ->order(['id' => 'desc'])
       ->where(['company_id' => $company_id])->toArray();

      return $result_record;
    }

    public function analytics_stamp_record($company_id, $before_date, $after_date)
    {
      // 開始時刻のみ
      if ($after_date == null) {
        $result_record = $this->find('all')
          ->contain(['ChildStampcards'])
          ->where(['company_id' => $company_id, 'before_expiry_date >=' => $before_date])->extract('id')->toArray();
      // 終了時刻のみ
      } elseif ($before_date == null) {
        $result_record = $this->find('all')
          ->contain(['ChildStampcards'])
          ->where(['company_id' => $company_id, 'after_expiry_date <=' => $after_date])->extract('id')->toArray();
      } else {
        $result_record = $this->find('all')
          ->contain(['ChildStampcards'])
          ->where(['company_id' => $company_id, 'after_expiry_date >=' => $before_date,  'after_expiry_date <=' => $after_date])->extract('id')->toArray();
      }
      return $result_record;
    }

    public function analytics_stamp_leader_record($company_id, $id_list, $before_date, $after_date)
    {
      // 開始時刻のみ
      if ($after_date == null) {
        $result_record = $this->find('all')
          ->contain(['ChildStampcards'])
          ->where(['company_id' => $company_id, 'id IN' => $id_list, 'before_expiry_date >=' => $before_date])->extract('id')->toArray();
      } elseif ($before_date == null) {
        $result_record = $this->find('all')
          ->contain(['ChildStampcards'])
          ->where(['company_id' => $company_id, 'id IN' => $id_list, 'after_expiry_date <=' => $after_date])->extract('id')->toArray();
      } else {
        $result_record = $this->find('all')
          ->contain(['ChildStampcards'])
          ->where(['company_id' => $company_id, 'id IN' => $id_list, 'before_expiry_date >=' => $before_date,  'after_expiry_date <=' => $after_date])->extract('id')->toArray();
      }
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('content')
            ->maxLength('content', 60)
            ->notEmptyString('content');

        $validator
            ->scalar('longitude')
            ->maxLength('longitude', 255)
            ->allowEmptyString('longitude');

        $validator
            ->scalar('latitude')
            ->maxLength('latitude', 255)
            ->allowEmptyString('latitude');

        $validator
            ->scalar('relevant_text')
            ->maxLength('relevant_text', 255)
            ->allowEmptyString('relevant_text');

        $validator
            ->scalar('background_color')
            ->maxLength('background_color', 255)
            ->requirePresence('background_color', 'create')
            ->notEmptyString('background_color');

        $validator
            ->scalar('foreground_color')
            ->maxLength('foreground_color', 255)
            ->requirePresence('foreground_color', 'create')
            ->notEmptyString('foreground_color');

        /*$validator
            ->dateTime('before_expiry_date')
            ->requirePresence('before_expiry_date', 'create')
            ->notEmptyDateTime('before_expiry_date');

        $validator
            ->dateTime('after_expiry_date')
            ->requirePresence('after_expiry_date', 'create')
            ->notEmptyDateTime('after_expiry_date');*/

        $validator
            ->scalar('address')
            ->maxLength('address', 50)
            ->allowEmptyString('address');

        $validator
            ->integer('max_limit')
            ->requirePresence('max_limit', 'create')
            ->notEmptyString('max_limit');

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
        $rules->add($rules->existsIn(['release_id'], 'ReleaseStates'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}
