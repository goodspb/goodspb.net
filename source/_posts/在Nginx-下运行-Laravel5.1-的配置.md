---
title: 在Nginx 下运行 Laravel5.1 的配置
url: 66.html
id: 66
categories:
  - laravel
date: 2015-06-29 20:47:37
tags:
---

**一、nginx 的 vhost.conf 配置：**

	server {
        listen  80;  
		server_name sub.domain.com;  
		set $root_path '/srv/www/default';  
		root $root_path;  
	  
		index index.php index.html index.htm;  
	  
		try_files $uri $uri/ @rewrite;  
	  
		location @rewrite {  
			rewrite ^/(.*)$ /index.php?_url=/$1;  
		}  
	  
		location ~ \\.php {  
	  
			fastcgi_pass 127.0.0.1:9000;  
			fastcgi_index /index.php;  
	  
			fastcgi\_split\_path_info       ^(.+\\.php)(/.+)$;  
			fastcgi\_param PATH\_INFO       $fastcgi\_path\_info;  
			fastcgi\_param PATH\_TRANSLATED $document\_root$fastcgi\_path_info;  
			fastcgi\_param SCRIPT\_FILENAME $document\_root$fastcgi\_script_name;  
			include                       fastcgi_params;
		}  
	  
		location ~* ^/(css|img|js|flv|swf|download)/(.+)$ {  
			root $root_path;  
		}  
	  
		location ~ /\\.ht {  
			deny all;  
		}  
    }

**二、测试：** 在CentOS6.5  + Nginx1.8.0 +Laravel5.1测试通过 在windows 7 +Nginx1.6.5+Laravel5.1测试通过