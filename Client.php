<?php
namespace Hutong\Queue;

/**
* @desc 列队客户端
*/
class Client
{
    private $instance;
    private $config;

    const EOF = "\r\n";

    public function __construct($ip = '127.0.0.1', $port = 9510, $timeout = 2.0)
    {
        $client = new \Swoole\Client(SWOOLE_SOCK_TCP);
        $client->set(array('open_eof_check' => true, 'package_eof' => self::EOF));

        if (!$client->connect($ip, $port, $timeout))
        {
            throw new Exception("cannot connect to server [$ip:$port].");
        }

        $this->instance = $client;
    }

    public function push($data)
    {
        if ($this->instance->send("PUSH " . $data.self::EOF))
        {
            $result = $this->instance->recv();

            if ($result === false)
            {
                return false;
            }

            if (substr($result, 0, 2) == 'OK')
            {
                return true;
            } else {
                $this->errMsg = substr($result, 4);
                return false;
            }
        } else {
            return false;
        }
    }

    public function pop()
    {
        if ($this->instance->send("POP " . self::EOF))
        {
            $result = $this->instance->recv();

            if ($result === false)
            {
                return false;
            }

            if (substr($result, 0, 2) == 'OK')
            {
                return substr($result, 3, strlen($result) - 3 - strlen(self::EOF));
            } else {
                $this->errMsg = substr($result, 4);
                return false;
            }
        } else {
            return false;
        }
    }
}
