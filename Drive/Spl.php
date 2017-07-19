<?php
namespace Hutong\Queue\Drive;

use HuTong\Queue\Contract;

/**
 * @desc redis 存储
 */
class Spl implements Contract
{
    private $config;
    private $container;
    private $pop_count = 0;
    private $push_count = 0;

    public function __construct($config)
    {
        $this->config = $config;

        $this->container = new \SplQueue();
    }

    public function pop()
    {
        $this->pop_count ++;
        return $this->container->dequeue();
    }

    public function push($data)
    {
        try {
            $this->push_count ++;
            return $this->container->enqueue($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function stats()
    {
        $info = array(
            'pop_count' => $this->pop_count,
            'push_count'=> $this->push_count,
            'head_index'=> $this->container->count()
        );
        return json_encode($info);
    }

    public function clear()
    {
        $this->pop_count = 0;
        $this->push_count = 0;
        $count = $this->container->count();

        for ($i = 0; $i < $count; $i++)
        {
            $this->container->dequeue();
        }
    }
}
