---
title: REMOTE_ADDR，HTTP_CLIENT_IP，HTTP_X_FORWARDED_FOR 的区别
url: 150.html
id: 150
categories:
  - PHP
date: 2014-07-23 12:27:15
tags:
---

看ecshop的lib_base.php的时候里面获取客户端真实ip的函数（real_ip），有许多情况的判断，主要判断客户端是否使用代理的情况，注意判断顺序，先判断客户端是否使用代理HTTP_X_FORWARDED_FOR

<!--more-->

```php
/**
 * 获得用户的真实IP地址
 *
 * @access  public
 * @return  string
 */
function real_ip()
{
    static $realip = NULL;
 
    if ($realip !== NULL)
    {
        return $realip;
    }
 
    if (isset($_SERVER))
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
 
            /\* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip)
            {
                $ip = trim($ip);
 
                if ($ip != 'unknown')
                {
                    $realip = $ip;
 
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_CLIENT_IP'))
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }
 
    preg_match("/[\\d\\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
 
    return $realip;
}
```

一、没有使用代理服务器的情况：

      REMOTE_ADDR = 您的 IP  
      HTTP_VIA = 没数值或不显示  
      HTTP_X_FORWARDED_FOR = 没数值或不显示

二、使用透明代理服务器的情况：Transparent Proxies

      REMOTE_ADDR = 最后一个代理服务器 IP  
      HTTP_VIA = 代理服务器 IP  
      HTTP_X_FORWARDED_FOR = 您的真实 IP ，经过多个代理服务器时，这个值类似如下：203.98.182.163, 203.98.182.163, 203.129.72.215。

  这类代理服务器还是将您的信息转发给您的访问对象，无法达到隐藏真实身份的目的。

三、使用普通匿名代理服务器的情况：Anonymous Proxies

      REMOTE_ADDR = 最后一个代理服务器 IP  
      HTTP_VIA = 代理服务器 IP  
      HTTP_X_FORWARDED_FOR = 代理服务器 IP ，经过多个代理服务器时，这个值类似如下：203.98.182.163, 203.98.182.163, 203.129.72.215。

  隐藏了您的真实IP，但是向访问对象透露了您是使用代理服务器访问他们的。

四、使用欺骗性代理服务器的情况：Distorting Proxies

      REMOTE_ADDR = 代理服务器 IP  
      HTTP_VIA = 代理服务器 IP  
      HTTP_X_FORWARDED_FOR = 随机的 IP ，经过多个代理服务器时，这个值类似如下：203.98.182.163, 203.98.182.163, 203.129.72.215。

  告诉了访问对象您使用了代理服务器，但编造了一个虚假的随机IP代替您的真实IP欺骗它。

五、使用高匿名代理服务器的情况：High Anonymity Proxies (Elite proxies)

      REMOTE_ADDR = 代理服务器 IP  
      HTTP_VIA = 没数值或不显示  
      HTTP_X_FORWARDED_FOR = 没数值或不显示 ，经过多个代理服务器时，这个值类似如下：203.98.182.163, 203.98.182.163, 203.129.72.215。

      完全用代理服务器的信息替代了您的所有信息，就象您就是完全使用那台代理服务器直接访问对象。

       REMOTE_ADDR 是你的客户端跟你的服务器“握手”时候的IP。如果使用了“匿名代理”，REMOTE_ADDR将显示代理服务器的IP。  
       HTTP_CLIENT_IP 是代理服务器发送的HTTP头。如果是“超级匿名代理”，则返回none值。同样，REMOTE_ADDR也会被替换为这个代理服务器的IP。  
       $_SERVER['REMOTE_ADDR']; //访问端（有可能是用户，有可能是代理的）IP  
       $_SERVER['HTTP_CLIENT_IP'];  //代理端的（有可能存在，可伪造）  
       $_SERVER['HTTP_X_FORWARDED_FOR']; //用户是在哪个IP使用的代理（有可能存在，也可以伪造）