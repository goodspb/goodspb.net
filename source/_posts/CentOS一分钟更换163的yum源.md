---
title: CentOS一分钟更换163的yum源
url: 156.html
id: 156
categories:
  - Linux/Unix
date: 2015-08-29 22:31:41
tags:
---

  1.下载repo文件： .代码如下:

wget http://mirrors.163.com/.help/CentOS6-Base-163.repo

//根据自己的需要哦。本人用的是centos 6.5的 2.备份并替换系统的repo文件 .代码如下:

\[root@localhost ~\]# cd /etc/yum.repos.d/

\[root@localhost ~\]# mv CentOS-Base.repo CentOS-Base.repo.bak

\[root@localhost ~\]# mv CentOS6-Base-163.repo CentOS-Base.repo

3.执行yum源更新 .代码如下:

\[root@localhost ~\]# yum clean all

\[root@localhost ~\]# yum makecache

\[root@localhost ~\]# yum update