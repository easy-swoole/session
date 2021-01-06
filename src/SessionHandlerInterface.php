<?php


namespace EasySwoole\Session;


interface SessionHandlerInterface
{
    function open(string $sessionId,?float $timeout = null):bool;
    function read(string $sessionId,?float $timeout = null):?array;
    function close(string $sessionId,?float $timeout = null):bool;
    function gc(int $expire,?float $timeout = null):bool;
}