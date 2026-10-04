
function onHomeTab() {
    var statsError = $("#playerStatsError");
    var crimeError = $("#playerCrimeError");
    var statsTable = $("#playerStats");
    var crimeTable = $("#criminalRecord");
    
    // The fetch call
    fetchPlayerStats().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            statsError.removeClass("d-none");
            crimeError.removeClass("d-none");
            statsTable.addClass("d-none");
            crimeTable.addClass("d-none");
        } else {            
            // Success, update the table
            updateTable("home", results.data);
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onTravelTab() {
    
    // The fetch call
    fetchAllLocations().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong
        } else {
            // Success, update the select list
            updateLocationSelect("travel", results.data);
            
        }
    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onJailTab() {
    
    // The fetch call
    fetchAllInmates().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong
        } else {
            // Success, update the jail table
            updateJailTable(results.data);
            
        }
    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}
    
// Create a fetch call to prevent reloading the page
function onSubmitNewPlayer(event) {
    event.preventDefault();
        
    // Remove any previous errors
    onResetAllForms();
        
    // The data for the new player
    var newPlayerName = $("#newPlayer").val();
        
    // Put the data in an easier-to-send format
    var data = {
        "player" : newPlayerName
    };
    
    fetchNewPlayer(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#newPlayerError");
        } else {
            // Successfully started anew
            // Show the home tab
            $("#btnHome").tab('show');
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

// Create a fetch call to prevent reloading the page
function onSubmitTravel(event) {
    event.preventDefault();
        
    // Remove any previous errors
    onResetAllForms();
        
    // The new location for this player
    var location = $("#travel").val();
        
    // Put the data in an easier-to-send format
    var data = {
        "location" : location
    };
    
    fetchTravel(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#travelError");
        } else {
            // Successfully traveled
    
            // Show the success message
            $("#travelForm").addClass("d-none");
            $("#travelSuccess").removeClass("d-none");
            
            // Update the table with the new location
            updatePlayerInfo();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

$(function() {
    // Set prevent page reloading when submitting form
    $("#newPlayerForm").on("submit", function(e) {onSubmitNewPlayer(e);});
    $("#travelForm").on("submit", function(e) {onSubmitTravel(e);});
});

function updateLocationSelect(select, data) {
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

function updateJailTable(data) {
    $("#jail tbody").empty();
    
    var rows = [];
    if (data.length > 0) {
        for (var row in data) {
            // Name, time, city, BUST OUT
            rows.push(
            `<tr>
                <td>` + data[row]["name"] + `</td>
                <td>` + data[row]["time"] + `</td>
                <td><button class="btn btn-link p-0 text-black-50" onclick="payPlayerBail(` + data[row]["id"] + `)"><b>` + data[row]["bail"] + `</b></button></td>
                <td><button class="btn btn-link p-0 text-black-50" onclick="bustPlayerOut(` + data[row]["id"] + `)"><b>` + data[row]["chance"] + `</b></button></td>
            </tr>`);
        }
    } else {
        $("#jailError").removeClass("d-none");
    }

    $("#jail tbody").append(rows.join(""));
}

function payPlayerBail(id) {
        
    // Remove any previous errors
    onResetAllForms();
        
    // Put the data in an easier-to-send format
    var data = {
        "id" : id
    };
    
    fetchPayBail(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#actionError");
        } else {
            // Successfully paid bail            
            // Update the table with the data
            onJailTab();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function bustPlayerOut(id) {
        
    // Remove any previous errors
    onResetAllForms();
        
    // Put the data in an easier-to-send format
    var data = {
        "id" : id
    };
    
    fetchBustOut(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#actionError");
        } else {
            if (results.data === "") {
                // We were sent to Jail!
                $("#actionNoSuccess").removeClass("d-none");
            }
            // Update the table with the data
            onJailTab();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

