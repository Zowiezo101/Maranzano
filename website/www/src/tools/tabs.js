
// TODO: update main player info table as well if needed after certain action}

var ranks = {
    // Ranks
    "rank-1": "Rookie",
    "rank-2": "Hustler",
    "rank-3": "Thug",
    "rank-4": "Enforcer",
    "rank-5": "Associate",
    "rank-6": "Runner",
    "rank-7": "Mafioso",
    "rank-8": "Soldier",
    "rank-9": "Hitman",
    "rank-10": "Veteran",
    "rank-11": "Captain",
    "rank-12": "Lieutenant",
    "rank-13": "Consigliere",
    "rank-14": "Underboss",
    "rank-15": "Boss",
    "rank-16": "Don",
    "rank-17": "Kingpin",
    "rank-18": "Mafia Lod",
    "rank-19": "Supreme Boss",
    "rank-20": "Godfather"
};

function getRankName(level) {
    // Insert the level into the string
    var name = "rank-" + level;

    // And use it as a key into $strings
    return ranks[name];
}

function onShowTab(event) {
    // Update the player info table
    updatePlayerInfo();

    // Insert the data per tab
    switch(event.target.id) {
        case "btnHome":
            insertHomeData();
            break;
    }
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
            // Is the player still alive?
            isAlive(results.data);
            
            // Remove the error message
            infoError.addClass("d-none");
            infoTable.removeClass("d-none");

            // Success, update the table
            updateTable("info", results.data);

            // Update the tabs
            updateTabs(results.data);
        }

    }).catch(function(results) {
        // Show an error if anything went wrong
        alert("error: " + results);
    });
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

function updateTabs(data) {
    var rank = parseInt(data["rank"]);
    
    // For all ranks
    for (var i = 1; i <= Object.keys(ranks).length; i++) {
        // If the player's rank is high enough
        if (rank >= i) {
            // Show the corresponding tabs
            $("[data-rank=" + i + "]").removeClass("d-none");
        }
    }
}

function isAlive(data) {
    
    if (data["deceased"] === true) {
        // You died!
        $("#killerName").text(data["killed_by"]);
        $("#btnDeceased").tab('show');
        
        // Get the player name to add a cross to their name
        var playerName = $("#playerName").html();
        
        // The cross that will be added
        var cross = '<i class="fa-solid fa-cross"></i>';

        // Make sure the cross isn't added twice
        if (!playerName.includes(cross)) {
            // Add a cross to the persons name
            $("#playerName").html(playerName + cross);
        }
        
        // Disable all other tabs temporarily
        $('[role="tab"]').attr("disabled", true);
    } else {
        // Enable all tabs again
        $('[role="tab"]').attr("disabled", false);
    }
}

$(function () {

    // Activate this function if a tab is loading
    $('button[data-bs-toggle="tab"]').on("show.bs.tab", function(event){
        onShowTab(event);
    });

    // Load the home tab after the page has loaded
    $("#btnHome").tab('show');
});


