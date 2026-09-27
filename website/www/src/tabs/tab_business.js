    
function onBulletTab() {
            
    // Remove any lingering messages
    onResetError("#shopError");
    onResetError("#swapError");
    
    // And make sure the forms are shown
    $("#shopSuccess").addClass("d-none");
    $("#swapSuccess").addClass("d-none");
    $("#shopForm").removeClass("d-none");
}
    
function onSubmitShop(event) {
    event.preventDefault();
        
    // Remove any previous errors
    onResetError("#shopError");
        
    // The data for the shop
    var amount = $("#shopAmount").val();
        
    // Put the data in an easier-to-send format
    var data = {
        "amount" : amount
    };
    
    fetchShopBullets(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#shopError");
        } else {
            // Successfully bought bullets
    
            // Show the success message
            $("#shopForm").addClass("d-none");
            $("#shopSuccess").removeClass("d-none");
            
            // Update the table with the new amounts
            updatePlayerInfo();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onSubmitSwap(event) {
    event.preventDefault();
        
    // Remove any previous errors or message
    onResetError("#swapError");
    
    // Show the success message
    $("#swapSuccess").addClass("d-none");
        
    // The data for the swap
    var amount = $("#swapAmount").val();
        
    // Put the data in an easier-to-send format
    var data = {
        "amount" : amount
    };
    
    fetchSwapBullets(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#swapError");
        } else {
            // Successfully swapped bullets
    
            // Show the success message
            $("#swapSuccess").removeClass("d-none");
            
            // Update the table with the new amounts
            updatePlayerInfo();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

$(function() {
    // Set prevent page reloading when submitting form
    $("#shopForm").on("submit", function(e) {onSubmitShop(e);});
    $("#swapForm").on("submit", function(e) {onSubmitSwap(e);});
});

