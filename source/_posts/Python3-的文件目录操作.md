---
title: Python3 的文件/目录操作
tags:
  - python3
  - 文件遍历
url: 251.html
id: 251
categories:
  - Python
date: 2015-10-11 22:46:09
---

最近不知为何，忽然爱上了 Python ， 无论做小爬虫，还是简单的文件处理都是很尝心悦目的。 不过，还是不太熟悉python的包和函数，来来来，让我 markdown 一下： 文件头，请导入：

 <!--more-->


    import os
    import shutil
    

    #创建空文件
    os.mknod("test.txt") 
     #直接打开一个文件，如果文件不存在则创建文件
    open("test.txt",w)
    
    #创建目录
    os.mkdir("file")
    
    #创建多层目录
    def mkdirs(path): 
        # 去除首位空格
        path=path.strip()
        #去除尾部 \ 符号
        path=path.rstrip("\\")
    
        #判断路径是否存在
        #存在     True
        #不存在   False
        isExists=os.path.exists(path)
    
        #判断结果
        if not isExists:
            # 创建目录操作函数
            os.makedirs(path)
            #如果不存在则创建目录
            print path + u' 创建成功'
            return True
        else:
            #如果目录存在则不创建，并提示目录已存在
            print path + u' 目录已存在'
            return False
    
    #复制文件：
    shutil.copyfile("oldfile","newfile")       #oldfile和newfile都只能是文件
    shutil.copy("oldfile","newfile")            #oldfile只能是文件夹，newfile可以是文件，也可以是目标目录
    
    #复制文件夹：
    shutil.copytree("olddir","newdir")       # olddir和newdir都只能是目录，且newdir必须不存在
    
    #重命名文件（目录）
    os.rename("oldname","newname")      # 文件或目录都是使用这条命令
    
    #移动文件（目录）
    shutil.move("oldpos","newpos")    
    
    #删除文件
    os.remove("file")
    
    #删除目录
    os.rmdir("dir")                           #只能删除空目录
    shutil.rmtree("dir")                   #空目录、有内容的目录都可以删 
    
    #转换目录
    os.chdir("path")    #换路径
    
    #判断目标
    os.path.exists("goal")    #判断目标是否存在
    os.path.isdir("goal")     #判断目标是否目录
    os.path.isfile("goal")    #判断目标是否文件  
    
    #PS: 若路径中含中文，在windows环境（编码为GBK）下，要将目录编码成GBK，如：dir.encode('GBK')
    
    #遍历目录
    root = os.path.abspath(".")   #本获取本目录
    for rootpath, dirs, files in os.walk(root):
        #遍历目录
            for dir in dirs:
                    print(dir)
         #遍历文件
        for file in files:
                   print(file)
    
    

欢迎大家补充。