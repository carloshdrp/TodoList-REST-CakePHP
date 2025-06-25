<?php
declare(strict_types=1);

namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\AuthUsersTable;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\AuthUsersTable Test Case
 */
class AuthUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var AuthUsersTable
     */
    protected $AuthUsers;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.Api.AuthUsers',
    ];

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses AuthUsersTable::validationDefault
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AuthUsers') ? [] : ['className' => AuthUsersTable::class];
        $this->AuthUsers = $this->getTableLocator()->get('AuthUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AuthUsers);

        parent::tearDown();
    }
}
