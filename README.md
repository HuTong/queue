# queue
数据列队

### 配置
```
1、php数组存储
$config = array(
    'type' => 'Arr',
    'host' => '0.0.0.0',
    'port' => '9510',
    'server' => array(
        'worker_num' => 1,
    )
);
2、redis存储
$config = array(
    'type' => 'Redis',
    'host' => '0.0.0.0',
    'port' => '9510',
    'server' => array(
        'worker_num' => 1,
    ),
    'set' => array(
        'host' => '127.0.0.1',
        'port' => '6379',
        'password' => '123456',
        'listName' => 'queueList',
    ),
);
3、SplQueue存储
$config = array(
    'type' => 'Spl',
    'host' => '0.0.0.0',
    'port' => '9510',
    'server' => array(
        'worker_num' => 1,
    )
);
```

### 服务端
```
include './vendor/autoload.php';

$serv = new HuTong\Queue\Server($config);
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

# 学习交流群
630730920