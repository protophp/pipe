<?php declare(strict_types=1);

namespace Proto\Pipe;

use Proto\Pack\Pack;
use React\EventLoop\LoopInterface;
use React\Stream\WritableResourceStream;

class Connector implements ConnectorInterface
{
    private $pipe;

    /**
     * @var WritableResourceStream
     */
    private $stream;

    public function __construct(string $pipe, LoopInterface $loop)
    {
        $this->pipe = $pipe;

        if (!file_exists($this->pipe))
            throw new PipeException(null, PipeException::ERR_PIPE_NOT_EXISTS);

        $resource = fopen($this->pipe, 'a');
        if (!$resource)
            throw new PipeException(null, PipeException::ERR_UNABLE_TO_OPEN_PIPE);

        $this->stream = new WritableResourceStream($resource, $loop);
    }

    public function write($data)
    {
        $this->stream->write((new Pack())->setData($data)->toString());
    }

}