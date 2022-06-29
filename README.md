# UMeng Service 友盟服务

[![996.icu](https://img.shields.io/badge/link-996.icu-red.svg)](https://996.icu)

## 支持服务

* 一键登录
* 推送

## 安装

```shell
composer require maxsky/umeng-service
```

## 调用

### 一键登录

**证书文件（.pem）命名格式为：android_pri_key.pem 和 ios_pri_key.pem，需放置到同一目录**

```php
// 示例 Laravel/Lumen 框架获取 POST 请求中 platform 参数
$platform = $request->post('platform', 'Android'); // 请求中获取所属平台 Android/iOS

// 一键登录 Token，由 App 方传入
$token = $request->post('token');

// 转成大写
$platform = strtoupper($platform);

// 获取平台 App Key，Laravel/Lumen 中设置环境配置 UMENG_IOS_APP_KEY=123456 和 UMENG_ANDROID_APP_KEY=654321。供参考
$platformKey = env("UMENG_{$platform}_APP_KEY");

try {
    // 成功时返回手机号码
    $mobileNumber = OneKeyLogin::getInstance(
        // 获取一键登录所需 App Key 及 App Secret
        env('UMENG_ONE_KEY_LOGIN_APP_KEY'), env('UMENG_ONE_KEY_LOGIN_APP_SECRET'))
        ->setPlatformKey($platformKey)
        // 设置证书文件所在位置，需注意命名。此处在 Laravel/Lumen 框架中为：项目目录/storage/app/oklogin
        ->setKeyPath(storage_path('app/oklogin'))
        ->oneKeyLogin($platform, $token);
} catch (UMengServiceException $e) {
    Log::error('一键登录异常，错误消息：' . $e->getMessage());

    throw new Exception('获取手机号码失败');
}
```

### 推送

推送参数可参考文档：[友盟 - 消息推送 - API 集成文档](https://developer.umeng.com/docs/67966/detail/68343)

```php
try {
    $broadcast = new AndroidBroadcast();

    $broadcast->setAppMasterSecret('MasterSecret');
    $broadcast->setPredefinedKeyValue('appkey', 'AppKey');
    $broadcast->setPredefinedKeyValue('timestamp', time());
    $broadcast->setPredefinedKeyValue('ticker', 'Android broadcast ticker');
    $broadcast->setPredefinedKeyValue('title', '中文的 title');
    $broadcast->setPredefinedKeyValue('text', 'Android broadcast text');
    $broadcast->setPredefinedKeyValue('after_open', 'go_app');

    // Set 'production_mode' to 'false' if it's a test device.
    // For how to register a test device, please see the developer doc.
    $broadcast->setPredefinedKeyValue('production_mode', 'true');

    // [optional] Set extra fields
    $broadcast->setExtraField('test', 'helloworld');

    $result = $broadcast->send();

    if ($result['ret'] === 'SUCCESS') {
        // 成功
    }
} catch (UMengServiceException $e) {
    Log::error('推送异常，错误消息：' . $e->getMessage());

    throw new Exception('推送失败');
}
```

