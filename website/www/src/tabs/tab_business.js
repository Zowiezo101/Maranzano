    
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
            // Success, update the table
            updateGarageTable(results.data);
            
            // Set the select-all checkbox unchecked
            $('#checkAll').prop('checked', false);
            $('#checkAll').prop('indeterminate', false);
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
        
    // Remove any previous errors or message
    onResetAllForms();
        
    // The checked checkboxes
    var checked = $('#garageTable tbody').find(':checked');
    
    // The data for the request
    var vehicle_ids = [];
    
    // The IDs of the checked checkboxes
    for (var i = 0; i < checked.length; i++) {
        var checkbox = checked.get(i);
        vehicle_ids.push(checkbox.value);
    }; 
        
    // Put the data in an easier-to-send format
    var data = {
        "ids": vehicle_ids.join(",")
    };
    
    fetchSellVehicles(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#sellError");
        } else {
            
            // Update the table with the new amounts
            updatePlayerInfo();
            
            // Refresh the page
            onGarageTab();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onClickCheckbox() {
    // All the select boxes
    var num_boxes = $('#garageTable tbody').find('[type="checkbox"]').length;
    var num_checked = $('#garageTable tbody').find(':checked').length;
    
    // Check if everything is already selected
    if (num_checked === num_boxes) {
        // Set the select-all checkbox checked
        $('#checkAll').prop('checked', true);
        $('#checkAll').prop('indeterminate', false);
        
    } else if (num_checked === 0) {
        // Set the select-all checkbox unchecked
        $('#checkAll').prop('checked', false);
        $('#checkAll').prop('indeterminate', false);
    } else {
        // Set the select-all checkbox to indeterminate
        $('#checkAll').prop('checked', false);
        $('#checkAll').prop('indeterminate', true);
        
    }
}

function onSelectAll() {
    var checked = $("#checkAll").prop('checked');
    var indeterminate = $("#checkAll").prop('indeterminate');
    
    if (indeterminate === true || checked === true) {
        // Select everything
        $('#garageTable tbody').find('[type="checkbox"]').prop('checked', true);
    } else if (checked === false) {
        // Toggle to unselect everything
        $('#garageTable tbody').find('[type="checkbox"]').prop('checked', false);
    }
}

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
                <td><img class="img-fluid border border-2 border-black" src="${data[row]["img"]}"/></td>
                <td>${data[row]["worth"]}</td>
                <td><input class="form-check-input" type="checkbox" value="${data[row]["id"]}"></td>
            </tr>`);
        }
    } else {
        $("#garageTableError").removeClass("d-none");
    }

    $("#garageTable tbody").append(rows.join(""));
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

