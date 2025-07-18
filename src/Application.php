<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Authenticator\UnauthenticatedException;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Http\Response;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Muffin\Throttle\Middleware\ThrottleMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Load more plugins here
        $this->addPlugin('Api');
        $this->addPlugin('Muffin/Throttle');
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $throttleMiddleware = new ThrottleMiddleware([
            // Resposta quando o limite é excedido
            'response' => [
                'body' => json_encode([
                    'success' => false,
                    'message' => 'Rate Limit excedido. Tente novamente mais tarde.'
                ]),
                'type' => 'application/json'
            ],
            'period' => 60,
            'limit' => 50,
            'identifier' => function ($request) {
                if (!empty($request->getHeaderLine('Authorization'))) {
                    return str_replace('Bearer ', '', $request->getHeaderLine('Authorization'));
                }
                return $request->clientIp();
            }
        ]);

        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance.
            // See https://github.com/CakeDC/cakephp-cached-routing
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())
            ->add(new AuthenticationMiddleware($this))
            ->add(function ($request, $handler) {
                try {
                    return $handler->handle($request);
                } catch (UnauthenticatedException $e) {
                    $response = new Response();
                    $response = $response->withStatus(401)
                        ->withType('application/json');
                    $response->getBody()->write(json_encode([
                        'success' => false,
                        'message' => 'Token inválido ou expirado.',
                        'error' => 'UNAUTHENTICATED'
                    ]));
                    return $response;
                }
            })
            ->add($throttleMiddleware);

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Bake');

        $this->addPlugin('Migrations');
        // Load more plugins here
    }

    /**
     * @param ServerRequestInterface $request
     * @return AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();
        $path = $request->getUri()->getPath();

        if (strpos($path, '/auth/login') !== false || strpos($path, '/auth/register') !== false) {
            $service->loadAuthenticator('Authentication.Form', [
                'fields' => ['username' => 'email', 'password' => 'password'],
            ]);
            $service->loadIdentifier('Authentication.Password', [
                'fields' => ['username' => 'email', 'password' => 'password'],
                'resolver' => [
                    'className' => 'Authentication.Orm',
                    'userModel' => 'Api.AuthUsers',
                ],
            ]);
        } else {
            $service->loadAuthenticator('Api\Authentication\Authenticator', [
                'resolver' => [
                    'className' => 'Authentication.Orm',
                    'userModel' => 'Api.AuthUsers',
                ],
            ]);
        }

        return $service;
    }

}
