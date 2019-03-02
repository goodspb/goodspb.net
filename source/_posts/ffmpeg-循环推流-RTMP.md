---
title: ffmpeg 循环推流 RTMP
tags:
  - ffmpeg
  - rtmp
url: 339.html
id: 339
categories:
  - Linux/Unix
date: 2017-11-20 01:29:07
---

    ffmpeg -threads 2 -re -fflags +genpts -stream_loop -1 -i "xxxxx.mp4" -acodec copy -vcodec copy -f flv -y "rtmp://pili-publish.pili.echohu.top/1314xicong/huxicongp?expire=1468471134&token=olwdBfksR8ycLmFPVEytGwjrwEs="