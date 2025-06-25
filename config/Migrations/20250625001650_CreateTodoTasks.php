<?php
declare(strict_types=1);

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateTodoTasks extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('todo_tasks');

        $table->addColumn('title', 'string', [
            'limit' => 255
        ])->addColumn('description', 'text', [
            'limit' => MysqlAdapter::TEXT_LONG
        ])->addColumn('user_id', 'integer', [
            'null' => false,
        ])->addColumn('created', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP'
        ])->addColumn('modified', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP'
        ])->addForeignKey(
            'user_id',
            'auth_users',
            'id',
        )->create();
    }

    public function down(): void
    {
        $table = $this->table('todo_tasks');
        $table->drop()->save();
    }
}
