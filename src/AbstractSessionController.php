<?php


namespace EasySwoole\Session;


use EasySwoole\Http\AbstractInterface\Controller;

abstract class AbstractSessionController extends Controller
{
    private $session;

    protected abstract function sessionConfig():Config;

    protected function session(): Session
    {
        if($this->session == null){
            $this->session = new Session($this->sessionConfig()->getHandler());
        }
        return $this->session;
    }

    protected function gc()
    {
        parent::gc();
        if($this->session){
            $this->session()->close();
            $this->session = null;
        }
    }
}