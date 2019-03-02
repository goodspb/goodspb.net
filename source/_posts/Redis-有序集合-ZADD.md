---
title: Redis 有序集合 ZADD
url: 94.html
id: 94
categories:
  - redis
date: 2014-11-23 21:38:20
tags:
---

Redis 的有序集合貌似很厉害，与SQL 中的order其实有点相似； 


**一、应用场景：排行榜** 



**二、常用的函数命令：** 


1、ZADD ：添加一个或多个成员到有序集合，或者如果它已经存在更新其分数 
2、ZRANGE：由索引返回一个成员范围的有序集合 
3、ZREM：从有序集合中删除一个或多个成员   

<!--more-->

**三、具体事例：**

redis 127.0.0.1:6379> ZADD tutorials 1 redis       //添加
(integer) 1
redis 127.0.0.1:6379> ZADD tutorials 2 mongodb     //添加
(integer) 1
redis 127.0.0.1:6379> ZADD tutorials 3 mysql       //添加
(integer) 1
redis 127.0.0.1:6379> ZADD tutorials 3 mysql       //更新分数
(integer) 0
redis 127.0.0.1:6379> ZADD tutorials 4 mysql       //更新分数
(integer) 0
redis 127.0.0.1:6379> ZRANGE tutorials 0 10 WITHSCORES

1) "redis"
2) "1"
3) "mongodb"
4) "2"
5) "mysql"
6) "4"

redis 127.0.0.1:6379&gt; ZREM mongodb    //删除
(integer) 1
redis 127.0.0.1:6379> ZRANGE tutorials 0 10 WITHSCORES

1) "redis"
2) "1"
3) "mysql"
4) "4"