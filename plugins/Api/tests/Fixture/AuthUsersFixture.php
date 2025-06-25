<?php
declare(strict_types=1);

namespace Api\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AuthUsersFixture
 */
class AuthUsersFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-06-25 00:59:49',
                'modified' => '2025-06-25 00:59:49',
            ],
        ];
        parent::init();
    }
}
