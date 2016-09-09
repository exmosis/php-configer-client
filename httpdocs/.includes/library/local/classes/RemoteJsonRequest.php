<?php

require_once('library/external/httpful/httpful.phar');

class RemoteJsonRequest {
   
    const REQUEST_CODE_OK = 200;
    
    protected $host;
    protected $uri_path;
    
    public function __construct($host, $uri_path) {
        $this->host = $host;
        $this->uri_path = $uri_path;
    }
    
    public function assembleUri() {
        return $this->host . '/' . $this->uri_path;
    }
  
  	/**
	 * @return RemoteJsonResponse object reflecting status, messages and errors of response from remote server
	 */
    public function go() {
        $request_uri = $this->assembleUri();

		$response = new RemoteJsonResponse();

 
		try {

	        $httpful_response = \Httpful\Request::get($request_uri)->send();
			
			$response->setCode($httpful_response->code);
	        $response->setBody($httpful_response->body);

			$response_obj = json_decode($httpful_response->body);
			
			$response->setToSuccess();
			
			if (isset($response_obj->messages)) {
				foreach ($response_obj->messages as $message) {
					$response->addMessage($message);
				}
			}

			if (isset($response_obj->errors)) {
				foreach ($response_obj->errors as $error) {
					$response->addError($error);
				}
			}
			
		} catch (Exception $e) {
			$response->setToFailure();
			$response->addError($e->getMessage());
		}
		
        return $response;
    }
    
    
    
}
