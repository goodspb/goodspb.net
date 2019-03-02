---
title: Linux日志查看常用命令大全
url: 398.html
id: 398
categories:
  - Linux/Unix
date: 2018-03-03 18:04:31
tags:
---

### **1、cat命令：**

**功能：**1）显示整个文件。 示例： $ cat fileName 2）把文件串连接后传到基本输出，如将几个文件合并为一个文件或输出到屏幕。 示例： $ cat file1 file2 > file 说明：把档案串连接后传到基本输出(屏幕或加 > fileName 到另一个档案) **     cat参数详解：** -n 或 –number 由 1 开始对所有输出的行数编号 -b 或 –number-nonblank 和 -n 相似，只不过对于空白行不编号 -s 或 –squeeze-blank 当遇到有连续两行以上的空白行，就代换为一行的空白行 -v 或 –show-nonprinting

<!--more-->

### **2、more命令：**

以百分比的形式查看日志。 参数说明： +n 从笫n行开始显示 -n 定义屏幕大小为n行 +/pattern 在每个档案显示前搜寻该字串（pattern），然后从该字串前两行之后开始显示 -c 从顶部清屏，然后显示 -d 提示“Press space to continue，’q’ to quit（按空格键继续，按q键退出）”，禁用响铃功能 -l 忽略Ctrl+l（换页）字符 -p 通过清除窗口而不是滚屏来对文件进行换页，与-c选项相似 -s 把连续的多个空行显示为一行 -u 把文件内容中的下画线去掉  

### **3、less命令：**

less 与 more 类似，但使用 less 可以随意浏览文件，而 more 仅能向前移动，却不能向后移动，而且 less 在查看之前不会加载整个文件。 参数说明： /字符串：向下搜索“字符串”的功能 ?字符串：向上搜索“字符串”的功能 n：重复前一个搜索（与 / 或 ? 有关） N：反向重复前一个搜索（与 / 或 ? 有关）    

### **4、head命令：**

**功能：**从文本文件的头部开始查看，head 命令用于查看一个文本文件的开头部分。 示例如下： head example.txt 显示文件 example.txt 的前十行内容； head -n 20 example.txt 显示文件 example.txt 的前二十行内容； **head详解：** -n      指定你想要显示文本多少行。 -n number     这个参数选项必须是十进制的整数，它将决定在文件中的位置，以行为单位。 -c number     这个参数选项必须是十进制的整数，它将决定在文件中的位置，以字节为单位。

### **5、tail命令：**

**功能：**tail 命令用于显示文本文件的末尾几行。 示例如下： tail example.txt 显示文件 example.txt 的后十行内容； tail -n 20 example.txt 显示文件 example.txt 的后二十行内容； tail -f example.txt 显示文件 example.txt 的后十行内容并在文件内容增加后，自动显示新增的文件内容。 tail -n 50 -f example.txt 显示文件 example.txt 的后50行内容并在文件内容增加后，自动显示新增的文件内容。 注意： 最后一条命令非常有用，尤其在监控日志文件时，可以在屏幕上一直显示新增的日志信息。 **参数说明：** -b Number 从 Number 变量表示的 512 字节块位置开始读取指定文件。 -c Number 从 Number 变量表示的字节位置开始读取指定文件。 -f 如果输入文件是常规文件或如果 File 参数指定 FIFO（先进先出）， 那么 tail 命令不会在复制了输入文件的最后的指定单元后终止，而是继续 从输入文件读取和复制额外的单元（当这些单元可用时）。如果没有指定 File 参数， 并且标准输入是管道，则会忽略 -f 标志。tail -f 命令可用于监视另一个进程正在写入的文件的增长。 -k Number 从 Number 变量表示的 1KB 块位置开始读取指定文件。 -m Number 从 Number 变量表示的多字节字符位置开始读取指定文件。使用该标志提供在单字节和双字节字符代码集环境中的一致结果。 -n Number 从首行或末行位置来读取指定文件，位置由 Number 变量的符号（+ 或 - 或无）表示，并通过行号 Number 进行位移。 -r 从文件末尾以逆序方式显示输出。-r 标志的缺省值是以逆序方式显示整个文件。如果文件大于 20,480 字节，那么-r标志只显示最后的 20,480 字节。 -r 标志只有 与 -n 标志一起时才有效。否则，就会将其忽略。  

### 6、wc 命令

该命令统计给定文件中的字节数、字数、行数。 参数说明： \- c 统计字节数。 - l 统计行数。 - w 统计字数。  

### 7、uniq 命令

uniq可检查文本文件中重复出现的行列。 参数说明： -c或--count 在每列旁边显示该行重复出现的次数。 -d或--repeated 仅显示重复出现的行列。 -f<栏位>或--skip-fields=<栏位> 忽略比较指定的栏位。 -s<字符位置>或--skip-chars=<字符位置> 忽略比较指定的字符。 -u或--unique 仅显示出一次的行列。 -w<字符位置>或--check-chars=<字符位置> 指定要比较的字符。 --help 显示帮助。 --version 显示版本信息。  

### 8、awk 命令

可以说的上是查看命令的终极武器，是一个强大的文本分析工具。但是使用起来相对复杂啦，等于在使用一个编程语言一样了。所以，以下只能使用一些实例来说明awk命令是如何使用的了。awk 在命令行是怎么使用的呢？

awk \[-F field-separator\] 'commands' input-file(s)
其中，commands 是真正awk命令，\[-F域分隔符\]是可选的。 input-file(s) 是待处理的文件。
在awk中，文件的每一行中，由域分隔符分开的每一项称为一个域。通常，在不指名-F域分隔符的情况下，默认的域分隔符是空格。

 

#### 实例说明：

假设last -n 5的输出如下

\[root@www ~\]# last -n 5 <==仅取出前五行
root     pts/1   192.168.1.100  Tue Feb 10 11:21   still logged in
root     pts/1   192.168.1.100  Tue Feb 10 00:46 - 02:28  (01:41)
root     pts/1   192.168.1.100  Mon Feb  9 11:41 - 18:30  (06:48)
dmtsai   pts/1   192.168.1.100  Mon Feb  9 11:41 - 11:41  (00:00)
root     tty1                   Fri Sep  5 14:09 - 14:10  (00:01)

如果只是显示最近登录的5个帐号

#last -n 5 | awk  '{print $1}'
root
root
root
dmtsai
root

awk工作流程是这样的：读入有'\\n'换行符分割的一条记录，然后将记录按指定的域分隔符划分域，填充域，$0则表示所有域,$1表示第一个域,$n表示第n个域。默认域分隔符是"空白键" 或 "\[tab\]键",所以$1表示登录用户，$3表示登录用户ip,以此类推。   如果只是显示/etc/passwd的账户

#cat /etc/passwd |awk  -F ':'  '{print $1}'  
root
daemon
bin
sys

这种是awk+action的示例，每行都会执行action{print $1}。 -F指定域分隔符为':'。   如果只是显示/etc/passwd的账户和账户对应的shell,而账户与shell之间以tab键分割

#cat /etc/passwd |awk  -F ':'  '{print $1"\\t"$7}'
root    /bin/bash
daemon  /bin/sh
bin     /bin/sh
sys     /bin/sh

  如果只是显示/etc/passwd的账户和账户对应的shell,而账户与shell之间以逗号分割,而且在所有行添加列名name,shell,在最后一行添加"blue,/bin/nosh"。

cat /etc/passwd |awk  -F ':'  'BEGIN {print "name,shell"}  {print $1","$7} END {print "blue,/bin/nosh"}'
name,shell
root,/bin/bash
daemon,/bin/sh
bin,/bin/sh
sys,/bin/sh
....
blue,/bin/nosh

awk工作流程是这样的：先执行BEGING，然后读取文件，读入有/n换行符分割的一条记录，然后将记录按指定的域分隔符划分域，填充域，$0则表示所有域,$1表示第一个域,$n表示第n个域,随后开始执行模式所对应的动作action。接着开始读入第二条记录······直到所有的记录都读完，最后执行END操作。   搜索/etc/passwd有root关键字的所有行

#awk -F: '/root/' /etc/passwd
root:x:0:0:root:/root:/bin/bash

这种是pattern的使用示例，匹配了pattern(这里是root)的行才会执行action(没有指定action，默认输出每行的内容)。 搜索支持正则，例如找root开头的: awk -F: '/^root/' /etc/passwd   搜索/etc/passwd有root关键字的所有行，并显示对应的shell

\# awk -F: '/root/{print $7}' /etc/passwd             
/bin/bash

这里指定了action{print $7}  

#### awk内置变量

awk有许多内置变量用来设置环境信息，这些变量可以被改变，下面给出了最常用的一些变量。

ARGC               命令行参数个数
ARGV               命令行参数排列
ENVIRON            支持队列中系统环境变量的使用
FILENAME           awk浏览的文件名
FNR                浏览文件的记录数
FS                 设置输入域分隔符，等价于命令行 -F选项
NF                 浏览记录的域的个数
NR                 已读的记录数
OFS                输出域分隔符
ORS                输出记录分隔符
RS                 控制记录分隔符

此外,$0变量是指整条记录。$1表示当前行的第一个域,$2表示当前行的第二个域,......以此类推。   统计/etc/passwd:文件名，每行的行号，每行的列数，对应的完整行内容:

#awk  -F ':'  '{print "filename:" FILENAME ",linenumber:" NR ",columns:" NF ",linecontent:"$0}' /etc/passwd
filename:/etc/passwd,linenumber:1,columns:7,linecontent:root:x:0:0:root:/root:/bin/bash
filename:/etc/passwd,linenumber:2,columns:7,linecontent:daemon:x:1:1:daemon:/usr/sbin:/bin/sh
filename:/etc/passwd,linenumber:3,columns:7,linecontent:bin:x:2:2:bin:/bin:/bin/sh
filename:/etc/passwd,linenumber:4,columns:7,linecontent:sys:x:3:3:sys:/dev:/bin/sh

  使用printf替代print,可以让代码更加简洁，易读

 awk  -F ':'  '{printf("filename:%10s,linenumber:%s,columns:%s,linecontent:%s\\n",FILENAME,NR,NF,$0)}' /etc/passwd

 

#### **print和printf**

awk中同时提供了print和printf两种打印输出的函数。 其中print函数的参数可以是变量、数值或者字符串。字符串必须用双引号引用，参数用逗号分隔。如果没有逗号，参数就串联在一起而无法区分。这里，逗号的作用与输出文件的分隔符的作用是一样的，只是后者是空格而已。 printf函数，其用法和c语言中printf基本相似,可以格式化字符串,输出复杂时，printf更加好用，代码更易懂。  

#### 更多说明

http://www.gnu.org/software/gawk/manual/gawk.html