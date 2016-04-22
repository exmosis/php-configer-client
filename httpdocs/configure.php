<?php

require_once('scripts/init.php');

$client_config_file = new ConfigerClientConfigFile();
$client_config = $client_config_file->getConfigerClientConfig();

$cfg_source_host = $client_config->getConfigValue('host');
$token = $client_config->getConfigValue('token');
$requesting_host = $_SERVER['HTTP_HOST'];

$remote_server = new RemoteConfigerServerConnection(
                            $cfg_source_host,
                            $requesting_host,
                            $token
);
$remote_request = $remote_server->createJsonRequest('get_host_config');
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