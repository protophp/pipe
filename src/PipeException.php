<?php

namespace Proto\Pipe;

class PipeException extends \Exception
{
    const ERR_PIPE_NOT_EXISTS = 100;
    const ERR_UNABLE_TO_OPEN_PIPE = 101;
    const ERR_UNABLE_TO_CREATE_PIPE = 102;
    const ERR_UNABLE_TO_REMOVE_PIPE = 103;

    const ERR_ANOTHER_READER_EXITS = 200;
}