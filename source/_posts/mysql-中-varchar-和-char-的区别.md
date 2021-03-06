---
title: mysql 中 varchar 和 char 的区别
url: 145.html
id: 145
categories:
  - Mysql
date: 2013-10-18 18:15:50
tags:
---

在mysql教程中char与varchar的区别呢，都是用来存储字符串的，只是他们的保存方式不一样罢了，char有固定的长度，而varchar属于可变长的字符类型。

char与varchar的区别 char (13)长度固定， 如'www.jb51.net' 存储需要空间 12个字符 varchar(13) 可变长 如'www.jb51.net' 需要存储空间 13字符, 从上面可以看得出来char 长度是固定的，不管你存储的数据是多少他都会都固定的长度。而varchar则处可变长度但他要在总长度上加1字符，这个用来存储位置。所以实际应用中用户可以根据自己的数据类型来做。 再看看char,与varchar在速度上的区别吧。

mysal>create tabe ab(v varchar(4),c char(4)); query ok ,0 rows affected(0.02 sec) mysql>insert into abc values('ab ','ab ') query ok ,1 row affected(0.00 sec); mysql->select concat(v ,'+') ,concat(c ,'+') form abc ab + | ab+ 1rows in set (0.00 sec)

从上面可以看出来，由于某种原因char 固定长度，所以在处理速度上要比varchar快速很多，但是对费存储空间，所以对存储不大，但在速度上有要求的可以使用char类型，反之可以用varchar类型来实例。 注明： 在用char字符类型时内容后面有空间时必须作相关处理，要不就会把空格自动删除。 建意: myisam 存储引擎 建议使用固定长度，数据列代替可变长度的数据列。 memory存储引擎 目前都使用固定数据行存储，因此无论使用char varchar列都没关系， innodb 存储引擎 建意使用varchar 类型 **以下是其它网友的补充** char是一种固定长度的类型，varchar则是一种可变长度的类型 char(M)类型的数据列里，每个值都占用M个字节，如果某个长度小于M，MySQL就会在它的右边用空格字符补足．（在检索操作中那些填补出来的空格字符将被去掉）在varchar(M)类型的数据列里，每个值只占用刚好够用的字节再加上一个用来记录其长度的字节（即总长度为L+1字节）． **在MySQL中用来判断是否需要进行对据列类型转换的规则** １、在一个数据表里，如果每一个数据列的长度都是固定的，那么每一个数据行的长度也将是固定的． ２、只要数据表里有一个数据列的长度的可变的，那么各数据行的长度都是可变的． ３、如果某个数据表里的数据行的长度是可变的，那么，为了节约存储空间，MySQL会把这个数据表里的固定长度类型的数据列转换为相应的可变长度类型． 例外：长度小于４个字符的char数据列不会被转换为varchar类型 对于MyISAM表，尽量使用Char，对于那些经常需要修改而容易形成碎片的myisam和isam数据表就更是如此，**它的缺点就是占用磁盘空间**； 对于InnoDB表，因为它的数据行内部存储格式对固定长度的数据行和可变长度的数据行不加区分（所有数据行共用一个表头部分，这个标头部分存放着指向各有关数据列的指针），所以使用char类型不见得会比使用varchar类型好。事实上，因为char类型通常要比varchar类型占用更多的空间，所以从减少空间占用量和减少磁盘i/o的角度，使用varchar类型反而更有利. 文章2： 字符应该是最常见的一种了，但似乎各个数据库都有所不同，比如oracle中就有啥varchar2之类。不过mysql似乎最多的还是集中在char和varchar上。 说说区别。char是固定长度的，而varchar会根据具体的长度来使用存储空间。比如char(255)和varchar(255)，在存储字符串"hello world"的时候，char会用一块255的空间放那个11个字符，而varchar就不会用255个，他先计算长度后只用11个再加上计算的到字符串长度信息，一般1-2个byte来，这样varchar在存储不确定长度的时候会大大减少存储空间。 **如此看来varchar比char聪明多了，那char有用武之地吗？还是很不少优势的。** 一，存储很短的信息，比如门牌号码101，201……这样很短的信息应该用char，因为varchar还要占个byte用于存储信息长度，本来打算节约存储的现在得不偿失。 二，固定长度的。比如使用uuid作为主键，那用char应该更合适。因为他固定长度，varchar动态根据长度的特性就消失了，而且还要占个长度信息。 三，十分频繁改变的column。因为varchar每次存储都要有额外的计算，得到长度等工作，如果一个非常频繁改变的，那就要有很多的精力用于计算，而这些对于char来说是不需要的。 还有一个关于varchar的问题是，varchar他既然可以自动适应存储空间，那我varchar(8)和varchar(255)存储应该都是一样的，那每次表设计的时候往大的方向去好了，免得以后不够用麻烦。这个思路对吗？答案是否定的。mysql会把表信息放到内存中（查询第一次后，就缓存住了，linux下很明显，但windows下似乎没有，不知道为啥），这时内存的申请是按照固定长度来的，如果varchar很大就会有问题。所以还是应该按需索取。 总结：仔细看DZ的数据表，定长的字段基本还都是用char....

摘自：http://www.jb51.net/article/23575.htm