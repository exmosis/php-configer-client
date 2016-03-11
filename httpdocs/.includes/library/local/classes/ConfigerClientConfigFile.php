<?php

class ConfigerClientConfigFile {

    protected $dir_path;    
    protected $file_name;
    
    const DEFAULT_CONFIG_FILE_NAME = '.configer';
    const DEFAULT_CONFIG_DIR = '.includes/config/site_config/generated';
    
    public function __construct($file_name = null, $alternative_dir = null) {
        
        if (! is_null($file_name) && $file_name) {
            $this->file_name = $file_name;
        } else {
            $this->file_name = self::DEFAULT_CONFIG_FILE_NAME;
        }
        
        if (! is_null($alternative_dir) && $alternative_dir) {
            $this->dir_path = $alternative_dir;
        } else {
            // Revert to default
            $this->dir_path = self::DEFAULT_CONFIG_DIR;
        }
        
    }
    
    /**
     * Throws an exception if config file fails to validate
     */
    public function validateConfigFile() {
        
        $this->validateConfigDir();
        
        if (file_exists($this->dir_path . DIRECTORY_SEPARATOR . $this->file_name)) {
            return true;
        }
        
        throw new Exception('Invalid config file: ' . $this->dir_path . DIRECTORY_SEPARATOR . $this->file_name);
    }
    
    public function validateConfigDir() {
        
        if (file_exists($this->dir_path)) {
            return true;
        }
        
        throw new Exception('Invalid config directory: ' . $this->dir_path);
       
    }
    
    /**
     * Constructs a ConfigerClientConfig object based on the file passed in
     */
    public function getConfigerClientConfig() {
        $config = $this->getConfigFromFile();
        // assume $config is a key: value array for now
        return new ConfigerClientConfig($config);
    }
    
    private function getConfigFromFile() {
        $this->validateConfigFile();
        $file_contents = file_get_contents($this->dir_path . DIRECTORY_SEPARATOR . $this->file_name);
        return json_decode($file_contents);
    }
    
    public function saveConfig(ConfigerClientConfig $config) {
        
        $this->validateConfigDir();
        
        file_put_contents($this->dir_path . DIRECTORY_SEPARATOR . $this->file_name,
                          json_encode($config->getConfigValues())
                         );
    }
    
}
