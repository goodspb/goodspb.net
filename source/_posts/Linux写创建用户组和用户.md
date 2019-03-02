---
title: Linux写创建用户组和用户
url: 433.html
id: 433
categories:
  - Linux/Unix
date: 2012-08-05 16:31:14
tags:
---

当你自行编译 php 的时候，很多时候需要指定启动 php-fpm 的 group 和 user ，这个时候，我们就需要自行创建用户和组了，这里做个记录，怎么在linux下创建用户组和用户。 我需要的事创建用户组: `www`， 用户: `www`

<!--more-->

### 一、创建用户组

sudo groupadd www

www  就是你需要创建的用户组的名称，我这里是直接创建组名为 www 的用户组  

### 二、创建用户

sudo useradd www -m -s /sbin/nologin -d /home/www -g www

参数解释：

-s /sbin/nologin 设置不能该用户登陆。假如需要用户可以登陆，可以设置： -s /bin/bash www
-d 设置用户主目录
-g 用户组
-m 创建用户目录

### 三、useradd 参数详解

执行：

useradd --help

Usage: useradd \[options\] LOGIN
       useradd -D
       useradd -D \[options\]

Options:
  -b, --base-dir BASE_DIR       base directory for the home directory of the
                                new account
  -c, --comment COMMENT         GECOS field of the new account
  -d, --home-dir HOME_DIR       home directory of the new account
  -D, --defaults                print or change default useradd configuration
  -e, --expiredate EXPIRE_DATE  expiration date of the new account
  -f, --inactive INACTIVE       password inactivity period of the new account
  -g, --gid GROUP               name or ID of the primary group of the new
                                account
  -G, --groups GROUPS           list of supplementary groups of the new
                                account
  -h, --help                    display this help message and exit
  -k, --skel SKEL_DIR           use this alternative skeleton directory
  -K, --key KEY=VALUE           override /etc/login.defs defaults
  -l, --no-log-init             do not add the user to the lastlog and
                                faillog databases
  -m, --create-home             create the user's home directory
  -M, --no-create-home          do not create the user's home directory
  -N, --no-user-group           do not create a group with the same name as
                                the user
  -o, --non-unique              allow to create users with duplicate
                                (non-unique) UID
  -p, --password PASSWORD       encrypted password of the new account
  -r, --system                  create a system account
  -R, --root CHROOT_DIR         directory to chroot into
  -s, --shell SHELL             login shell of the new account
  -u, --uid UID                 user ID of the new account
  -U, --user-group              create a group with the same name as the user
  -Z, --selinux-user SEUSER     use a specific SEUSER for the SELinux user mapping
      --extrausers              Use the extra users database

 

### 四、为用户设置密码

sudo passwd www

 

### 五、更改用户属性

sudo usermod -s /bin/bash username

使用 usermod 来更改用户的属性，这里的命令代表：使用户可以登陆。  

### 六、如何删除用户组或者用户

#删除用户组
sudo groupdel www

#删除用户
sudo userdel www

ps: 需要先删除用户，再删除用户组。   Have fun!