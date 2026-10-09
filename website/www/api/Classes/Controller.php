<?php

namespace Classes;

class Controller {
    // Other classes
    private $message = null;
    private $parameters = null;
    private $db = null;
    
    public function __construct() {
        $this->message = new Message();
        $this->parameters = new Parameters();
        
        // To connect to the database
        $this->db = new Database();
    }
    
    public function route($route) {
        try {
            // Get all the given input data
            $data = $this->parameters->getData();
            
            // Passing on the database connection
            $conn = $this->db->getConnection();
        
            // Registering
            if (str_starts_with($route, "register")) {
                $controller = new Register($conn);
            }

            // Resetting password
            else if (str_starts_with($route, "reset")) {
                $controller = new Reset($conn);
            }

            // Loggin in
            else if (str_starts_with($route, "login")) {
                $controller = new Login($conn);
            }

            // Player info
            else if (str_starts_with($route, "player")) {
                $controller = new Player($conn);
            }

            // Travel actions
            else if (str_starts_with($route, "travel")) {
                $controller = new Travel($conn);
            }

            // Jail actions
            else if (str_starts_with($route, "jail")) {
                $controller = new Jail($conn);
            }

            // Shop actions
            else if (str_starts_with($route, "shop")) {
                $controller = new Shop($conn);
            }

            // Bank actions
            else if (str_starts_with($route, "bank")) {
                $controller = new Bank($conn);
            }

            // Bank actions
            else if (str_starts_with($route, "garage")) {
                $controller = new Garage($conn);
            }

            // Player actions
            else if (str_starts_with($route, "location")) {
                $controller = new Location($conn);
            }

            // Crime actions
            else if (str_starts_with($route, "crime")) {
                $controller = new Crime($conn);
            }

            // Mailbox actions
            else if (str_starts_with($route, "message")) {
                $controller = new Mailbox($conn);
            }

            if (isset($controller)) {                
                // Executing the actual request
                $result = $controller->route($route, $data);
                
                // Set the data in the message object, to send it back to the client
                $this->message->setData($result);
            }
        } catch (\Exception $ex) {
            // Set the error in the message object, to send it back to the client
            $this->message->setError($ex->getMessage(), $ex->getCode());
        } catch (\PDOException $ex) {
            // Set the error in the message object, to send it back to the client
            $this->message->setError($ex->getMessage(), $ex->getCode());
        }
        
        $this->message->sendMessage();
    }
    
}
