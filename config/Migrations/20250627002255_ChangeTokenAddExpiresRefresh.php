<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class ChangeTokenAddExpiresRefresh extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('auth_tokens');

        $table->addColumn('expires', 'datetime', [
            'null' => false
        ])->addColumn('refresh', 'boolean')->save();
    }

    public function down(): void
    {
        $table = $this->table('auth_tokens');
        $table->removeColumn('expires')
            ->removeColumn('refresh')
            ->save();
    }
}
