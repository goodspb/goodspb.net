---
title: 安装phalcon时出现‘ZEND_FE_END’ undeclared here (not in a function)
tags:
  - phalcon
  - PHP
  - php-config
url: 163.html
id: 163
categories:
  - phalcon
date: 2015-09-05 12:13:18
---

出现这个问题的首要原因是phalcon不支持php5.3所导致的，大家可能觉得好奇怪，为何明明我的运行环境是php5.6，phalcon找到的却是5.3呢?  原因很简单，大家请到在终端输入: php -v 查看一下，你会发现你先上运行的环境和 php cli是不一样（我是centos 6.5），具体原因不谈谈，只需知道phalcon认错php了。因此，你的安装方式需要更改了！   一、查找php-config 首先，你要查找到你php-fpm 或 php-cgi 是用哪个php-config的，终端输入：

sudo find / -name php-config

<!--more-->

然后看看找到哪些路径，这个时候肯定有2个php-config的路径，因为php有2个版本存在于你的系统当中。假设，找到的php-config如下：

/usr/local/php/bin/php-config
/usr/bin/php-config

这个时候可以，我们就可以清晰的知道，/usr/bin/php-config 是不正确的，因为系统默认就是这个。   二、指定配置文件来安装

git clone --depth 1 --branch phalcon-v2.0.3 https://github.com/phalcon/cphalcon.git
cd cphalcon/ext
phpize
./configure --with-php-config=/usr/local/php/bin/php-config #此处更改了php-config的位置
make && make install

这样就编译完成了。这个时候就不会出现编译错误了。   三、将扩张添加到php.ini

\[phalcon\]
extension=phalcon.so

  四、重启php ，查看 phpinfo() ，大功告成，慢慢享受phalcon吧，哈哈哈~