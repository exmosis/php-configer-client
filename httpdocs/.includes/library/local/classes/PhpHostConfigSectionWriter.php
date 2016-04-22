<?php

/**
 * Deals with outputting the content for a config section for PHP files.
 */
class PhpHostConfigSectionWriter implements HostConfigSectionWriterInterface {
   
    protected $host_config_section; 
    protected $option_writer_class; 
    
    public function __construct(HostConfigSection $host_config_section, $option_writer_class = 'PhpConfigOptionWriter') {
        $this->setHostConfigSection($host_config_section);
        $this->setConfigOptionWriterClass($option_writer_class);    
    } 
    
    
    public function setHostConfigSection($host_config_section) {
        $this->host_config_section = $host_config_section;
    }
    
    public function getHostConfigSection() {
        return $this->host_config_section;
    }
    
    
    public function setConfigOptionWriterClass($option_writer_class) {
        $this->option_writer_class = $option_writer_class;
    }
    
    public function getConfigOptionWriterClass() {
        return $this->option_writer_class;
    }
    
    
    public function getContent() {
    
        $content = array();
    
        $host_config_section = $this->getHostConfigSection();
    
        $name = $ihost_config_section->getName();
        $name = '/* ' . str_replace('*/', '* /', $name) . '*/';
        
        $content[] = $name;
        $content[] = ''; // blank line
        
        $option_writer_class = $this->getConfigOptionWriterClass();
       
        // Loop through config options and add content from each 
        foreach ($host_config_section->getConfigOptions() as $config_option) {
            $option_writer = new $option_writer_class($config_option);
            $content = array_merge($content, $option_writer->getContent());
        }
        
        $content[] = ''; // blank line
        
        return $content;
        
    }
    
}
