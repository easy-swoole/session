<?php


namespace EasySwoole\Session;


class FileSessionHandler implements \SessionHandlerInterface
{
    /*
     * 无锁实现
     */
    private $savePath;
    private $name;
    private $sessionId;

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        $this->sessionId = $session_id;
        $file = $this->file();
        if(file_exists($file)){
            unlink($file);
            return true;
        }else{
            return null;
        }
    }

    public function gc($maxlifetime)
    {

    }

    public function open($save_path, $name)
    {
       $this->savePath = $save_path;
       $this->name = $name;
       return true;
    }

    public function read($session_id)
    {
        $this->sessionId = $session_id;
        $file = $this->file();
        if(file_exists($file)){
            return file_get_contents($file);
        }else{
            return null;
        }
    }

    public function write($session_id, $session_data)
    {
        $this->sessionId = $session_id;
        $file = $this->file();
        return (bool)file_put_contents($file,$session_data);
    }

    private function file()
    {
        return "{$this->savePath}/{$this->name}_{$this->sessionId}";
    }
}