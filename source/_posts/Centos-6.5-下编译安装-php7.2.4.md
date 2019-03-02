---
title: Centos 6.5 下编译安装 php7.2.4
url: 442.html
id: 442
categories:
  - Linux/Unix
  - PHP
date: 2018-03-21 14:20:12
tags:
---

公司服务器还是处于centos6.5的版本，因此，最近在改php扩展的时候，还是需要先在 centos6 下测试一下插件，所以搞了个虚拟机安装起 centos , 在编译一下 php7.2 的源码，期间遇到的问题都记录一下。

<!--more-->

### 一、安装各种依赖：

```sh
yum -y install libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel libxml2 libxml2-devel pcre-devel curl-devel 
yum -y install libxslt-devel   libmcrypt-devel  gd-devel openssl openssl-devel bison cyrus-sasl-devel git
```

### 二、下载源码

```sh
wget -c https://github.com/php/php-src/archive/php-7.2.4.tar.gz
tar -zxf php-7.2.4.tar.gz
cd php-src-7.2.4
```
 

### 三、编译参数

```sh
./buildconf
./configure --with-openssl --with-mysqli --with-pdo-mysql --with-gd --with-iconv --with-zlib --enable-zip --enable-inline-optimization --enable-xml --enable-bcmath --enable-shmop --enable-sysvsem --enable-mbregex --enable-mbstring --enable-ftp --enable-pcntl --enable-sockets --with-xmlrpc --enable-soap --with-gettext --enable-session --with-curl --with-jpeg-dir --with-freetype-dir --enable-opcache --enable-fpm --with-fpm-user=www --with-fpm-group=www --without-gdbm --with-pcre-regex --with-png-dir --enable-fileinfo  --enable-debug
make
sudo make install
```
 

### 四、常用插件安装

#### 1、redis:

```sh
pecl install redis
```

#### 2、memcached:

先安装 memcached:

```sh
wget http://memcached.org/files/memcached-1.5.7.tar.gz
tar -zxf memcached-1.5.7.tar.gz
cd memcached-1.5.7
./configure 
make
sudo make install
```

在安装php-memcaced扩展：

```sh
wget -c https://pecl.php.net/get/memcached-3.0.4.tgz
tar -zxf memcached-3.0.4.tgz
cd memcached-3.0.4
phpize
./configure
make
sudo make install
```

#### 3、swoole:

```sh
pecl install swoole
```
 

### 五、配置 php

因为从源码安装的php没有自动放置好 `php.ini` 文件，所以，我们需要从源码文件夹中复制：

```sh
cd php-src-7.2.4
cp php.ini.production /usr/local/lib/php.ini
```

然后我们就可以配置php.ini 加入以上扩展了，\`vim /usr/local/lib/php.ini\` 然后在文件尾加入：

```sh
extension=redis.so
extension=swoole.so
extension=memcached.so
```
 

### 六、检查php

确认php版本

    php -v

确认读取的 php.ini 文件的位置：

    php -i | grep php.ini

检查已经安装的插件

    php -m