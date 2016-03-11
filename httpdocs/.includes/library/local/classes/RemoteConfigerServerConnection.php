<?php

class RemoteConfigerServerConnection {

    protected $remote_domain;
    protected $local_domain;
    protected $remote_access_token;
    
    public function __construct($remote_domain, $local_domain, $remote_access_token) {
        $this->remote_domain = $remote_domain;
        $this->local_domain = $local_domain;
        $this->remote_access_token = $remote_access_token;
    }
    
    public function getRemoteDomain() {
        return $this->remote_domain;
    }
    
    public function getLocalDomain() {
        return $this->local_domain;
    }
    
    public function getRemoteAccessToken() {
        return $this->remote_access_token;
    }
    
    public function createJsonRequest($uri_path) {
        $request = new RemoteConfigerServerJsonRequest(
                                        $this->getRemoteDomain(),
                                        $uri_path,
                                        $this // pass in reference to self
        );
        return $request;
    }
        
}
