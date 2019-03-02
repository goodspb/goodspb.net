---
title: 'MAC OSX下include头文件缺失引起的问题及解决办法: command line tools 安装'
url: 115.html
id: 115
categories:
  - mac
  - Python
date: 2014-07-13 21:03:01
tags:
---

安装软件的时候可能出现缺少头文件而导致的安装失败，笔者就尝试过，在安装 pip install Scrapy 的时候，就出现过：

Command "/usr/bin/python -c "import setuptools, tokenize;\_\_file\_\_='/private/tmp/pip-build-UX3Es9/lxml/setup.py';exec(compile(getattr(tokenize, 'open', open)(\_\_file\_\_).read().replace('\\r\\n', '\\n'), \_\_file\_\_, 'exec'))" install --record /tmp/pip-VDXsGm-record/install-record.txt --single-version-externally-managed --compile" failed with error code 1 in /private/tmp/pip-build-UX3Es9/lxml

由此导致 安装 lxml 失败，所以安装 scrapy 失败，各种悲伤。 其实这是由于mac os 中缺少 c变异所需的include 头文件导致的，查看： cd /usr/目录下是否缺少 include 文件夹，如果没有，我们可以通过以下命令安装：

xcode-select --install

执行这条命令后，会出现GUI界面安装程序哦，一直 下一步 下一步 就好，记得，必须在有网络的情况下能安装哦。