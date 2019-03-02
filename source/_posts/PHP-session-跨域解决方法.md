---
title: PHP session 跨域解决方法
tags:
  - PHP
  - session
  - 跨域
url: 108.html
id: 108
categories:
  - PHP
date: 2013-09-04 21:57:02
---

web站点经常出现二级域名跨域的情况，例如：a.goodspb.net 、bbs.goodspb.net 这种情况！这个时候我们当然希望只要在www.goodspb.net登录之后在bbs.goodspb.net也同时登录了（用户系统一致的情况下）。因此，我们就需要另session跨域了！ 我觉得常用的session跨域有2种。 一、配置PHP设置： 而这种方式可以在应用中设置或者直接配置php.ini 1、在session_start()之前配置：

<!--more-->

```php
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.goodspb.net');
ini_set('session.cookie_lifetime', '1800');
```

2、直接在php.ini配置（必须找到正确的php.ini哦，终端输入：php -i | grep php.ini）

```c
session.cookie_path = /
session.cookie_domain = .goodspb.net
session.cookie_lifetime = 1800
```

以上是通过配置PHP达成session跨域的，当然，还能自己手动去保存session\_id来达到跨域的效果   二、手动设置session\_id 1、先保存session_id

```php
<?php

session_start();
$_SESSION\['uid'\] = 1;

//获取session_id
$session\_id = session\_id();

//然后保存起来，例如用mysql / cookie / redis 等

?>
```

2、然后到跨域处保存设置session_id

```php
<?php

//从mysql / cookie /redis 获取
$session_id = '1234567890';
session\_id($session\_id);
session_start();

?>
```