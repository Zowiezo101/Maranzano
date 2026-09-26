<?php

namespace Classes;

class Controller {
    // Other classes
    private $message = null;
    private $parameters = null;
    
    public function __construct() {
        $this->message = new Message();
        $this->parameters = new Parameters();
    }
    
    public function route($route) {
        // Get all the given input data
        $data = $this->parameters->getData();
        
        // Registering
        if (str_starts_with($route, "register")) {
            $controller = new Register();
        }
        
        // Resetting password
        else if (str_starts_with($route, "reset")) {
            $controller = new Reset();
        }
        
        // Loggin in
        else if (str_starts_with($route, "login")) {
            $controller = new Login();
        }
        
        // Player info
        else if (str_starts_with($route, "player")) {
            $controller = new Player();
        }
        
        // Travel actions
        else if (str_starts_with($route, "travel")) {
            $controller = new Travel();
        }
        
        // Shop actions
        else if (str_starts_with($route, "shop")) {
            $controller = new Shop();
        }
        
        // Player actions
        else if (str_starts_with($route, "location")) {
            $controller = new Location();
        }
        
        if (isset($controller)) {
            try {
                // Executing the actual request
                $result = $controller->route($route, $data);
                
                // Set the data in the message object, to send it back to the client
                $this->message->setData($result);
            } catch (\Exception $ex) {
                // Set the error in the message object, to send it back to the client
                $this->message->setError($ex->getMessage(), $ex->getCode());
            } catch (\PDOException $ex) {
                // Set the error in the message object, to send it back to the client
                $this->message->setError($ex->getMessage(), $ex->getCode());
            }
        }
        
        $this->message->sendMessage();
    }
    
}
