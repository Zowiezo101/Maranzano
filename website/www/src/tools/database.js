
// Get the player info for the member page
function fetchPlayerInfo() {
    return fetchGet("get_player_info");
}

// Get the player stats for the home tab
function fetchPlayerStats() {
    return fetchGet("get_player_stats");
}

function fetchNewPlayer(data) {
    return fetchPost("get_new_player", data);
}

// Send a post request to the given URL with fetch
function fetchGet(url) {
    var response = fetchRequest(url, "GET");
    return response;
}

// Send a post request to the given URL with fetch
function fetchPost(url, data) {
    var response = fetchRequest(url, "POST", data);
    return response;
}

function fetchRequest(url, method, data = null) {
    // Makes it easier in case the file is moved
    var base_url = "/api/";
    
    // The options for this fetch request
    var fetch_options = {
        method: method,
        credentials: "include",
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    };
    
    if (data !== null) {
        // Add the data if it's given
        fetch_options["body"] = JSON.stringify(data);
    }

    // The actual fetch request itself
    var response = fetch(base_url + url, fetch_options);

    // The response
    return response.then(
        response => response.text()
    ).then (function (response) {
        console.log(response);
        return JSON.parse(response);
    });
}


