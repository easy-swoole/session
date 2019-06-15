<?php


namespace EasySwoole\Session;


class Session
{
    protected $handler = null;

    function __construct(\SessionHandlerInterface $sessionHandler)
    {
        $this->handler = $sessionHandler;
    }

    function start()
    {

    }

    function set()
    {

    }

    function get()
    {

    }

    function destroy()
    {

    }

    function close()
    {

    }
}