---
title: PHP 文件夹遍历
url: 103.html
id: 103
categories:
  - PHP
date: 2012-05-04 20:46:59
tags:
---

直上代码：

```php

<?php

/*
 * 第一种实现办法：用dir返回对象
 */
function tree($directory) 
{ 
	$mydir = dir($directory); 
	while($file = $mydir->read())
	{ 
		if((is_dir("$directory/$file")) AND ($file!=".") AND ($file!="..")) 
		{
			//递归子文件夹
				tree("$directory/$file"); 
		} 
		else
		{
			echo "<li>$file</li>\\n"; 
    }
		
	} 
	$mydir->close(); 
} 
//开始运行
tree("D:/www/data"); 

/*
 * 第二种实现办法：用readdir()函数
 */
function listDir($dir)
{
	if(is_dir($dir))
	{
		if ($dh = opendir($dir)) 
		{
			while (($file = readdir($dh)) !== false)
			{
				if((is_dir($dir."/".$file)) && $file!="." && $file!="..")
				{
					//递归子文件夹
					listDir($dir."/".$file."/");
				}
				else
				{
					if($file!="." && $file!="..")
					{
						echo $file."<br>";
					}
				}
			}
			//处理完毕关闭文件夹句柄
			closedir($dh);
		}
	}
}

//开始运行
listDir("D:/www/data");

?>

```