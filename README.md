# ssoSDK

mnssoSDK

## 安装

```shell
$ composer require wangshukai/mn-sso-php-sdk
```

## 使用示例 

```php
<?php
#使用分配的key和appSecret 网关地址填写测试或者线上地址  参数相关请参考文档
$config =['appKey' => '', 'appSecret' => '', 'gatewayUrl' => "","jumpUrl"=>''];
$sso = new Sso($config);
$token = $sso->token;//用户相关
$data = [
            'grant_type' => 'authorization_code',
            'code' => 'xxx'
             ];
$res=$token->getToken($data);//token操作
```

##   

## License

MIT