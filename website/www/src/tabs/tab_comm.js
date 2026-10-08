
function onOnlineTab() {
    
    // The fetch call
    fetchOnlineList().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#onlineError");
        } else {
            // Success, update the player list
            updatePlayerList(results.data);
        }
    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function onPlayerlistTab() {
    
    // The fetch call
    fetchAllPlayers().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#playerError");
        } else {
            // Success, update the player list
            updatePlayerTable(results.data);
        }
    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

function updatePlayerTable(data) {
    $("#playerlist tbody").empty();
    
    var rows = [];
    if (data.length > 0) {
        for (var row in data) {
            var classNames = "";
            var nameIcon = " • ";
            var onclickMessage = 'onclick="onClickMessage(' + data[row]["id"] + ')"';
            var onclickBefriend = 'onclick="onClickBefriend(' + data[row]["id"] + ')"';
            
            // TODO: Add Ban option for moderators
            // Deceased, name, rank, message
            if (data[row]["deceased"]) {
                // Enclose it all with strikethrough tags (<s>)
                classNames = "text-decoration-line-through text-black-50";
                
                // Use a cross as name icon
                nameIcon = '<i class="fa-solid fa-cross fa-width-auto"></i>';
                
                // No onclick
                onclickMessage = 'disabled';
                onclickBefriend = 'disabled';
            }
            
            rows.push(
                `<tr>
                    <td class="${classNames}"><b>${nameIcon} ${data[row]["name"]}</b></td>
                    <td class="${classNames}"><b><i>${data[row]["rank"]}</i></b></td>
                    <td class="${classNames}"><b>
                        <button class="btn btn-link p-0 text-black-50" ${onclickMessage}>
                            <b>${data[row]["message"]}</b>
                        </button>
                    </b></td>
                    <td class="${classNames}"><b>
                        <button class="btn btn-link p-0 text-black-50" ${onclickBefriend}>
                            <b>${data[row]["befriend"]}</b>
                        </button>
                    </b></td>
                </tr>`);
        }
    } else {
        $("#playerlistError").removeClass("d-none");
    }

    $("#playerlist tbody").append(rows.join(""));
}

function updatePlayerList(data) {
    $("#onlineList").empty();
    
    var names = [];
    if (data.length > 0) {
        for (var row in data) {
            var name = data[row]["name"];
            
            if (data[row]["is_confirmed"] === 1) {
                name = `<span class="text-blue"><b>${data[row]["name"]}</b></span>`;
            }
            
            names.push(name);
        }
    }

    $("#onlineList").append(names.join(", "));
}

function onClickMessage(id) {
    
}

function onClickBefriend(player_id) {        
    // Remove any previous errors or message
    onResetAllForms();
        
    // Put the data in an easier-to-send format
    var data = {
        "id": player_id
    };
    
    fetchBefriendPlayer(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#playerError");
        } else {    
            // Show the success message
            $("#playerSuccess").text(results.data);
            $("#playerSuccess").removeClass("d-none");
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}


