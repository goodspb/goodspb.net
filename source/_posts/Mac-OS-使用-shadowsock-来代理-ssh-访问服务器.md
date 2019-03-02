---
title: Mac OS 使用 shadowsock 来代理 ssh 访问服务器
url: 322.html
id: 322
categories:
  - Linux/Unix
date: 2017-05-13 18:44:04
tags:
---

有时候，有时候，你需要访问国外的服务器，但是，如果直接使用 ssh 去连接的话，你会感觉奇慢无比，这时候，一个代理可能会拯救你，例如：ss。

> 本文是说明如何使用 shadowsock 来代理 ssh 访问服务器，不是使用 ssh 来做代理哦，大家请看清楚

当然，系统针对 Unix（其实只在 Mac 下使用过）

<!--more-->

### 使用方法

     ssh root@192.168.0.1 -o "ProxyCommand nc -X 5 -x 127.0.0.1:1080 %h %p"
    

### 解释

`ssh root@192.168.0.1` 这部分就不用解释了吧？我们来看看 `-o "ProxyCommand nc -X 5 -x 127.0.0.1:1080 %h %p"` `-o ProxyCommand` : ssh 命令选项，你可以理解成使用 "在 ssh 中使用代理" `nc`: nc命令(netcat) `127.0.0.1:1080`: 本地 shadowsock 的监听地址和监听端口，这是这条命令的唯一需要你自己配置的部分，可内网或者外网。

### 鉴别自己是否真的使用了代理来登陆服务器

终端执行命令 `who` ， 会出现以下结果：

    root@ubuntu:~# who
    root     pts/2        2017-05-13 18:13 (xxx.xxx.xxx.xxx)
    

这个时候，就就要看一下 `(xxx.xxx.xxx.xxx)` 是不是代理的命令了。 Have fun!