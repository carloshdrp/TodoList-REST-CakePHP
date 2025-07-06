<?php
declare(strict_types=1);

namespace Api\Model\Table;

use Api\Model\Entity\TodoTask;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TodoTasks Model
 *
 * @method TodoTask newEmptyEntity()
 * @method TodoTask newEntity(array $data, array $options = [])
 * @method TodoTask[] newEntities(array $data, array $options = [])
 * @method TodoTask get($primaryKey, $options = [])
 * @method TodoTask findOrCreate($search, ?callable $callback = null, $options = [])
 * @method TodoTask patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method TodoTask[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method TodoTask|false save(EntityInterface $entity, $options = [])
 * @method TodoTask saveOrFail(EntityInterface $entity, $options = [])
 * @method TodoTask[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method TodoTask[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method TodoTask[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method TodoTask[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin TimestampBehavior
 */
class TodoTasksTable extends Table
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

        $this->setTable('todo_tasks');
        $this->setDisplayField('title');
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->maxLength('description', 1000)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        return $validator;
    }

    /**
     * @param RulesChecker $rules
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['title']));
        return $rules;
    }
}
