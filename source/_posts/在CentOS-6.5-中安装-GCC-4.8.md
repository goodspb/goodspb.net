---
title: 在CentOS 6.5 中安装 GCC 4.8
url: 466.html
id: 466
categories:
  - Linux/Unix
date: 2018-05-21 14:00:54
tags:
---

因为最近使用 Dlib，要求使用 C++11，所以必须在 Centos 6.5上使用 gcc 4.8 以上版本，因此记录一下安装的过程:

```sh
cd /etc/yum.repos.d
wget http://people.centos.org/tru/devtools-2/devtools-2.repo -O /etc/yum.repos.d/devtools-2.repo
yum install devtoolset-2-gcc devtoolset-2-binutils devtoolset-2-gcc-c++
scl enable devtoolset-2 bash
```