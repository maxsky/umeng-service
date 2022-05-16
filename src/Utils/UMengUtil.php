<?php

/**
 * Created by IntelliJ IDEA.
 * User: maxsky
 * Date: 2022/3/7
 * Time: 5:01 PM
 */

namespace UMeng\Utils;

class UMengUtil {

    /**
     * 解密
     *
     * @param string $platform
     * @param string $key_path iOS/Android Key 存储路径，命名示例：/path/key/ios_pri_key.pem；/path/key/android_pri_key.pem
     * @param string $encrypted_aes_key
     * @param string $encrypted_content
     *
     * @return string
     */
    public static function decrypt(string $platform,
                                   string $key_path,
                                   string $encrypted_aes_key,
                                   string $encrypted_content): string {
        $platform = strtolower($platform);

        $key = file_get_contents("$key_path/{$platform}_pri_key.pem");

        $aesKey = Crypt::RSA($key)->decrypt(base64_decode($encrypted_aes_key));

        return Crypt::AES($aesKey, 128, null, 'ecb')->decrypt(base64_decode($encrypted_content));
    }
}
