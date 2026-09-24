
// Get the player info for the member page
function fetchPlayerInfo() {
    return fetchGet("get_player_info");
}

// Get the player stats for the home tab
function fetchPlayerStats() {
    return fetchGet("get_player_stats");
}

// Get the list of locations to travel to
function fetchAllLocations() {
    return fetchGet("get_locations");
}

function fetchNewPlayer(data) {
    return fetchPost("create_new_player", data);
}

function fetchTravel(data) {
    return fetchPost("move_to_location", data);
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
    return response.then(function (response) {
        isCookieValid(response);
        return response.text();
    }).then (function (response) {
        console.log(response);
        return JSON.parse(response);
    });
}

function isCookieValid(response) {
    if(response.status === 401) {
        // Cookie is no longer valid, we need to refresh the page
        window.location.href = "\\";
    }
}


