<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1')) || clientInSameSubnet($_SERVER['REMOTE_ADDR'], $_SERVER['SERVER_ADDR']) || php_sapi_name() === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__.'/../app/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);


/**
 * @param $clientIp - The IP of the client
 * @param $serverIp - The IP of the server
 * @return true|false
 */
function clientInSameSubnet($clientIp, $serverIp) {
 
    // Extract broadcast and netmask from ifconfig
    if (!($p = popen("/sbin/ifconfig","r")))
        return false;
 
    $out = "";
    while(!feof($p)) {
        $out .= fread($p, 1024);
    }
    pclose($p);
 
    // This is because the php.net comment function does not
    // allow long lines.
 
    $match  = "/^.*".$serverIp.".*Bcast:(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*Mask:(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})$/im";
 
    if (!preg_match($match, $out, $regs)) {
        return false;
    }
 
    $broadcast = ip2long($regs[1]);
    $subnetMask = ip2long($regs[2]);
    $ipAddress = ip2long($clientIp);
    $subnet = $broadcast & $subnetMask;
 
    return (($ipAddress & $subnetMask) == ($subnet & $subnetMask));
 
}