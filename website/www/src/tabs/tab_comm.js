
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
            var id = data[row]["id"];
            var body = data[row]["body"];
            
            // Show something as read or unread
            var className = "fw-bolder";
            if (data[row]["read"] === 1) {
                className = "fw-normal";
            }
            
            var reply = "";
            if (data[row]["sender_id"] !== -999) {
                // Can't reply to system messages
                reply = `<button class="btn btn-link mb-1 px-0 py-0" onclick="onClickReply(${data[row]["sender_id"]})">
                            ${data[row]["reply"]}
                        </button>`;
            }
            
            // Friend request stuff
            if (body.includes("[accept_friend_request]")) {
                // We need to rewrite this as a link
                body = body.replace("[accept_friend_request]", `<button class="btn btn-link mb-1 px-0 py-0" onclick="onClickAcceptFriendRequest(${id})">accept</button>`);
            }
            
            if (body.includes("[decline_friend_request]")) {
                // We need to rewrite this as a link
                body = body.replace("[decline_friend_request]", `<button class="btn btn-link mb-1 px-0 py-0" onclick="onClickDeclineFriendRequest(${id})">decline</button>`);
            }
            
            rows.push(
                `<tr id="tr_${id}" onclick="onClickMarkAsRead(${id}, ${data[row]["read"]})"><td class="px-2">
                    <input class="form-check-input" type="checkbox" value="${id}">
                </td><td id="td_${id}" class="${className}">
                    <div class="row text-black">
                        <div class="col-6">
                            ${data[row]["from"]}
                        </div>
                        <div class="col-6 text-end">
                            ${data[row]["sent"]}
                        </div>
                    </div>
                    <hr class="my-0 py-0">
                    <div class="row text-black">
                        <span>${data[row]["subject"]}</span>
                    </div>
                    <div id="body_${id}" class="row text-black-50 body-div d-none">
                        <span>${body}</span>
                        ${reply}
                    </div>
                </td></tr>`);
        }
    } else {
        $("#maillistError").removeClass("d-none");
    }

    $("#maillist tbody").append(rows.join(""));
}

function onClickMessage(player_id) {
    // TODO
}

function onClickReply(player_id) {
    // TODO
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

function onClickAcceptFriendRequest(message_id) {
    // TODO
}

function onClickDeclineFriendRequest(message_id) {
    // TODO
}

function onClickMarkAsRead(message_id, read) {  
        
    // Remove any previous errors or message
    onResetAllForms();
    
    // Show the clicked message
    $(".body-div").addClass("d-none");
    $(`#body_${message_id}`).toggleClass("d-none");
    
    if (read === 0) {
        // Mark the message as read
        $(`#td_${message_id}`).removeClass("fw-bolder").addClass("fw-normal");  
    
        // Put the data in an easier-to-send format
        var data = {
            "id": message_id
        };

        fetchMarkAsRead(data).then(function(results) {
            // Handle the results of the fetch call

            if (results.error !== "" && results.error !== null) {
                // Something went wrong, show an error message
                onReturnedError(results.error, "#mailError");
            } else {

                // Update the table with the new unread message amount
                updatePlayerInfo();
                
                // Update the function to no longer access the server for this
                $(`#tr_${message_id}`).click(function() {
                    onClickMarkAsRead(message_id, 1);
                });
            }

        }).catch(function(results) {
            // Show an error if anything went wrong
            alert("error: " + results);
        });
    }
}

function onClickDelete() {    
        
    // Remove any previous errors or message
    onResetAllForms();
    
    // The checked checkboxes
    var checked = $('#maillist tbody').find(':checked');
    
    // The data for the request
    var message_ids = [];
    
    // The IDs of the checked checkboxes
    for (var i = 0; i < checked.length; i++) {
        var checkbox = checked.get(i);
        message_ids.push(checkbox.value);
    }; 
        
    // Put the data in an easier-to-send format
    var data = {
        "ids": message_ids.join(",")
    };
    
    fetchDeleteMessages(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#mailError");
        } else {
            
            // Update the table with the new unread message amount
            updatePlayerInfo();
            
            // Refresh the page
            onMailTab();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}