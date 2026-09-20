<?php

    $RANK_ROOKIE = getRankLevel("Rookie");
    $RANK_MAFIOSO = getRankLevel("Mafioso");
    $RANK_HITMAN = getRankLevel("Hitman");
    $RANK_DON = getRankLevel("Don");
    
    function getRankLevel($name) {
        global $strings;
        
        // Get the key of this name
        $key = array_search($name, $strings);
        
        // Remove the "rank." and only return the number
        $rank = str_replace("rank.", "", $key);
        
        // Return the rank as an integer (base 10)
        return intval($rank, 10);
    }
    
    function getRankName($level) {
        // Insert the level into the string
        $name = "rank.$level";
        
        // And use it as a key into $strings
        return getString($name);
    }
    
    function printTableTemplate($table, $fields) {
        // Make sure the information is actually avaialbe
        if (isset($fields)) {            
            
            // Show every property of this player per row
            foreach ($fields as $key) {
                
                // Creating the row for this property
                getTableTemplateRow($table, $key);
            }
        }
    }
    
    function getTableTemplateRow($table, $key) {
        
        // The row with information
        $table_row = '
                                    <tr><th class="fst-normal text-black">'.getString("info.{$key}").':</th>
                                        <td id="data'.ucfirst($table).ucfirst($key).'" class="text-center"></td></tr>';
        
        // Print the row
        echo $table_row;
    }

    function printMenu($category, $tab_data) {
        
        $tabs = [];
        // Get all the tab names and create a tablist with them
        foreach ($tab_data as $tab_name => $tab_rank) {
            $tabs[] = getTab($category, $tab_name, $tab_rank);
        }

        // The menu
        $menu = '                                 
                                    <!-- '.ucfirst($category).' menu -->
                                    <div class="mt-2">
                                        <!-- Menu titel -->
                                        <div class="bg-body-tertiary fst-normal fw-bold text-center border border-3 border-black">
                                            <button type="button" class="btn bg-body-tertiary collapsible" data-bs-toggle="collapse" data-bs-target="#acc-'.$category.'" aria-expanded="true" aria-controls="acc-'.$category.'">
                                                <b>'.getString("menu.$category").'</b>
                                            </button>
                                        </div>

                                        <!-- Menu items -->
                                        <div id="acc-'.$category.'" class="show row justify-content-end">
                                            <div class="col-10">
                                                <div class="btn-group-vertical">
                                                    '. join("
                                                    ", $tabs).'
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
        
        echo $menu;
    }
    
    function getTab($category, $tab_name, $tab_rank) {
        
        // Create the tab
        // All tabs for higher ranks are invisible by default
        // They will be made visible by JS in updateTabs
        $tab = '<button id="btn'.ucfirst($tab_name).'" type="button" class="btn btn-link'.($tab_rank > 1 ? " d-none" : "").'" data-rank="'.$tab_rank.'" data-bs-toggle="tab" data-bs-target="#tab'. ucfirst($tab_name).'" role="tab">'.getString("$category.$tab_name").'</button>';
        
        return $tab;
    }
