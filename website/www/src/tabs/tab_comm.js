
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
function onFriendsTab() {
    
    // The fetch call
    fetchFriendList().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#friendError");
        } else {
            // Success, update the player list
            updateFriendList(results.data);
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

function updateFriendList(data) {
    updateStatusFriendList(data, "online");
    updateStatusFriendList(data, "offline");
}

function updateStatusFriendList(data, status) {
    $("#" + status + "Friends tbody").empty();
    
    var rows = [];
    if (data[status].length > 0) {
        for (var row in data[status]) {
            rows.push(
                `<tr>
                    <td><b>${data[status][row]["name"]}</b></td>
                    <td><b>
                        <button class="btn btn-link p-0 mb-1 text-danger" onclick="onClickMessage(${data[status][row]["id"]}, '${data[status][row]["name"]}')">
                            <b>${data[status][row]["message"]}</b>
                        </button>
                    </b></td>
                    <td><b>
                        <button class="btn btn-link p-0 mb-1 text-black-50" onclick="onClickFriendDelete(${data[status][row]["id"]})">
                            <b>${data[status][row]["remove"]}</b>
                        </button>
                    </b></td>
                </tr>`);
        }
    } else {
        $("#" + status + "FriendsError").removeClass("d-none");
    }

    $("#" + status + "Friends tbody").append(rows.join(""));
}

function updatePlayerTable(data) {
    $("#playerlist tbody").empty();
    
    var rows = [];
    if (data.length > 0) {
        for (var row in data) {
            var classNames = "";
            var nameIcon = " • ";
            var onclickMessage = `onclick="onClickMessage(${data[row]["id"]}, '${data[row]["name"]}')"`;
            var onclickBefriend = `onclick="onClickBefriend(${data[row]["id"]})"`;
            
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
            
            // TODO: Ban option for moderators will be implemented later
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
                reply = `<button class="btn btn-link mb-1 px-0 py-0" onclick="onClickReply(${data[row]["sender_id"]}, '${data[row]["from"]}')">
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

function onClickMessage(player_id, player_name) {
    // TODO
    // Update the title
    $("#messageToName").text(player_name);
    
    // Show the message modal
    var modal = new bootstrap.Modal("#messageModal");
    modal.show();
    
}

function onClickReply(player_id, player_name) {
    // TODO
    // Update the title
    $("#messageToName").text(player_name);
    
    // Show the message modal
    var modal = new bootstrap.Modal("#messageModal");
    modal.show();
}

function onClickFriendDelete(player_id) {  
    // Remove any previous errors or message
    onResetAllForms();
        
    // Put the data in an easier-to-send format
    var data = {
        "id": player_id
    };
    
    fetchDeleteFriend(data).then(function(results) {
        onFriendsTab();
        
        // Handle the results of the fetch call
        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#friendError");
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
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

function onSubmitFriend() {
    event.preventDefault();
        
    // Remove any previous errors or message
    onResetAllForms();
        
    // The data for the request
    var player = $("#friendName").val();
        
    // Put the data in an easier-to-send format
    var data = {
        "player" : player
    };
    
    fetchAddFriend(data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#friendAddError");
        } else {
            // Show the success message
            $("#friendAddSuccess").removeClass("d-none");
            
            // Update the friends table
            onFriendsTab();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}
    
// Load the form sending a message
function onModalMessage() {
    var html = getHTML(`
                <-- Subject -->
                <div class="mb-3 row">
                    <label for="messageSubject" class="col-4 col-sm-2 col-form-label"><?php printString("login.email"); ?></label>
                    <div class="col-8 col-sm-10">
                        <input type="input" class="form-control" id="messageSubject" required>
                    </div>
                </div>

                <-- Message -->
                <div class="mb-3 row">
                    <label for="messageBody" class="col-4 col-sm-2 col-form-label"><?php printString("login.password"); ?></label>
                    <div class="col-8 col-sm-10">
                        <input type="input" class="form-control" id="messageBody" required>
                    </div>
                </div>

                <-- Login button -->
                <div class="mb-3 mx-3 row">
                    <button type="submit" class="btn btn-primary"><?php printString("menu.login"); ?></button>
                </div>

                <-- Error message -->
                <div id="loginError" class="mb-4 mx-3 text-center text-warning d-none">
                    <-- Filled in later in case of error -->
                </div>`);

    // Update the information
    $("#messageForm").html(html);
}
    
// Create a fetch call to prevent reloading the page
function onSubmitMessage(event) {
    event.preventDefault();

    // Remove any previous errors
    onResetAllForms();

    // The data for resetting password
    var resetEmail = $("#resetEmail").val();

    // Put the data in an easier-to-send format
    var data = {
        "email": resetEmail
    };

    // The fetch call
    fetchPost("reset", data).then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#resetError");
        } else {
            // Success, show the confirm content
            onInformReset();
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}
    
// Update the form to inform the player that a message has been sent
function onInformReset() {
    var html = getHTML(`
                    <-- Inform user that an e-mail has been send for password reset -->
                    <div class="row text-center">
                        <p><?php printString("reset.email"); ?></p>
                    </div>

                    <-- Done -->
                    <div class="mb-3 mx-3 row">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" ><?php printString("global.done"); ?></button>
                    </div>`);

    // Update the information
    $("#reset-form").html(html);
}

$(function() {
    // Set prevent page reloading when submitting form
    $("#friendForm").on("submit", function(e) {onSubmitFriend(e);});
    $("messageForm").on("submit", function(e) {onSubmitMessage(e);});
    
    // Make sure the login modal gets reset whenever it gets hidden
    $("#messageModal").on("hidden.bs.modal", function(){
        onModalMessage();
    });

    // Show the message content in the message modal
    onModalMessage();
});