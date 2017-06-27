<?php
/**
 * Source (adapted) from Corey Maynard (accessed 2017) from:
 * Credit: http://coreymaynard.com/blog/creating-a-restful-api-with-php/
 */

namespace model;

include_once 'api.php';

class mensaAPI extends API {

    protected $User;

    public function __construct($origin) {
        parent::__construct();
    }
}