<?php
declare(strict_types=1);

namespace Api\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AuthTokensFixture
 */
class AuthTokensFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'token' => 'Lorem ipsum dolor sit amet',
                'user_id' => 1,
                'created' => '2025-06-25 00:59:43',
                'modified' => '2025-06-25 00:59:43',
            ],
        ];
        parent::init();
    }
}
