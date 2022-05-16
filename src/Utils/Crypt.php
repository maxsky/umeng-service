<?php

/**
 * Created by IntelliJ IDEA.
 * User: maxsky
 * Date: 2019-02-27
 * Time: 15:26
 */

namespace UMeng\Utils;

use phpseclib3\Crypt\{AES, PublicKeyLoader, RSA, RSA\PrivateKey};

/**
 * Class Crypt
 *
 * @package App\Common\Utils
 */
class Crypt {

    /**
     * @param string|null $key
     * @param int         $keyLength
     * @param string|null $iv
     * @param string      $mode AES mode，default 'ctr'
     * @param bool        $disable_padding
     *
     * @return AES
     */
    public static function AES(string  $key, int $keyLength = 128,
                               ?string $iv = null, string $mode = 'ctr', bool $disable_padding = false): AES {
        $aes = new AES($mode);

        $aes->setKey($key);
        $aes->setKeyLength($keyLength);

        if ($iv) {
            $aes->setIV($iv);
        }

        if ($disable_padding) {
            $aes->disablePadding();
        }

        return $aes;
    }

    /**
     * @param string      $key
     * @param string|null $password
     * @param int         $padding
     * @param string|null $hash
     * @param string|null $mgf_hash
     *
     * @return RSA
     */
    public static function RSA(string  $key, ?string $password = null, int $padding = RSA::ENCRYPTION_PKCS1,
                               ?string $hash = null, ?string $mgf_hash = null) {
        /** @var PrivateKey $loaded */
        $loaded = PublicKeyLoader::load($key, $password);

        $loaded = $loaded->withPadding($padding);

        if ($hash) {
            $loaded = $loaded->withHash($hash);
        }

        if ($mgf_hash) {
            $loaded = $loaded->withMGFHash($mgf_hash);
        }

        return $loaded;
    }
}
