---
title: 在 Laravel 5/4 中生成二维码
tags:
  - laravel5
  - qrcode
  - 二维码
url: 266.html
id: 266
categories:
  - laravel
  - PHP
date: 2016-07-28 15:12:40
---

### 库地址

[SimpleSoftwareIO/simple-qrcode](https://github.com/SimpleSoftwareIO/simple-qrcode)

<!--more-->

### 安装

修改 `composer.json` 文件，添加：

                "require": {
                                "simplesoftwareio/simple-qrcode": "1.4.3"  //当前稳定版本
         }
    

执行 composer update

### 添加 Service Provider

Laravel 4 注册 `SimpleSoftwareIO\QrCode\QrCodeServiceProvider` 至 `app/config/app.php` 的 providers 数组里. Laravel 5 注册 `SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class` 至 `config/app.php` 的 providers 数组里.

### 添加 Aliases

Laravel 4 最后,注册`'QrCode' => 'SimpleSoftwareIO\QrCode\Facades\QrCode'`至`app/config/app.php`的 aliases 数组里. Laravel 5 最后,注册`'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class` 至`config/app.php`的 aliases 数组里.

### 简例

打印视图 一个重要的应用是在打印页面添加的来源二维码.这里我们只需要在 footer.blade.php 文件里添加如下代码即可!

    <div class="visible-print text-center">
        {!! QrCode::size(100)->generate(Request::url()); !!}
        <p>Scan me to return to the original page.</p>
    </div>
    

嵌入二维码 你可以嵌入一个二维码在你的Email里,让收信的用户可以快速扫描.以下是使用 Laravel 实现的一个例子:

    <img src="{!!$message->embedData(QrCode::format('png')->generate('Embed me into an e-mail!'), 'QrCode.png', 'image/png')!!}">
    

更多的使用方式，请参考：[https://www.simplesoftware.io/docs/simple-qrcode/zh](https://www.simplesoftware.io/docs/simple-qrcode/zh)