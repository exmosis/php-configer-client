<?php

class RemoteConfigerServerJsonRequest extends RemoteJsonRequest {
    
    protected $configer_server;
    
    /**
     * Host is currently ignored. Can we drop it under PHP constructors?
     */
    public function __construct($host, $uri_path, RemoteConfigerServer $configer_server) {
        parent::__construct($configer_server->getRemoteDomain(), $uri_path);
        $this->configer_server = $configer_server;
    }
    
    public function assembleUri() {
        $uri = parent::assembleUri();
        if (strpos($uri, '?') === false) {
            // not there, so add it
            $uri .= '?';
        } else {
            $uri .= '&';
        }
        $uri .= 'host=' . $this->configer_server->getLocalDomain();
        $uri .= 'token=' . $this->configer_server->getRemoteAccessToken();
        
        return $uri;
    }
    
}
