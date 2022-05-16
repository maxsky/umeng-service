<?php

/**
 * Created by IntelliJ IDEA.
 * User: maxsky
 * Date: 2022/5/16
 * Time: 19:21
 */

namespace Tests;

use PHPUnit\Framework\TestCase;
use UMeng\Services\Login\OneKeyLogin;
use UMeng\Utils\UMengServiceException;

class TestLogin extends TestCase {

    public function testLogin() {
        $platform = 'Android';
        $token = 'Token';

        $appKey = 'test';
        $appSecret = 'test';

        $platformKey = 'test';

        $keyPath = 'path/app/oklogin';

        try {
            $mobileNumber = OneKeyLogin::getInstance($appKey, $appSecret)
                ->setPlatformKey($platformKey)
                ->setKeyPath($keyPath)
                ->oneKeyLogin($platform, $token);

            $this->assertTrue(strlen($mobileNumber) === 11);
        } catch (UMengServiceException $e) {
            print('获取手机号码失败');
        }
    }
}
