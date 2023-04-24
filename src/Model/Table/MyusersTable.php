<?php
namespace App\Model\Table;

use App\Model\Entity\Myuser;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use CakeDC\Users\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
/**
 * Myusers Model
 *
 * @property \App\Model\Table\CompaniesTable&\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\BelongsTo $Roles
 *
 * @method \App\Model\Entity\Myuser get($primaryKey, $options = [])
 * @method \App\Model\Entity\Myuser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Myuser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Myuser|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Myuser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Myuser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Myuser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Myuser findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MyusersTable extends UsersTable
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

        $this->setTable('myusers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('MyuserShops', [
            'foreignKey' => 'myuser_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('AffiliaterCoupons', [
           'foreignKey' => 'myuser_id'
        ]);
        $this->hasMany('AffiliaterPoints', [
           'foreignKey' => 'myuser_id'
        ]);
        $this->hasOne('MyuserBanks', [
            'foreignKey' => 'myuser_id',
            'joinType' => 'INNER',
        ]);
    }

    /*
    * リーダー/メンバーリスト
    *  配列化
    */
    public function my_company_leder_list_array($role_id, $company_id)
    {
      // emptyは、オブジェクト判定はしないため、配列化する。
      $result_record_array = $this->find('all')
        ->where(['role_id' => $role_id, 'company_id' => $company_id])
        ->contain([
          'MyuserShops',
          'MyuserShops.shops'
         ])
        ->toArray();

        return $result_record_array;
    }

    /**
     * @param $role_id
     * @param $company_id
     * @return array
     */
    public function my_affiliater_list_array($role_id, $company_id=null) {
        $AffiliaterCoupons = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
        $Myusers = TableRegistry::getTableLocator()->get('Myusers');

        if (!$company_id) {
            $myusers = $Myusers->find()
                ->where(['role_id' => $role_id]);

            return $myusers->toArray();
        }

        $subquery = $AffiliaterCoupons->getAssociation('Coupons')->find()
            ->select(['id'])
            ->distinct()
            ->where(['Coupons.company_id in' => $company_id]);

        $affiliaterCoupons = $AffiliaterCoupons->find()
            ->select('myuser_id')
            ->group('myuser_id')
            ->contain('Coupons')
            ->where(['coupon_id IN' => $subquery]);

        $myusers = $Myusers->find()

            ->where(['role_id' => $role_id, 'id IN' => $affiliaterCoupons]);

        return $myusers->toArray();
    }

    public function get_point($id) {
        return $this->find('all')
            ->where(['id' => $id])
            ->select(['point'])
            ->first()->point;
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
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->add('username', [
                'username_unique'  => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'その名前は既に使用されています'
                ]
            ]);

$sitesTable = TableRegistry::get('Myusers');
        $validator
->provider('table', $sitesTable)
            ->email('email')
            ->allowEmptyString('email')
            #->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
            ->add('email', [
              'email_unique'  => [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'そのメールアドレスは既に登録済みです'
              ]
            ]);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password')
            ->minLength('password', 8, '半角英数字8文字以上にして下さい。');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->allowEmptyString('token');

        $validator
            ->dateTime('token_expires')
            ->allowEmptyDateTime('token_expires');

        $validator
            ->dateTime('activation_date')
            ->allowEmptyDateTime('activation_date');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }

    public function afterSave(Event $event, Myuser $entity) {
        if($entity->isNew() && $entity->role_id == 4) {
        }
    }

    public function afterDelete(Event $event, Myuser $myuser) {
        if($myuser->role_id == 4) {
            $affiliaterCoupons = TableRegistry::getTableLocator()->get('AffiliaterCoupons');
            $affiliaterCoupons->deleteAll(['myuser_id' => $myuser->id]);
        }
    }

}
