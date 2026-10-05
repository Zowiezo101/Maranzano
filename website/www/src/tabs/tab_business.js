    
function onBankTab() {
    
    // The fetch call
    fetchFriends().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong
        } else {
            // Success, update the select list
            updateFriendSelect("friend", results.data);
            
        }
    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}
function onGarageTab() {
    
    // The fetch call
    fetchAllVehicles().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong
        } else {
            // Success, update the select list
            updateGarageTable(results.data);
            
        }
    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}
    
function onSubmitShop(event) {
    event.preventDefault();
        
    // Remove any previous errors
    onResetAllForms();
        
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
    onResetAllForms();
        
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

function onSubmitDeposit(event) {
    event.preventDefault();
        
    // Remove any previous errors or message
    onResetAllForms();
        
    // The data for the request
    var amount = $("#depositAmount").val();
        
    // Put the data in an easier-to-send format
    var data = {
        "amount" : amount
    };
    
    fetchDeposit(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#depositError");
        } else {    
            // Show the success message
            $("#depositSuccess").removeClass("d-none");
            
            // Update the table with the new amounts
            updatePlayerInfo();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onSubmitWithdraw(event) {
    event.preventDefault();
        
    // Remove any previous errors or message
    onResetAllForms();
        
    // The data for the request
    var amount = $("#withdrawAmount").val();
        
    // Put the data in an easier-to-send format
    var data = {
        "amount" : amount
    };
    
    fetchWithdraw(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#withdrawError");
        } else {    
            // Show the success message
            $("#withdrawSuccess").removeClass("d-none");
            
            // Update the table with the new amounts
            updatePlayerInfo();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onSubmitSend(event) {
    event.preventDefault();
        
    // Remove any previous errors or message
    onResetAllForms();
        
    // The data for the request
    var friend_id = $("#friend").val();
    var amount = $("#sendAmount").val();
        
    // Put the data in an easier-to-send format
    var data = {
        "id": friend_id,
        "amount" : amount
    };
    
    fetchSendMoney(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#sendError");
        } else {    
            // Show the success message
            $("#sendSuccess").removeClass("d-none");
            
            // Update the table with the new amounts
            updatePlayerInfo();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onSubmitSell(event) {
    event.preventDefault();
}

$(function() {
    // Set prevent page reloading when submitting form
    $("#shopForm").on("submit", function(e) {onSubmitShop(e);});
    $("#swapForm").on("submit", function(e) {onSubmitSwap(e);});
    $("#depositForm").on("submit", function(e) {onSubmitDeposit(e);});
    $("#withdrawForm").on("submit", function(e) {onSubmitWithdraw(e);});
    $("#sendForm").on("submit", function(e) {onSubmitSend(e);});
    $("#garageForm").on("submit", function(e) {onSubmitSell(e);});
});

function updateFriendSelect(select, data) {
    var options = [];
    
    for (var value of Object.values(data)) {     
        var id = value["id"];
        var name = value["name"];
        
        options.push(`<option class="friend-option" value="${id}">${name}</option>`); 
    }
    
    // Remove the previous location options
    $(".friend-option").remove();
    
    // Insert the new location options
    $("#" + select).append(options.join(""));
}

function updateGarageTable(data) {
    $("#garageTable tbody").empty();
    
    var rows = [];
    if (data.length > 0) {
        for (var row in data) {
            // Name, time, city, BUST OUT
            rows.push(
            `<tr>
                <td><img class="img-fluid border border-2 border-black" src="` + data[row]["img"] + `"/></td>
                <td>` + data[row]["worth"] + `</td>
                <td><input class="form-check-input" type="checkbox" value="" id="checkVehicle` + data[row]["id"] + `"></td>
            </tr>`);
        }
    } else {
        $("#garageTableError").removeClass("d-none");
    }

    $("#garageTable tbody").append(rows.join(""));
}

