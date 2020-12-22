<?php
namespace CCR\BLAT\Service\Dispatcher;

// BLAT libraries with namespaces
use CCR\BLAT\Service\Message\{BatchCommand, CommandInterface};
/**
 * Dispatcher decorator that handles a batched command. Each command in the
 * batch is sent to the next decorated dispatcher individually.
 */
class BatchedDispatcher implements DispatcherInterface
{
    /** @var DispatcherInterface $decorated Decorated dispatcher. */
    private $decorated;
    public function __construct(DispatcherInterface $decorated)
    {
        $this->decorated = $decorated;
    }
    public function send(CommandInterface $command): void
    {
        if ( $command instanceof BatchCommand ) {
            foreach ( $command->commands as $command ) {
                $this->decorated->send($command);
            }
        } else {
            $this->decorated->send($command);
        }
    }
}
