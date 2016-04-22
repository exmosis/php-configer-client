<?php

/**
 * A section of config containing a number of ConfigOptions.
 */
 
class HostConfigSection {
    
    protected $name;
    protected $config_options;
    
    /**
     * @param String    $name           Name/ID of section
     * @param Array     $config_options Array of ConfigOption objects to initialise object with. Optional, default = null
     */
    public function __construct($name, array $config_options = null) {
        $this->setName($name);
        $this->resetConfigOptions();
        if (is_array($config_options)) {
            $this->addConfigOptions($config_options);
        }
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function resetConfigOptions() {
        $this->config_options = array();
    }
    
    public function addConfigOptions(array $config_options) {
        foreach ($config_options as $config_option) {
            $this->addConfigOption($config_option);
        }
    }
    
    public function addConfigOption(ConfigOption $config_option) {
        $this->config_options[] = $config_option;
    }
    
    public function getConfigOptions() {
        return $this->config_options;
    }
    
}
