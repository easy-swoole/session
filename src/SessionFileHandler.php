<?php


namespace EasySwoole\Session;


use EasySwoole\Component\ChannelLock;
use EasySwoole\Spl\SplContextArray;
use EasySwoole\Spl\SplFileStream;

class SessionFileHandler implements \SessionHandlerInterface
{
    private $temp;
    private $pathContext;

    function __construct(?string $tempDir = null)
    {
        ChannelLock::getInstance();
        if(!$tempDir){
            $tempDir = sys_get_temp_dir();
        }
        $this->temp = $tempDir;
        $this->pathContext = new SplContextArray(true);
    }

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        $file = "{$this->pathContext['path']}/{$session_id}";
        $stream = new SplFileStream($file);
        $stream->lock();
        ChannelLock::getInstance()->lock($file);
        try{
            unlink($file);
        }catch (\Throwable $throwable){
            throw $throwable;
        }finally{
            $stream->unlock();
            ChannelLock::getInstance()->unlock($file);
            $stream->close();
        }
        return true;
    }

    public function gc($maxlifetime)
    {
        // TODO: Implement gc() method.
    }

    public function open($save_path, $name)
    {
        $dir = $this->temp.'/'.$save_path;
        $this->pathContext['path'] = $dir;
        if(!is_dir($dir)){
            return mkdir($this->temp.'/'.$save_path,0777,true);
        }
        return true;
    }

    public function read($session_id)
    {
        $ret = '';
        $file = "{$this->pathContext['path']}/{$session_id}";
        $stream = new SplFileStream($file);
        $stream->lock();
        try{
            $ret = $stream->__toString();
        }catch (\Throwable $throwable){
            throw $throwable;
        }finally{
            $stream->unlock();
            ChannelLock::getInstance()->unlock($file);
            $stream->close();
        }
        return $ret;
    }

    public function write($session_id, $session_data)
    {
        $file = "{$this->pathContext['path']}/{$session_id}";
        $stream = new SplFileStream($file);
        $stream->lock();
        ChannelLock::getInstance()->lock($file);
        try{
            $stream->truncate();
            $stream->write($session_data);
        }catch (\Throwable $throwable){
            throw $throwable;
        }finally{
            $stream->unlock();
            ChannelLock::getInstance()->unlock($file);
            $stream->close();
        }
    }
}