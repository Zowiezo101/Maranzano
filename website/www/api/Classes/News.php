<?php

namespace Classes;

class News {
    // Other classes
    private $parameters;
    
    // The database connection
    protected $conn;
    
    // The timespan in which we show the news
    private const NEWS_TIME = 7 * 24 * 60 * 60;
    
    // Types of news
    public const NEWS_KILL = 0;
    public const NEWS_NEW = 1;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->parameters = new Parameters();
    }
    
    public function route($route, $data) {
        
        // Parse the input data
        $this->parameters->setData($data);
        
        $result = null;
        
        switch($route) {
            case "news_load":
                $result = $this->getNews();
                break;
        }
        
        return $result;
    }
    
    /**
     * API functions
     */
    
    private function getNews() {
        $player = new Player($this->conn);
        
        // Get the killed players
        $results_kill = $player->getKilledPlayersNews(self::NEWS_TIME);
        
        // Get the new players
        $results_new = $player->getNewPlayersNews(self::NEWS_TIME);
        
        // Merge the arrays 
        $results = array_merge($results_kill, $results_new);
        
        return $results;
    }
}
