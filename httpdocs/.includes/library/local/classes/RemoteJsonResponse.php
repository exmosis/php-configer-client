<?php

class RemoteJsonResponse {

    protected $success;
    protected $messages;
    protected $errors;
    
    public function __construct($success = false) {
        $this->success = $success;
        $this->messages = array();
        $this->errors = array();
    }
        
    public function setToSuccess() {
        $this->success = true;
    }
    
    public function setToFailure() {
        $this->success = false;
    }
    
    public function wasSuccessful() {
        return $this->success;
    }
    
    public function addMessage($message) {
        $this->messages[] = $message;
    }
    
    public function getMessages() {
        return $this->messages;
    }
    
    public function addError($error) {
        $this->errors[] = $error;
        $this->setToFailure();
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    
}
