---
title: '/bin, /sbin, /usr/bin, /usr/sbin 有何区别'
url: 439.html
id: 439
categories:
  - Linux/Unix
date: 2012-10-07 13:38:35
tags:
---

相信配置过系统 PATH 的人都知道 /bin, /sbin, /usr/bin, /usr/sbin ，bash在寻找二进制文件的时候有加载的顺序，它们之间有何不同呢？   /bin：是系统的一些指令。bin为binary的简写主要放置一些系统的必备执行档例如:cat、cp、chmod df、dmesg、gzip、kill、ls、mkdir、more、mount、rm、su、tar等 /sbin：一般是指超级用户指令。主要放置一些系统管理的必备程式例如:cfdisk、dhcpcd、dump、e2fsck、fdisk、halt、ifconfig、ifup、 ifdown、init、insmod、lilo、lsmod、mke2fs、modprobe、quotacheck、reboot、rmmod、 runlevel、shutdown等 /usr/bin：是你在后期安装的一些软件的运行脚本。主要放置一些应用软体工具的必备执行档例如c++、g++、gcc、chdrv、diff、dig、du、eject、elm、free、gnome*、 gzip、htpasswd、kfm、ktop、last、less、locale、m4、make、man、mcopy、ncftp、 newaliases、nslookup passwd、quota、smb*、wget等 /usr/sbin ：放置一些用户安装的系统管理的必备程式例如:dhcpd、httpd、imap、in.*d、inetd、lpd、named、netconfig、nmbd、samba、sendmail、squid、swap、tcpd、tcpdump等