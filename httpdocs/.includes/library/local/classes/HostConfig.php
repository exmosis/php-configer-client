<?php

/**
 * An entire Host Config (basically a single file) which consists of 1 or more HostConfigSection objects.
 */

class HostConfig {
    
    protected $host_config_sections;
    
    /**
     * @param array    $host_config_sections    Array of HostConfigSection objects to initialise this object with. Optional, default = null
     */
    public function __construct(array $host_config_sections = null) {
        $this->resetHostConfigSections();
        if (is_array($host_config_sections)) {
            $this->addHostConfigSections($host_config_sections);
        }
    }
    
    public function resetHostConfigSections() {
        $this->host_config_sections = array();
    }
    
    public function addHostConfigSections(array $host_config_sections) {
        foreach ($host_config_sections as $host_config_section) {
            $this->addHostConfigSection($host_config_section);
        }
    }
    
    public function addHostConfigSection(HostConfigSection $host_config_section) {
        $this->host_config_sections[] = $host_config_section;
    }
    
    public function getHostConfigSections() {
        return $this->host_config_sections;
    }
    
}
