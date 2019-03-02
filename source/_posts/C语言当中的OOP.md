---
title: C语言当中的OOP
url: 333.html
id: 333
categories:
  - C/C++
date: 2017-09-04 19:11:36
tags:
---

### 前言

最近搞 Unix编程 & PHP扩展开发 搞得不亦乐乎，忽然，凭我低下的C语言掌控能力，忽然看到一个不能理解的片段（来自Swoole):

    struct _swReactor
    {
        /*
             .... 省略100行
            */
    
        int (*add)(swReactor *, int fd, int fdtype);
        int (*set)(swReactor *, int fd, int fdtype);
        int (*del)(swReactor *, int fd);
        int (*wait)(swReactor *, struct timeval *);
        void (*free)(swReactor *);
    
        int (*setHandle)(swReactor *, int fdtype, swReactor_handle);
        swDefer_callback *defer_callback_list;
    
        void (*onTimeout)(swReactor *);
        void (*onFinish)(swReactor *);
    
        void (*enable_accept)(swReactor *);
    
        int (*write)(swReactor *, int, void *, int);
        int (*close)(swReactor *, int);
        int (*defer)(swReactor *, swCallback, void *);
    };
    

嗯嗯嗯嗯？ 为何在 struct 里面有函数指针？作者想干什么？ 哦，继续往下看代码，发现，原来是在用C写OOP。

<!--more-->

### C语言下的面向对象编程

所以，参考了各方教程，写了个小demo:

    #include <stdio.h>
    #include <stdlib.h>
    
    typedef struct people People;
    
    struct people{
        int age;
        int (*get_age) (People *);
    };
    
    int get_age(People *p) {
        return p->age;
    }
    
    People base = {
        0,
        get_age
    };
    
    People* newPeople(int age)
    {
        People *new_people = (People *)malloc(sizeof(People));
        new_people = &base;
        new_people->age = age;
        return new_people;
    }
    
    int main() {
        char string[100];
        People *people1 = newPeople(20);
        printf("Get age %d", people1->get_age(people2));
        return 0;
    }