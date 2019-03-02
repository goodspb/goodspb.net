---
title: php5扩展迁移php7扩展
url: 411.html
id: 411
categories:
  - PHP扩展开发
date: 2018-04-01 18:31:42
tags:
---

记录一下PHP5扩展升级到PHP7扩展的一些点，覆盖十分不全，只是自己在工作中的遇到项目的一些改动。

<!--more-->

### 1,  zend\_hash\_get\_current\_data_ex

php5  定义：

```c
ZEND_API int zend_hash_get_current_data_ex(HashTable *ht, void **pData, HashPosition *pos)
```

php7  定义：

```c
ZEND_API zval* ZEND_FASTCALL zend_hash_get_current_data_ex(HashTable *ht, HashPosition *pos)
```

也就是说，php5的时候，是传递指针获取data的，但是到php7的时候，已经变成通过直接返回获取的data了。

#### 修改方法：

```c
zval **tmp;
zend_hash_get_current_data_ex(ht, tmp, pos)
```

变成：

```c
zval *tmp;
tmp = zend_hash_get_current_data_ex(ht, pos)
```

### 2, zend\_hash\_get\_current\_key_ex

php5  定义：

```c
ZEND_API int zend_hash_get_current_key_ex(const HashTable *ht, char **str_index, uint *str_length, ulong *num_index, zend_bool duplicate, HashPosition *pos)
```

php7 定义：

```c
ZEND_API int ZEND_FASTCALL zend_hash_get_current_key_ex(const HashTable *ht, zend_string **str_index, zend_ulong *num_index, HashPosition *pos)
```

这里的最大出入是php7当中将zend\_string 替换  char ， zend\_ulong 替换 ulong 了，所以需要注意定义的时候需要更换。 还需要注意的是： A, 去除参数 `zend_bool duplicate` ，表示是否去除最后的字符。 B, 去除参数 `uint *str_length` ，因为 zend_string 里面已经包含字符的长度。  

### 3, zend_uint

php7已经将这个宏去掉了，但是其实php5当中的定义只是简单的：

```c
typedef unsigned int zend_uint;
```

所以，如果你的扩展用到了，可以直接替换为 `uint`  

### 4，zend\_hash\_apply、zend\_hash\_apply\_with\_argument、zend\_hash\_apply\_with\_arguments

这三个函数是将一个 HashTable 迭代的方法，再看他们后缀的不同，大家也是可以知道这3个函数其实性质一样，就是传不传参数的差别而已。还是直接用例子来说明：

#### php5:

```c
zend_hash_apply(EG(function_table), (apply_func_t) zend_cleanup_function_data_full TSRMLS_CC);
```

其中，zend\_cleanup\_function\_data\_full 是一个回调的函数，函数定义：

```c
ZEND_API int zend_cleanup_function_data_full(zend_function *function TSRMLS_DC)
{
	if (function->type == ZEND_USER_FUNCTION) {
		zend_cleanup_op_array_data((zend_op_array *) function);
	}
	return 0;
}
```

大家可以看到，这里传入的参数是 \`zend_function *function\` ，也就是说 php5 的HashTable是直接储存实体的。再看看php7的。

#### php7:

```c
zend_hash_apply(ht, (apply_func_t) is_not_internal_function);
```

然后再看回调函数 \`is\_not\_internal_function\` :

```c
static int is_not_internal_function(zval *zv)
{
	zend_function *function = Z_PTR_P(zv);
	return(function->type != ZEND_INTERNAL_FUNCTION);
}
```

这个时候的回调函数传入的参数是一个 zval 的指针，假如需要获得内容，我们需要使用宏 ：Z\_PTR\_P 。   当然，还有基本的，可以直接查看官网： [https://wiki.php.net/phpng-upgrading](https://wiki.php.net/phpng-upgrading)