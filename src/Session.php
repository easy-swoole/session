<?php


namespace EasySwoole\Session;


use EasySwoole\Utility\Random;

class Session
{
    protected $context = [];
    protected $handler;
    protected $timeout;

    function __construct(SessionHandlerInterface $handler,float $timeout = 3.0)
    {
        $this->handler = $handler;
        $this->timeout = $timeout;
    }

    function create(?string $sessionId = null,float $timeout = null):?Context
    {
        if($timeout === null){
            $timeout = $this->timeout;
        }
        if(empty($sessionId)){
            $sessionId = Random::makeUUIDV4();
        }
        if(!isset($this->context[$sessionId])){
            if($this->handler->open($sessionId,$timeout)){
                $this->context[$sessionId] = new Context($this->handler->read($sessionId,$timeout));
            }else{
                return null;
            }
        }
        return $this->context[$sessionId];
    }

    function close(string $sessionId,float $timeout = null):?bool
    {
        if(!isset($this->context[$sessionId])){
            if($timeout === null){
                $timeout = $this->timeout;
            }
            return $this->handler->close($sessionId,$timeout);
        }
        return null;
    }

    function gc(int $expire,float $timeout = null):bool
    {
        if($timeout === null){
            $timeout = $this->timeout;
        }
        return $this->handler->gc($expire,$timeout);
    }
}
