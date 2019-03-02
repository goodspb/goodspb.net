---
title: linux下的时区修改(Base on Ubuntu/Linode)
url: 313.html
id: 313
categories:
  - Linux/Unix
date: 2017-03-06 15:35:55
tags:
---

最近买了个 linode ，使用 cronjob 的时候发现时区不对啊，美国的时区不太适合俺这个中国仁，好吧。容我修改一下吧。 自己手工去修改？NONONO，我们使用 `tzselect`

    root@ubuntu:~# tzselect
    Please identify a location so that time zone rules can be set correctly.
    Please select a continent, ocean, "coord", or "TZ".
     1) Africa
     2) Americas
     3) Antarctica
     4) Arctic Ocean
     5) Asia
     6) Atlantic Ocean
     7) Australia
     8) Europe
     9) Indian Ocean
    10) Pacific Ocean
    11) coord - I want to use geographical coordinates.
    12) TZ - I want to specify the time zone using the Posix TZ format.
    

<!--more-->

选择 5

    Please select a country whose clocks agree with yours.
     1) Afghanistan       18) Israel            35) Palestine
     2) Armenia       19) Japan         36) Philippines
     3) Azerbaijan        20) Jordan            37) Qatar
     4) Bahrain       21) Kazakhstan        38) Russia
     5) Bangladesh        22) Korea (North)     39) Saudi Arabia
     6) Bhutan        23) Korea (South)     40) Singapore
     7) Brunei        24) Kuwait            41) Sri Lanka
     8) Cambodia          25) Kyrgyzstan        42) Syria
     9) China         26) Laos          43) Taiwan
    10) Cyprus        27) Lebanon           44) Tajikistan
    11) East Timor        28) Macau         45) Thailand
    12) Georgia       29) Malaysia          46) Turkmenistan
    13) Hong Kong         30) Mongolia          47) United Arab Emirates
    14) India         31) Myanmar (Burma)       48) Uzbekistan
    15) Indonesia         32) Nepal         49) Vietnam
    16) Iran          33) Oman          50) Yemen
    17) Iraq          34) Pakistan
    

选择9

    Please select one of the following time zone regions.
    1) Beijing Time
    2) Xinjiang Time
    

选择1

    The following information has been given:
    
        China
        Beijing Time
    
    Therefore TZ='Asia/Shanghai' will be used.
    Local time is now:  Mon Mar  6 14:44:25 CST 2017.
    Universal Time is now:  Mon Mar  6 06:44:25 UTC 2017.
    Is the above information OK?
    1) Yes
    2) No
    
    

选择 1

#### 最后

复制 `TZ='Asia/Shanghai'; export TZ` 到 `.profile` 即可。 然后执行：

    source ~/.profile
    date
    

这个时候，就会看见显示的是北京时间。