<?php declare(strict_types=1);

namespace Proto\Pipe;

use Proto\Stream\ReadableStream;
use React\EventLoop\LoopInterface;

class Owner extends ReadableStream implements OwnerInterface
{
    private $pipe;

    public function __construct(string $pipe, LoopInterface $loop, $mode = 0620)
    {
        $this->pipe = $pipe;

        if (file_exists($this->pipe))
            throw new PipeException(null, PipeException::ERR_ANOTHER_READER_EXITS);

        if (!posix_mkfifo($this->pipe, $mode))
            throw new PipeException(null, PipeException::ERR_UNABLE_TO_CREATE_PIPE);

        $resource = fopen($this->pipe, 'r+');
        if (!$resource)
            throw new PipeException(null, PipeException::ERR_UNABLE_TO_OPEN_PIPE);

        // Open stream
        parent::__construct($resource, $loop);
    }

    public function close()
    {
        // Close the stream
        parent::close();

        // Remove pipe when reader closed the stream.
        if (file_exists($this->pipe))
            if (!unlink($this->pipe))
                throw new PipeException(null, PipeException::ERR_UNABLE_TO_REMOVE_PIPE);
    }
}