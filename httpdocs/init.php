<?php

require_once('scripts/init.php');

define('CFG_HOST', 'cfg_server_host');
define('CFG_HOST_TOKEN', 'cfg_server_host_token');

if (! isset($_GET[CFG_HOST])) {
    echo 'ERROR: No host set in URL';
    exit;
}

if (! isset($_GET[CFG_HOST_TOKEN])) {
    echo 'ERROR: No token set in URL';
    exit;
}

$cfg_source_host = $_GET[CFG_HOST];
$requesting_host = $_SERVER['HTTP_HOST'];
$token = $_GET[CFG_HOST_TOKEN];

$remote_server = new RemoteConfigerServerConnection(
                            $cfg_source_host,
                            $requesting_host,
                            $token
);
$remote_request = $remote_server->createJsonRequest('confirm_access');
try {
    $response = $remote_request->go();

    if (! $response->success) {
        echo json_encode($response);
        // quit with failure JSON
        exit;
    }
} catch (Httpful\Exception\ConnectionErrorException $e) {
    echo json_encode(array(
        'success' => false,
        'errors' => array( $e->getMessage() )
        )
    );
    exit;
} 

echo json_encode($response);

$config = new ConfigerClientConfig(
    array(
        'host' => $cfg_source_host,
        'token' => $token
    )
);

$config_file = new ConfigerClientConfigFile();
$config_file->saveConfig($config);

