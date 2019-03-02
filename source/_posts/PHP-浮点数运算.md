---
title: PHP 浮点数运算
url: 141.html
id: 141
categories:
  - PHP
date: 2013-06-23 22:38:14
tags:
---

bcadd — 将两个高精度数字相加 bcsub — 将两个高精度数字相减 bccomp — 比较两个高精度数字，返回-1, 0, 1 bcdiv — 将两个高精度数字相除 bcmod — 求高精度数字余数 bcmul — 将两个高精度数字相乘 bcpow — 求高精度数字乘方 bcpowmod — 求高精度数字乘方求模，数论里非常常用 bcscale — 配置默认小数点位数，相当于就是Linux bc中的”scale=” bcsqrt — 求高精度数字平方根 例子：

<!--more-->

```php
<?php

$a = '1.234';
$b = '5';

echo bcadd($a, $b);     // 6
echo bcadd($a, $b, 4);  // 6.2340

?>
```

第三个参数说明：此可选参数用于设置结果中小数点后的小数位数。也可通过使用 [bcscale()](http://php.net/manual/zh/function.bcscale.php) 来设置全局默认的小数位数，用于所有函数。   比较说明：

```php
<?php

echo bccomp('1', '2') . "\\n";   // -1
echo bccomp('1.00001', '1', 3); // 0
echo bccomp('1.00001', '1', 5); // 1

?>
```

说明：如果两个数相等返回0, 左边的数比较右边的数大返回1, 否则返回-1.