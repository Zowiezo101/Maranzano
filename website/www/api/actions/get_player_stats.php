<?php

    http_response_code(200);
    
    echo json_encode([
        "error" => "",
        "data" => [
            "name" => "TheToweler",
            "prank" => "20",
            "created" => "4-9-2026",
            "friends" => "69",
            "bikes" => "15",
            "cars" => "15",
            "stores" => "9",
            "kills" => "2",
            "jail" => "512",
            "hospital" => "231",
        ]
    ]);
