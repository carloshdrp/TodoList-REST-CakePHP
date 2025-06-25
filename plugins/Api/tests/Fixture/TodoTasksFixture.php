<?php
declare(strict_types=1);

namespace Api\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TodoTasksFixture
 */
class TodoTasksFixture extends TestFixture
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
                'title' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2025-06-25 00:55:44',
                'modified' => '2025-06-25 00:55:44',
            ],
        ];
        parent::init();
    }
}
