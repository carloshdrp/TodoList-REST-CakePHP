<?php
declare(strict_types=1);

namespace Api\Controller\V1;

use Api\Authentication\JwtService;
use Api\Controller\AppController;
use Api\Model\Entity\AuthUser;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

/**
 * Auth Controller
 *
 * @method AuthUser[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuthController extends AppController
{
    private JwtService $jwtService;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->jwtService = new JwtService();
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login', 'register', 'refresh']);
    }

    public function login()
    {
        $this->request->allowMethod(['post']);

        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $user = $result->getData();
            $this->jwtService->revokeUserToken($user->id);

            $token = $this->jwtService->generateToken($user->id);
            $refreshToken = $this->jwtService->generateRefreshToken($user->id);

            $this->set([
                'success' => true,
                'data' => [
                    'token' => $token,
                    'refreshToken' => $refreshToken,
                    'expires' => 900,
                    'token_type' => 'Bearer',
                ]
            ]);
        } else {
            $this->response = $this->response->withStatus(401);
            $this->set([
                'success' => false,
                'message' => 'Credenciais Inválidas',
            ]);
        }
    }

    public function refresh()
    {
        $this->request->allowMethod(['post']);

        $refreshToken = $this->request->getData('refresh');
        if (!$refreshToken) {
            $this->response = $this->response->withStatus(400);
            $this->set(['success' => false, 'message' => 'Refresh token não informado']);
            return;
        }

        $userId = $this->jwtService->validateRefreshToken($refreshToken);
        if (!$userId) {
            $this->response = $this->response->withStatus(401);
            $this->set(['success' => false, 'message' => 'Refresh token inválido ou expirado']);
            return;
        }

        $accessToken = $this->jwtService->generateToken($userId);

        $this->set([
            'success' => true,
            'data' => [
                'token' => $accessToken,
                'expires' => 900,
                'token_type' => 'Bearer',
            ]
        ]);
    }


    public function logout()
    {
        $this->request->allowMethod(['post']);

        $user = $this->Authentication->getIdentity();
        if ($user) {
            $this->jwtService->revokeUserToken($user->id);
        }

        $this->set([
            'success' => true,
            'message' => 'Logout realizado com sucesso!'
        ]);
    }

    public function register()
    {
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();

        if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
            $this->response = $this->response->withStatus(400);
            $this->set([
                'success' => false,
                'message' => 'Você precisa informar todos os valores'
            ]);
            return;
        }
        $userTable = TableRegistry::getTableLocator()->get('Api.AuthUsers');
        $user = $userTable->newEmptyEntity();

        $findUser = $userTable->find()
            ->where([
                'email' => $data['email'],
            ])->first();

        if ($findUser) {
            $this->response = $this->response->withStatus(409);
            $this->set([
                'success' => false,
                'message' => 'O email já está em uso'
            ]);
            return;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = $userTable->patchEntity($user, $data);

        if ($userTable->save($user)) {
            $token = $this->jwtService->generateToken($user->id);
            $refreshToken = $this->jwtService->generateRefreshToken($user->id);

            $this->response = $this->response->withStatus(200);
            $this->set([
                'success' => true,
                'message' => 'Registro realizado com sucesso!',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'nome' => $user->name,
                        'email' => $user->email,
                    ],
                    'token' => $token,
                    'refreshToken' => $refreshToken,
                    'expires' => 900,
                    'token_type' => 'Bearer',
                ]
            ]);
        } else {
            $this->response = $this->response->withStatus(500);
            $this->set([
                'success' => false,
                'message' => 'Ocorreu um erro ao realizar o registro',
                'error' => $user->getErrors()
            ]);
        }
    }
}
