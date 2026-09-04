
// Send a post request to the given URL with fetch
function fetchPost(url, data) {
    // Makes it easier in case the file is moved
    var base_url = "api/auth/";

    // Prepend these to the given URL
    url = base_url + url;

    response = fetch(url, {
        method: "POST",
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

// Netbeans does not like HTML comments in jQuery as it confused it with React
// Therefore we're adding the comments back in by added the missing '!'
function getHTML(html) {
    // Get the HTML comments back
    return html.replaceAll("<--", "<!--");
}
