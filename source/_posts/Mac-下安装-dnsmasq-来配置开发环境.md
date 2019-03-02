---
title: Mac 下安装 dnsmasq 来配置开发环境
url: 171.html
id: 171
categories:
  - Linux/Unix
  - mac
date: 2015-11-23 22:52:43
tags:
---

由于实际的开发当中，可能存在多个项目的情况，如果是在本地自行搭建的坏境的话，就很有可能需要使用多个域名了，当然，也可以是 localhost / localhost:81 / localhost:82 这样来访问多个项目。其实，有更加便捷的方法（忽略自行修改/etc/hosts，这种哈，会改死人的），使用一个管理dns的工具： dnsmasq。 1 、安装dnsmasq 已经默认各位Mac下有homebrew , 安装：

<!--more-->

```sh
brew install dnsmasq
```

2、复制配置文件

```sh
sudo cp /usr/local/opt/dnsmasq/dnsmasq.conf.example /usr/local/etc/dnsmasq.conf
```

3、编辑配置文件

```sh
sudo vim /usr/local/etc/dnsmasq.conf
```

修改（去除address=前的#）：

```
# Add domains which you want to force to an IP address here.
# The example below send any host in double-click.net to a local
# web-server.
address=/dev/127.0.0.1   #这样的意思就是访问 ********.dev 直接引导到 127.0.0.0
```

4、新建dns文件

```sh
sudo mkdir -p /etc/resolver
sudo vim /etc/resolver/dev
```

**（PS：这里新建的文件叫 dev ,  因为我配置的域名结尾是 .dev，假如大家配置的是 .app ，这里新文件应该叫：app。同理，如果配置多个域名后续，就必须写入多个文件哦！）** 写入：

```
nameserver 127.0.0.1
```

5、编辑开机自启动

```sh
sudo cp -fv /usr/local/opt/dnsmasq/*.plist /Library/LaunchDaemons
sudo chown root /Library/LaunchDaemons/homebrew.mxcl.dnsmasq.plist
sudo launchctl load /Library/LaunchDaemons/homebrew.mxcl.dnsmasq.plist
```

6、如果修改了配置文件，可以自行控制

```sh
sudo launchctl stop homebrew.mxcl.dnsmasq
sudo launchctl start homebrew.mxcl.dnsmasq
```

7、测试

```sh
ping abcd.dev
```

发现指向： 127.0.0.1