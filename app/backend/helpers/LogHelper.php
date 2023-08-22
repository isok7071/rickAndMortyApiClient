<?php

namespace App\Backend\Helpers;

use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\StreamHandler;

class LogHelper
{
    public Logger $log;
    private const STREAM = 'file.log';

    public function __construct()
    {
        $this->log = new Logger('api');
        $this->log->pushHandler(new StreamHandler(self::STREAM, Level::Error));
    }
}