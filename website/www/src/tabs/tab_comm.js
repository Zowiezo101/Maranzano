
function onPlayerlistTab() {
    
    // The fetch call
    fetchAllPlayers().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            onReturnedError(results.error, "#databaseError");
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
            var onclick = 'onclick="messagePlayer(' + data[row]["id"] + ')"';
            
            // TODO: Add Ban option for moderators
            // Deceased, name, rank, message
            if (data[row]["deceased"]) {
                // Enclose it all with strikethrough tags (<s>)
                classNames = "text-decoration-line-through text-black-50";
                
                // Use a cross as name icon
                nameIcon = '<i class="fa-solid fa-cross fa-width-auto"></i>';
                
                // No onclick
                onclick = 'disabled';
            }
            
            rows.push(
                `<tr>
                    <td class="${classNames}"><b>${nameIcon} ${data[row]["name"]}</b></td>
                    <td class="${classNames}"><b><i>${data[row]["rank"]}</i></b></td>
                    <td class="${classNames}"><b>
                        <button class="btn btn-link p-0 text-black-50" ${onclick}>
                            <b>Message</b>
                        </button>
                    </b></td>
                </tr>`);
        }
    } else {
        $("#playerlistError").removeClass("d-none");
    }

    $("#playerlist tbody").append(rows.join(""));
}


