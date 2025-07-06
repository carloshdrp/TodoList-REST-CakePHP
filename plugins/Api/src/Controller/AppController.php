<?php
declare(strict_types=1);

namespace Api\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
    protected ?int $user_id = null;

    public function initialize(): void
    {
        parent::initialize();

        $this->viewBuilder()->setClassName('Json');
        $this->viewBuilder()->disableAutoLayout();
        $this->viewBuilder()->setOption('serialize', true);

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Authentication.Authentication');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $identity = $this->Authentication->getIdentity();
        if($identity) $this->user_id = $identity->getIdentifier();
    }
}
