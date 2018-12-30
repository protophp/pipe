<?php declare(strict_types=1);

namespace Proto\Pipe;

use Proto\Stream\WritableStreamInterface;
use React\EventLoop\LoopInterface;

interface WritablePipeInterface extends WritableStreamInterface
{
    /**
     * WritablePipeInterface constructor.
     * @param string $pipe
     * @param LoopInterface $loop
     * @throws PipeException
     */
    public function __construct(string $pipe, LoopInterface $loop);
}