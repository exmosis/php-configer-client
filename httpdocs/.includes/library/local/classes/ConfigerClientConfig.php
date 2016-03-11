<?php

class ConfigerClientConfig {
    
    protected $config;
    
    public function __construct(array $config_options = array()) {
        foreach ($config_options as $config_key => $config_value) {
            $this->setConfigValue($config_key, $config_value);
        } 
    }
    
    public function getConfigValue($config_key) {
        if (isset($this->config[$config_key])) {
            return $this->config[$config_key];    
        }
        
        return null;
    }
    
    public function setConfigValue($config_key, $config_value) {
        $this->config[$config_key] = $config_value;
    } 
    
    public function getConfigValues() {
        return $this->config;
    }
    
}
