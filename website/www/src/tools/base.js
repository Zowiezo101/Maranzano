
// Netbeans does not like HTML comments in jQuery as it confused it with React
// Therefore we're adding the comments back in by added the missing '!'
function getHTML(html) {
    // Get the HTML comments back
    return html.replaceAll("<--", "<!--");
}
    
// If there is an error
function onReturnedError(message, element) {
    $(element).html(message).removeClass("d-none");
}

// Remove the error
function onResetError(element) {
    $(element).html("").addClass("d-none");
}

// A rewrite of a PHP function
function ucfirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
