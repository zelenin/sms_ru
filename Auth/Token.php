<?php
/**
 * Token.php
 * @author Revin Roman
 * @link https://rmrevin.com
 */

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Api;

/**
 * Class Token
 * @package Zelenin\SmsRu\Auth
 */
class Token
{

    /**
     * @var string адрес файла, в котором будет закэширован токен
     */
    public static $storageFile = __DIR__ . '/../runtime/token.bin';

    /**
     * @var integer кол-во секунд, после которого токен будет обновлён (если указать 0 или null, то токен не будет кэшироваться)
     */
    public static $threshold = 540;

    /**
     * @param Api $Api
     * @return string
     */
    public static function get(Api $Api)
    {
        $file = static::$storageFile;

        $needUpdate = true;

        if (!empty(static::$threshold) && file_exists($file)) {
            $time = filemtime($file);

            $needUpdate = $time !== false && (time() - $time) >= static::$threshold;
        }

        if ($needUpdate) {
            $token = $Api
                ->getClient()
                ->request('auth/get_token');

            if (!empty(static::$threshold)) {
                file_put_contents($file, $token);
            }
        } else {
            $token = file_get_contents($file);
        }

        return $token;
    }
}