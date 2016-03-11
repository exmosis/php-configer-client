<?php

require_once('scripts/init.php');

if (! isset($_GET['host'])) {
    echo 'ERROR: No host set in URL';
    exit;
}

if (! isset($_GET['token'])) {
    echo 'ERROR: No token set in URL';
    exit;
}

$host = $_GET['host'];
$token = $_GET['token'];

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

print_r($response);

$config = new ConfigerClientConfig(
    array(
        'host' => $host,
        'token' => $token
    )
);

$config_file = new ConfigerClientConfigFile();
$config_file->saveConfig($config);

