<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateTokens extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('auth_tokens');

        $table->addColumn('token', 'string', [
            'length' => 3000,
            'null' => false
        ])->addColumn('user_id', 'integer', [
            'null' => false,
        ])->addColumn('created', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false
        ])->addColumn('modified', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => 'CURRENT_TIMESTAMP',
            'null' => false
        ])->addForeignKey(
            'user_id',
            'auth_users',
            'id',
            [
                'delete' => 'CASCADE',
            ]
        )->create();

    }

    public function down(): void
    {
        $table = $this->table('auth_tokens');
        $table->drop()->save();
    }
}
