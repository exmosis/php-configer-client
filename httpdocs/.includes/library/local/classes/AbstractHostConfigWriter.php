<?php

/** 
 * Basic, non-instantiable code for HostConfig writing
 */

abstract class AbstractHostConfigWriter implements HostConfigWriterInterface {

    protected $file_name;
    protected $directory;
    protected $host_config;
    protected $section_writer_class;
    protected $option_writer_class;
    
    protected $file_handle;
    protected $overwrite_existing_file;
   
    public function __construct($file_name, $directory, HostConfig $host_config,
                                $section_writer_class = null, $option_writer_class = null 
    ) {
        $this->setFile($file_name);
        $this->setDirectory($directory);
        $this->resetHostConfig();
        $this->setHostConfig($host_config);
        if (! is_null($section_writer_class)) {
            $this->setHostConfigSectionWriterClass($section_writer_class);
        }
        if (! is_null($option_writer_class)) {
            $this->setConfigOptionWriterClass($option_writer_class);
        }
        $this->setFileHandle(null);
        $this->setOverWriteExistingFile(false);
    }
  
    public function setFile($file_name) {
       $this->file_name = $file_name; 
    }
    
    public function getFile() {
        return $this->file_name;
    }
    
    public function setDirectory($directory) {
        // remove tailing slashes
        $directory = preg_replace('/\\' . DIRECTORY_SEPARATOR . '+$/', '', $directory);
        $this->directory = $directory;
    }
    public function getDirectory() {
        return $this->directory;
    }
    
    public function getFullFile() {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . $this->getFile();
    }
    
    public function fileExists() {
        return file_exists($this->getFullFile());
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
        $this->section_writer_class = $section_writer_class;
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
    
   
    protected function getFileHandle() {
        return $this->file_handle;
    }
    
    protected function setFileHandle($fh) {
        $this->file_handle = $fh;
    } 
    
    public function getOverwriteExistingFile() {
        return $this->overwrite_existing_file;
    }
    
    public function setOverWriteExistingFile($overwrite) {
        $this->overwrite_existing_file = $overwrite;
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

    /**
     * Writes content to the file, using the concrete "writeContent" function implemented at child class levels.
     * 
     * @throws Exception if any problems encountered.
     */
    public function write() {
        $this->openFile();
        $this->writeContent(); // magic happens here
        $this->closeFile();
    }
    
    protected function openFile() {
        
        if ($this->fileExists() && ! $this->getOverwriteExistingFile()) {
            throw new Exception("Can't overwrite existing config file.");
        }
        
        $file = $this->getFullFile();
        echo "\nWriting to $file\n";
        $fh = fopen($file, 'w');
        if ($fh === false) {
            throw new Exception("Couldn't open file for writing.");
        }
        
        $this->setFileHandle($fh);
        
    }
    
    protected function closeFile() {
        
        $this->confirmFileIsOpen();
        
        $fh = $this->getFileHandle();
        
        if (! fclose($fh)) {
            throw new Exception("Couldn't close file, unknown error.");
        }
        $this->setFileHandle(null);
        
    }

    public function writeContent() {
       
        $this->confirmFileIsOpen();
        
        $content = $this->getContent();
        $fh = $this->getFileHandle();
        
        if (false === fwrite($fh, implode("\n", $content))) {
            throw new Exception("Couldn't write contents to file, unknown error.");
        }
    }
    
    protected function confirmFileIsOpen() {
        if (is_null($this->getFileHandle())) {
            throw new Exception("Expecting output file to be open but wasn't.");
        }
    }
    
}
