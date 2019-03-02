---
title: Mac 删除原生的 nodejs 或者 通过 pkg 安装的 nodejs
tags:
  - gulp
  - homebrew
  - nodejs
url: 191.html
id: 191
categories:
  - nodejs
date: 2015-12-18 11:55:55
---

使用Laravel 5.1 的时候，免不了和 gulp 打交道，因此，系统当中必须要有好的 nodejs 啊，原生的 npm 和 nodejs 是在太旧了，因此想用 homebrew 来管理 nodejs ，因此，我们需要先删除原来的 node ，步骤如下：

sudo npm uninstall npm -g
sudo rm -rf /usr/local/lib/node /usr/local/lib/node_modules /var/db/receipts/org.nodejs.*
sudo rm -rf /usr/local/include/node /Users/$USER/.npm
sudo rm /usr/local/bin/node
sudo rm /usr/local/share/man/man1/node.1
sudo rm /usr/local/lib/dtrace/node.d
sudo rm /usr/local/share/systemtap/tapset/node.stp
sudo rm /usr/local/share/doc/node/gdbinit

然后，就可以通过 homebrew 来安装 nodejs 了：

brew install node
node -v