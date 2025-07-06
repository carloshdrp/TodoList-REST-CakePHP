<?php
declare(strict_types=1);

namespace Api\Model\Table;

use Api\Model\Entity\AuthUser;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AuthUsers Model
 *
 * @method AuthUser newEmptyEntity()
 * @method AuthUser newEntity(array $data, array $options = [])
 * @method AuthUser[] newEntities(array $data, array $options = [])
 * @method AuthUser get($primaryKey, $options = [])
 * @method AuthUser findOrCreate($search, ?callable $callback = null, $options = [])
 * @method AuthUser patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method AuthUser[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method AuthUser|false save(EntityInterface $entity, $options = [])
 * @method AuthUser saveOrFail(EntityInterface $entity, $options = [])
 * @method AuthUser[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method AuthUser[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method AuthUser[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method AuthUser[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin TimestampBehavior
 */
class AuthUsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('auth_users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('AuthTokens', [
            'foreignKey' => 'id',
            'className' => 'Api.AuthTokens'
        ]);

        $this->hasMany('TodoTasks', [
            'foreignKey' => 'id',
            'className' => 'Api.TodoTasks'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        return $validator;
    }

    /**
     * @param RulesChecker $rules
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
