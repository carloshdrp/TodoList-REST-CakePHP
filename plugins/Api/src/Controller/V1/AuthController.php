<?php
declare(strict_types=1);

namespace Api\Controller\V1;

use Api\Controller\AppController;
use Api\Model\Entity\Auth;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * Auth Controller
 *
 * @method Auth[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuthController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        $auth = $this->paginate($this->Auth);

        $this->set(compact('auth'));
    }

    /**
     * View method
     *
     * @param string|null $id Auth id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $auth = $this->Auth->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('auth'));
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $auth = $this->Auth->newEmptyEntity();
        if ($this->request->is('post')) {
            $auth = $this->Auth->patchEntity($auth, $this->request->getData());
            if ($this->Auth->save($auth)) {
                $this->Flash->success(__('The auth has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The auth could not be saved. Please, try again.'));
        }
        $this->set(compact('auth'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Auth id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $auth = $this->Auth->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $auth = $this->Auth->patchEntity($auth, $this->request->getData());
            if ($this->Auth->save($auth)) {
                $this->Flash->success(__('The auth has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The auth could not be saved. Please, try again.'));
        }
        $this->set(compact('auth'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Auth id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $auth = $this->Auth->get($id);
        if ($this->Auth->delete($auth)) {
            $this->Flash->success(__('The auth has been deleted.'));
        } else {
            $this->Flash->error(__('The auth could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
