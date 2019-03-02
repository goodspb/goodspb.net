---
title: PHP 数组游标
url: 91.html
id: 91
categories:
  - PHP
date: 2015-07-15 18:47:46
tags:
---

某次面试的过程中，被问到关于 数组游标的问题，我还傻乎乎的写了个 $a\[count($a)-1\] ，以为\[ \] 就是游标，那个尴尬简直绕梁3日。所以要mark 一下何为 “游标” ！   **一、那什么是“游标”呢？** 当定义一个数组的时候，数组当中包含一个所谓的“指针”，初始化的时候指向数组当中下表为0的值。 例如：

    $transport = array('foot', 'bike', 'car', 'plane');

这个时候，游标就指向 foot 这个值哦。   

<!--more-->


**二、如何操作游标？** 讲到操作游标，必须知道一下几个函数，还是上面的数组：

$transport = array('foot', 'bike', 'car', 'plane');
$mode = current($transport); // $mode = 'foot';
$mode = next($transport);    // $mode = 'bike';
$mode = current($transport); // $mode = 'bike';
$mode = prev($transport);    // $mode = 'foot';
$mode = end($transport);     // $mode = 'plane';
$mode = current($transport); // $mode = 'plane';

*   end() - 将数组的内部指针指向最后一个单元
*   key() - 从关联数组中取得键名
*   each() - 返回数组中当前的键／值对并将数组指针向前移动一步
*   prev() - 将数组的内部指针倒回一位
*   reset() - 将数组的内部指针指向第一个单元
*   next() - 将数组中的内部指针向前移动一位
*   current() — 返回数组中的当前单元

然后，大家是不是廓然开朗啦？哈哈~我也是！   再然后，具体例子请看：http://php.net/manual/zh/function.current.php