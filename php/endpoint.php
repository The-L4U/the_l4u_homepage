<?php
/**
 * Source (adapted) from Corey Maynard (accessed 2017) from:
 * Credit: http://coreymaynard.com/blog/creating-a-restful-api-with-php/
 */


use model\mensaAPI;

include_once 'api.php';
include_once 'myapi.php';

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new mensaAPI($_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    print_r(Array('error' => $e->getMessage()));
}
