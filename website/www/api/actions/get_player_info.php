<?php

    http_response_code(200);
    
    echo json_encode([
        "error" => "",
        "data" => [
            "cash" => "€100",
            "bank" => "€10",
            "rank" => "15",
            "progress" => "98.7%",
            "family" => "-None-",
            "city" => "Gouda",
            "country" => "Netherlands",
            "health" => "100%",
            "bullets" => "10.050",
            "shields" => "20.000",
            "deceased" => "false",
            "killed_by" => ""
        ]
    ]);
