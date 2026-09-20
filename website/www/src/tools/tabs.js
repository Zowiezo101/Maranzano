
// TODO: update main player info table as well if needed after certain action}

var ranks = {
    // Ranks
    "rank.1": "Rookie",
    "rank.2": "Hustler",
    "rank.3": "Thug",
    "rank.4": "Enforcer",
    "rank.5": "Associate",
    "rank.6": "Runner",
    "rank.7": "Mafioso",
    "rank.8": "Soldier",
    "rank.9": "Hitman",
    "rank.10": "Veteran",
    "rank.11": "Captain",
    "rank.12": "Lieutenant",
    "rank.13": "Consigliere",
    "rank.14": "Underboss",
    "rank.15": "Boss",
    "rank.16": "Don",
    "rank.17": "Kingpin",
    "rank.18": "Mafia Lod",
    "rank.19": "Supreme Boss",
    "rank.20": "Godfather"
};

function getRankName(level) {
    // Insert the level into the string
    var name = "rank." + level;

    // And use it as a key into $strings
    return ranks[name];
}

function onShowTab(event) {
    // Update the player info table
    updatePlayerInfo();

    switch(event.target.id) {
        case "btnHome":
            insertHomeData();
            break;
    }
}

function updateTable(table, data) {
    for (var [key, value] of Object.entries(data)) {
        if (key === "rank" || key === "prank") {
            // Use the rank name
            value = getRankName(value);
        }

        $("#data" + ucfirst(table) + ucfirst(key)).text(value);
    };
}

function updatePlayerInfo() {
    var infoError = $("#playerInfoError");
    var infoTable = $("#playerInfo");

    // The fetch call
    fetchPlayerInfo().then(function(results) {
        // Handle the results of the fetch call

        if (results.error !== "" && results.error !== null) {
            // Something went wrong, show an error message
            infoError.removeClass("d-none");
            infoTable.addClass("d-none");
        } else {
            // Remove the error message
            infoError.addClass("d-none");
            infoTable.removeClass("d-none");

            // Success, update the table
            updateTable("info", results.data);

            // TODO: Update the tabs
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
}

$(function () {

    // Activate this function if a tab is loading
    $('button[data-bs-toggle="tab"]').on("show.bs.tab", function(event){
        onShowTab(event);
    });

    // Load the home tab after the page has loaded
    $("#btnHome").tab('show');
});


