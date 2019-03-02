---
title: PHP CURL 超时设置 CURLOPT_CONNECTTIMEOUT 和 CURLOPT_TIMEOUT 的区别
tags:
  - curl
  - CURLOPT_CONNECTTIMEOUT
  - CURLOPT_TIMEOUT
url: 319.html
id: 319
categories:
  - PHP
date: 2017-04-25 16:39:04
---

### CURLOPT_CONNECTTIMEOUT

用来告诉 PHP 在成功连接服务器前等待多久（连接成功之后就会开始缓冲输出），这个参数是为了应对目标服务器的过载，下线，或者崩溃等可能状况。

<!--more-->

### CURLOPT_TIMEOUT

用来告诉成功 PHP 从服务器接收缓冲完成前需要等待多长时间，如果目标是个巨大的文件，生成内容速度过慢或者链路速度过慢，这个参数就会很有用。

### 例子

使用 cURL 下载 MP3 文件是一个对开发人员来说不错的例子，CURLOPT\_CONNECTTIMEOUT 可以设置为10秒，标识如果服务器10秒内没有响应，脚本就会断开连接，CURLOPT\_TIMEOUT 可以设置为100秒，如果MP3文件100秒内没有下载完成，脚本将会断开连接。 需要注意的是：CURLOPT\_TIMEOUT 默认为0，意思是永远不会断开链接。所以不设置的话，可能因为链接太慢，会把 HTTP 资源用完。 在 WordPress 中，wp\_http 类，这两个值是一样的，默认是设置为 5 秒。