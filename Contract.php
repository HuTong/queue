<?php
namespace Hutong\Queue;

interface Contract
{
    /**
     * 出列
     * @return boolval
     *
     * @author hutong
     * @date   2017-07-07
     */
    public function pop();

    /**
     * 入列
     * @param  string     $data
     * @return boolval
     *
     * @author hutong
     * @date   2017-07-07
     */
    public function push($data);

    /**
     * 查看列队状态
     * @return string
     *
     * @author hutong
     * @date   2017-07-07
     */
    public function stats();

    /**
     * 清空列队数据
     * @return void
     *
     * @author hutong
     * @date   2017-07-07
     */
    public function clear();
}
