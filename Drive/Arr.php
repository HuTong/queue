<?php
namespace Hutong\Queue\Drive;

/**
 * @desc array 存储
 */
class Arr
{
    private $config;
    private $container = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function pop()
    {
        return array_shift($this->container);
    }

    public function push($data)
    {
        return array_push($this->container, $data);
    }

    public function stats()
    {

    }
}
