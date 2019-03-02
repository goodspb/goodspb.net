---
title: PHP 判断类中是否包含特定的 const 常量
url: 263.html
id: 263
categories:
  - PHP
date: 2016-07-26 14:28:08
tags:
---

    class A {
           const STATUS_SUCCESS = 1;
                 const STATUS_FAILED = 0;
    }
    
    

要怎样才能判断类中是否包含 A::STATUS_SUCCESS 这个变量呢？ 理论上来讲，我们可以使用PHP的 `Reflection` 机制，如：

    $ref = new ReflectionClass('A');
    print_r($ref->getConstants());
    
    /* 输出：
    
    Array
    (
        ['STATUS_SUCCESS'] => 1
        ['STATUS_FAILED'] => 0
    )
    
    */
    
    

但是， 使用 反射 的话，对性能影响可是很大的，如果只需要简单的判断是否存在某某名称的常量，可以这样操作：

    class A {
           const STATUS_SUCCESS = 1;
                 const STATUS_FAILED = 0;
    
                 public function checkStatus()
                 {
                         return defined("self::STATUS_SUCCESS");
                 }
    
    }
    
    var_dump((new A)->checkStatus());
    
    /* 输出：
    
        bool(true)
    
     */
    
    

通过这种方法，能简单的判断是否出现是否存在常量。 have fun!