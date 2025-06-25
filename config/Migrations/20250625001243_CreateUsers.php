<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('auth_users');

        $table->addColumn('name', 'string', [
            'null' => false,
            'limit' => 255
        ])->addColumn('email', 'string', [
            'null' => false,
            'limit' => 255,
        ])->addColumn('password', 'string', [
            'null' => false,
            'limit' => 255
        ])->addColumn('created', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false
        ])->addColumn('modified', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false
        ])->create();
    }

    public function down(): void
    {
        $table = $this->table('auth_users');
        $table->drop()->save();
    }
}
