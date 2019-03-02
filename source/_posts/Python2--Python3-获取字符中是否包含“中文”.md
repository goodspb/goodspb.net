---
title: Python2 / Python3 获取字符中是否包含“中文”
url: 293.html
id: 293
categories:
  - Python
date: 2016-09-07 15:22:27
tags:
---

在做爬虫的时候，发现挺有用的：

> Python2 版本的

        def check_contain_chinese(check_str):
        for ch in check_str.decode('utf-8'):
            if u'\u4e00' <= ch <= u'\u9fff':
                return True
        return False
    

> Python3 版本的

        def check_contain_chinese(check_str):
        for ch in check_str:
            if u'\u4e00' <= ch <= u'\u9fff':
                return True
        return False