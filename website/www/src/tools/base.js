
    function fetchPost(url, data) {
        // The base URL and page language are stored in the body of the page
        var base_url = "";

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


