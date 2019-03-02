---
title: 谷歌支付 Google Play In-app Billing 服务端验证（PHP描述）
url: 286.html
id: 286
categories:
  - PHP
date: 2015-08-07 14:19:55
tags:
---

#### 前提

好了，二话不说，先扔一个链接： [https://developer.android.com/google/play/billing/billing_reference.html#getBuyIntent](https://developer.android.com/google/play/billing/billing_reference.html#getBuyIntent) 以上是谷歌支付 Google Play In-app Billing 的服务器验证说明文档。 支付的时候，Android 客户端 通过调用 `getBuyIntent()` 方法获得以下 3个 参数：

1.  RESPONSE_CODE
2.  INAPP_PURCHASE_DATA
3.  INAPP_DATA_SIGNATURE

<!--more-->

> 详细说明：

[![D300A9F1-0AA1-462F-BCDA-F5E49480C66B](/images/2016/09/D300A9F1-0AA1-462F-BCDA-F5E49480C66B.png)](/images/2016/09/D300A9F1-0AA1-462F-BCDA-F5E49480C66B.png) 其中，`INAPP_PURCHASE_DATA` 是一段 json 字符串，包含订单的信息，具体内容如下： [![C989DAD1-EC02-4C59-8235-78D3ACFB09A4](/images/2016/09/C989DAD1-EC02-4C59-8235-78D3ACFB09A4.png)](/images/2016/09/C989DAD1-EC02-4C59-8235-78D3ACFB09A4.png) 有几个字段我们必须关注的：

1.  developerPayload : 这个是客户端的透传参数，建议放置自身的交易流水号（自有服务器的订单号）
2.  purchaseState ：支付的结果， 0 (支付了)， 1 (取消)， 2 (退款)
3.  productId ：商品ID
4.  orderId : 谷歌的订单ID （Ps: 当处于沙箱环境的时候，没有这个字段）

#### 验证

作为服务端，需要接收 客户端 将 `INAPP_PURCHASE_DATA` 和 `INAPP_DATA_SIGNATURE` 参数。 以下是 PHP 服务端的实例：

```php
<?php

function checkGooglePlayBilling() {
    $inappPurchaseData = isset($_REQUEST['INAPP_PURCHASE_DATA']) ? $_REQUEST['INAPP_PURCHASE_DATA'] : null ;
    $inappDataSignature =isset($_REQUEST['INAPP_DATA_SIGNATURE']) ? $_REQUEST['INAPP_DATA_SIGNATURE'] : null ;
    $googlePublicKey = 'Google Play Developer Console 中此应用的许可密钥';

    $publicKey = "-----BEGIN PUBLIC KEY-----". PHP_EOL .
                                                        chunk_split($google_public_key, 64, PHP_EOL) . 
                                                        "-----END PUBLIC KEY-----";

    $publicKeyHandle = openssl_get_publickey($publicKey);
    $result = openssl_verify($inappPurchaseData, base64_decode($inappDataSignature), $publicKeyHandle, OPENSSL_ALGO_SHA1);
    if (1 !== $result) {
            retuan false;
    }

    $data = json_decode(inappPurchaseData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    //判断订单号，订单情况，自行解决
    if ($data['developerPayload'] != 'xxxx') {
        return false;
    }

    //判断订单完成情况
    if ($data['purchaseState'] != 0) {
        return false;
    }

    return true;
}
```

#### 进阶

当然，如果需要进一步的验证的话，可以通过 `Google Play Developer API` 来验证订单的真实情况。 以下是 `Google Play Developer API` 验证订单接口的文档： [https://developers.google.com/android-publisher/api-ref/purchases/products/get](https://developers.google.com/android-publisher/api-ref/purchases/products/get) 从安全角度考虑，可以考虑接入这一部分。不过，使用这些 API 的话，还需要 服务端 获取 Oauth2.0 的 Server to Server 的 Access token, 这部分暂时没有深入研究，但是其实也不难，具体的文档如下： [https://developers.google.com/identity/protocols/OAuth2ServiceAccount#authorizingrequests](https://developers.google.com/identity/protocols/OAuth2ServiceAccount#authorizingrequests) 通过 生成 JWT 来获取请求 access token。 日后补充 这部分。 Have fun!!!