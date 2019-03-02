---
title: Mac 下让终端 ls 命令对不同文件类型着色
tags:
  - .bash_profile
  - ls
  - mac
  - 终端
url: 173.html
id: 173
categories:
  - Linux/Unix
  - mac
date: 2015-02-24 09:51:22
---

打开终端，输入：

```sh
vim .bash_profile
```

<!--more-->

然后编辑，一般分2种情况，背景喜欢黑色的和背景喜欢白色的：

```
#黑色背景的可以这样
export CLICOLOR=1
export LSCOLORS=GxFxCxDxBxegedabagaced

#白色背景的可以这样
export CLICOLOR=1
export LSCOLORS=GxFxCxDxBxegedabagaced
```

然后退出，执行：

```sh
source .bash_profile
```

这样就能看到 ls 命令的不同着色了。 可能大家有疑问，究竟上面的命令究竟是什么意思？

自定义终端命令颜色


"exfxcxdxbxegedabagacad"是终端默认的，下面给出的各个字母代表指代的不同颜色

a black
b red
c green
d brown
e blue
f magenta
g cyan
h light grey
A bold black, usually shows up as dark grey
B bold red
C bold green
D bold brown, usually shows up as yellow
E bold blue
F bold magenta
G bold cyan
H bold light grey; looks like bright white
x default foreground or background


LSCOLOR上方各个字母的次序指代的不同命令：

1\. directory
2\. symbolic link
3\. socket
4\. pipe
5\. executable
6\. block special
7\. character special
8\. executable with setuid bit set
9\. executable with setgid bit set
10\. directory writable to others, with sticky bit
11\. directory writable to others, without sticky