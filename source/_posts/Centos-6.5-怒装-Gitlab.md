---
title: Centos 6.5 怒装 Gitlab
tags:
  - gitlab
  - 运维
url: 328.html
id: 328
categories:
  - Linux/Unix
date: 2017-06-17 20:59:32
---

### Git 服务器的可选范围?

*   [Gitlab](https://gitlab.com/)
*   [gogs](https://github.com/gogits/gogs)

假如现在让我重新选，那其实轻量级的gogs其实也不差，因为Gitlab实在实在实在太”重“了。

<!--more-->

### 对机器的要求

*   2核CPU
*   4G内存（至少2G）

我的单核CPU+2G内存，Gitlab经常ENOMEM...

### 安装 gitlab-ce 的源

想直接从 gitlab 官网下载？国内的网络环境你又不是不知道，还是乖乖选择国内源吧，可以选择清华大学源：

    cd ~
    curl -LJO https://mirror.tuna.tsinghua.edu.cn/gitlab-ce/yum/el6/gitlab-ce-9.2.6-ce.0.el6.x86_64.rpm
    rpm -i gitlab-ce-9.2.6-ce.0.el6.x86_64.rpm
    

### 安装依赖

    sudo yum install curl openssh-server openssh-clients postfix cronie
    sudo service postfix start
    sudo chkconfig postfix on
    

### 安装 gitlab-ce (ce就是社区版的意思哦)

    sudo yum install gitlab-ce
    

### 配置 gitlab

由于 gitlab 的一键安装是包含以下服务的

*   Nginx
*   PostgreSQL
*   Redis
*   gitlab

你需要先确认你当前机器是否有Nginx & Redis & PostgreSQL 这类服务了，然后，你需要根据自己需要来配置 gitlab：

    vim /etc/gitlab/gitlab.rb
    

### 假如本来有 Nginx 服务的，可以关于gitlab自带的nginx

    nginx['enable'] = false
    

#### 然后在自己的Nginx 加入配置：

    # gitlab socket 文件地址
    upstream gitlab {
      server unix://var/opt/gitlab/gitlab-rails/sockets/gitlab.socket;
    }
    
    server {
      listen 80;
    
      server_name git.goodspb.com;   # 请修改为你的域名
    
      server_tokens off;     # don't show the version number, a security best practice
      root /opt/gitlab/embedded/service/gitlab-rails/public;
    
      # Increase this if you want to upload large attachments
      # Or if you want to accept large git objects over http
      client_max_body_size 0;
    
      # individual nginx logs for this gitlab vhost
      access_log  /var/log/gitlab/nginx/gitlab_access.log;
      error_log   /var/log/gitlab/nginx/gitlab_error.log;
    
      location / {
        # serve static files from defined root folder;.
        # @gitlab is a named location for the upstream fallback, see below
        try_files $uri $uri/index.html $uri.html @gitlab;
      }
    
      # if a file, which is not found in the root folder is requested,
      # then the proxy pass the request to the upsteam (gitlab unicorn)
      location @gitlab {
        # If you use https make sure you disable gzip compression 
        # to be safe against BREACH attack
    
        proxy_read_timeout 300; # Some requests take more than 30 seconds.
        proxy_connect_timeout 300; # Some requests take more than 30 seconds.
        proxy_redirect     off;
    
    
        proxy_set_header   Host              $http_host;
        proxy_set_header   X-Real-IP         $remote_addr;
        proxy_set_header   X-Forwarded-For   $proxy_add_x_forwarded_for;
        proxy_set_header   X-Frame-Options   SAMEORIGIN;
        proxy_set_header   X-Forwarded-Proto $scheme;
    
        proxy_pass http://gitlab;
      }
    
      # Enable gzip compression as per rails guide: http://guides.rubyonrails.org/asset_pipeline.html#gzip-compression
      # WARNING: If you are using relative urls do remove the block below
      # See config/application.rb under "Relative url support" for the list of
      # other files that need to be changed for relative url support
      location ~ ^/(assets)/  {
        root /opt/gitlab/embedded/service/gitlab-rails/public;
        # gzip_static on; # to serve pre-gzipped version
        expires max;
        add_header Cache-Control public;
      }
    
      error_page 502 /502.html;
    }
    

### 假如本来就有 Redis 服务，可以：

    # 关闭原来redis
    redis['enable'] = false
    
    # 配置自己的redis信息
    gitlab_rails['redis_host'] = "127.0.0.1"
    gitlab_rails['redis_port'] = 6379
    gitlab_rails['redis_password'] = nil
    gitlab_rails['redis_database'] = 0
    

\### 还需要配置SMTP邮件服务，不然邮件发不出，就不能完成注册了

     # 修改以下配置
    gitlab_rails['smtp_enable'] = true
    gitlab_rails['smtp_address'] = "smtp.163.com"
    gitlab_rails['smtp_port'] = 25
    gitlab_rails['smtp_user_name'] = "abc@163.com"
    gitlab_rails['smtp_password'] = "xxxxxx"
    gitlab_rails['smtp_domain'] = "163.com"
    gitlab_rails['smtp_authentication'] = "login"
    gitlab_rails['smtp_enable_starttls_auto'] = true
    gitlab_rails['smtp_tls'] = false
    
    gitlab_rails['gitlab_email_enabled'] = true
    gitlab_rails['gitlab_email_from'] = 'abc@163.com'     # 有些服务商如 163，需要将 gitlab_email_from 和 git_user_email 和 smtp_user_name 设置成相同的
    user['git_user_email'] = "abc@163.com"
    

> 设置好想调试邮件，可以在命令行执行 `gitlab-rails console` ，然后再在ruby命令行执行：

    Notify.test_email('destination_email@address.com', 'Message Subject', 'Message Body').deliver_now
    

更多邮件设置，可以查看[官方文档](https://docs.gitlab.com/omnibus/settings/smtp.html)

### 设置时区

    gitlab_rails['time_zone'] = 'Asia/Shanghai'
    

### 设置域名

    external_url 'http://git.goodspb.com/'
    

### 假如还是 SSL 的话，好需要配置

    nginx['listen_port'] = 80
    
    ##! **Override only if your reverse proxy internally communicates over HTTP**
    ##! Docs: https://docs.gitlab.com/omnibus/settings/nginx.html#supporting-proxied-ssl
     nginx['listen_https'] = false
    
    # nginx['custom_gitlab_server_config'] = "location ^~ /foo-namespace/bar-project/raw/ {\n deny all;\n}\n"
    # nginx['custom_nginx_config'] = "include /etc/nginx/conf.d/example.conf;"
    # nginx['proxy_read_timeout'] = 3600
    # nginx['proxy_connect_timeout'] = 300
     nginx['proxy_set_headers'] = {
    #  "Host" => "$http_host_with_default",
    #  "X-Real-IP" => "$remote_addr",
    #  "X-Forwarded-For" => "$proxy_add_x_forwarded_for",
      "X-Forwarded-Proto" => "https",
      "X-Forwarded-Ssl" => "on",
    #  "Upgrade" => "$http_upgrade",
    #  "Connection" => "$connection_upgrade"
     }
    
    

### 配置差不多了，可以重启服务了

    # 执行命令
    sudo gitlab-ctl reconfigure
    

### 查看 log

    sudo gitlab-ctl tail
    

Have fun.