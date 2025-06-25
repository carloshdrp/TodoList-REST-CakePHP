<?php
declare(strict_types=1);

namespace Api\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->disableAutoLayout();
        $this->viewBuilder()->setOption('serialize', true);
    }
}
