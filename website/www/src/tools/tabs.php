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
    
    function printTable($user) {
        if (isset($user)) {
            foreach ($user as $key => $value) {
                getTableRow($key, $value);
            }
        }
    }
    
    function getTableRow($key, $value) {
        // TODO: Make sure API also respects these ranks and abilities
        $data = $value;
        if ($key == "rank") {
            $data = getRankName($value);
        } 
        
        $table_row = '
                                    <tr><th class="fst-normal text-black">'.getString("info.{$key}").':</th>
                                        <td class="text-center">'.$data.'</td></tr>';
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
        global $rank;
        
        // The hometab is active on each reload
        $active = $tab_name == "home";
        
        // Is this tab visible with the current rank?
        $visible = $rank >= $tab_rank;
        
        // Create the tab
        $tab = '<button type="button" class="btn btn-link'.($active ? " active" : "").($visible ? "" : " d-none").'" data-bs-toggle="tab" data-bs-target="#tab'. ucfirst($tab_name).'" role="tab">'.getString("$category.$tab_name").'</button>';
        
        return $tab;
    }
