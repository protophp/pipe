<?php declare(strict_types=1);

namespace Proto\Pipe;

use React\EventLoop\LoopInterface;

interface ConnectorInterface
{
    /**
     * WritablePipeInterface constructor.
     * @param string $pipe
     * @param LoopInterface $loop
     * @throws PipeException
     */
    public function __construct(string $pipe, LoopInterface $loop);

    /**
     * Write to pipe
     * @param mixed $data
     */
    public function write($data);
}