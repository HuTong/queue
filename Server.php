<?php
namespace Hutong\Queue;

/**
* @desc 列队服务端
*/
class Server
{
    private $instance;
    private $config;

    const EOF = "\r\n";

    public function __construct($config)
    {
        if(!isset($this->instance))
        {
            if(!isset($config['type'])){
                throw new \Exception('列队类型不能为空');
            }

            $this->config = $config;
        }
    }

    public function onStart(\Swoole\Server $serv, $worker_id)
    {
        if(!isset($this->instance))
        {
            if(isset($this->config['type'])){
                $class = "HuTong\Queue\Drive\\".$this->config['type'];
            }else{
                throw new \Exception('列队类型不能为空');
            }

            $this->instance = new $class($this->config);
        }
    }

    public function onReceive(\Swoole\Server $serv, $fd, $reactor_id, $data)
    {
        $op = strtolower(strstr($data, ' ', true));

        if ($op == 'pop')
        {
            $rs = $this->instance->pop();

            $serv->send($fd, 'OK '.$rs.self::EOF);
        } elseif($op == 'push') {
            $rs = substr($data, 5, strlen($data) - 5 - strlen(self::EOF));

            $this->instance->push($rs);

            $serv->send($fd, 'OK '.self::EOF);
        } elseif($op == 'stats') {
            $rs = $this->instance->stats();

            $serv->send($fd, 'OK '.$rs.self::EOF);
        } elseif($op == 'clear') {
            $this->instance->clear();

            $serv->send($fd, 'OK '.self::EOF);
        } else {
            $serv->send($fd, 'ERR unsupported command ['.$op.']'.self::EOF);
        }
    }

    public function onStop()
    {
        $this->instance->stop();
        unset($this->instance);
    }

    public function listen()
    {
        $host = isset($this->config['host']) ? $this->config['host'] : '0.0.0.0';
        $port = isset($this->config['port']) ? $this->config['port'] : 9510;

        $swoole_setting = isset($this->config['swoole']) ? $this->config['swoole'] : array();

        $server = new \Swoole\Server($host, $port, SWOOLE_BASE);
        $swoole_setting['open_eof_check'] = true;
        $swoole_setting['open_eof_split'] = true;
        $swoole_setting['package_eof'] = self::EOF;
        $server->set($swoole_setting);
        $server->on('WorkerStart', [$this, 'onStart']);
        $server->on('WorkerStop', [$this, 'onStop']);
        $server->on('receive', [$this, 'onReceive']);
        $this->server = $server;
        $this->server->start();
    }
}
