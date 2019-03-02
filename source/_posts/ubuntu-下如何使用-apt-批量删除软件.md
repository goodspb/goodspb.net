---
title: ubuntu 下如何使用 apt 批量删除软件
url: 408.html
id: 408
categories:
  - Linux/Unix
  - PHP
date: 2014-08-28 18:44:14
tags:
---

想删除 ubuntu 服务器下的所有 php5.4 的软件，例如：php5.4-cli, php5.4-fpm 等, 但是一个一个来删除，有点难度啊，所以，查了一下，可以使用一下方法： 1、直接 apt-get purge

sudo apt-get purge php5.4*

2、apt-get purge + 筛选函数

sudo apt-get purge \`dpkg -l | grep php5.4 | awk '{print $2}' |tr "\\n" " "\`