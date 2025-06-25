<?php
declare(strict_types=1);

namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\AuthTokensTable;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\AuthTokensTable Test Case
 */
class AuthTokensTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var AuthTokensTable
     */
    protected $AuthTokens;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.Api.AuthTokens',
        'plugin.Api.AuthUsers',
    ];

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses AuthTokensTable::validationDefault
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses AuthTokensTable::buildRules
     */
    public function testBuildRules(): void
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
        $config = $this->getTableLocator()->exists('AuthTokens') ? [] : ['className' => AuthTokensTable::class];
        $this->AuthTokens = $this->getTableLocator()->get('AuthTokens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AuthTokens);

        parent::tearDown();
    }
}
