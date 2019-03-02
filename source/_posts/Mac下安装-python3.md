---
title: Mac下安装 python3
url: 246.html
id: 246
categories:
  - mac
date: 2016-02-10 00:25:09
tags:
---

还是一个老生常谈的问题，究竟使用 python3 还是 python2 好呢？假如你用的 nix 平台，当然， 2和 3 可以并存。 有鉴于本人使用的是 Mac ， 所以介绍的还是如何在 Mac 下安装 python3 。

<!--more-->

### 安装方法

> Homebrew 安装

执行：

    brew search python
    

返回结果： [![屏幕快照 2016-06-10 上午12.22.14](/images/2016/06/屏幕快照-2016-06-10-上午12.22.14.png)](/images/2016/06/屏幕快照-2016-06-10-上午12.22.14.png) 再执行：

    brew install python3
    

python 3 这样就安装完成了，当你想进入命令行模式的时候，请用 `python3`

> 安装 pip

python 下有一个包管理工具：pip ，当然，pip 也有 python3 的版本 `pip3` 当你使用 homebrew 安装 python3 的时候， pip3 其实也已经安装好了，请尝试命令：

    pip3 install BeautifulSoup4
    

好啦，你马上就可以尝试使用 python3 来编写 爬虫了！