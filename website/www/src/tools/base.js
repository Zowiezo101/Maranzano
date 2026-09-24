
// Netbeans does not like HTML comments in jQuery as it confused it with React
// Therefore we're adding the comments back in by added the missing '!'
function getHTML(html) {
    // Get the HTML comments back
    return html.replaceAll("<--", "<!--");
}
    
// If there is an error
function onReturnedError(message, element) {
    $(element).html(message).removeClass("d-none");
}

// Remove the error
function onResetError(element) {
    $(element).html("").addClass("d-none");
}

// A rewrite of a PHP function
function ucfirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

/*
 * Simple functions to update HTML elements
 */ 

function updateTable(table, data) {
    for (var [key, value] of Object.entries(data)) {
        if (key === "rank" || key === "prank") {
            // Use the rank name
            value = getRankName(value);
        }

        $("#data" + ucfirst(table) + ucfirst(key)).text(value);
    }
}

function updateSelect(select, data) {
    var options = [];
    
    for (var [key, value] of Object.entries(data)) {
        if (value === null) {
            // Skip this value
            continue;
        }
        
        // Get the country and the city out of the value
        var country = value[0];
        var city = value[1];
        
        options.push(`<option class="location-option" value="${key}">${city} (${country})</option>`); 
    }
    
    // Remove the previous location options
    $(".location-option").remove();
    
    // Insert the new location options
    $("#" + select).append(options.join(""));
}

function updateTabs(data) {
    var rank = parseInt(data["rank"]);
    
    // For all ranks
    for (var i = 1; i <= Object.keys(ranks).length; i++) {
        // If the player's rank is high enough
        if (rank >= i) {
            // Show the corresponding tabs
            $("[data-rank=" + i + "]").removeClass("d-none");
        }
    }
}
