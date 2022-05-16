<?php

/**
 * Created by IntelliJ IDEA.
 * User: maxsky
 * Date: 2022/2/28
 * Time: 2:23 PM
 */

namespace UMeng\Services\Login;

use Aliyun\ApiGateway\Http\HttpClient;
use GuzzleHttp\Exception\{BadResponseException, ClientException};
use Throwable;
use UMeng\Utils\UMengServiceException;
use UMeng\Utils\UMengUtil;

class OneKeyLogin {

    private static $instance = null;

    private $cloud_app_key;
    private $cloud_app_secret;

    private $platform_key;
    private $key_path;

    public function __construct(string $cloud_app_key, string $cloud_app_secret) {
        $this->cloud_app_key = $cloud_app_key;
        $this->cloud_app_secret = $cloud_app_secret;
    }

    /**
     * @param string $cloud_app_key
     * @param string $cloud_app_secret
     *
     * @return OneKeyLogin
     */
    public static function getInstance(string $cloud_app_key, string $cloud_app_secret): OneKeyLogin {
        if (!self::$instance) {
            self::$instance = new self($cloud_app_key, $cloud_app_secret);
        }

        return self::$instance;
    }

    /**
     * @param string $platform_key
     *
     * @return OneKeyLogin
     */
    public function setPlatformKey(string $platform_key): OneKeyLogin {
        $this->platform_key = $platform_key;

        return $this;
    }

    /**
     * @param string $key_path
     *
     * @return OneKeyLogin
     */
    public function setKeyPath(string $key_path): OneKeyLogin {
        $this->key_path = $key_path;

        return $this;
    }

    /**
     * @param string $platform App 平台，Android/iOS
     * @param string $token
     * @param string $verify_id
     *
     * @return string
     * @throws UMengServiceException
     */
    public function oneKeyLogin(string $platform, string $token, string $verify_id = ''): string {
        if (!$this->platform_key) {
            throw new UMengServiceException('平台 Key 不存在');
        }

        if (!$this->key_path) {
            throw new UMengServiceException('请先设置私钥所在路径');
        }

        $platform = strtoupper($platform);

        try {
            $response = HttpClient::setKey($this->cloud_app_key, $this->cloud_app_secret)
                ->execute('POST', UMENG_ONE_KEY_LOGIN, [
                    'headers' => [
                        'accept' => 'application/json',
                        'x-ca-stage' => 'RELEASE',
                        'x-ca-version' => 1
                    ],
                    'query' => [
                        'appkey' => $this->platform_key,
                        'verifyId' => $verify_id
                    ],
                    'body' => [
                        'json' => ['token' => $token]
                    ]
                ]);
        } catch (Throwable $e) {
            if ($e instanceof ClientException) {
                // show error message from AliCloud when request failed
                throw new UMengServiceException($e->getResponse()->getHeader('X-Ca-Error-Message')[0]);
            } elseif ($e instanceof BadResponseException) {
                throw new UMengServiceException($e->getMessage(), (int)$e->getCode(), $e);
            }

            throw new UMengServiceException($e->getMessage(), (int)$e->getCode(), $e);
        }

        $response = json_decode($response, true);

        if ($response['success'] ?? null) {
            return UMengUtil::decrypt($platform,
                $this->key_path, $response['data']['aesEncryptKey'], $response['data']['mobile']);
        }

        throw new UMengServiceException('获取手机号码失败');
    }
}
