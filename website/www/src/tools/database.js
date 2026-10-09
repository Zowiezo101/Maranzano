
// Get the player info for the member page
function fetchPlayerInfo() {
    return fetchGet("get_player_info");
}

// Get the player stats for the home tab
function fetchPlayerStats() {
    return fetchGet("get_player_stats");
}

// Get a list of all the players
function fetchAllPlayers() {
    return fetchGet("get_all_players");
}

// Get a list of all the online players
function fetchOnlinePlayers() {
    return fetchGet("get_online_count");
}

// Get the list of locations to travel to
function fetchAllLocations() {
    return fetchGet("get_locations");
}

// Travel to a different location
function fetchTravel(data) {
    return fetchPost("move_to_location", data);
}

// Get the list of inmates from this jail
function fetchAllInmates() {
    return fetchGet("get_inmates");
}

// Pay bail for this player
function fetchPayBail(data) {
    return fetchPost("pay_bail", data);
}

// Try to bust this player out
function fetchBustOut(data) {
    return fetchPost("bust_out", data);
}

// Create a new player
function fetchNewPlayer(data) {
    return fetchPost("create_new_player", data);
}

// Get the list of friends of this player
function fetchFriends() {
    return fetchGet("get_friends");
}

// Buy bullets
function fetchShopBullets(data) {
    return fetchPost("buy_bullets", data);
}

// Swap bullets
function fetchSwapBullets(data) {
    return fetchPost("swap_bullets", data);
}

// Deposit money
function fetchDeposit(data) {
    return fetchPost("deposit_money", data);
}

// Withdraw money
function fetchWithdraw(data) {
    return fetchPost("withdraw_money", data);
}

// Send money
function fetchSendMoney(data) {
    return fetchPost("send_money", data);
}

// Get the vehicles
function fetchAllVehicles() {
    return fetchGet("get_vehicles");
}

// Sell the vehicles
function fetchSellVehicles(data) {
    return fetchPost("sell_vehicles", data);
}

// Get the success rate
function fetchBikeRate() {
    return fetchGet("get_bike_rate");
}

// Steal the bike
function fetchStealBike() {
    return fetchGet("steal_bike");
}

// Get the success rate
function fetchCarRate() {
    return fetchGet("get_car_rate");
}

// Steal the car
function fetchStealCar() {
    return fetchGet("steal_car");
}

// Get the success rate
function fetchStoreRate() {
    return fetchGet("get_store_rate");
}

// Rob the store
function fetchRobStore() {
    return fetchGet("rob_store");
}

// Befriend a player
function fetchBefriendPlayer(data) {
    return fetchPost("friend_request", data);
}

// Get a list of all the online players
function fetchOnlineList() {
    return fetchGet("get_online_players");
}

// Get a list of all the messages for this player
function fetchAllMessages() {
    return fetchGet("get_messages");
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


