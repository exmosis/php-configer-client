<?php

interface HostConfigWriterInterface {
    
    public function __construct($file, $directory, HostConfig $host_config, $section_writer_class, $option_writer_class);
    
    public function setFile($file);
    public function getFile();
    
    public function setDirectory($directory);
    public function getDirectory();
    
    public function setHostConfig(HostConfig $host_config);
    public function getHostConfig();
    
    public function fileExists();
    
    public function setHostConfigSectionWriterClass($section_writer_class);
    public function getHostConfigSectionWriterClass();
    
    public function setConfigOptionWriterClass($option_writer_class);
    public function getConfigOptionWriterClass();
    
    public function write();
    
}
