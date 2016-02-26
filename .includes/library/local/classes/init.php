<?php

$remote_server = new RemoteConfigerServerConnection(
                            '127.0.0.1',
                            'test',
                            'abc'
);
$remote_request = $remote_server->createJsonRequest('confirm_access');

print_r($remote_request->go());

