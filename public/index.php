<?php
$loader = require(__DIR__ . '/../vendor/autoload.php');

use Mxc\Parsec\Rpc\Rpc;

$server = new Zend\Json\Server\Server();
$server->setClass(Rpc::class);

// SMD request
if ('GET' === $_SERVER['REQUEST_METHOD']) {
    // Indicate the URL endpoint, and the JSON-RPC version used:
    $server->setTarget('/json-index')
           ->setEnvelope(Zend\Json\Server\Smd::ENV_JSONRPC_2);

    // Grab the SMD
    $smd = $server->getServiceMap();

    // Return the SMD to the client
    header('Content-Type: application/json');
    echo $smd;
    return;
}
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

// Normal request
$server->handle();
