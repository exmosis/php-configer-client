<?php

require_once('library/external/httpful/httpful.phar');

class RemoteJsonRequest {
   
    const REQUEST_CODE_OK = 200;
    
    protected $host;
    protected $uri_path;
    
    protected $response;
    protected $body;
    
    public function __construct($host, $uri_path) {
        $this->host = $host;
        $this->uri_path = $uri_path;
    }
    
    public function assembleUri() {
        return $this->host . '/' . $this->uri_path;
    }
  
    public function go() {
        $request_uri = $this->assembleUri();
        $this->response = \Httpful\Request::get($request_uri)->send();
        $this->body = $this->response->body;
        
        return $this->response;
    }
    
    
    
}
