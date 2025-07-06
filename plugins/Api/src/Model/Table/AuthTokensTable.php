<?php
declare(strict_types=1);

namespace Api\Model\Table;

use Api\Model\Entity\AuthToken;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use DateTime;

/**
 * AuthTokens Model
 *
 * @property AuthUsersTable&BelongsTo $AuthUsers
 *
 * @method AuthToken newEmptyEntity()
 * @method AuthToken newEntity(array $data, array $options = [])
 * @method AuthToken[] newEntities(array $data, array $options = [])
 * @method AuthToken get($primaryKey, $options = [])
 * @method AuthToken findOrCreate($search, ?callable $callback = null, $options = [])
 * @method AuthToken patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method AuthToken[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method AuthToken|false save(EntityInterface $entity, $options = [])
 * @method AuthToken saveOrFail(EntityInterface $entity, $options = [])
 * @method AuthToken[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method AuthToken[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method AuthToken[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method AuthToken[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin TimestampBehavior
 */
class AuthTokensTable extends Table
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

        $this->setTable('auth_tokens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AuthUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Api.AuthUsers',
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
            ->scalar('token')
            ->maxLength('token', 3000)
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('refresh')
            ->boolean('refresh')
            ->requirePresence('refresh', 'create')
            ->notEmptyString('refresh');

        $validator
            ->scalar('expires')
            ->requirePresence('expires', 'create')
            ->notEmptyString('expires');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'AuthUsers'), ['errorField' => 'user_id']);
        $rules->isUnique(['token']);
        return $rules;
    }

    public function cleanExpiredTokens($expiredTokens)
    {
        return $this->deleteAll(['expires <' => new  DateTime()]);
    }

    public function revokeUserTokens(int $userId, string $type = null): int
    {
        $where = ['user_id' => $userId];
        if ($type) $where['refresh'] = $type == 'refresh' ? 1 : 0;

        return $this->deleteAll($where);
    }
}
