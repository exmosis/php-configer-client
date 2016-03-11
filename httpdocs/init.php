<?php

require_once('scripts/init.php');

$remote_server = new RemoteConfigerServerConnection(
                            'configer.vm',
                            'test',
                            'abc'
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
