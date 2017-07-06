<?php
namespace Hutong\Queue\Array;

/**
 * @desc array 存储
 */

class Array
{
    private $config;
    private $container;

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
