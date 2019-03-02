---
title: MySql 性能调试之 —— 开启慢查询日志
url: 56.html
id: 56
categories:
  - Mysql
date: 2013-10-04 14:46:44
tags:
---

**一、查看当前服务器是否开启慢查询：** 1、快速办法，运行sql语句show VARIABLES like "%slow%" 2、直接去my.conf中查看。

#### **二、开启慢查询：**

在配置文件my.conf中配置 **#记录地址** slow\_query\_log_file = /usr/local/mysql/var/slowquery.log **#时间** long\_query\_time = 1  #单位是秒 **#设置未启用索引的查询是否被记录** log\_queries\_not\_using\_indexes = 0 _或者使用sql 语句来修改：_ set global slow\_query\_log\_file = ‘/usr/local/mysql/var/slowquery.log’; set global slow\_query\_log = ON; set global long\_query_time=1; #设置大于1s的sql语句记录下来   **三、慢查询日志文件的信息格式：** # Time: 130905 14:15:59         时间是2013年9月5日 14:15:59(前面部分容易看错哦,乍看以为是时间戳) # User@Host: root\[root\] @  \[183.239.28.174\]  请求mysql服务器的客户端ip # Query\_time: 0.735883  Lock\_time: 0.000078 Rows\_sent: 262  Rows\_examined: 262 这里表示执行用时多少秒，0.735883秒，1秒等于1000毫秒 SET timestamp=1378361759;  这目前我还不知道干嘛用的 show tables from \`test_db\`; 这个就是关键信息，指明了当时执行的是这条语句   **四、调试：** select sleep(0.13);