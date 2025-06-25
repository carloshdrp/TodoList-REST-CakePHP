<?php
declare(strict_types=1);

namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\TodoTasksTable;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\TodoTasksTable Test Case
 */
class TodoTasksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var TodoTasksTable
     */
    protected $TodoTasks;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.Api.TodoTasks',
    ];

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses TodoTasksTable::validationDefault
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
        $config = $this->getTableLocator()->exists('TodoTasks') ? [] : ['className' => TodoTasksTable::class];
        $this->TodoTasks = $this->getTableLocator()->get('TodoTasks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TodoTasks);

        parent::tearDown();
    }
}
