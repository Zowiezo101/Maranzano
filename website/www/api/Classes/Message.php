<?php

namespace Classes;

class Message {
    // Different codes to return
    public const CODE_SUCCESS = 200;        // Ok
    public const CODE_CREATED = 201;        // Created
    public const CODE_INVALID = 400;        // Invalid request
    public const CODE_UNAUTHETICATED = 401; // You need to log in
    public const CODE_FORBIDDEN = 403;      // Missing permissions
    public const CODE_NOTFOUND = 404;       // No results found
    public const CODE_ERROR = 503;          // Database issues
    
    private $code = self::CODE_SUCCESS;
    private $error = "";
    private $data = [];
    
    public function setError($error, $code) {
        $this->error = $error;
        $this->code  = $code;
    }
    
    // Function to set the data to return
    public function setData($data) {
        $this->data = $data;
    }
    
    public function sendMessage() {
        $message = [
            "error" => hasString($this->error) ? getString($this->error) : $this->error,
            "data" => $this->data ? $this->data : ""
        ];
        
        http_response_code($this->code);
        echo json_encode($message);
    }
}
