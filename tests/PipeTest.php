<?php

namespace Proto\Pipe\Tests;

use PHPUnit\Framework\TestCase;
use Proto\Pipe\ReadablePipe;
use Proto\Pipe\WritablePipe;
use React\EventLoop\Factory;

class PipeTest extends TestCase
{
    /**
     * @throws \Proto\Pipe\PipeException
     */
    public function testPipe()
    {
        $loop = Factory::create();
        $pipe = __DIR__ . '/pipe.fifo';

        if(file_exists($pipe))
            unlink($pipe);

        $owner = new ReadablePipe($pipe, $loop);
        $connector = new WritablePipe($pipe, $loop);

        $DATA = random_bytes(1024 * 1024 * 1);
        $checksum = sha1($DATA);

        $owner->on('data', function ($data) use ($loop, $owner, $checksum) {
            $this->assertSame($checksum, sha1($data));
            $owner->close();
            $loop->stop();
        });

        $connector->write($DATA);

        $loop->run();
    }
}