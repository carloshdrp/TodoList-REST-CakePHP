<?php
declare(strict_types=1);

namespace Api\Controller\V1;

use Api\Controller\AppController;
use Api\Model\Entity\TodoTask;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Cake\ORM\Table;

/**
 * Todo Controller
 *
 * @method TodoTask[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class TasksController extends AppController
{
    private Table $Tasks;

    public function initialize(): void
    {
        parent::initialize();
        $this->Tasks = $this->fetchTable('Api.TodoTasks');
    }

    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);
        $page = (int)$this->request->getQuery('page', 1);
        $limit = (int)$this->request->getQuery('limit', 10);

        $tasks = $this->Tasks->find()
            ->select([
                'id',
                'title',
                'description'
            ])->limit($limit)
            ->offset(($page - 1) * $limit)
            ->where([
                'user_id' => $this->user_id
            ])->order([
                'created' => 'DESC'
            ])->toArray();

        if (empty($tasks)) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'success' => false,
                'message' => 'Nenhuma tarefa encontrada.'
            ]);
            return;
        }

        $count = $this->Tasks->find()->where([
            'user_id' => $this->user_id
        ])->all()->count();

        $this->response = $this->response->withStatus(200);
        $this->set([
            'success' => true,
            'data' => $tasks,
            'page' => $page,
            'limit' => $limit,
            'total' => $count
        ]);
    }

    /**
     * Edit method
     *
     * @param int|null $id Tasks id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(int $id = null)
    {
        if ($id) {
            // edição
            $task = $this->Tasks->find()->where([
                'id' => $id,
                'user_id' => $this->user_id
            ])->first();
        } else {
            // criação
            $task = $this->Tasks->newEmptyEntity();
        }

        if ($this->request->is('post') && !empty($this->request->getData())) {
            $data = $this->request->getData();
            $data['user_id'] = $this->user_id;

            if (!empty($task)) {
                $task = $this->Tasks->patchEntity($task, $data);
                if ($this->Tasks->save($task)) {
                    $this->response = $this->response->withStatus(200);
                    $this->set([
                        'success' => true,
                        'message' => 'A tarefa foi salva.',
                        'data' => $task,
                    ]);
                    return;
                } else {
                    $code = 400;
                }
            } else {
                $message = "Tarefa não encontrada ou permissão negada.";
                $code = 401;
            }

            $this->response = $this->response->withStatus($code);
            $this->set([
                'success' => false,
                'message' => $message ?? $task->getErrors(),
            ]);
        } else {
            $this->response = $this->response->withStatus(400);
            $this->set([
                'success' => false,
                'message' => 'Método ou conteúdo inválidos',
            ]);
        }

    }

    /**
     * Delete method
     *
     * @param int|null $id Tasks id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id)
    {
        $this->request->allowMethod(['delete']);
        $task = $this->Tasks->find()->where([
            'id' => $id,
            'user_id' => $this->user_id
        ])->first();

        if (empty($task)) {
            $this->response = $this->response->withStatus(401);
            $this->set([
                'success' => false,
                'message' => 'Tarefa não encontrada ou permissão negada.'
            ]);
        }

        if ($this->Tasks->delete($task)) {
            $this->response = $this->response->withStatus(200);
            $this->set([
                'success' => true,
                'message' => 'A tarefa foi excluída.',
            ]);
        } else {
            $this->response = $this->response->withStatus(500);
            $this->set([
                'success' => false,
                'message' => $task->getErrors() ?? 'Não foi possível excluir a tarefa. Tente novamente.',
            ]);
        }

        return $this->redirect(['action' => 'index']);
    }
}
