<?php

namespace Classes;

class Location {
    
    public const IDX_COUNTRY = 0;
    public const IDX_CITY = 1;
    
    public const LOCATIONS = [
        0 => ["Italy", "Palermo"],
        1 => ["Italy", "Milan"],
        2 => ["Netherlands", "Amsterdam"],
        3 => ["England", "London"],
        4 => ["France", "Paris"],
        5 => ["Germany", "Berlin"],
        6 => ["Spain", "Madrid"],
        7 => ["Hungary", "Budapest"],
        8 => ["Czech Republic", "Praque"],
        9 => ["Poland", "Warschau"],
        10 => ["Portugal", "Lisbon"],
        11 => ["Denmark", "Copenhagen"],
    ];
    
    public function __construct() {
        
    }
    
    public function getCity($value) {
        $result = self::LOCATIONS[$value][self::IDX_CITY];
        return $result;
    }
    
    public function getCountry($value) {        
        $result = self::LOCATIONS[$value][self::IDX_COUNTRY];
        return $result;
    }
}
