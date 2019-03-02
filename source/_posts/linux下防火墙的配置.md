---
title: linux下防火墙的配置
url: 58.html
id: 58
categories:
  - Linux/Unix
date: 2014-05-12 15:27:55
tags:
---

如果大家是使用一键环境（如：lnmp.org)，有时候防火墙会被设置成正式环境一样，当你是用作测试服务器的时候，这样就很不方便了。 因此，在这里记录一下linux下防火墙的配置： 地址是：/etc/sysconfig/iptables

<!--more-->

\# Firewall configuration written by system-config-firewall
\# Manual customization of this file is not recommended.
*filter
:INPUT ACCEPT \[0:0\]
:FORWARD ACCEPT \[0:0\]
:OUTPUT ACCEPT \[0:0\]
-A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
-A INPUT -p icmp -j ACCEPT
-A INPUT -i lo -j ACCEPT
-A INPUT -i eth0 -j ACCEPT
-A INPUT -m state --state NEW -m tcp -p tcp --dport 22 -j ACCEPT
-A INPUT -m state --state NEW -m tcp -p tcp --dport 80 -j ACCEPT
-A INPUT -m state --state NEW -m tcp -p tcp --dport 3306 -j ACCEPT #这条是mysql的端口，正式服务器请注释这条
-A INPUT -m state --state NEW -m tcp -p tcp --dport 6379 -j ACCEPT #这条是redis的端口，正式服务器请注释这条
-A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT
-A FORWARD -p icmp -j ACCEPT
-A FORWARD -i lo -j ACCEPT
-A FORWARD -i eth0 -j ACCEPT
-A INPUT -j REJECT --reject-with icmp-host-prohibited
-A FORWARD -j REJECT --reject-with icmp-host-prohibited
COMMIT