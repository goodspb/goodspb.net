---
title: ThinkPHP 关于行为扩展 app_begin 标签不能使用 session 的问题
tags:
  - app_begin
  - behaveior
  - session
  - thinkphp
url: 214.html
id: 214
categories:
  - PHP
  - Thinkphp
date: 2016-04-06 00:31:35
---

ThinkPHP 中有一个叫行为扩展的功能模块，其实就是设计模式当中的“监听者模式” （当然啦，这个还是处于十分粗糙的），对于网站的初始化等处理还是挺有用处的。 当中有一个行为标签叫 app_begin ，见名知意，就是在整个应用最开始时执行的操作。 但是有个缺憾，在 app_begin 这个标签的 Behavior 无法执行 session 的 增删改查 ，到底为何呢？ 可以查看 TP 的源码： ThinkPHP/Library/Think/


**App.class.php**

<!--more-->

```php
/**
 * 运行应用实例 入口文件使用的快捷方法
 * @access public
 * @return void
*/
public static function run()
{
    // 加载动态应用公共文件和配置
    load_ext_file(COMMON_PATH);
    // 应用初始化标签
    Hook::listen('app_init');
    App::init();
    // 应用开始标签
    Hook::listen('app_begin');
    // Session初始化
    if (!IS_CLI) {
        session(C('SESSION_OPTIONS'));
    }
    // 记录应用初始化时间
    G('initTime');
    App::exec();
    // 应用结束标签
    Hook::listen('app_end');
    return;
}
```


这里注意，先执行了 Hoop:listen('app_begin') ， 再执行 session(C('SESSION_OPTIONS')) 的 session 初始化，因此，在 app_begin 的 Behavior 是不能使用 session 的。 如果需要令到 app_begin 当中的 session 操作生效，必须 将运行的顺序交换一下，如：

```php
/**
 * 运行应用实例 入口文件使用的快捷方法
 * @access public
 * @return void
*/
public static function run()
{
    // 加载动态应用公共文件和配置
    load_ext_file(COMMON_PATH);
    // 应用初始化标签
    Hook::listen('app_init');
    App::init();
    // Session初始化
    if (!IS_CLI) {
        session(C('SESSION_OPTIONS'));
    }
    // 应用开始标签
    Hook::listen('app_begin');
    // 记录应用初始化时间
    G('initTime');
    App::exec();
    // 应用结束标签
    Hook::listen('app_end');
    return;
}
```