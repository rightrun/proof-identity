### 安装
```shell
composer require rightrun/proof-identity
```

### 一、 实名认证库
#### 1. 工宣部实名


#### 2. 阿里实名
阿里二要素
https://market.aliyun.com/products/57000002/cmapi029454.html



### 二、实名测试
#### 1. 安装phpunit
```shell


$ curl -LO https://phar.phpunit.de/phpunit-9.6.phar
$ chmod +x phpunit-9.6.phar
$ sudo mv phpunit-9.6.phar /usr/local/bin/phpunit
$ phpunit --version
```

#### 2. 运行单元测试
```shell

#单个测试
#国家游戏防沉迷实名测试
phpunit --bootstrap tests/bootstrap.php tests/Cases/WlcnppaProofTest.php
#国家游戏防沉迷系统账号激活单元测试
phpunit --bootstrap tests/bootstrap.php tests/Cases/WlcnppaActivationTest.php


#阿里 
phpunit --bootstrap tests/bootstrap.php tests/Cases/AliyunProofTest.php

```
