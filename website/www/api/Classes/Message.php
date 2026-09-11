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
    
    public function setError($error, $code = self::CODE_INVALID) {
        // Always show the first code first
        // Meaning we don't update the error after more errors have been received
        // since the first error might have caused more errors
        if ($this->error == "") {
            $this->error = hasString($error) ? getString($error) : $error;
            $this->code = $code;
        }
    }
    
    public function getError() {
        return $this->error;
    }
    
    public function clearError() {
        // Clear any error or error code
        $this->error = "";
        $this->code = self::CODE_SUCCESS;
    }

    // Function to throw an exception
    public function throwError() {
        throw new \Exception($this->error, $this->code);
    }
    
    // Function to set the data to return
    public function setData($data) {
        $this->data = $data;
    }
    
    public function sendMessage() {
        $message = [
            "error" => $this->error,
            "data" => $this->data
        ];
        
        http_response_code($this->code);
        echo json_encode($message);
    }
}
