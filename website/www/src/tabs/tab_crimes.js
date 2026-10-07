
function onBikeTab() {
    $("#bikeTry").removeClass("d-none");
    
    // The fetch call
    fetchBikeRate().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong
        } else {
            // Success, update the span
            $("#bikeRate").text(results.data);
        }
    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onCarTab() {
    $("#carTry").removeClass("d-none");
    
    // The fetch call
    fetchCarRate().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong
        } else {
            // Success, update the span
            $("#carRate").text(results.data);
        }
    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}
    
function onSubmitBike(event) {
    event.preventDefault();
        
    // Remove any previous errors
    onResetAllForms();
    
    fetchStealBike().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#bikeError");
        } else {
            // Successfully attempted to steal a bike
            if (results.data !== "") {
                // We succeeded in stealing the bike!
                $("#bikeTry").addClass("d-none");
                $("#bikeSuccess").removeClass("d-none");
                
                $("#bikeWorth").text("€" + results.data["worth"] + ",-");
                $("#bikeImg").attr("src", results.data["img"]);
            } else {
                // We failed in stealing the bike..
                $("#bikeTry").addClass("d-none");
                $("#bikeNoSuccess").removeClass("d-none");
            }
            
            // Update the table with the new amounts
            updatePlayerInfo();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}
    
function onSubmitCar(event) {
    event.preventDefault();
        
    // Remove any previous errors
    onResetAllForms();
    
    fetchStealCar().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#carError");
        } else {
            // Successfully attempted to steal a car
            if (results.data !== "") {
                // We succeeded in stealing the car!
                $("#carTry").addClass("d-none");
                $("#carSuccess").removeClass("d-none");
                
                $("#carWorth").text("€" + results.data["worth"] + ",-");
                $("#carImg").attr("src", results.data["img"]);
            } else {
                // We failed in stealing the car..
                $("#carTry").addClass("d-none");
                $("#carNoSuccess").removeClass("d-none");
            }
            
            // Update the table with the new amounts
            updatePlayerInfo();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onClickGarage() {
    $("#btnGarage").click();
}

function onClickJail() {
    $("#btnJail").click();
}

$(function() {
    // Set prevent page reloading when submitting form
    $("#bikeForm").on("submit", function(e) {onSubmitBike(e);});
    $("#carForm").on("submit", function(e) {onSubmitCar(e);});
    
    // The buttons to go to jail and our garage
    $(".goToGarage").on("click", function(e) {onClickGarage(e);});
    $(".goToJail").on("click", function(e) {onClickJail(e);});
});