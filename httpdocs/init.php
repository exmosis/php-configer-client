<?php

require_once('scripts/init.php');

define('CFG_HOST', 'cfg_host');
define('CFG_HOST_TOKEN', 'cfg_host_token');

if (! isset($_GET[CFG_HOST])) {
    echo 'ERROR: No host set in URL';
    exit;
}

if (! isset($_GET[CFG_HOST_TOKEN])) {
    echo 'ERROR: No token set in URL';
    exit;
}

$host = $_GET[CFG_HOST];
$token = $_GET[CFG_HOST_TOKEN];

$remote_server = new RemoteConfigerServerConnection(
                            'configer.vm',
                            $host,
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
        'host' => $host,
        'token' => $token
    )
);

$config_file = new ConfigerClientConfigFile();
$config_file->saveConfig($config);

