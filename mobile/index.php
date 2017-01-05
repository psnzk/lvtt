<?php
define('APP_DEBUG', true);

/**
 * ECTouch E-Commerce Project
 *
 * @package  ECTouch
 * @author   Carson <docxcn@gmail.com>
 */

define('IN_ECTOUCH', true);


/*
|--------------------------------------------------------------------------
| Load Application Configuration
|--------------------------------------------------------------------------
*/

require __DIR__ . '/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__ . '/bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

ECTouch::start();
