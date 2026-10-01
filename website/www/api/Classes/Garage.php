<?php

namespace Classes;

use PDO;

class Garage extends Action {
    
    /**
     * Adding things to the garage
     */
    public function addBike($player_id, $bike) {
        $this->addVehicle($player_id, Crime::CRIME_BIKE, $bike["id"]);
    }
    
    private function addVehicle($player_id, $vehicle_type, $vehicle_id) {
        $conn = $this->db->getConnection();
        
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
}
