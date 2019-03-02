---
title: PHP 对递归的优化
url: 239.html
id: 239
categories:
  - PHP
date: 2016-05-31 16:55:46
tags:
---

### 一、何为递归

递归（recursion）在编程当中相当常见，当你的栈底看不见的时候，递归的作用显而易见。详解请参见[维基百科](https://zh.wikipedia.org/zh/%E9%80%92%E5%BD%92)

<!--more-->

### 二、程序中的递归一

    function recursion($n)
    {
        if ($n == 0) {
            return $n;
        }
        $n--;
        return recursion($n) + $n;
    }
    
    echo recursion(11) ;
    

这样，就可以通过递归求出11+10+.....+2+1的结果。 但是，当你将 $n 改成 300 `recursion(300) ;` 的时候，这个时候会出现：

    Fatal error: Maximum function nesting level of '256' reached, aborting!
    

这是为什么呢？因为函数的调用需要运用 `栈` 来储存 函数的信息，当函数中包含 `return recursion($n) + $n;` 的时候，计算这个结果就必须保存上一次函数的调用才能进行 \* 操作，因此 ，当 $n = 300 的时候，`栈` 就必须保存 300次函数调用的信息了，因为 `栈` 空间有限，从而导致程序出错。 那上面显示的错误，是 PHP 的保护机制。

### 三、程序中的递归二 — 尾递归

将上述代码改成：

    function recursion2($n, $result = 0)
    {
        if ($n == 0) {
            return $result;
        }
        $n--;
        $result += $n;
        return recursion2($n, $result);
    }
    
    echo recursion2(253);
    

同样运行 ， 当 $n = 255 以上， 就会出现 `Fatal error: Maximum function nesting level of '256' reached, aborting!` 错误。 但是，上面的这样写法，属于尾递归函数，将结果集传到函数的中，C/C++ ， java 等语言对 尾递归 有优化， 不过，PHP/Python 不对尾递归做优化，所以，问题同样存在。

### 四、回避递归中的层级限制

接下来，请看如下方法：

    function recursion3($n)
    {
        function interRecursion($n, $result = 0)
        {
            if ($n == 0) {
                return $result;
            }
            $n--;
            $result += $n;
            return function () use ($n, $result) {
                return interRecursion($n, $result);
            };
        }
        $result = call_user_func('interRecursion', $n);
        while (is_callable($result)) {
            $result = $result();
        }
        return $result;
    }
    
    echo recursion3(1255);
    

以上方法，就不会产生 层数的限制。和 Ptyhon 当中的 装饰器 一样。如果对 call\_user\_func 这样的函数不太了解的话，可以先看看 php.net 。

### 五、测试文件下载

[recursion.php](/images/2016/05/recursion.php_.zip)