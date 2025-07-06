<?php

namespace Api\Authentication;

use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use DateTime;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class JwtService
{
    private Configuration $config;
    private $tokensTable;

    public function __construct()
    {
        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(CONFIG . 'jwt.pem'),
            InMemory::file(CONFIG . 'jwt.pub')
        );

        $this->tokensTable = TableRegistry::getTableLocator()->get('Api.AuthTokens');
    }

    public function generateToken(int $userId): array
    {
        $expires = (new DateTimeImmutable('+15 minutes'));

        $token = $this->config->builder()
            ->issuedBy('localhost')
            ->permittedFor('localhost')
            ->relatedTo($userId)
            ->withClaim('refresh', false)
            ->expiresAt($expires)
            ->getToken($this->config->signer(), $this->config->signingKey());

        $tokenEntity = $this->tokensTable->newEntity([
            'token' => $token->toString(),
            'refresh' => false,
            'user_id' => $userId,
            'expires' => $expires->format('Y-m-d H:i:s')
        ]);

        $this->tokensTable->save($tokenEntity);

        return [
            'token' => $token->toString(),
            'expires' => $expires->format('Y-m-d H:i:s'),
        ];
    }

    public function generateRefreshToken(int $userId): array
    {
        $expires = new DateTimeImmutable('+2 days');
        $token = Security::randomString(64);

        $tokenEntity = $this->tokensTable->newEntity([
            'token' => $token,
            'refresh' => true,
            'user_id' => $userId,
            'expires' => $expires->format('Y-m-d H:i:s'),
        ]);

        $this->tokensTable->save($tokenEntity);

        return [
            'token' => $token,
            'expires' => $expires->format('Y-m-d H:i:s'),
        ];
    }

    public function validateRefreshToken(string $refreshToken): ?int
    {
        $tokenEntity = $this->tokensTable->find()
            ->where([
                'token' => $refreshToken,
                'refresh' => true,
                'expires >' => new DateTime()
            ])->first();

        return $tokenEntity ? $tokenEntity->user_id : null;
    }

    public function revokeToken(string $token): bool
    {
        return $this->tokensTable->deleteAll(['token' => $token]) > 0;
    }

    public function revokeUserToken(int $userId, bool $refresh = false): int
    {
        return $this->tokensTable->revokeUserTokens($userId, $refresh);
    }
}
