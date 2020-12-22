<?php
namespace CCR\BLAT\Service\Dispatcher;

// Standard PHP Libraries (SPL)
use Throwable;
// Third-party libraries
use Psr\Log\LoggerInterface;
// BLAT libraries with namespaces
use CCR\BLAT\Service\Message\CommandInterface;
/**
 * Dispatcher decorator that logs the command 
 * before dispatching to the next dispatcher.
 */
class LoggedDispatcher implements DispatcherInterface
{
    /** @var DispatcherInterface $decorated Decorated dispatcher. */
    private $decorated;
    /** @var LoggerInterface $logger Logger for logging the command. */
    private $logger;
    public function __construct(
        DispatcherInterface $decorated,
        LoggerInterface $logger
    ) {
        $this->decorated = $decorated;
        $this->logger = $logger;
    }
    public function send(CommandInterface $command): void
    {
        $this->logger->debug(
            "sending command",
            ["message" => $command]
        );
        try {
            $this->decorated->send($command);
        } catch ( Throwable $e ) {
            $this->logger->error(
                "failed sending command",
                ["exception" => $e]
            );
            throw $e;
        }
    }
}
