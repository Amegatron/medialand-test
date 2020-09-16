<?php

namespace Counters\Web;

use Counters\Commands\CreateOrIncrementCounterCommand;
use Counters\Commands\GetCounterCommand;
use Counters\Commands\Handlers\CreateOrIncrementCounterCommandHandler;
use Counters\Commands\Handlers\GetCounterCommandHandler;
use Counters\Core\CounterManagerInterface;
use Counters\Management\CounterManager;
use Counters\Management\SingletonManager;
use Counters\Persistence\FileSystemPersistenceDriver;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use League\Route\Http\Exception as HttpException;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

class WebApplication
{
    /**
     * @var array
     */
    private $config;

    private $services = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function run()
    {
        $response = null;
        $emitter = new SapiEmitter();

        try {
            $this->services = $this->initServices();

            $request = ServerRequestFactory::fromGlobals();
            /** @var Router $router */
            $router = $this->services['router'];
            $response = $router->dispatch($request);

            if (!$response instanceof Response) {
                if (is_scalar($response)) {
                    $response = new Response\TextResponse($response);
                } else {
                    throw new \Exception("Response was of unknowen format");
                }
            }
        } catch (HttpException $e) {
            $response = new Response\TextResponse($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $e) {
            $response = new Response\TextResponse("Internal server Error" . PHP_EOL . get_class($e) . ': ' . $e->getMessage(), 500);
        }

        $emitter->emit($response);
    }

    private function initServices()
    {
        $singletonManager = new SingletonManager();
        $persistenceDriver = new FileSystemPersistenceDriver($this->config['storage_path']);
        $counterManager = new CounterManager($singletonManager, $persistenceDriver);
        $commandBus = $this->initCommandBus($counterManager);
        $router = $this->initRouter($commandBus);

        return compact([
            'singletonManager',
            'persistenceDriver',
            'counterManager',
            'commandBus',
            'router'
        ]);
    }

    private function initRouter(CommandBus $commandBus)
    {
        $router = new Router();

        $controller = new UuidController();
        $controller->setCommandBus($commandBus);

        $router->map('GET', '/v1/counters/{uuid:uuid}', [$controller, 'getUuid']);
        $router->map('POST', '/v1/counters/{uuid:uuid}', [$controller, 'postUuid']);

        return $router;
    }

    private function initCommandBus(CounterManagerInterface $manager)
    {
        $config = [];

        $handler = new GetCounterCommandHandler();
        $handler->setCounterManager($manager);
        $config[GetCounterCommand::class] = $handler;

        $handler = new CreateOrIncrementCounterCommandHandler();
        $handler->setCounterManager($manager);
        $config[CreateOrIncrementCounterCommand::class] = $handler;

        return new CommandBus([
            new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                new InMemoryLocator($config),
                new HandleInflector()
            )
        ]);
    }
}