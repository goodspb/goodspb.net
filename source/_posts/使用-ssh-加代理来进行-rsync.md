---
title: 使用 ssh 加代理来进行 rsync
tags:
  - proxy
  - rsync
  - ssh
url: 325.html
id: 325
categories:
  - Linux/Unix
date: 2017-05-31 18:55:50
---

废话少讲，直接上命令：

    rsync -av --delete --exclude-from=./deployignore -e "ssh -o 'ProxyCommand nc -X 5 -x 127.0.0.1:1080 %h %p'" /data/wwwroot/abc  root@192.168.0.100:/home/wwwroot/abc
    

解析：

     --delete --exclude-from=xxxx                                                           //忽略该文件内容中的列表
     -e "ssh -o 'ProxyCommand nc -X 5 -x 127.0.0.1:1080 %h %p'"      //使用 ssh 做通讯，并使用 sock5 代理，代理地址：127.0.0.1 ，端口：1080
    /data/wwwroot/abc                                                                             //本地地址
    root@192.168.0.100:/home/wwwroot/abc                                        //远端地址
    

have fun!