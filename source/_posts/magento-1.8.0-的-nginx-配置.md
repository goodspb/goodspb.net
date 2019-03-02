---
title: magento 1.8.0 的 nginx 配置
tags:
  - magento
  - nginx
url: 179.html
id: 179
categories:
  - PHP
date: 2014-06-26 11:29:43
---

server {
    listen 80;
    server_name abc.com;
    rewrite / $scheme://www.$host$request_uri permanent; ## Forcibly prepend a www
}

server {
	listen       80;
	server_name  www.abc.com;
	set  $DOC_ROOT d:/abc.com/shop;
	root $DOC_ROOT;
    location / {
        index index.html index.php; ## Allow a static html file to be shown first
        try_files $uri $uri/ @handler; ## If missing pass the URI to Magento's front handler
        expires 30d; ## Assume all files are cachable
    }

    ## These locations would be hidden by .htaccess normally
    location ^~ /app/                { deny all; }
    location ^~ /includes/           { deny all; }
    location ^~ /lib/                { deny all; }
    location ^~ /media/downloadable/ { deny all; }
    location ^~ /pkginfo/            { deny all; }
    location ^~ /report/config.xml   { deny all; }
    location ^~ /var/                { deny all; }

    location /var/export/ { ## Allow admins only to view export folder
        auth_basic           "Restricted"; ## Message shown in login window
        auth\_basic\_user_file htpasswd; ## See /etc/nginx/htpassword
        autoindex            on;
    }

    location  /. { ## Disable .htaccess and other hidden files
        return 404;
    }

    location @handler { ## Magento uses a common front handler
        rewrite / /index.php;
    }

    location ~ .php/ { ## Forward paths like /js/index.php/x.js to relevant handler
        rewrite ^(.*.php)/ $1 last;
    }

     location ~ .php$ { ## Execute PHP scripts
        if (!-e $request\_filename) { rewrite / /index.php last; } ## Catch 404s that try\_files miss
 
        expires        off; ## Do not cache dynamic content
        fastcgi_pass   127.0.0.1:9000;
        fastcgi\_param  SCRIPT\_FILENAME  $document\_root$fastcgi\_script_name;
        fastcgi\_param  MAGE\_RUN_CODE default; ## Store code is defined in administration > Configuration > Manage Stores
        fastcgi\_param  MAGE\_RUN_TYPE store;
        include        fastcgi\_params; ## See /etc/nginx/fastcgi\_params
    }
	
    location ~* ^.+\\.(jpg|jpeg|gif|css|png|js|ico)$ {
	    root  $DOC_ROOT;
	    index  index.php;
	    access_log off;
	    expires 30d;
   }