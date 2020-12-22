<?php
namespace CCR\BLAT\Service\Dispatcher;

// BLAT libraries with namespaces
use CCR\BLAT\Service\Message\CommandInterface;
/**
 * Dispatcher decorator for wrapping a command handler.
 */
class TransactionalDispatcher implements DispatcherInterface
{
    /** @var DispatcherInterface $decorated Decorated dispatcher. */
    private $decorated;
    public function __construct(DispatcherInterface $decorated) {
        $this->decorated = $decorated;
    }
    public function send(CommandInterface $command): void
    {
        $this->db->tryFlatTransaction(function () use ($command) {
            $this->decorated->send($command);
        });
    }
}
