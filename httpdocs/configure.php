<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '.includes/scripts/init.php');

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
		       	
			$config_file_dir = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $configer_client_config['config_file_dir'];
			$config_file_name = $configer_client_config['config_file_name'];
			$generated_files_base_dir = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $configer_client_config['generated_files_base_dir'];
			
		} else {
			echo "Couldn't get client config"; 
		}
	}
	
	$client_config_file = new ConfigerClientConfigFile($config_file_name, $config_file_dir);
	$client_config = $client_config_file->getConfigerClientConfig();

} catch (Exception $e) {
	echo json_encode(array(
		'success' => false,
		'errors' => array( $e->getMessage() )
	));
	exit;
}

$cfg_source_host = $client_config->getConfigValue('host');
$token = $client_config->getConfigValue('token');
$requesting_host = $client_config->getConfigValue('local_domain');

$remote_server = new RemoteConfigerServerConnection(
                            $cfg_source_host,
                            $requesting_host,
                            $token
);
$remote_request = $remote_server->createJsonRequest('get_host_config');
try {
    $response = $remote_request->go();

    if ($response->getCode() != RemoteJsonRequest::REQUEST_CODE_OK) {
        echo json_encode(array(
            'success' => false,
            'errors' => $response->getErrors()
        ));
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

/*
echo json_encode(array(
    'success' => true,
    'body' => $response->body // body stored as JSON format string, not JSON object
));
*/

$body = json_decode($response->getBody(), true);

$output_files = array();

foreach ($body as $file_id => $file_config) {
    
    $file_info = $file_config['file_info'];
    $file_vars = $file_config['config_vars'];
    
    $host_config = new HostConfig();
    // Add dummy output section for now
    $host_config_section = new HostConfigSection('magic configer'); 
    $host_config_options = array();
    
    // Set up HostConfig values
    foreach ($file_vars as $var_id => $var_info) {
        
        $config_option = new ConfigOption(
            $var_info['var_key'],
            $var_info['var_value'],
            $var_info['add_quotes'],
            $var_info['comment'],
            $var_info['allow_client_override']
        );
        
        $host_config_section->addConfigOption($config_option);
    }

    // Add dummy output section to host config for this file
    $host_config->addHostConfigSection($host_config_section);
    
    // Set up file writer
    if ($file_info['file_type'] == 'php') {
        $file_writer = new PhpHostConfigWriter(
                                $file_info['file_name'], 
                                $generated_files_base_dir . DIRECTORY_SEPARATOR . $file_info['file_path'], 
                                $host_config,
                                'PhpHostConfigSectionWriter', 
                                'PhpConfigOptionWriter'
                           );
        $file_writer->setOverWriteExistingFile(true);
                           
        // print_r($file_writer->getContent());
        $file_writer->write();
    }

	$output_files[] = $file_info['file_path'] . DIRECTORY_SEPARATOR . $file_info['file_name']; 
    
}

echo json_encode(
	array(
		'success' => true,
		'files_written' => $output_files
	)
);
