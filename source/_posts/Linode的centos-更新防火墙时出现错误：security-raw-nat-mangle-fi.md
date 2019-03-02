---
title: Linode的centos 更新防火墙时出现错误：security raw nat mangle fi
tags:
  - iptables
  - linode
url: 168.html
id: 168
categories:
  - Linux/Unix
date: 2015-09-29 16:11:22
---

主要原因在于linode修改过centos的内核，导致 iptables和原来的不太一样，解决方案：

vim /etc/init.d/iptables

查找：case "$i" in ， 将如下内容，

for i in $tables; do
        echo -n "$i "
        case "$i" in
            raw)
                $IPTABLES -t raw -P PREROUTING $policy \
                    && $IPTABLES -t raw -P OUTPUT $policy \
                    || let ret+=1
                ;;
            filter)
                $IPTABLES -t filter -P INPUT $policy \
                    && $IPTABLES -t filter -P OUTPUT $policy \
                    && $IPTABLES -t filter -P FORWARD $policy \
                    || let ret+=1
                ;;
            nat)

修改成：

for i in $tables; do
   echo -n "$i "
   case "$i" in
       security)
          $IPTABLES -t filter -P INPUT $policy \
               && $IPTABLES -t filter -P OUTPUT $policy \
               && $IPTABLES -t filter -P FORWARD $policy \
               || let ret+=1
            ;;
       raw)
          $IPTABLES -t raw -P PREROUTING $policy \
              && $IPTABLES -t raw -P OUTPUT $policy \
              || let ret+=1
           ;;
       filter)
          $IPTABLES -t filter -P INPUT $policy \
              && $IPTABLES -t filter -P OUTPUT $policy \
              && $IPTABLES -t filter -P FORWARD $policy \
              || let ret+=1
           ;;
       nat)

保存，重启

/etc/init.d/iptables restart

大功告成！