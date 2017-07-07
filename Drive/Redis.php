<?php
namespace Hutong\Queue\Drive;

/**
 * @desc redis 存储
 */
class Redis
{
    private $config;
    private $container;
    private $pop_count = 0;
    private $push_count = 0;

    public function __construct($config)
    {
        $this->config = $config['set'];

        $this->container = new \Redis();
        $this->container->connect($this->config['host'], $this->config['port']);

        if(isset($this->config['password']) && $this->config['password'])
        {
            $this->container->auth($this->config['password']);
        }
    }

    public function pop()
    {
        $this->pop_count ++;
        return $this->container->lpop($this->config['listName']);
    }

    public function push($data)
    {
        try {
            $this->push_count ++;
            return $this->container->rpush($this->config['listName'], $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function stats()
    {
        $info = array(
            'pop_count' => $this->pop_count,
            'push_count'=> $this->push_count,
            'head_index'=> $this->push_count - $this->pop_count
        );
        return json_encode($info);
    }

    public function stop()
    {
        unset($this->container);
    }

    public function clear()
    {
        $this->pop_count = 0;
        $this->push_count = 0;
        $this->container->delete($this->config['listName']);
    }
}
