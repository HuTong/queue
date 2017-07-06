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
            if(isset($config['type'])){
                $class = "HuTong\Queue\Drive\\".$config['type'];
            }else{
                throw new \Exception('列队类型不能为空');
            }
            
            $this->instance = new $class($config);
        }
    }

    public function onStart(Swoole\Server $serv, $worker_id)
    {

    }

    public function onReceive(Swoole\Server $serv, $fd, $reactor_id, $data)
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

        } else {

        }
    }

    public function onStop()
    {

    }

    public function listen($host = '0.0.0.0', $port = 9510)
    {
        $swoole_setting = $this->config['swoole'];

        $server = new Swoole\Server($host, $port, SWOOLE_BASE);
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
