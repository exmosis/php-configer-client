<?php

/**
 * Gets PHP-formatted content for a ConfigOption
 */
class PhpConfigOptionWriter implements ConfigOptionWriterInterface {
    
    protected $config_option;
    
    public function __construct(ConfigOption $config_option) {
        $this->setConfigOption($config_option);
    }
    
    public function setConfigOption(ConfigOption $config_option) {
        $this->config_option = $config_option;
    }
    
    public function getConfigOption() {
        return $this->config_option;
    }
    
    public function getContent() {
    
        $content = array();
            
        $config_option = $this->getConfigOption();
        
        $key = $config_option->getKey();
        $value = $config_option->getValue();
        $be_quoted = $config_option->getBeQuoted();
        
        // add quotes
        if ($be_quoted) {
            $value = $this->quoteValue($value);
        }
        
        // Turn into define('key', value)
        $define_stmt = $this->defineValue($key, $value);
        $define_stmt .= ';';
        
        if ($config_option->isOverridable()) {
            $define_stmt = $this->optionalDefine($key, $define_stmt);
        }
       
        $comment = $config_option->getComment();
        if ($comment) {
            $content[] = $this->makeComment($comment);
        }
        
        $content[] = $define_stmt;
        
        // add blank line
        $content[] = '';
     
        return $content;
        
    }
    
    /**
     * Helper function to add quotes
     */
    private function quoteValue($value) {
        $value = "'" . str_replace("'", "\\'", $value) . "'";
        return $value;
    }
    
    private function defineValue($key, $value) {
        $content = "define('" . $key . "', " . $value . ")";
        return $content;
    }
    
    private function optionalDefine($key, $define_stmt) {
        $define_stmt = "if (! defined('" . $key . "')) { " . $define_stmt . " }";
        return $define_stmt;
    }
    
    private function makeComment($comment) {
        return '// ' . $comment;
    }
    
}  
