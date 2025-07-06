<?php
declare(strict_types=1);

namespace Api\Controller\V1;

use Api\Controller\AppController;
use Api\Model\Entity\Todo;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * Todo Controller
 *
 * @method Todo[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class TodoController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        $todo = $this->paginate($this->Todo);

        $this->set(compact('todo'));
        $this->viewBuilder()->setLayout('api');
    }

    /**
     * View method
     *
     * @param string|null $id Todo id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $todo = $this->Todo->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('todo'));
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $todo = $this->Todo->newEmptyEntity();
        if ($this->request->is('post')) {
            $todo = $this->Todo->patchEntity($todo, $this->request->getData());
            if ($this->Todo->save($todo)) {
                $this->Flash->success(__('The todo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The todo could not be saved. Please, try again.'));
        }
        $this->set(compact('todo'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Todo id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $todo = $this->Todo->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $todo = $this->Todo->patchEntity($todo, $this->request->getData());
            if ($this->Todo->save($todo)) {
                $this->Flash->success(__('The todo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The todo could not be saved. Please, try again.'));
        }
        $this->set(compact('todo'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Todo id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $todo = $this->Todo->get($id);
        if ($this->Todo->delete($todo)) {
            $this->Flash->success(__('The todo has been deleted.'));
        } else {
            $this->Flash->error(__('The todo could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
