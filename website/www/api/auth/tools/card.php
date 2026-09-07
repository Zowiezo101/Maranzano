<?php
    function showCard($card) {
        $header = $card["header"];
        $title = $card["title"];
        $body = $card["body"];
        
        $button = "";
        // We don't always want this button
        if (isset($card["button"])) {
            $button_url = $card["button"]["url"];
            $button_text = $card["button"]["text"];
            
            $button = '<a href="'.$button_url.'" class="btn btn-primary">
                                '.$button_text.'
                            </a>';
        }
        
        // The card to show if something has succeeded or not
        $output =  '<div class="card bg-body-secondary text-center">
                        <div id="card-header" class="card-header bg-body-tertiary">
                            '.$header.'
                        </div>
                        <div class="card-body">                            
                            <h5 id="card-title" class="card-title">
                                '.$title.'
                            </h5>
                            <p  id="card-text"  class="card-text">
                                '.$body.'
                            </p>
                            '.$button.'
                        </div>
                    </div>';
                                
        echo $output;
    }
