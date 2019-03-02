---
title: PHP扩展开发（1）-初识扩展
url: 347.html
id: 347
categories:
  - PHP扩展开发
date: 2018-02-10 01:23:02
tags:
---

### PHP 扩展开发（一）：新建扩展

> 本文在 **php 7.1** 的基础上描述，请大家注意哦。

#### 1、下载PHP源码

最简单的，就是直接 clone github 的源码，怎么使用 git 就不做介绍了。

    cd ~
    git clone git@github.com:php/php-src.git 
    

<!--more-->

#### 2、目录结构

*   ext: 官方扩展目录，包括了绝大多数PHP的函数的定义和实现，如array系列，pdo系列，spl系列等函数的实现，都在这个目录中。
*   main: 这里存放的就是PHP最为核心的文件了，主要实现PHP的基本设施，这里和Zend引擎不一样，Zend引擎主要实现语言最核心的语言运行环境。
*   sapi: 包含了各种服务器抽象层的代码，例如apache的mod_php，cgi，fastcgi以及fpm等等接口。
*   TSRM: PHP的线程安全是构建在TSRM库之上的，PHP实现中常见的*G宏通常是对TSRM的封装，TSRM(Thread Safe Resource Manager)线程安全资源管理器。
*   tests: PHP的测试脚本集合，包含PHP各项功能的测试文件
*   win32: 这个目录主要包括Windows平台相关的一些实现，比如sokcet的实现在Windows下和*Nix平台就不太一样，同时也包括了Windows下编译PHP相关的脚本。

#### 3\. 新建扩展

PHP 官方其实提供了很多有用的工具来帮助你开发扩展，例如：ext 目录下的 `./ext_skel` , 运行

    ./ext_skel
    

得出说明：

    ./ext_skel --extname=module [--proto=file] [--stubs=file] [--xml[=file]]
               [--skel=dir] [--full-xml] [--no-help]
    
      --extname=module   module is the name of your extension
      --proto=file       file contains prototypes of functions to create
      --stubs=file       generate only function stubs in file
      --xml              generate xml documentation to be added to phpdoc-svn
      --skel=dir         path to the skeleton directory
      --full-xml         generate xml documentation for a self-contained extension
                         (not yet implemented)
      --no-help          don't try to be nice and create comments in the code
                         and helper functions to test if the module compiled
    

我们先忽略其他说明，只关注： `--ext <name>` 就好，这个命令的是指定扩展名称的意思，那我们就新建一个名为：hello 的扩展作为开始了。

    ./ext_skel --extname=hello
    

执行完命令之后，得出：

    Creating directory hello
    Creating basic files: config.m4 config.w32 .gitignore hello.c php_hello.h CREDITS EXPERIMENTAL tests/001.phpt hello.php [done].
    
    To use your new extension, you will have to execute the following steps:
    
    1.  $ cd ..
    2.  $ vi ext/hello/config.m4
    3.  $ ./buildconf
    4.  $ ./configure --[with|enable]-hello
    5.  $ make
    6.  $ ./sapi/cli/php -f ext/hello/hello.php
    7.  $ vi ext/hello/hello.c
    8.  $ make
    
    Repeat steps 3-6 until you are satisfied with ext/hello/config.m4 and
    step 6 confirms that your module is compiled into PHP. Then, start writing
    code and repeat the last two steps as often as necessary.
    

就这样，ext_skel 工具就帮你创建了一个名为 hello 的扩展了，扩展在 ext/hello 文件夹中，我们 `cd hello & tree` 进去看看文件结构：

    .
    ├── CREDITS
    ├── EXPERIMENTAL
    ├── config.m4
    ├── config.w32
    ├── hello.c
    ├── hello.php
    ├── php_hello.h
    └── tests
        └── 001.phpt
    
    1 directory, 8 files
    

然后，我们先不管 hello 扩展的内容有什么，先编译先，但是在编译之前，我们需要修改一下 `config.m4`, 执行命令 `vim config.m4` 修改：

    dnl If your extension references something external, use with:
    
    dnl PHP_ARG_WITH(say, for say support,
    dnl Make sure that the comment is aligned:
    dnl [  --with-say             Include say support])
    
    dnl Otherwise use enable:
    
    dnl PHP_ARG_ENABLE(say, whether to enable say support,
    dnl Make sure that the comment is aligned:
    dnl [  --enable-say           Enable say support])
    

为：

    dnl If your extension references something external, use with:
    
    dnl PHP_ARG_WITH(say, for say support,
    dnl Make sure that the comment is aligned:
    dnl [  --with-say             Include say support])
    
    dnl Otherwise use enable:
    
    PHP_ARG_ENABLE(say, whether to enable say support,
    Make sure that the comment is aligned:
    [  --enable-say           Enable say support])
    

其中 `dnl` 是 注释的意思，类似于 php 当中的 // 一样。 然后，我们就可以开始执行编译了，输入命令：

    phpize
    ./configure
    make
    make install
    

最后，你可以在 目录 `mudule` 下看到 `hello.so` 这个文件，这样的话，我们就可以在 `php.ini` 中添加:

    [hello]
    extension="hello.so"
    

再运行 `php -m` 查看是否存在 hello 扩展了。