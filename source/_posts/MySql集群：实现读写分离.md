---
title: MySql集群：实现读写分离
url: 54.html
id: 54
categories:
  - Mysql
date: 2012-09-13 09:52:33
tags:
---

一个完整的mysql读写分离环境包括以下几个部分：

*   应用程序client
*   database proxy
*   database集群

<!--more-->

在本次实战中，应用程序client基于c3p0连接后端的database proxy。database proxy负责管理client实际访问database的路由策略，采用开源框架amoeba。database集群采用mysql的master-slave的replication方案。整个环境的结构图如下所示： [![0_1278126303lqtC](/images/2012/09/0_1278126303lqtC.jpg)](/images/2012/09/0_1278126303lqtC.jpg) 实战步骤与详解 **一.搭建mysql的master-slave环境** 1）分别在host1（10.20.147.110）和host2（10.20.147.111）上安装mysql（5.0.45），具体安装方法可见官方文档 2）配置master 首先编辑/etc/my.cnf，添加以下配置： log-bin=mysql-bin #slave会基于此log-bin来做replication server-id=1 #master的标示 binlog-do-db = amoeba_study #用于master-slave的具体数据库 然后添加专门用于replication的用户： mysql> GRANT REPLICATION SLAVE ON *.* TO repl@10.20.147.111 IDENTIFIED BY '111111'; 重启mysql，使得配置生效： /etc/init.d/mysqld restart 最后查看master状态： [![0_1278127160u2x8](/images/2012/09/0_1278127160u2x8.jpg)](/images/2012/09/0_1278127160u2x8.jpg) 3）配置slave 首先编辑/etc/my.cnf，添加以下配置： server-id=2 #slave的标示 配置生效后，配置与master的连接： mysql> CHANGE MASTER TO -> MASTER\_HOST='10.20.147.110', -> MASTER\_USER='repl', -> MASTER\_PASSWORD='111111', -> MASTER\_LOG\_FILE='mysql-bin.000003', -> MASTER\_LOG\_POS=161261; 其中MASTER\_HOST是master机的ip，MASTER\_USER和MASTER\_PASSWORD就是我们刚才在master上添加的用户，MASTER\_LOG\_FILE和MASTER\_LOG\_POS对应与master status里的信息 最后启动slave： mysql> start slave; 4）验证master-slave搭建生效 通过查看slave机的log（/var/log/mysqld.log）： 100703 10:51:42 \[Note\] Slave I/O thread: connected to master 'repl@10.20.147.110:3306',  replication started in log 'mysql-bin.000003' at position 161261 如看到以上信息则证明搭建成功，如果有问题也可通过此log找原因   **二.搭建database proxy** 此次实战中database proxy采用[amoeba](http://amoeba.sourceforge.net/wordpress/) ，它的相关信息可以查阅官方文档，不在此详述 1）安装amoeba 下载amoeba（1.2.0-GA）后解压到本地（D:/openSource/amoeba-mysql-1.2.0-GA），即完成安装 2）配置amoeba 先配置proxy连接和与各后端mysql服务器连接信息（D:/openSource/amoeba-mysql-1.2.0-GA/conf/amoeba.xml）：

<server>
	<!\-\- proxy server绑定的端口 -->
	<property name="port">8066</property>
	
	<!\-\- proxy server绑定的IP -->
	<!\-\- 
	<property name="ipAddress">127.0.0.1</property>
	 \-\->
	<!\-\- proxy server net IO Read thread size -->
	<property name="readThreadPoolSize">20</property>
	
	<!\-\- proxy server client process thread size -->
	<property name="clientSideThreadPoolSize">30</property>
	
	<!\-\- mysql server data packet process thread size -->
	<property name="serverSideThreadPoolSize">30</property>
	
	<!\-\- socket Send and receive BufferSize(unit:K)  -->
	<property name="netBufferSize">128</property>
	
	<!\-\- Enable/disable TCP_NODELAY (disable/enable Nagle's algorithm). -->
	<property name="tcpNoDelay">true</property>
	
	<!\-\- 对外验证的用户名 -->
	<property name="user">root</property>
	
	<!\-\- 对外验证的密码 -->
	<property name="password">root</property>
</server>

以上是proxy提供给client的连接配置：

<dbServerList>
	<dbServer name="server1">			
		<!\-\- PoolableObjectFactory实现类 -->
		<factoryConfig class="com.meidusa.amoeba.mysql.net.MysqlServerConnectionFactory">
			<property name="manager">defaultManager</property>
			
			<!\-\- 真实mysql数据库端口 -->
			<property name="port">3306</property>
			
			<!\-\- 真实mysql数据库IP -->
			<property name="ipAddress">10.20.147.110</property>
			<property name="schema">amoeba_study</property>
			
			<!\-\- 用于登陆mysql的用户名 -->
			<property name="user">root</property>
			
			<!\-\- 用于登陆mysql的密码 -->
			<property name="password"></property>
			
		</factoryConfig>
		
		<!\-\- ObjectPool实现类 -->
		<poolConfig class="com.meidusa.amoeba.net.poolable.PoolableObjectPool">
			<property name="maxActive">200</property>
			<property name="maxIdle">200</property>
			<property name="minIdle">10</property>
			<property name="minEvictableIdleTimeMillis">600000</property>
			<property name="timeBetweenEvictionRunsMillis">600000</property>
			<property name="testOnBorrow">true</property>
			<property name="testWhileIdle">true</property>
		</poolConfig>
	</dbServer>
	<dbServer name="server2">
		
		<!\-\- PoolableObjectFactory实现类 -->
		<factoryConfig class="com.meidusa.amoeba.mysql.net.MysqlServerConnectionFactory">
			<property name="manager">defaultManager</property>
			
			<!\-\- 真实mysql数据库端口 -->
			<property name="port">3306</property>
			
			<!\-\- 真实mysql数据库IP -->
			<property name="ipAddress">10.20.147.111</property>
			<property name="schema">amoeba_study</property>
			
			<!\-\- 用于登陆mysql的用户名 -->
			<property name="user">root</property>
			
			<!\-\- 用于登陆mysql的密码 -->
			<property name="password"></property>
			
		</factoryConfig>
		
		<!\-\- ObjectPool实现类 -->
		<poolConfig class="com.meidusa.amoeba.net.poolable.PoolableObjectPool">
			<property name="maxActive">200</property>
			<property name="maxIdle">200</property>
			<property name="minIdle">10</property>
			<property name="minEvictableIdleTimeMillis">600000</property>
			<property name="timeBetweenEvictionRunsMillis">600000</property>
			<property name="testOnBorrow">true</property>
			<property name="testWhileIdle">true</property>
		</poolConfig>
	</dbServer>		
</dbServerList>

以上是proxy与后端各mysql数据库服务器配置信息，具体配置见注释很明白了 最后配置读写分离策略：

<queryRouter class="com.meidusa.amoeba.mysql.parser.MysqlQueryRouter">
	<property name="LRUMapSize">1500</property>
	<property name="defaultPool">server1</property>
	<property name="writePool">server1</property>
	<property name="readPool">server2</property>
	<property name="needParse">true</property>
</queryRouter>

从以上配置不然发现，写操作路由到server1（master），读操作路由到server2（slave） 3）启动amoeba 在命令行里运行D:/openSource/amoeba-mysql-1.2.0-GA/amoeba.bat即可： log4j:WARN log4j config load completed from file:D:/openSource/amoeba-mysql-1.2.0-GA/conf/log4j.xml log4j:WARN ip access config load completed from file:D:/openSource/amoeba-mysql-1.2.0-GA/conf/access_list.conf 2010-07-03 09:55:33,821 INFO  net.ServerableConnectionManager - Server listening on 0.0.0.0/0.0.0.0:8066. **三.client端调用与测试** 1）编写client调用程序 具体程序细节就不详述了，只是一个最普通的基于mysql driver的jdbc的数据库操作程序 2）配置数据库连接 本client基于c3p0，具体数据源配置如下：

<bean id="dataSource" class="com.mchange.v2.c3p0.ComboPooledDataSource"
	destroy-method="close">
	<property name="driverClass" value="com.mysql.jdbc.Driver" />
	<property name="jdbcUrl" value="jdbc:mysql://localhost:8066/amoeba_study" />
	<property name="user" value="root" />
	<property name="password" value="root" />
	<property name="minPoolSize" value="1" />
	<property name="maxPoolSize" value="1" />
	<property name="maxIdleTime" value="1800" />
	<property name="acquireIncrement" value="1" />
	<property name="maxStatements" value="0" />
	<property name="initialPoolSize" value="1" />
	<property name="idleConnectionTestPeriod" value="1800" />
	<property name="acquireRetryAttempts" value="6" />
	<property name="acquireRetryDelay" value="1000" />
	<property name="breakAfterAcquireFailure" value="false" />
	<property name="testConnectionOnCheckout" value="true" />
	<property name="testConnectionOnCheckin" value="false" />
</bean>

值得注意是，client端只需连到proxy，与实际的数据库没有任何关系，因此jdbcUrl、user、password配置都对应于amoeba暴露出来的配置信息 3）调用与测试 首先插入一条数据：insert into zone\_by\_id(id,name) values(20003,'name\_20003') 通过查看master机上的日志/var/lib/mysql/mysql\_log.log： 100703 11:58:42       1 Query       set names latin1 1 Query       SET NAMES latin1 1 Query       SET character\_set\_results = NULL 1 Query       SHOW VARIABLES 1 Query       SHOW COLLATION 1 Query       SET autocommit=1 1 Query       SET sql\_mode='STRICT\_TRANS\_TABLES' 1 Query       SHOW VARIABLES LIKE 'tx\_isolation' 1 Query       SHOW FULL TABLES FROM \`amoeba\_study\` LIKE 'PROBABLYNOT' 1 Prepare     \[1\] insert into zone\_by\_id(id,name) values(?,?) 1 Prepare     \[2\] insert into zone\_by\_id(id,name) values(?,?) 1 Execute     \[2\] insert into zone\_by\_id(id,name) values(20003,'name\_20003') 得知写操作发生在master机上 通过查看slave机上的日志/var/lib/mysql/mysql\_log.log： 100703 11:58:42       2 Query       insert into zone\_by\_id(id,name) values(20003,'name\_20003') 得知slave同步执行了这条语句 然后查一条数据：select t.name from zone\_by\_id t where t.id = 20003 通过查看slave机上的日志/var/lib/mysql/mysql\_log.log： 100703 12:02:00      33 Query       set names latin1 33 Prepare     \[1\] select t.name from zone\_by\_id t where t.id = ? 33 Prepare     \[2\] select t.name from zone\_by\_id t where t.id = ? 33 Execute     \[2\] select t.name from zone\_by\_id t where t.id = 20003 得知读操作发生在slave机上 并且通过查看slave机上的日志/var/lib/mysql/mysql\_log.log发现这条语句没在master上执行   通过以上验证得知简单的master-slave搭建和实战得以生效。   摘自原文：http://blog.csdn.net/cutesource/article/details/5710645