---
title: 服务器端验证 Apple Game Center GKLocalPlayer 签名（PHP描述）
url: 278.html
id: 278
categories:
  - PHP
date: 2016-09-05 12:09:20
tags:
---

#### 前提

苹果 Game Center 登录的时候，需要到自身的服务端中去验证用户的有效性。具体可以查看 Apple 的说明文档： [https://developer.apple.com/library/mac/documentation/GameKit/Reference/GKLocalPlayer\_Ref/index.html#//apple\_ref/occ/instm/GKLocalPlayer/generateIdentityVerificationSignatureWithCompletionHandler](https://developer.apple.com/library/mac/documentation/GameKit/Reference/GKLocalPlayer_Ref/index.html#//apple_ref/occ/instm/GKLocalPlayer/generateIdentityVerificationSignatureWithCompletionHandler) 重点在于整个验证的描述： [![97C40134-873E-4A9E-9986-F4A66EF1E2C0](/images/2016/09/97C40134-873E-4A9E-9986-F4A66EF1E2C0.png)](/images/2016/09/97C40134-873E-4A9E-9986-F4A66EF1E2C0.png) 由此可见，服务端需要接受来自客户端的参数有以下 `6` 个：

1.  publicKeyURL
2.  playerID
3.  bundleID
4.  timestamp
5.  signature
6.  salt

<!--more-->

#### 参数预处理

其中，`timestamp` 需要注意，这不是字面意义上的 时间戳 ，实际上是 `Big-Endian UInt-64 format` 表示的时间值。需要再PHP中识别，我们需要作一下处理：

        function unpackTimestamp($timestamp)
        {
            $highMap = 0xffffffff00000000;
            $lowMap = 0x00000000ffffffff;
            $higher = ($timestamp & $highMap) >> 32;
            $lower = $timestamp & $lowMap;
            return pack('NN', $higher, $lower);
        }
    

还有，`publicKeyURL` 是一个 `.cer` 结尾的签名文件，我们需要将佢做一定的格式编码，可以这样处理：

        function getPublicKey($url)
        {
            $content = file_get_content($url); //建议使用 curl 来处理，这里为了演示简单直接使用 file_get_content 
            return '-----BEGIN CERTIFICATE-----' . PHP_EOL .
                    chunk_split(base64_encode($content), 64, PHP_EOL) .
                    '-----END CERTIFICATE-----'. PHP_EOL;
        }
    

最后，`signature` 和 `salt` 均需进行 `base64_decode` 。

#### 验证签名

    function checkSignature($publicKeyURL, $playerID, $bundleID, $timestamp, $signature, $salt)
    {
            $publicKey = getPublicKey($publicKeyURL);
                    $timestamp = unpackTimestamp($timestamp);
                    $signature = base64_decode($signature);
                    $salt = base64_decode($salt);
    
                    $data = $playerID.$bundleID.$timestamp.$salt;
    
                    $result = openssl_verify($data, $signature, openssl_pkey_get_public($publicKey), OPENSSL_ALGO_SHA256);
            openssl_free_key($publicKey);
    
                    if ( 1 === $result) {
                          return true;
                    }
                    return false;
    }
    

当 `checkSignature` 函数返回 `true` 时，表示签名验证通过。

> 好了，大体的验证流程就是这样了。当验证通过的时候，就可以使用 `playerID` 来在自己的用户系统中生成唯一的用户ID了。 Have fun.