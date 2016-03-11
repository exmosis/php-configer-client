<?php

require_once('scripts/init.php');

$remote_server = new RemoteConfigerServerConnection(
                            'configer.vm',
                            'test',
                            'abc'
);
$remote_request = $remote_server->createJsonRequest('confirm_access');
echo 'Making call';
print_r($remote_request->go());

