---
title: 'PHP CURL 发起POST , GET , DELETE , PUT 请求'
url: 147.html
id: 147
categories:
  - PHP
date: 2013-04-19 10:25:46
tags:
---

```php
<?php 

	function api_request($URL,$type,$params,$headers){
		$ch = curl_init(); //初始化curl，还应该判断一下是否包含curl这个函数
		$timeout = 5; //设置过期时间
		curl\_setopt($ch, CURLOPT\_URL, $URL); //发贴地址
		if($headers!=""){
			curl\_setopt($ch, CURLOPT\_HTTPHEADER, $headers);
		}else {
			curl\_setopt($ch, CURLOPT\_HTTPHEADER, array('Content-type: text/json'));
		}
		curl\_setopt($ch, CURLOPT\_RETURNTRANSFER, 1); //以文件流的形式返回
		curl\_setopt($ch, CURLOPT\_CONNECTTIMEOUT, $timeout);
		switch ($type){
			case "GET" : curl\_setopt($ch, CURLOPT\_HTTPGET, true);break;
			case "POST": curl\_setopt($ch, CURLOPT\_POST,true); 
				     curl\_setopt($ch, CURLOPT\_POSTFIELDS,$params);break;
			case "PUT" : curl\_setopt ($ch, CURLOPT\_CUSTOMREQUEST, "PUT"); 
				     curl\_setopt($ch, CURLOPT\_POSTFIELDS,$params);break;
			case "DELETE":curl\_setopt ($ch, CURLOPT\_CUSTOMREQUEST, "DELETE"); 
				      curl\_setopt($ch, CURLOPT\_POSTFIELDS,$params);break;
		}
		$file\_contents = curl\_exec($ch);//获得返回值
		return $file_contents;
		curl_close($ch);
	}


?>
```