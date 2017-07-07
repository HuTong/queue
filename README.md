# queue
数据列队

### 配置
```
1、php数组存储
$config = array(
    'type' => 'Arr',
    'host' => '0.0.0.0',
    'port' => '9510',
    'set' => array(
        'worker_num' => 1,
    ),
);
2、redis存储
$config = array(
    'type' => 'Redis',
    'host' => '0.0.0.0',
    'port' => '9510',
    'set' => array(
        'host' => '127.0.0.1',
        'port' => '6379',
        'password' => '123456',
        'listName' => 'queueList',
    ),
);
```

### 服务端
```
include './vendor/autoload.php';

$serv = new HuTong\Queue\Server(array('type'=>'Arr','swoole'=>array('worker_num'=>1)));
$serv->listen();
```

### 客户端
```
$config = array(
    'ip' => '127.0.0.1',
    'port' => '9510',
    'timeout' => 2
);
```
```
include './vendor/autoload.php';

$client = new HuTong\Queue\Client($config);

for ($i = 0; $i < 100; $i++)
{
    $info = $client->push('N:'.$i);
    var_dump($info);
}

$info = $client->pop();
var_dump($info);
```
