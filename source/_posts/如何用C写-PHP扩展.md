---
title: 如何用C写 PHP扩展
url: 51.html
id: 51
categories:
  - PHP
date: 2014-11-15 20:40:32
tags:
---

在我们编写自己的第一个php扩展之前，先了解一下php的整体架构和运行机制。 [![0](/images/2014/11/0.png)](/images/2014/11/0.png) php的架构如图1所示。其中一个重要的就是SAPI（服务器端应用编程端口），它使得PHP可以和其他应用进行数据交互，把外部错综复杂的外部环境进行抽象化，为内部的php提供一套固定和统一的接口，使得php自身不受外部影响，保持一定的独立性。常见的SAPI有CGI，FastCGI，Shell的CLI，apache的mod_php5，IIS的ISAPI。 另外一个非常重要就是ZendEngine。Zend Engine是官方提供的PHP实现的核心，提供了语言实现上的基础设施，其他比较知名的还有facebook的hiphop实现。例如PHP的语法实现，脚本的编译运行环境，扩展机制以及内存管理等。我们在后面编写php扩展时，也将基于Zend Engine。 PHP3时代还是采用边解释边执行的运行方式，这种方式运行效率很受影响，其次代码整体耦合度比较高，可扩展性也不够好。因此随着php在web应用开发中的普及，于是ZeevSuraski和Andi Gutmans决定重写代码以解决这两个问题，最终他们俩把该项技术的核心引擎命名为Zend Engine 。 Zend Engine最主要的特性就是把PHP的边解释边执行的运行方式改为先预编译(Compile)，再执行(Execute)。这两者的分开给 PHP 带来了革命性的变化：执行效率大幅提高。由于实行了功能分离，降低了模块间耦合度，可扩展性也大大增强。 目前PHP的实现和Zend Engine之间的关系非常紧密，例如很多PHP扩展都是使用的Zend API，而Zend正是PHP语言本身的实现，PHP只是使用Zend这个内核来构建PHP语言的，而PHP扩展大都使用Zend API，这就导致PHP的很多扩展和Zend引擎耦合在一起了，后来才有PHP核心开发者就提出将这种耦合解开的建议。不过下面我们还下面在Zend Engine的基础上开始编写我们第一个简单的php扩展。  

<!--more-->

1.配置文件
------

  每一个PHP扩展都至少需要一个配置文件和一个源文件。配置文件用来告诉编译器应该编译哪几个文件，以及编译本扩展是否需要的其它库文件。 在php源码文件夹的ext目录下创建一个新的文件，扩展的名字取作myfirst。然后在这个目录下创建一个config.m4文件，并输入以下内容：  

```
PHP_ARG_ENABLE(

   myfirst,

   [Whether to enable the "myfirst" extension],

   [enable-myfirst    Enable"myfirst" extension support])

if test $PHP_Myfirst !="no"; then

   PHP_SUBST(Myfirst_SHARED_LIBADD)

   PHP_NEW_EXTENSION(myfirst, myfirst.c, $ext_shared)

fi
```

  上面PHP_ARG_ENABLE函数有三个参数，第一个参数是我们的扩展名(注意不用加引号)，第二个参数是当我们运行./configure脚本时显示的内容，最后一个参数则是我们在调用./configure--help时显示的帮助信息。PHP_SUBST函数只是php官方对autoconf中AC_SUBST函数的一层封装。PHP_NEW_EXTENSION函数声明了这个扩展的名称、需要的源文件名、扩展的编译形式。如果扩展使用了多个文件，可以将文件名罗列在函数的参数里，如：PHP_NEW_EXTENSION(sample, sample.c sample2.c sample3.c, $ext_shared)最后的$ext_shared参数用来声明这个扩展为动态库，在php运行时动态加载的。  

2.源文件
-----

在完成了配置文件后，下面的就是完成扩展主逻辑的头文件和C文件。 头文件

```c
//php_myfirst.h

#ifndef Myfirst_H

#define Myfirst_H

//加载config.h，如果配置了的话

#ifdef HAVE_CONFIG_H

#include "config.h"

#endif

//加载php头文件

#include "php.h"

#define phpext_myfirst_ptr &myfirst_module_entry

extern zend_module_entrymyfirst_module_entry;

#endif
```

  C文件  

```c
//myfirst.c

#include "php_myfirst.h"

//module entry

zend_module_entrymyfirst_module_entry = {

#if ZEND_MODULE_API_NO >= 20010901

     STANDARD_MODULE_HEADER,

#endif

    "myfirst",//扩展名称

    NULL, /*Functions */

    NULL, /*MINIT */

    NULL, /*MSHUTDOWN */

    NULL, /*RINIT */

    NULL, /*RSHUTDOWN */

    NULL, /*MINFO */

#if ZEND_MODULE_API_NO >= 20010901

    "2.1",//扩展的版本

#endif

    STANDARD_MODULE_PROPERTIES

};

 

#ifdef COMPILE_DL_Myfirst

ZEND_GET_MODULE(myfirst)

#endif
```
   

3.扩展编译
------

准备好了扩展需要编译的源文件，接下来需要的便是把它们编译成目标文件了。 第一步：根据config.m4文件使用phpize生成一个configure脚本、Makefile等文件：   $ phpize PHP Api Version: 20041225 Zend Module Api No: 20050617 Zend Extension Api No: 220050617   现在查看扩展所在的目录，会发现phpize程序根据config.m4里的信息生成了许多编译php扩展必须的文件，比如makefiles等。 第二部：运行./configure脚本，然后执行make; make test即可。如果没有错误，那么在module文件夹下面便会生成扩展的目标文件 myfirst.so，这里由于之前我们在配置文件里写申明的是动态扩展，所以会被编译成动态库。 现在，先让我们执行一下PHP源码根目录下的./buildconf —force，再执行./configure --help命令。会发现myfirst扩展的信息已经出现了。 为了使PHP能够找到需要的扩展文件，我们需要把编译好的so文件复制到PHP的扩展目录下，并在php.ini中配置： extension_dir=/usr/local/lib/php/modules/ extension=myfirst.so   这样php就会在每次启动的时候自动加载我们的扩展了。  

4.扩展功能函数编写
----------

前面我们已经生成好了一份扩展框架，但它是没有什么实际作用的，我们还需要编写具体的功能函数。 #definePHP_FUNCTION         ZEND_FUNCTION #defineZEND_FUNCTION(name)    ZEND_NAMED_FUNCTION(ZEND_FN(name)) #defineZEND_NAMED_FUNCTION(name)   void name(INTERNAL_FUNCTION_PARAMETERS) #define ZEND_FN(name)                zif_##name 其中zif是zend internal function的意思，zif前缀是可供PHP语言调用的函数在C语言中的函数名称前缀。    

```c
ZEND_FUNCTION(myfirst_hello)
{

    php_printf("HelloWorld!\\n");

}
```

  上面的函数在C语言中宏展开后是这样的:  

```c
voidzif_myfirst_hello(INTERNAL_FUNCTION_PARAMETERS)
{

    php_printf("HelloWorld!\\n");

}
```

    函数的功能已经实现了，但是还不能在程序中调用，因为这个函数还没有在扩展模块中注册。现在看下扩展中zend_module_entry myfirst_module_entry（它是联系C扩展与PHP语言的重要纽带）中/*Functions*/的值为NULL，这是之前还没有编写函数。现在我们可以将编写的函数赋值给它了，这个值需要是zend_function_entry[]类型：    

```c
static zend_function_entrymyfirst_functions[] = {

     ZEND_FE(myfirst_hello, NULL)

    { NULL, NULL,NULL }

};
```

  其中最后的{NULL,NULL,NULL}是固定不变的。ZEND_FE()宏函数是对myfirst_hello函数的一个声明，如果有多个函数，可以直接以类似的形式添加到{NULL,NULL,NULL}之前，注意每个之间不需要加逗号。确保一切无误后，我们替换掉zend_module_entry里的原有成员，现在应该是这样的：  

```c
ZEND_FUNCTION(myfirst_hello)
{

    php_printf("HelloWorld!\\n");

}

 

static zend_function_entrymyfirst_functions[] = {

    ZEND_FE(myfirst_hello,       NULL)

    { NULL, NULL,NULL }

};

 

zend_module_entrymyfirst_module_entry = {

#if ZEND_MODULE_API_NO >= 20010901

     STANDARD_MODULE_HEADER,

#endif

    "myfirst",//扩展名称。

    myfirst_functions,/* Functions */

    NULL, /*MINIT */

    NULL, /*MSHUTDOWN */

    NULL, /*RINIT */

    NULL, /*RSHUTDOWN */

    NULL, /*MINFO */

#if ZEND_MODULE_API_NO >= 20010901

    "2.1",//这个地方是我们扩展的版本

#endif

    STANDARD_MODULE_PROPERTIES

};

```

    这样我们就完成扩展的一个简单功能，然后再重新configure、make、make test，并复制.so文件到extension dir目录。 最后写一个脚本在命令行测试，应该可以输出helloworld了。  

```php
<?php

    myfirst_hello();

?>
```

  原文：http://blog.csdn.net/heiyeshuwu/article/details/40041601