---
title: 在PHP中使用cURL上传文件
tags:
  - curl
  - PHP
  - post
  - 上传
url: 158.html
id: 158
categories:
  - PHP
date: 2013-07-30 00:04:10
---

最近在做一个接口，接口中需要上传文件，一看肯定是需要使用curl无疑，让我再次记录： 

<!--more-->

一、最简单的例子:

```php
// initialise the curl request
$request = curl_init('http://example.com/');

// send a file
curl\_setopt($request, CURLOPT\_POST, true);
curl_setopt(
    $request,
    CURLOPT_POSTFIELDS,
    array(
      'file' => '@' . realpath('example.txt')
    ));

// output the response
curl\_setopt($request, CURLOPT\_RETURNTRANSFER, true);
echo curl_exec($request);

// close the session
curl_close($request);
```

二、设置文件名：

```php
curl_setopt(
    $request,
    CURLOPT_POSTFIELDS,
    array(
      'file' => '@' . realpath('example.txt') . ';filename=name.txt'
    ));
```

  三、如果是在 表单中上传的：

```php
curl_setopt(
    $request,
    CURLOPT_POSTFIELDS,
    array(
      'file' =>
          '@'            . $\_FILES\['file'\]\['tmp\_name'\]
          . ';filename=' . $_FILES\['file'\]\['name'\]
          . ';type='     . $_FILES\['file'\]\['type'\]
    ));
```

在表单中上传则会 先将文件上传到服务器，然后再上传到curl的服务器