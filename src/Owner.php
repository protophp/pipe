<?php declare(strict_types=1);

namespace Proto\Pipe;

use Evenement\EventEmitter;
use Proto\Pack\PackInterface;
use Proto\Pack\Unpack;
use React\EventLoop\LoopInterface;
use React\Stream\ReadableResourceStream;
use React\Stream\Util;

class Owner extends EventEmitter implements OwnerInterface
{
    private $pipe;

    /**
     * @var ReadableResourceStream
     */
    private $stream;

    /**
     * @var Unpack
     */
    private $unpack;

    public function __construct(string $pipe, LoopInterface $loop, $mode = 0620)
    {
        $this->pipe = $pipe;

        if (file_exists($this->pipe))
            throw new PipeException('', PipeException::ERR_ANOTHER_READER_EXITS);

        if (!posix_mkfifo($this->pipe, $mode))
            throw new PipeException('', PipeException::ERR_UNABLE_TO_CREATE_PIPE);

        $resource = fopen($this->pipe, 'r+');
        if (!$resource)
            throw new PipeException('', PipeException::ERR_UNABLE_TO_OPEN_PIPE);

        $this->stream = new ReadableResourceStream($resource, $loop);
        Util::forwardEvents($this->stream, $this, ['error', 'close']);

        $this->unpack = new Unpack();
        $this->unpack->on('unpack', function (PackInterface $pack) {
            $this->emit('data', [$pack->getData()]);
        });

        $this->stream->on('data', function ($data) {
            $this->unpack->feed($data);
        });
    }

    public function close()
    {
        $this->stream->close();

        // Remove pipe when reader closed the stream.
        if (file_exists($this->pipe))
            if (!unlink($this->pipe))
                throw new PipeException('', PipeException::ERR_UNABLE_TO_REMOVE_PIPE);
    }
}