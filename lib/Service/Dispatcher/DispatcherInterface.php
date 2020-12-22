<?php
namespace CCR\BLAT\Service\Dispatcher;

// BLAT libraries with namespaces
use CCR\BLAT\Service\Exception\HandlerNotFoundException;
use CCR\BLAT\Service\Message\CommandInterface;
/**
 * Interface defining a dispatcher that dispatches a command 
 * to its handler.
 */
interface DispatcherInterface
{
    /**
     * @param CommandInterface $command Command to dispatch.
     * @throws HandlerNotFoundException If no handler is registered to handle
     *     the command.
     */
    public function send(CommandInterface $command): void;
}
