<?php

/**
 * A configurable option received from the remote configuration host server.
 */

class ConfigOption {
    
    // Properties imported from 
    protected $key;      // Key
    protected $value;    // Value
    protected $be_quoted;       // Boolean for whether we should put the value in quotes or not
    protected $comment;         // Comment to go alongside the key/value pair 
    protected $overridable;     // Whether this value should be able to be overridden
    
    public function __construct($key, $value = null, $be_quoted = true, $comment = null, $overridable = false) {
        $this->setKey($key);
    }
    
    private function setKey($key) {
        $this->key = $key;
    } 
    
    public function getKey() {
        return $this->key;
    }
    
    public function setValue($value) {
        $this->value;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function setBeQuoted($quoted) {
        $this->be_quoted = ($quoted) ? true : false;
    }
    
    public function getBeQuoted() {
        return $this->be_quoted;
    }
    
    public function setComment($comment) {
        $this->comment = $comment;
    }
    
    public function getComment() {
        return $this->comment;
    }
    
    public function setOverridable($overridable) {
        $this->overridable = ($overridable) ? true : false;
    }
    
    public function isOverridable() {
        return $this->overridable;
    }
    
}
