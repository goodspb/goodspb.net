---
title: ThinkPHP 3.2 如何使用自定义类，应该放置在什么目录
url: 86.html
id: 86
categories:
  - Thinkphp
date: 2015-07-09 16:54:34
tags:
---

TP 一直觉得是PHP入门的最佳框架，很适合国内的敏捷开发环境！ 曾经有一个问题，就是，如果我需要自定义一个类，那我应该放置在哪个目录？然后在控制器当中应该如何引用呢？   

**一、自定义类放置的目录：** 

我们需要在项目的模块下（默认是Home）下新建一个叫"Lib"的文件夹，然后往里面放置我们的类，当然，明明规则用TP默认的明明规则，加入我们的类名叫：myclass ，我们的类文件名就叫：myclass.class.php [![QQ图片20150709164707](/images/2015/07/QQ图片20150709164707.png)](/images/2015/07/QQ图片20150709164707.png)     

<!--more-->

**二、 如何编写自定义类** 直接上代码比较快，请注意：**一定要写明明空间！**

```php
namespace Home\\Lib;

class myclass{

    public function __construct(){
        echo 1;
    }

}
```

**三、如何引用自定义类** 


还是看代码，以下是indexController：

```php
namespace Home\\Controller;
use Think\\Controller;

class IndexController extends Controller {
    public function index(){

        $class = new \\Home\\Lib\\myclass();

    }
}
```

我只能讲，就这么简单，那些使用什么 improt 函数的方法太复杂了~~个人不太喜欢使用，推荐大家使用这种方法！！