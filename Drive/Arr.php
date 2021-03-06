<?php
namespace Hutong\Queue\Drive;

use HuTong\Queue\Contract;

/**
 * @desc array 存储
 */
class Arr implements Contract
{
    private $config;
    private $container = [];
    private $pop_count = 0;
    private $push_count = 0;


    public function __construct($config)
    {
        $this->config = $config;
    }

    public function pop()
    {
        $this->pop_count ++;
        return array_shift($this->container);
    }

    public function push($data)
    {
        $this->push_count ++;
        return array_push($this->container, $data);
    }

    public function stats()
    {
        $info = array(
            'pop_count' => $this->pop_count,
            'push_count'=> $this->push_count,
            'head_index'=> count($this->container)
        );
        return json_encode($info);
    }

    public function clear()
    {
        $this->pop_count = 0;
        $this->push_count = 0;
        unset($this->container);
        $this->container = [];
    }
}
