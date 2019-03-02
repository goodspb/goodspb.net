---
title: 用 Redis  实现无序队列 LPUSH / LPOP
url: 96.html
id: 96
categories:
  - redis
date: 2014-10-03 23:26:46
tags:
---

在实际的开发当中，当系统需要使用简单的任务列表的时候，我们可以使用Redis来充当队列（因为Redis本来就自带队列，简单易用，效率也不低，免去使用MQ等麻烦）。 使用Redis的队列，我们要记住2个常用的命令 ： LPUSH  & LPOP 上面两个命令是不是很熟悉：PUSH 和 POP ，不错，其实就是这个英文，只是在前面加上L大写开头。   上例子（PHP）： 

<!--more-->

**一、生产者：**

```php
<?php

//需要Redis扩展，请自定搞定
$redis = new Redis();
//长连接
$redis->pconnect('127.0.0.1", "6379");
//写入单个信息
$redis->LPush('message', 'a');
//写入多个信息
$redis->LPush('message','b','c','d');
//关闭redis连接
$redis->close();

?>
```

**二、消费者:**

```php
<?php


$redis = new Redis();
//还是长连接
$redis->pconnect('127.0.0.1', 6379);
//循环取出队列中的数据
while(true) {
    try {
        //取出数据成功时
        $data =  $redis->LPOP('message');
        //也可以使用阻塞型的函数：BLPOP
        //$redis->BLPOP('list1', 10) //等到超时时间为10秒
    } catch(Exception $e) {
        //队列中什么都没有，继续运行
    }
}

?>
```

具体例子就如上面，其实还有其他关于 队列的 函数可以用，具体请参考： http://redisdoc.com/list/index.html