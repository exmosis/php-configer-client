<?php

require_once(__DIR__ . '/.includes/scripts/init.php');

/* Accepted GET params */

// Address of server to get config from 
define('CFG_HOST', 'cfg_server_host');
// Token to use to access config server
define('CFG_HOST_TOKEN', 'cfg_server_host_token');
// Optional client host name override
define('CFG_CLIENT_HOST', 'cfg_client_host');

if (! isset($_GET[CFG_HOST])) {
    echo 'ERROR: No host set in URL';
    exit;
}

if (! isset($_GET[CFG_HOST_TOKEN])) {
    echo 'ERROR: No token set in URL';
    exit;
}

$cfg_source_host = $_GET[CFG_HOST];
$token = $_GET[CFG_HOST_TOKEN];
$requesting_host = $_SERVER['HTTP_HOST'];
if (isset($_GET[CFG_CLIENT_HOST])) {
	$requesting_host = $_GET[CFG_CLIENT_HOST];
}

$remote_server = new RemoteConfigerServerConnection(
                            $cfg_source_host,
                            $requesting_host,
                            $token
);
$remote_request = $remote_server->createJsonRequest('confirm_access');
try {

	/* @var $response RemoteJsonResponse */
    $response = $remote_request->go();

    if (! $response->wasSuccessful()) {
        echo json_encode(array(
        	'success' => false,
        	'errors' => $response->getErrors()
        ));
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

// echo json_encode($response);

$config = new ConfigerClientConfig(
    array(
        'host' => $cfg_source_host,
        'token' => $token,
        'local_domain' => $requesting_host
    )
);

try {
		
	$config_file_dir = null;	
	$config_file_name = null;
	
	$configer_client_config_file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '.configer/configer_client.json';
	
	if (file_exists($configer_client_config_file)) {

		// @TODO Replace this with something nicer/re-used, and with better error checking
		$content = file_get_contents($configer_client_config_file);
		$configer_client_config = json_decode($content, true);

		if (isset($configer_client_config['config_file_dir']) &&
		       isset($configer_client_config['config_file_name'])) {
		       	
			$config_file_dir = $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR . $configer_client_config['config_file_dir'];
			$config_file_name = $configer_client_config['config_file_name'];
			
		} else {
			echo "Couldn't get client config"; 
		}
	}
	
	$config_file = new ConfigerClientConfigFile($config_file_name, $config_file_dir);
	$config_file->saveConfig($config);
} catch (Exception $e) {
	echo json_encode(array(
		'success' => false,
		'errors' => array( $e->getMessage() )
	));
}

echo json_encode(array(
	'success' => true	
));


