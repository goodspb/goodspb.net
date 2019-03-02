---
title: Homebrew 更新 php56 和 php56-mcrypt 之后，动态链接 ext-mcrypt 失败的处理。
tags:
  - homebrew
  - libtool
  - mcrypt
  - PHP
url: 316.html
id: 316
categories:
  - mac
  - PHP
date: 2017-04-16 15:58:16
---

今天手贱使用 homebrew 执行了一下 `brew upgrade` 来更新安装过的程序。 更新完之后，执行 `php -m` ，发现出现报错：

    PHP Warning:  PHP Startup: Unable to load dynamic library '/usr/local/opt/php56-mcrypt/mcrypt.so' - dlopen(/usr/local/opt/php56-mcrypt/mcrypt.so, 9): Library not loaded: /usr/local/opt/libtool/lib/libltdl.7.dylib
      Referenced from: /usr/local/opt/php56-mcrypt/mcrypt.so
      Reason: image not found in Unknown on line 0
    
    Warning: PHP Startup: Unable to load dynamic library '/usr/local/opt/php56-mcrypt/mcrypt.so' - dlopen(/usr/local/opt/php56-mcrypt/mcrypt.so, 9): Library not loaded: /usr/local/opt/libtool/lib/libltdl.7.dylib
      Referenced from: /usr/local/opt/php56-mcrypt/mcrypt.so
      Reason: image not found in Unknown on line 0
    

看了一下，目录`/usr/local/opt/php56-mcrypt/mcrypt.so`下面是有东西的，主要的问题是: `Library not loaded: /usr/local/opt/libtool/lib/libltdl.7.dylib` 详细可见 [http://stackoverflow.com/questions/12323252/brew-doctor-dyld-library-not-loaded-error-no-available-formula-for-zlib](http://stackoverflow.com/questions/12323252/brew-doctor-dyld-library-not-loaded-error-no-available-formula-for-zlib)

### 解决方案

`brew install libtool`