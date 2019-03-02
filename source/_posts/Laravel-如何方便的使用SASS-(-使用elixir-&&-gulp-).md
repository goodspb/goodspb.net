---
title: Laravel 如何方便的使用SASS ( 使用elixir && gulp )
url: 73.html
id: 73
categories:
  - laravel
date: 2015-07-05 20:32:17
tags:
---

Laravel 中有很多是否好用的工具，真是得laravel者得天下（哈哈哈~~）。 小弟用的版本是 laravel 5.1 。 以下亲自测试可用：   **一、安装 node.js** 因为elixir 建基础于 gulp ，而 gulp 是nodejs的产物，所以需要到http://nodejs.org 中安装，请点击图片中“ install "即可 [![QQ图片20150705165422](/images/2015/07/QQ图片20150705165422.png)](/images/2015/07/QQ图片20150705165422.png) 然后在终端 或者 cmd 中运行 node -v 确实是否安装成功，成功如下图： [![QQ图片20150705165606](/images/2015/07/QQ图片20150705165606.png)](/images/2015/07/QQ图片20150705165606.png)     **二、安装 gulp ** 终端 或者 cmd 中运行 npm install --global gulp ，成功的时候得到如下结果： [![QQ截图20150705195329](/images/2015/07/QQ截图20150705195329.png)](/images/2015/07/QQ截图20150705195329.png)   **三、安装 laravel5 目录下的 node.js 配置依赖，配置文件为：package.json ，大家可以打开看看；** 1、首先进入 laravel 所在目录 2、然后运行：npm install PS: 如果在mac下，可能还要：

sudo chown -R $USER ~/.npm
sudo chown -R $USER /usr/local

3、结果如下： [![QQ截图20150705195741](/images/2015/07/QQ截图20150705195741.png)](/images/2015/07/QQ截图20150705195741.png)   （第一行的错误不知道是啥，知道的告知一声）

<!--more-->

#### PS： 由于国内网络的G**，所以，一定要要VPN才能连上npm的源哦，否则会一直报错！谨记！！！！！

  **四、配置 laravel 根目录下的 gulpfile.js 的配置文件，laravel 5.1的时候是这样的：**

elixir(function(mix) {
    mix.sass('app.scss');
});

可以改成这样：

elixir(function(mix) {
    mix.sass('app.scss')
        .version('css/app.css') //添加自动更替版本号，保存在 public/build/css/app-xxxxx.css
    ;
});

  **五、编写sass文件，随便在 resources/assets/sass/app.sass 中编写：**

//@import "node_modules/bootstrap-sass/assets/stylesheets/bootstrap";
//此为bootstrap的css文件，按照项目需求选择是否包含

.header {

  .left{
    color: #fff;
  }

  .middle{
    color: #fff;
  }

  .right{

  }
}

  **六、然后 在终端或者 cmd 中运行 : gulp ，结果如下：** [![QQ截图20150705201437](/images/2015/07/QQ截图20150705201437.png)](/images/2015/07/QQ截图20150705201437.png) 大功告成，现在 pulice/css/中已经 存在由app.sass编译已成的 app.css，可以直接引用到项目当中 然后，因为我们刚刚在  **gulpfile.js** 文件当中加入 了 version 进行版本控制，因此，会在 public/build/css/中出现以下文件： [![QQ截图20150705201730](/images/2015/07/QQ截图20150705201730.png)](/images/2015/07/QQ截图20150705201730.png)     **七、在项目模板中引用 CSS 文件：** 1、方法一，直接引用非版本控制的css ： pulice/css/app.css

<link rel="stylesheet" href="/public/css/app.css" >

2、方法二，引用版本控制的css ,

<link rel="stylesheet" href="{ { elixir('css/app.css')  }}" >

elixir 是全局helper中的方法，用于获得版本控制的css，读出public/build/rev-manifest.json 文件中从而引入文件   **八、监控sass 文件** 那是否每一次我们更改app.sass文件都要手动在终端中输入 gulp 来编译呢？非也非也 我们可以运行 gulp watch , 运行结果如下： [![QQ图片20150705202952](/images/2015/07/QQ图片20150705202952.png)](/images/2015/07/QQ图片20150705202952.png) 这样，每次我们对ass.sass进行更改，gulp 就会对其自动进行编译了，我们刷新页面也能马上看到效果哦！！   laravel 5.1 兼职就是神器！