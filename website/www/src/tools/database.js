
// Send a post request to the given URL with fetch
function fetchPost(url, data) {
    // Makes it easier in case the file is moved
    var base_url = "/api/";

    // Prepend these to the given URL
    url = base_url + url;

    response = fetch(url, {
        method: "POST",
        credentials: "include",
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        },
        body: JSON.stringify(data)
    });

    return response.then(
        response => response.text()
    ).then (function (response) {
        console.log(response);
        return JSON.parse(response);
    });
}


