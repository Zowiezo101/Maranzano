<?php

namespace Classes;

use PDO;

class Garage extends Action {
    
    public const ACTION_TABLE = "garage";
    
    public function route($route, $data) {
        parent::route($route, $data);
        
        $result = null;
        
        switch($route) {
            case "garage_all":
                $result = $this->getAllVehicles();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function getAllVehicles() {
        $conn = $this->conn;
        
        // The table
        $table = self::ACTION_TABLE;
        
        // The player data
        $player = $this->player_data;
        
        // Retrieve the token from the token table
        $sql = "SELECT * FROM {$table} "
                . "WHERE player_id = :player_id AND sold = 0 "
                . "ORDER BY created_at";
    
        // Prepare query statement
        $stmt = $conn->prepare($sql);    

        // Bind the parameter
        $stmt->bindValue(":player_id", $player["id"], PDO::PARAM_INT);  

        // Execute the statement
        $stmt->execute();

        // Get the results
        $result = getAllResults($stmt);
        
        return $this->formatResults($result);
    }
    
    /**
     * Adding things to the garage
     */
    public function addBike($player_id, $bike) {
        $this->addVehicle($player_id, Crime::CRIME_BIKE, $bike["id"]);
    }
    
    private function addVehicle($player_id, $vehicle_type, $vehicle_id) {
        $conn = $this->conn;
        
        // Create a new vehicle
        $sql = "INSERT INTO garage (player_id, vehicle_type, vehicle_id) "
                . "VALUES (:player_id, :vehicle_type, :vehicle_id)";

        // Prepare query statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(":player_id",    $player_id,    PDO::PARAM_INT);
        $stmt->bindValue(":vehicle_type", $vehicle_type, PDO::PARAM_STR);
        $stmt->bindValue(":vehicle_id",   $vehicle_id,   PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Check that the vehicle has been properly created
        $id = $conn->lastInsertId();
        
        if (!isset($id)) {
            // Do NOT continue is the vehicle hasn't been created
            throwError();
        }
    }
    
    /**
     * Other functions
     */
    
    private function formatResults($results) {
        
        // The formatted array
        $formatted_results = [];
        
        // Format the results of each row
        if(isset($results)) {
            foreach ($results as $row) {
                
                // Vehicle type
                $vehicle = $this->getVehicle($row["vehicle_type"], $row["vehicle_id"]);

                // The actual data we want to return:
                // - ID
                // - Image
                // - Value
                $formatted_results[] = [
                    "id" => $vehicle["id"],
                    "img" => $vehicle["img"],
                    "worth" => "€".$vehicle["worth"],
                ];
            }
        }
        
        return $formatted_results;
    }
    
    private function getVehicle($vehicle_type, $vehicle_id) {
        $vehicle = null;
        
        // Use the vehicle type and id to get the information about this vehicle
        if ($vehicle_type === Crime::CRIME_BIKE) {
            // This is a bike
            $vehicle = Crime::BIKES[$vehicle_id];
        } else if ($vehicle_type === Crime::CRIME_CAR) {
            // This is a car
            $vehicle = Crime::CARS[$vehicle_id];
        }
        
        return $vehicle;
    }
}
