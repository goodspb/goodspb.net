---
title: npm 慢成狗，let's 更换 npm 淘宝源
tags:
  - nodejs
  - npm
url: 194.html
id: 194
categories:
  - nodejs
date: 2015-12-18 12:19:32
---

中国网络差真系不是说笑的，用 npm 下载3个包竟然要半小时，受不了。因此我们需要为 npm 更换一个淘宝源。 理论上，加入你要安装 koa ，可以这样做：

npm --registry=https://registry.npm.taobao.org install koa

然后，秒速的就下载完，但是你会发现，每次 install 都要加 --registry=https://registry.npm.taobao.org ，这么麻烦？来一个一劳永逸的方法，直接安装淘宝的 cnpm ，运行：

npm install -g cnpm --registry=https://registry.npm.taobao.org

等到完成后，你就可以使用 cnpm 来代替 npm 命令来下载了，如上例 koa , 可以使用如下命令安装：

cnpm install koa

这样，其实命令就等价于：

npm --registry=https://registry.npm.taobao.org install koa

  至此，OVER，大家可以去看看淘宝源网址：http://npm.taobao.org/