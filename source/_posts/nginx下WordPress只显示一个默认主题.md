---
title: nginx下WordPress只显示一个默认主题
url: 30.html
id: 30
categories:
  - wordpress
date: 2013-02-03 18:30:52
tags:
---

用lnmp一键安装包在CentOS中架了一个博客站，安装完WordPress之后，准备给博客换一个主题，但是在后台只显示一个主题，其他官方主题都不显示了。 刚开始以为是wordpress新版本的问题，不管安装什么主题都只显示一个，安装同一个提示安装的目录有存在。开始以为是文件夹权限问题，重新设置了下发现还是只显示一个主题，后来通过搜索发现原来是php.ini禁止了scandir函数。 翻看php手册，scandir() 函数是这样被定义的：“scandir() 函数返回一个数组，其中包含指定路径中的文件和目录”，wordpress可能居于这个函数去开发的，所以就只显示了一个主题。 由于我装的是lnmp的安装包，其中禁用了部分危险函数：“passthru, exec, system, chroot, scandir, chgrp, chown, shell\_exec, proc\_open, proc\_get\_status, ini\_alter, ini\_alter, ini_restore, dl, pfsockopen”，而scandir函数也在此列，所以这样问题的解决方法只能是将scandir从禁用函数剔除就可以了。 我们可以通过登录到Xshell 或是putty来修改/usr/local/php/etc下的php.ini文件，然后重启一下php进程“ service php-fpm restart 或 /etc/init.d/php-fpm restart ”就可以了。