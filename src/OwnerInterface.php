<?php declare(strict_types=1);

namespace Proto\Pipe;

use Evenement\EventEmitterInterface;
use React\EventLoop\LoopInterface;

interface OwnerInterface extends EventEmitterInterface
{
    /**
     * ReadablePipeInterface constructor.
     * @param string $pipe
     * @param LoopInterface $loop
     * @param int $mode default is 0420
     * @throws PipeException
     */
    public function __construct(string $pipe, LoopInterface $loop, $mode = 0420);

    /**
     * Close pipe
     * @throws PipeException
     */
    public function close();
}