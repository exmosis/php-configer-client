<?php

class RemoteConfigerServerJsonRequest extends RemoteJsonRequest {
    
    protected $configer_server_cn;
    
    /**
     * Host is currently ignored. Can we drop it under PHP constructors?
     */
    public function __construct($host, $uri_path, RemoteConfigerServerConnection $configer_server_cn) {
        parent::__construct($configer_server_cn->getRemoteDomain(), $uri_path);
        $this->configer_server_cn = $configer_server_cn;
    }
    
    public function assembleUri() {
        $uri = parent::assembleUri();
        if (strpos($uri, '?') === false) {
            // not there, so add it
            $uri .= '?';
        } else {
            $uri .= '&';
        }
        $uri .= 'host=' . $this->configer_server_cn->getLocalDomain();
        $uri .= '&';
        $uri .= 'token=' . $this->configer_server_cn->getRemoteAccessToken();
        
        return $uri;
    }
    
}
