---
title: 如何通过 API 获取 实时的货币汇率
url: 269.html
id: 269
categories:
  - PHP
date: 2016-09-01 19:50:16
tags:
---

在做支付的系统时，经常会遇到这样的问题（例如：Google Play 支付），怎么将其他国家的货币转换成人民币呢？那就肯定涉及如何获取实时的汇率进行兑换了。

<!--more-->

#### 前提

首先，我们需要知道各个国家的货币的符号，按照 ISO 4217 标准来获取： [ISO 4217](http://www.iso.org/iso/home/standards/currency_codes.htm) 其次，我们要根据各个国家的货币代号来获取实时的汇率，这里，我们使用 Yahoo 的汇率API： [说明页 https://developer.yahoo.com/yql/console/?q=show%20tables&env=store://datatables.org/alltableswithkeys#h=select+*+from+yahoo.finance.xchange+where+pair+in+(%22CNYUSD%22)](https://developer.yahoo.com/yql/console/?q=show%20tables&env=store://datatables.org/alltableswithkeys#h=select+*+from+yahoo.finance.xchange+where+pair+in+(%22CNYUSD%22))

> https://query.yahooapis.com/v1/public/yql?q=SELECT%20*%20FROM%20yahoo.finance.xchange%20WHERE%20pair%20IN%20(\[CODES\])&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys

其中 \[CODES\] 部分是可以替换货币代码的，假如：你想获取 `美元` 兑 `人民币` ， 就可以写： `"USDCNY"` :

> https://query.yahooapis.com/v1/public/yql?q=SELECT%20*%20FROM%20yahoo.finance.xchange%20WHERE%20pair%20IN%20("USDCNY")&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys

返回json:

    {
                query: {
                            count: 1,
                            created: "2016-09-01T11:43:17Z",
                            lang: "zh-CN",
                            results: {
                                        rate: {
                                                        id: "USDCNY",
                                                        Name: "USD/CNY",
                                                        Rate: "6.6795",
                                                        Date: "9/1/2016",
                                                        Time: "5:41am",
                                                        Ask: "6.6808",
                                                        Bid: "6.6795"
                                        }
                            }
                }
    }
    

其中, Rate:"6.6795" 就是 1美元 可以兑换的人民币了。

#### 进阶

但是，做应用的时候，我们总不可能一个个货币这样获取吧？ 通常的做法是，获取所有国家的汇率，然后缓存在本地，一段时间才进行更新，这个时候，我们就要这样拼凑 URL ：

    https://query.yahooapis.com/v1/public/yql?q=SELECT%20*%20FROM%20yahoo.finance.xchange%20WHERE%20pair%20IN%20(%22AEDCNY%22,%20%22AMDCNY%22,%20%22ANGCNY%22,%20%22ARSCNY%22,%20%22AUDCNY%22,%20%22AWGCNY%22,%20%22AZNCNY%22,%20%22BAMCNY%22,%20%22BBDCNY%22,%20%22BDTCNY%22,%20%22BGNCNY%22,%20%22BHDCNY%22,%20%22BIFCNY%22,%20%22BMDCNY%22,%20%22BNDCNY%22,%20%22BOBCNY%22,%20%22BOVCNY%22,%20%22BRLCNY%22,%20%22BSDCNY%22,%20%22BTNCNY%22,%20%22BWPCNY%22,%20%22BYNCNY%22,%20%22BYRCNY%22,%20%22BZDCNY%22,%20%22CADCNY%22,%20%22CDFCNY%22,%20%22CHECNY%22,%20%22CHFCNY%22,%20%22CHWCNY%22,%20%22CLFCNY%22,%20%22CLPCNY%22,%20%22CNYCNY%22,%20%22COPCNY%22,%20%22COUCNY%22,%20%22CRCCNY%22,%20%22CUCCNY%22,%20%22CUPCNY%22,%20%22CVECNY%22,%20%22CZKCNY%22,%20%22DJFCNY%22,%20%22DKKCNY%22,%20%22DOPCNY%22,%20%22EGPCNY%22,%20%22ERNCNY%22,%20%22ETBCNY%22,%20%22EURCNY%22,%20%22FJDCNY%22,%20%22FKPCNY%22,%20%22GBPCNY%22,%20%22GELCNY%22,%20%22GHSCNY%22,%20%22GIPCNY%22,%20%22GMDCNY%22,%20%22GNFCNY%22,%20%22GTQCNY%22,%20%22GYDCNY%22,%20%22HKDCNY%22,%20%22HNLCNY%22,%20%22HRKCNY%22,%20%22HTGCNY%22,%20%22HUFCNY%22,%20%22IDRCNY%22,%20%22ILSCNY%22,%20%22INRCNY%22,%20%22IQDCNY%22,%20%22IRRCNY%22,%20%22ISKCNY%22,%20%22JMDCNY%22,%20%22JODCNY%22,%20%22JPYCNY%22,%20%22KESCNY%22,%20%22KGSCNY%22,%20%22KHRCNY%22,%20%22KMFCNY%22,%20%22KPWCNY%22,%20%22KRWCNY%22,%20%22KWDCNY%22,%20%22KYDCNY%22,%20%22KZTCNY%22,%20%22LAKCNY%22,%20%22LBPCNY%22,%20%22LKRCNY%22,%20%22LRDCNY%22,%20%22LSLCNY%22,%20%22LYDCNY%22,%20%22MADCNY%22,%20%22MDLCNY%22,%20%22MGACNY%22,%20%22MKDCNY%22,%20%22MMKCNY%22,%20%22MNTCNY%22,%20%22MOPCNY%22,%20%22MROCNY%22,%20%22MURCNY%22,%20%22MVRCNY%22,%20%22MWKCNY%22,%20%22MXNCNY%22,%20%22MXVCNY%22,%20%22MYRCNY%22,%20%22MZNCNY%22,%20%22NADCNY%22,%20%22NGNCNY%22,%20%22NIOCNY%22,%20%22NOKCNY%22,%20%22NPRCNY%22,%20%22NZDCNY%22,%20%22OMRCNY%22,%20%22PABCNY%22,%20%22PENCNY%22,%20%22PGKCNY%22,%20%22PHPCNY%22,%20%22PKRCNY%22,%20%22PLNCNY%22,%20%22PYGCNY%22,%20%22QARCNY%22,%20%22RONCNY%22,%20%22RSDCNY%22,%20%22RUBCNY%22,%20%22RWFCNY%22,%20%22SARCNY%22,%20%22SBDCNY%22,%20%22SCRCNY%22,%20%22SDGCNY%22,%20%22SEKCNY%22,%20%22SGDCNY%22,%20%22SHPCNY%22,%20%22SLLCNY%22,%20%22SOSCNY%22,%20%22SRDCNY%22,%20%22SSPCNY%22,%20%22STDCNY%22,%20%22SVCCNY%22,%20%22SYPCNY%22,%20%22SZLCNY%22,%20%22THBCNY%22,%20%22TJSCNY%22,%20%22TMTCNY%22,%20%22TNDCNY%22,%20%22TOPCNY%22,%20%22TRYCNY%22,%20%22TTDCNY%22,%20%22TWDCNY%22,%20%22TZSCNY%22,%20%22UAHCNY%22,%20%22UGXCNY%22,%20%22USDCNY%22,%20%22USNCNY%22,%20%22UYICNY%22,%20%22UYUCNY%22,%20%22UZSCNY%22,%20%22VEFCNY%22,%20%22VNDCNY%22,%20%22VUVCNY%22,%20%22WSTCNY%22,%20%22XAFCNY%22,%20%22XCDCNY%22,%20%22XDRCNY%22,%20%22XOFCNY%22,%20%22XPFCNY%22,%20%22XSUCNY%22,%20%22XUACNY%22,%20%22YERCNY%22,%20%22ZARCNY%22,%20%22ZMWCNY%22,%20%22ZWLCNY%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys
    

这里包含了164个国家兑换 人民币 的汇率。 返回的 json 大概是：

    {
    query: {
    count: 165,
    created: "2016-09-01T11:47:42Z",
    lang: "zh-CN",
    results: {
    rate: [
    {
    id: "AEDCNY",
    Name: "AED/CNY",
    Rate: "1.8187",
    Date: "9/1/2016",
    Time: "12:47pm",
    Ask: "1.8189",
    Bid: "1.8187"
    },
    {
    id: "AMDCNY",
    Name: "AMD/CNY",
    Rate: "0.0141",
    Date: "8/31/2016",
    Time: "3:05pm",
    Ask: "0.0141",
    Bid: "0.0141"
    },
    {
    id: "ANGCNY",
    Name: "ANG/CNY",
    Rate: "3.7740",
    Date: "9/1/2016",
    Time: "4:37am",
    Ask: "3.7742",
    Bid: "3.7740"
    },
    {
    id: "ARSCNY",
    Name: "ARS/CNY",
    Rate: "0.4452",
    Date: "8/31/2016",
    Time: "9:02pm",
    Ask: "0.4455",
    Bid: "0.4452"
    },
                                 ........
                     }
            }
    }