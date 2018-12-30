<?php declare(strict_types=1);

namespace Proto\Pipe;

use Proto\Stream\WritableStream;
use React\EventLoop\LoopInterface;

class WritablePipe extends WritableStream implements WritablePipeInterface
{
    private $pipe;

    public function __construct(string $pipe, LoopInterface $loop)
    {
        $this->pipe = $pipe;

        if (!file_exists($this->pipe))
            throw new PipeException(null, PipeException::ERR_PIPE_NOT_EXISTS);

        $resource = fopen($this->pipe, 'a');
        if (!$resource)
            throw new PipeException(null, PipeException::ERR_UNABLE_TO_OPEN_PIPE);

        parent::__construct($resource, $loop);
    }

}