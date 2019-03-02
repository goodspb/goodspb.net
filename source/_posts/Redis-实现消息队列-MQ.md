---
title: Redis 实现消息队列 MQ
url: 98.html
id: 98
categories:
  - redis
date: 2014-12-03 16:36:51
tags:
---

Redis 2.4版本之后就内置队列的功能了，如果是日常比较简单的队列应用，可以选择Redis , 效率还很高的！！   Redis 还能实现 **有序** 和 **无序** 两种队列（只讨论生产者和消费者这种模式的队列）： 

<!--more-->


**一、有序队列：** 1、生产者：

$redis = new Redis();
$redis->pconnect('127.0.0.1', 6379);
$redis->zAdd('MQ', 1, 'need to do 1');
$redis->zAdd('MQ', 2, 'need to do 2');

2、消费者：

while (true) {
        $pid = pcntl_fork();
        if ($pid == -1) {
            //创建子进程失败，不处理
        } else if ($pid == 0) {
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            //执行有序查询，取出排序前10进行处理
            $redis->zRevRangeByScore('MQ', '+inf', '-inf', array('withscores'=>false, 'limit'=>array(0,10)));
            exit;
        } else {
            //主进行执行中，等待
            pcntl_wait($status);
        }
}

  二、无序队列： 1、生产者：

$redis = new Redis();
$redis->pconnect('127.0.0.1', 6379);
$redis->LPUSH('MQ', 1, 'need to do 1');
$redis->LPUSH('MQ', 2, 'need to do 2');

2、消费者：

while (true) {
        $pid = pcntl_fork();
        if ($pid == -1) {
            //创建子进程失败，不处理
        } else if ($pid == 0) {
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            //执行出队处理，BLPOP是阻塞的出队方式，其实还可以用LPOP，不过用lPOP就要自行判断数据是否为空了
            $mq = $redis->BLPOP('MQ')
            //do something

        } else {
            //主进行执行中，等待
            pcntl_wait($status);
        }
}

  简单版就是这样了~~当然，如果应用规模大，还是建议用正规的MQ，例如：RabbitMQ