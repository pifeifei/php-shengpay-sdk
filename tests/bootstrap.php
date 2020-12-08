<?php

use Pff\Client\AlibabaCloud;
use Pff\Client\Exception\ClientException;
use Symfony\Component\Dotenv\Dotenv;

/**
 * TODO : 修改/完善测试文件
 *
 * Test Entry File for sdk client
 * This file will be automatically loaded.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
if (!ini_get('date.timezone')) {
    date_default_timezone_set('GMT');
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| We'll simply require it into the script here so that we don't have to
| worry about manual loading any of our classes later on.
|
*/

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$env = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
if (is_readable($env)) {
    (new Dotenv())->load($env);
    try {
        AlibabaCloud::load($env);
        $default = AlibabaCloud::getDefaultClient()->getCredential();
        putenv('ACCESS_KEY_ID=' . $default->getAccessKeyId());
        putenv('ACCESS_KEY_SECRET=' . $default->getAccessKeySecret());
    } catch (ClientException $e) {
        echo "<error>error: {$e->getMessage()}(" . __FILE__ . ")</error>";
        exit(-1);
    }
}
