---
title: 'php正则方法去除html标签或者javascript,css标签'
url: 118.html
id: 118
categories:
  - PHP
date: 2013-05-24 19:46:46
tags:
---

方法一：

```php
$str = "<div style='color:red'>test string</div><br /><p>abc</p><h1>yyyyyyyyyy</h1><a href=''>222</a>";
$preg = "/<\\/?\[^<>\]+>/i";
echo $str;
echo '<br/>';
echo preg_replace($preg,'',$str);
```

 <!--more-->

方法二:(去除多种)

```php
$search = array ("'<script\[^>\]*?>.*?</script>'si",   // 去掉 javascript
                "'<style\[^>\]*?>.*?</style>'si",   // 去掉 css
                "'<\[/!\]*?\[^<>\]*?>'si",           // 去掉 HTML 标记
                "'<!--\[/!\]*?\[^<>\]*?>'si",           // 去掉 注释 标记
                "'(\[rn\])\[s\]+'",                 // 去掉空白字符
                "'&(quot|#34);'i",                 // 替换 HTML 实体
                "'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&#(d+);'e");                    // 作为 PHP 代码运行
 
$replace = array ("",
               "",
               "",
               "",
               "\\1",
               "\\"",
               "&",
               "<",
               ">",
               " ",
               chr(161),
               chr(162),
               chr(163),
               chr(169),
               "chr(\\1)");
//$document为需要处理字符串，如果来源为文件可以$document = file\_get\_contents($filename);
$out = preg_replace($search, $replace, $document);

```

也可以使用php的内置函数strip_tags()清除html,js,注释等标记