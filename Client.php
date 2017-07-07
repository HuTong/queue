<?php
namespace Hutong\Queue;

/**
* @desc 列队客户端
*/
class Client
{
    private $instance;

    const EOF = "\r\n";

    public function __construct($config = array())
    {
        $ip = isset($config['ip']) ? $config['ip'] : '127.0.0.1';
        $port = isset($config['port']) ? $config['port'] : 9510;
        $timeout = isset($config['timeout']) ? $config['timeout'] : 2;

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

    public function stats()
    {
        if ($this->instance->send("STATS " . self::EOF))
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

    public function clear()
    {
        if ($this->instance->send("CLEAR " . self::EOF))
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
}
