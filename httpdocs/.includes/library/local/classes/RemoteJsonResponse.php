<?php

class RemoteJsonResponse {

    protected $success;
	protected $code;
	protected $body;
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
    
	public function setCode($code) {
		$this->code = $code;
	}
	
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * @param Mixed $body Array or object for body?	
	 */
	public function setBody($body) {
		$this->body = $body;
	}
	
	public function getBody() {
		return $this->body;
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
