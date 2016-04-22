<?php

/** 
 * Basic, non-instantiable code for HostConfig writing
 */

class AbstractHostConfigWriter implements HostConfigWriterInterface {

    protected $file_name;
    protected $directory;
    protected $host_config;
    protected $section_writer_class;
    protected $option_writer_class;
   
    public function __construct($file_name, $directory, HostConfig $host_config,
                                $section_writer_class = null, $option_writer_class = null 
    ) {
        $this->setFileName($file_name);
        $this->setDirectory($directory);
        $this->resetHostConfig();
        $this->setHostConfig($host_config);
        if (! is_null($section_writer_class)) {
            $this->setHostConfigSectionWriterClass($section_writer_class);
        }
        if (! is_null($option_writer_class)) {
            $this->setConfigOptionWriterClass($option_writer_class);
        }
    }
    
    public function setFileName($file_name) {
       $this->file_name = $file_name; 
    }
    
    public function getFileName() {
        return $this->file_name;
    }
    
    public function setDirectory($directory) {
        // remove tailing slashes
        $directory = preg_replace('/\\' . DIRECTORY_SEPARATOR . '+$/', $directory);
        $this->directory = $directory;
    }
    public function getDirectory() {
        return $this->directory;
    }
    
    public function getFile() {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . $this->getFileName();
    }
    
    public function fileExists() {
        return file_exists($this->getFile());
    }
    
    
    public function resetHostConfig() {
        $this->host_config = null;
    }
    public function setHostConfig(HostConfig $host_config) {
        $this->host_config = $host_config;
    }
  
    public function getHostConfig() {
        return $this->host_config;
    }
    
    
    public function setHostConfigSectionWriterClass($section_writer_class) {
        $this->section_writer_class = $secton_writer_class;
    }
    
    public function getHostConfigSectionWriterClass() {
        return $this->section_writer_class;
    }
    
    public function setConfigOptionWriterClass($option_writer_class) {
        $this->option_writer_class = $option_writer_class;
    }
    
    public function getConfigOptionWriterClass() {
        return $this->option_writer_class;
    }
    
    public function getContent() {
        
        $content = array();
        
        // Loop through the HostConfigSections in the HostConfig
        $sections = $this->getHostConfig()->getHostConfigSections();
        $section_writer_class = $this->getHostConfigSectionWriterClass();
        
        foreach ($sections as $section) {
            $section_writer = new $section_writer_class($section, $this->getConfigOptionWriterClass());
            $content = array_merge($content, $section_writer->getContent());
        }
        
        return $content;
        
    }

    public function write() {
        $content = $this->getContent();
    }
    
}
