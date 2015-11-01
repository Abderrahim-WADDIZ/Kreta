<?php

/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

date_default_timezone_set('Europe/Madrid');

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../app/autoload.php';

Debug::enable();

require_once __DIR__ . '/../app/AppKernel.php';

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '192.168.10.1', '::1'])
) {
    header('HTTP/1.0 403 Forbidden');
    exit(
        'You are not allowed to access this file from '
        . @$_SERVER['REMOTE_ADDR']
        . '. Check '
        . basename(__FILE__)
        . ' for more information.'
    );
}

$kernel = new AppKernel('test', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
