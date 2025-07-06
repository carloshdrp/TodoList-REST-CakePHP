<?php

namespace Api\Authentication;

use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Exception;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\Clock\SystemClock;

use Psr\Http\Message\ServerRequestInterface;
use Cake\ORM\TableRegistry;

class Authenticator extends AbstractAuthenticator
{
    protected $_defaultConfig = [
        'header' => 'Authorization',
        'tokenPrefix' => 'Bearer',
        'publicKey' => null,
        'userModel' => 'Api.AuthUsers',
    ];

    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        $token = $this->getToken($request);

        if (!$token) {
            return new Result(null, Result::FAILURE_CREDENTIALS_MISSING);
        }

        try {
            $config = Configuration::forAsymmetricSigner(
                new Sha256(),
                InMemory::file(CONFIG . 'jwt.pem'),
                InMemory::file(CONFIG . 'jwt.pub')
            );

            $config = $config->withValidationConstraints(
                new SignedWith($config->signer(), $config->verificationKey()),
                new LooseValidAt(SystemClock::fromUTC())
            );

            $parsedToken = $config->parser()->parse($token);

            if (!$config->validator()->validate($parsedToken, ...$config->validationConstraints())) {
                return new Result(null, Result::FAILURE_CREDENTIALS_INVALID);
            }

            $userId = $parsedToken->claims()->get('sub');

            if (!$userId) {
                return new Result(null, Result::FAILURE_CREDENTIALS_INVALID);
            }

            $usersTable = TableRegistry::getTableLocator()->get($this->getConfig('userModel'));
            $user = $usersTable->get($userId);

            return new Result($user, Result::SUCCESS);

        } catch (Exception $e) {
            return new Result(null, Result::FAILURE_CREDENTIALS_INVALID);
        }
    }

    protected function getToken(ServerRequestInterface $request): ?string
    {
        $header = $request->getHeaderLine($this->getConfig('header'));
        $prefix = $this->getConfig('tokenPrefix');

        if (!$header) {
            return null;
        }

        if (strpos($header, $prefix . ' ') === 0) {
            return substr($header, strlen($prefix . ' '));
        }

        return null;
    }
}
