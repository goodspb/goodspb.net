---
title: PHP扩展开发（3）- 创建类class
url: 364.html
id: 364
categories:
  - PHP扩展开发
date: 2018-02-15 20:05:38
tags:
---

当然了，现代编程语言当然少不了 **类(Class)** 这种元素，这一篇我们来看一下如何在PHP扩展中创建一个类。

<!--more-->

### 一、前言

要完成一个任务，我们需要订立一个目标。实际上，我们需要在扩展中实现的类就像下面我们在PHP当中实现的类一样，包含构造函数，属性，方法等。

```php
<?php

Class Car()
{
    protect $driver;
    
    public function __construct() 
    {
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    public function run()
    {
        return sprintf("%s is driving the car.", $this->driver);
    }
}


$car = new Car();
$car->setDriver("Goodspb");
echo $car->run();

/*
输出:
Goodspb is driving the car.
 */
```

分析一下：

*   类名：Car
*   属性：driver
*   方法：run

### 二、声明类

如何创建一个扩展这里就不说了，具体可以参考《[PHP扩展开发（1）-初识扩展](https://www.goodspb.net/php%e6%89%a9%e5%b1%95%e5%bc%80%e5%8f%91%ef%bc%881%ef%bc%89-%e6%96%b0%e5%bb%ba%e6%89%a9%e5%b1%95/)》是如何创建一个扩展的，这次我们创建一个名叫：hello\_class 的扩展，主要包含头文件： hello\_class.h 头文件和 hello\_class.c 。我们在 hello\_class.h 中添加类的声明。 在 hello_class.h 的尾部添加：


```c
/* Class Car */
zend_class_entry *car_ce;              //定义全局的类结构
PHP_METHOD(Car, __construct);          //声明构造函数
PHP_METHOD(Car, setDriver);            //声明方法
PHP_METHOD(Car, run);                  //声明方法
```

 

### 三、实现类

在 hello_class.c 中添加：

```c
/* class Car */
ZEND_BEGIN_ARG_INFO(arg_car_setDriver, 0)
   ZEND_ARG_INFO(0, driver)
ZEND_END_ARG_INFO()
 
const zend_function_entry car_methods[] = {
   /* Class Car */
   PHP_ME(Car, __construct, NULL, ZEND_ACC_PUBLIC | ZEND_ACC_CTOR)
   PHP_ME(Car, run, NULL, ZEND_ACC_PUBLIC)
   PHP_ME(Car, setDriver, arg_car_setDriver, ZEND_ACC_PUBLIC)
   PHP_FE_END    /* Must be the last line in hello_class_functions[] */
};
 
PHP_METHOD(Car, __construct)
{
 
}
 
PHP_METHOD(Car, setDriver)
{
    zend_string *driver;
 
    ZEND_PARSE_PARAMETERS_START(1, 1)
            Z_PARAM_STR(driver)
    ZEND_PARSE_PARAMETERS_END();
 
    zend_update_property_string(car_ce,  getThis(), "driver", sizeof("driver") - 1, ZSTR_VAL(driver));
}
 
PHP_METHOD(Car, run)
{
    zval *driver, rdriver;
    zend_string *driver_string;
    driver = zend_read_property(car_ce, getThis(), "driver", sizeof("driver") - 1, 0, &rdriver);
    driver_string = Z_STR_P(driver);
    RETURN_STR(strpprintf(0, "%s is driving the car.", ZSTR_VAL(driver_string)));
}
```

这样就实现类了，我们来分解一下代码：

#### 1). 这段是对函数 setDriver 配置参数:

```c
ZEND_BEGIN_ARG_INFO(arg_car_setDriver, 0)      //第一个参数为配置名，用于后面填充；第二参数默认为0，暂无使用
   ZEND_ARG_INFO(0, driver)                    //第一个参数表示是否传递引用，0表示否，1表示是；第二个参数表示参数名
ZEND_END_ARG_INFO()
```

#### 2). 这段是新建一个方法的入口：

```c
const zend_function_entry car_methods[] = {
   /* Class Car */
   PHP_ME(Car, __construct, NULL, ZEND_ACC_PUBLIC | ZEND_ACC_CTOR)
   PHP_ME(Car, run, NULL, ZEND_ACC_PUBLIC)
   PHP_ME(Car, setDriver, arg_car_setDriver, ZEND_ACC_PUBLIC)
   PHP_FE_END    /* Must be the last line in hello_class_functions[] */
};
```

增加类方法：

```c
PHP_ME(Car, __construct, NULL, ZEND_ACC_PUBLIC | ZEND_ACC_CTOR)
```

*   第一个参数为：类名
*   第二个参数为方法名
*   第三个参数为参数结构体，例如上面我们新建的 `arg_car_setDriver`
*   第四个参数为属性的掩码，例如上面的 `ZEND_ACC_PUBLIC | ZEND_ACC_CTOR` 表示该方法为 public 且为  `构造函数`

关于第四个参数，定义有以下这些，大家看到最后的单词估计也能猜测得到。

```c
/* method flags (types) */
#define ZEND_ACC_STATIC			0x01
#define ZEND_ACC_ABSTRACT		0x02
#define ZEND_ACC_FINAL			0x04
#define ZEND_ACC_IMPLEMENTED_ABSTRACT		0x08
 
/* class flags (types) */
/* ZEND_ACC_IMPLICIT_ABSTRACT_CLASS is used for abstract classes (since it is set by any abstract method even interfaces MAY have it set, too). */
/* ZEND_ACC_EXPLICIT_ABSTRACT_CLASS denotes that a class was explicitly defined as abstract by using the keyword. */
#define ZEND_ACC_IMPLICIT_ABSTRACT_CLASS	0x10
#define ZEND_ACC_EXPLICIT_ABSTRACT_CLASS	0x20
#define ZEND_ACC_INTERFACE		            0x40
#define ZEND_ACC_TRAIT						0x80
#define ZEND_ACC_ANON_CLASS                 0x100
#define ZEND_ACC_ANON_BOUND                 0x200
#define ZEND_ACC_INHERITED                  0x400
 
/* method flags (visibility) */
/* The order of those must be kept - public < protected < private */
#define ZEND_ACC_PUBLIC		0x100
#define ZEND_ACC_PROTECTED	0x200
#define ZEND_ACC_PRIVATE	0x400
#define ZEND_ACC_PPP_MASK  (ZEND_ACC_PUBLIC | ZEND_ACC_PROTECTED | ZEND_ACC_PRIVATE)
 
#define ZEND_ACC_CHANGED	0x800
#define ZEND_ACC_IMPLICIT_PUBLIC	0x1000
 
/* method flags (special method detection) */
#define ZEND_ACC_CTOR		0x2000
#define ZEND_ACC_DTOR		0x4000
#define ZEND_ACC_CLONE		0x8000
 
/* method flag used by Closure::__invoke() */
#define ZEND_ACC_USER_ARG_INFO 0x80
 
/* method flag (bc only), any method that has this flag can be used statically and non statically. */
#define ZEND_ACC_ALLOW_STATIC	0x10000
 
/* shadow of parent's private method/property */
#define ZEND_ACC_SHADOW 0x20000
 
/* deprecation flag */
#define ZEND_ACC_DEPRECATED 0x40000
```

#### 3). 具体实现特定的方法：

之前在 hello\_class.h 中使用 PHP\_ME  定义过方法，就好似函数一样，有定义就必须有实现。

```c
PHP_METHOD(Car, setDriver)
{
    zend_string *driver;
 
    ZEND_PARSE_PARAMETERS_START(1, 1)
            Z_PARAM_STR(driver)
    ZEND_PARSE_PARAMETERS_END();
 
    zend_update_property_string(car_ce,  getThis(), "driver", sizeof("driver") - 1, ZSTR_VAL(driver));
}
```

中间 `ZEND_PARSE_PARAMETERS_START(1, 1)` 到 `ZEND_PARSE_PARAMETERS_END();` 是获取传入的参数，这里不做解释了，前面的教程有介绍过，重点来看：

```c
zend_update_property_string(car_ce,  getThis(), "driver", sizeof("driver") - 1, ZSTR_VAL(driver));
```

这个方法 zend\_update\_property_string  直接从翻译过来应该也不能，可以解释为：更新属性为一个特定的字符串。

*   第一个参数：类的结构体指针，这里是之前定义的： car_ce
*   第二个参数：当前实例的指针，其实就类似PHP当中的 $this ，扩展中使用 getThis()
*   第三个参数：属性名
*   第四个参数：属性名的长度
*   第五个参数：需要设置的字符串，这里通过 ZSTR\_VAL 将 zend\_string 中实际的值取出来。

  再来看看另外一个方法 `run` :

```c
PHP_METHOD(Car, run)
{
    zval *driver, rdriver;
    zend_string *driver_string;
    driver = zend_read_property(car_ce, getThis(), "driver", sizeof("driver") - 1, 0, &rdriver);
    driver_string = Z_STR_P(driver);
    RETURN_STR(strpprintf(0, "%s is driving the car.", ZSTR_VAL(driver_string)));
}
```

重点关注里面调用的方法：`zend_read_property` , 我们通过这个方法获取属性的内容，就像 PHP 当中的 $this->driver 。 解析一下 `zend_read_property` 的参数：

*   第一个参数：类的结构体指针，这里是之前定义的： car_ce
*   第二个参数：当前实例的指针，其实就类似PHP当中的 $this ，扩展中使用 getThis()
*   第三个参数：属性名
*   第四个参数：属性名的长度
*   第五个参数：是否报错，0表示不报，1表示报
*   第六个参数：PHP7扩展中新增，貌似也没啥用，可以直接传 NULL

返回这是一个 zval ，通过 Z\_STR\_P 转变成 zend_string 供后面 strpprintf 来使用。

### 四、注册类

我们已经声明并且实现了类了，但是这个时候，如果你编译了，其实还是不能找到这个类哦，我们需要向PHP注册这个类。具体可以在 \`PHP\_MINIT\_FUNCTION (hello_class)\` 中注册，这里要稍稍解释一下这个方法的意思，`PHP_MINIT_FUNCTION` 是PHP执行的 **模块初始化** 的回调函数，也就是当 PHP 的声明周期走到 模块初始化 的时候，会调用这个方法。上代码：

```c
PHP_MINIT_FUNCTION (hello_class)
{
    zend_class_entry car;
    INIT_CLASS_ENTRY(car, "Car", car_methods);
    car_ce = zend_register_internal_class(&car);
    zend_declare_property_null(car_ce, "driver", sizeof("driver") - 1, ZEND_ACC_PROTECTED);
 
    return SUCCESS;
}
```

*   zend\_class\_entry car; 这行创建一个类结构体
*   INIT\_CLASS\_ENTRY(car, "Car", car\_methods);  这里初始化类，第一个参数是上面创建的 car ；第二个参数是类名；第三个参数就是之前定义的：const zend\_function\_entry car\_methods\[\]；
*   car\_ce = zend\_register\_internal\_class(&car); 注册类; 参数为之前创建的 car; 返回值是 zend\_class\_entry 的指针，复制给我们定义的全局变量 car_ce;
*   zend\_declare\_property\_null(car\_ce, "driver", sizeof("driver") - 1, ZEND\_ACC\_PROTECTED); 这一行注册了类的属性 driver ，第一个参数是全局类结构指针，第二个参数是属性名；第三个参数是属性名长度；第四个参数是一个属性的掩码，ZEND\_ACC\_PROTECTED 表示该属性是 protected 的。

 

### 五. 结束语

到这里为止，这个 Car 这个类已经在 PHP 扩展中实现了，可以直接在 php -a 中直接尝试 new 一个 car 来试一下，但是大家是否觉得有点缺失，类应该是需要有“继承” 的，那下一篇我们就介绍一下如何在PHP扩张中继承一个类。