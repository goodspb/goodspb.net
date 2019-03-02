---
title: PHP扩展开发（2）- 创建函数
url: 359.html
id: 359
categories:
  - PHP扩展开发
date: 2018-02-13 22:17:04
tags:
---

在我们日常使用 PHP 当中，当然少不了使用 PHP提供的内部函数，例如：file\_get\_contents / file\_put\_contents / trim / array_flip... 等等。因为PHP刚开始面向过程的语言的（C/perl的综合体），所以单独的函数很重要。本文讨论如何在 PHP扩展中写自己的方法。 在开始之前，我们需要先了解一下 **hello.c** 文件的一些结构，按照上一篇文章生成的扩展 hello 来展开我们本章的描述，未看过的可以先查阅[《PHP扩展开发（1）-初识扩展》](https://www.goodspb.net/php%e6%89%a9%e5%b1%95%e5%bc%80%e5%8f%91%ef%bc%881%ef%bc%89-%e6%96%b0%e5%bb%ba%e6%89%a9%e5%b1%95/)。

<!--more-->

### 一、分析生成的源码

以下就是 hello.c 文件的内容：

```c
/*
  +----------------------------------------------------------------------+
  | PHP Version 7                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2018 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author:                                                              |
  +----------------------------------------------------------------------+
*/
 
/* $Id$ */
 
#ifdef HAVE_CONFIG_H
#include "config.h"
#endif
 
#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_hello.h"
 
/* If you declare any globals in php_hello.h uncomment this:
ZEND_DECLARE_MODULE_GLOBALS(hello)
*/
 
/* True global resources - no need for thread safety here */
static int le_hello;
 
/* {{{ PHP_INI
 */
/* Remove comments and fill if you need to have entries in php.ini
PHP_INI_BEGIN()
    STD_PHP_INI_ENTRY("hello.global_value",      "42", PHP_INI_ALL, OnUpdateLong, global_value, zend_hello_globals, hello_globals)
    STD_PHP_INI_ENTRY("hello.global_string", "foobar", PHP_INI_ALL, OnUpdateString, global_string, zend_hello_globals, hello_globals)
PHP_INI_END()
*/
/* }}} */
 
/* Remove the following function when you have successfully modified config.m4
   so that your module can be compiled into PHP, it exists only for testing
   purposes. */
 
/* Every user-visible function in PHP should document itself in the source */
/* {{{ proto string confirm_hello_compiled(string arg)
   Return a string to confirm that the module is compiled in */
PHP_FUNCTION(confirm_hello_compiled)
{
	char *arg = NULL;
	size_t arg_len, len;
	zend_string *strg;
 
	if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
		return;
	}
 
	strg = strpprintf(0, "Congratulations! You have successfully modified ext/%.78s/config.m4. Module %.78s is now compiled into PHP.", "hello", arg);
 
	RETURN_STR(strg);
}
/* }}} */
/* The previous line is meant for vim and emacs, so it can correctly fold and
   unfold functions in source code. See the corresponding marks just before
   function definition, where the functions purpose is also documented. Please
   follow this convention for the convenience of others editing your code.
*/
 
 
/* {{{ php_hello_init_globals
 */
/* Uncomment this function if you have INI entries
static void php_hello_init_globals(zend_hello_globals *hello_globals)
{
	hello_globals->global_value = 0;
	hello_globals->global_string = NULL;
}
*/
/* }}} */
 
/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(hello)
{
	/* If you have INI entries, uncomment these lines
	REGISTER_INI_ENTRIES();
	*/
	return SUCCESS;
}
/* }}} */
 
/* {{{ PHP_MSHUTDOWN_FUNCTION
 */
PHP_MSHUTDOWN_FUNCTION(hello)
{
	/* uncomment this line if you have INI entries
	UNREGISTER_INI_ENTRIES();
	*/
	return SUCCESS;
}
/* }}} */
 
/* Remove if there's nothing to do at request start */
/* {{{ PHP_RINIT_FUNCTION
 */
PHP_RINIT_FUNCTION(hello)
{
#if defined(COMPILE_DL_HELLO) && defined(ZTS)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif
	return SUCCESS;
}
/* }}} */
 
/* Remove if there's nothing to do at request end */
/* {{{ PHP_RSHUTDOWN_FUNCTION
 */
PHP_RSHUTDOWN_FUNCTION(hello)
{
	return SUCCESS;
}
/* }}} */
 
/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(hello)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "hello support", "enabled");
	php_info_print_table_end();
 
	/* Remove comments if you have entries in php.ini
	DISPLAY_INI_ENTRIES();
	*/
}
/* }}} */
 
/* {{{ hello_functions[]
 *
 * Every user visible function must have an entry in hello_functions[].
 */
const zend_function_entry hello_functions[] = {
	PHP_FE(confirm_hello_compiled,	NULL)		/* For testing, remove later. */
	PHP_FE_END	/* Must be the last line in hello_functions[] */
};
/* }}} */
 
/* {{{ hello_module_entry
 */
zend_module_entry hello_module_entry = {
	STANDARD_MODULE_HEADER,
	"hello",
	hello_functions,
	PHP_MINIT(hello),
	PHP_MSHUTDOWN(hello),
	PHP_RINIT(hello),		/* Replace with NULL if there's nothing to do at request start */
	PHP_RSHUTDOWN(hello),	/* Replace with NULL if there's nothing to do at request end */
	PHP_MINFO(hello),
	PHP_HELLO_VERSION,
	STANDARD_MODULE_PROPERTIES
};
/* }}} */
 
#ifdef COMPILE_DL_HELLO
#ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
#endif
ZEND_GET_MODULE(hello)
#endif
 
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
```

其中，大家可以看到：

```c
PHP_FUNCTION(confirm_hello_compiled)
{
	char *arg = NULL;
	size_t arg_len, len;
	zend_string *strg;
 
	if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
		return;
	}
 
	strg = strpprintf(0, "Congratulations! You have successfully modified ext/%.78s/config.m4. Module %.78s is now compiled into PHP.", "hello", arg);
 
	RETURN_STR(strg);
}
```

这个就是一个在扩展中定义的 php 函数了，函数名为：confirm\_hello\_compiled，我们调用一下这个函数:

```php
<?php 
 
var_dump(confirm_hello_compiled('hello'));
 
/*
 
输出:
string(107) "Congratulations! You have successfully modified ext/hello/config.m4. Module hello is now compiled into PHP."
 
*/
```

再全文搜索一下“confirm\_hello\_compiled” ， 就会发现另外一个地方出现：

```c
const zend_function_entry hello_functions[] = {
	PHP_FE(confirm_hello_compiled,	NULL)		/* For testing, remove later. */
	PHP_FE_END	/* Must be the last line in hello_functions[] */
};
```

这里 `PHP_FE(confirm_hello_compiled, NULL)` 其实就是向 Zend 引擎注册编写好的函数，之后我们写自己的方法也必须在这里注册。  

### 二、编写自己的函数

学习编程最后的方法莫过于照样画葫芦，我们先按照 confirm\_hello\_compiled 方法的格式写一个自己的方法：

```c
PHP_FUNCTION(get_length)      //获取字符串长度
{
	char *arg = NULL;
	size_t arg_len, len;
 
	if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
		return;
	}
 
	int length;
	length = strlen(arg);
 
    RETURN_LONG(length);
}
```

注册该函数：

```c
const zend_function_entry hello_functions[] = {
	PHP_FE(get_length,	NULL)
	PHP_FE(confirm_hello_compiled,	NULL)		/* For testing, remove later. */
	PHP_FE_END	/* Must be the last line in hello_functions[] */
};
```

重新编译：

```sh
make & make install
```

然后执行：

```php
<?php

echo get_length("fuxxxxk");

//输出 7
```

然后，一个函数就这样完成了，然后我们该来分析一下函数内的一些代码。  

### 三、代码分析

#### 1、如何获取参数

可以看这段代码，其中函数 zend\_parse\_parameters 就是获取参数的方法：

```c
char  *arg = NULL;
size_t arg_len, len;
 
if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
   return;
}
```

其中，第一个参数 ZEND\_NUM\_ARGS() 其实就是 PHP 当中的 $argc，获取所有的参数的数量，无需改变。 第二个参数，格式化字符串。将传入的参数转化成对应的PHP变量类型。s 表示参数是一个字符串。要把传入的参数转换为zend_string类型。  

#### 2、更好的获取参数方式: FAST ZPP

先放代码：

```c
PHP_FUNCTION(get_length)
{
    zend_string *str;
 
    ZEND_PARSE_PARAMETERS_START(1, 1)
            Z_PARAM_STR(str)
    ZEND_PARSE_PARAMETERS_END();
 
   int length;
   length = ZSTR_LEN(str);
 
    RETURN_LONG(length);
}
```

其中，获取参数变成了：

```c
ZEND_PARSE_PARAMETERS_START(1, 1)
    Z_PARAM_STR(type)
ZEND_PARSE_PARAMETERS_END();
```

这3个类似函数的其实都是宏，所以你可以看到最后都没有加 “;" 分号。 ZEND\_PARSE\_PARAMETERS\_START(1, 1) 解释：第一个参数表示必传的参数个数，第二个参数表示最多传入的参数个数。 Z\_PARAM\_STR(type) 解释：将参数解释成 zend\_string ZEND\_PARSE\_PARAMETERS\_END(); 解释：结束，直接填写即可。   那当然了，获取参数还是有很多的参数可以选择的，具体的参数选择可以参考： http://php.net/manual/zh/internals2.funcs.php https://wiki.php.net/rfc/fast\_zpp  

#### 3\. 返回值

最后我们看看实现返回值：

```c
RETURN_LONG(length);
```

通过这个宏，我们可以返回需要的值，常用的宏方法有：

*   RETURN_NULL()                            返回 NULL
*   RETURN_LONG(l)                          返回整型
*   RETURN_DOUBLE(d)                   返回浮点型
*   RETURN\_STR(s)                             返回一个字符串。参数是一个zend\_string * 指针
*   RETURN_STRING(s)                     返回一个字符串。参数是一个char * 指针
*   RETURN_STRINGL(s, l)               返回一个字符串。第二个参数是字符串长度。
*   RETURN\_EMPTY\_STRING()      返回一个空字符串。
*   RETURN\_ARR(r)                            返回一个数组。参数是zend\_array *指针。
*   RETURN\_OBJ(r)                             返回一个对象。参数是zend\_object *指针。
*   RETURN_ZVAL(zv, copy, dtor)   返回任意类型。参数是 zval *指针。
*   RETURN_FALSE                            返回false
*   RETURN_TRUE                             返回true