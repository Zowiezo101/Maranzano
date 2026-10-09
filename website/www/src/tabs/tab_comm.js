
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

function onMailTab() {
    
    // The fetch call
    fetchAllMessages().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#mailError");
        } else {
            // Success, update the player list
            updateMailTable(results.data);
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

function updateMailTable(data) {
    $("#maillist tbody").empty();
    
    var rows = [];
    if (data.length > 0) {
        for (var row in data) {
            
            var tag = "b";
            if (data[row]["read"] === 1) {
                tag = "span";
            }
            
            var body = data[row]["body"];
            if (body.includes("[accept_friend_request]")) {
                var id = data[row]["id"];
                // We need to rewrite this as a link
                // TODO:
                body = body.replace("[accept_friend_request]", `<button class="btn btn-link mb-1 px-0 py-0" onclick="onClickAcceptFriendRequest(` + id + `)">accept</button>`);
            }
            
            if (body.includes("[decline_friend_request]")) {
                var id = data[row]["id"];
                // We need to rewrite this as a link
                // TODO:
                body = body.replace("[decline_friend_request]", `<button class="btn btn-link mb-1 px-0 py-0" onclick="onClickDeclineFriendRequest(` + id + `)">decline</button>`);
            }
            
            rows.push(
                `<tr><td class="px-2">
                    <input class="form-check-input" type="checkbox">
                </td><td>
                    <div class="row text-black-50">
                        <div class="col-6">
                            <b>${data[row]["from"]}</b>
                        </div>
                        <div class="col-6 text-end">
                            <b>${data[row]["sent"]}</b>
                        </div>
                    </div>
                    <hr class="my-0 py-0">
                    <div class="row text-black-50">
                        <${tag}>${data[row]["subject"]}</${tag}>
                    </div>
                    <div class="row">
                        <${tag}>${body}</${tag}>
                    </div>
                </td></tr>`);
        }
    } else {
        $("#maillistError").removeClass("d-none");
    }

    $("#maillist tbody").append(rows.join(""));
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

function onClickAcceptFriendRequest(player_id) {
    
}

function onClickDeclineFriendRequest(player_id) {
    
}


