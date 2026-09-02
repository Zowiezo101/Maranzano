
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

function getHTML(html) {
    // Get the HTML comments back
    return html.replaceAll("<--", "<!--");
}
