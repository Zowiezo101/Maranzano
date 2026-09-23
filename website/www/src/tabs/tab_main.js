
function insertHomeData() {
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
            // Remove the error message
            statsError.addClass("d-none");
            crimeError.addClass("d-none");
            statsTable.removeClass("d-none");
            crimeTable.removeClass("d-none");
            
            // Success, update the table
            updateTable("home", results.data);
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

    
// Create a fetch call to prevent reloading the page
function onSubmitReset(event) {
    event.preventDefault();
        
    // Remove any previous errors
    onResetError("#newPlayerError");
        
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

$(function() {
    // Set prevent page reloading when submitting form
    $("#newPlayerForm").on("submit", function(e) {onSubmitNewPlayer(e);});
});

