<?php
/**
 * This file contains the dependency injection container configuration.
 * Pimple * (@see https://pimple.symfony.com) is used as the underlying 
 * dependency injection container implementation.
 */
$container = $app->getContainer();
// config
$container["config"] = function () {
    return $GLOBALS["options"];
};
// data source
$container["blat"] = function ($c) {
    return new CCR\BLAT\Service\External\BlatDataSource(
        new GuzzleHttp\Client(["base_uri" => $c->get("config")->blat->url])
    );
};
// dispatcher
$container["dispatcher"] = function ($c) {
    return new CCR\BLAT\Service\Dispatcher\LoggedDispatcher(
        new CCR\BLAT\Service\Dispatcher\ValidatingDispatcher(
            new CCR\BLAT\Service\Dispatcher\DispatcherInterface()
        ),
        $c->get("logger")
    );
};
// logger
$container["logger"] = function () {
    $logger = new Monolog\Logger("BLAT");
    $handler = new Monolog\Handler\FingersCrossedHandler(new Monolog\Handler\ErrorLogHandler());
    $formatter = new Monolog\Formatter\LineFormatter();
    $formatter->allowInlineLineBreaks();
    $formatter->ignoreEmptyContextAndExtra();
    $handler->setFormatter($formatter);
    $logger->pushHandler($handler);

    return $logger;
};
// middleware
$container["debug-middleware"] = function ($c) {
    return new CCR\BLAT\Middleware\DebugMiddleware();
};
// query handler
$container[CCR\BLAT\Datasource\Query\GetAlignmentListHandler::class] = function ($c) {
    return new CCR\BLAT\Datasource\Query\GetAlignmentListHandler(
        new CCR\BLAT\Datasource\Service\AlignmentMatcher($c->get("blat")));
};
