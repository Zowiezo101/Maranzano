<?php     
    require __DIR__ . "/../tools/base.php";
    
    $page_title = "reset.title";
    
    $result = validateReset();
    
    // check the result for errors
    if (isset($result) && ($result->error == "")) {
        // This request is valid
        $user = $result->data->name;
        $header = getString("reset.title");
        $title = "";
    } else {
        // The request is not valid
        $user = "";
        $header = getString("global.error");
        $title = $result->error;
    }
?>

<!doctype html>

<html>
    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Imports (External scripts) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
        
        <!-- Imports (Local scripts) -->
        <script src="../src/tools/base.js"></script>
        <script src="../src/tools/database.js"></script>

        <!-- Imports (CSS) -->
        <link rel="stylesheet" href="../../css/bootstrap.css" type="text/css"/>
        <link rel="stylesheet" href="../../css/mafiani.css" type="text/css"/>

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="../../img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../img/favicon-16x16.png">

        <title><?php printString($page_title); ?></title>
    </head>
    
    <body>        
        
        <!-- The container with all the rows and columns -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card bg-body-secondary text-center">
                        <div id="card-header" class="card-header bg-body-tertiary">
                            <?php echo $header; ?>
                        </div>
                        <div class="card-body">
                            <h5 id="card-title" class="card-title">
                                <?php echo $title; ?>
                            </h5>
                            <p id="card-text"  class="card-text">
<?php if ($user == "") { ?>
                                <?php printString("reset.again"); ?>
<?php } else { ?>
                                <form id="resetForm">
                                    <!-- Username -->
                                    <div class="mb-3 mx-3">
                                        <label for="resetUser" class="form-label"><?php printString("signup.username")?></label>
                                        <input type="text" disabled class="form-control text-center" id="resetUser" value=<?php echo json_encode($user); ?>>
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3 mx-3">
                                        <label for="resetPassword" class="form-label"><?php printString("signup.password")?></label>
                                        <input type="password" class="form-control" id="resetPassword" required>
                                    </div>

                                    <!-- Confirm password -->
                                    <div class="mb-4 mx-3">
                                        <label for="resetPassword2" class="form-label"><?php printString("signup.confirm")?></label>
                                        <input type="password" class="form-control" id="resetPassword2" required>
                                    </div>

                                    <!-- Reset button -->
                                    <div class="mb-3 mx-5 row">
                                        <button type="submit" class="btn btn-primary"><?php printString("login.reset")?></button>
                                    </div>

                                    <!-- Error message -->
                                    <div id="resetError" class="mb-4 mx-3 text-center text-warning d-none">
                                        <!-- Filled in later in case of error -->
                                    </div>
                                </form>
<?php } ?>                            
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
    
    // Create a fetch call to prevent reloading the page
    function onSubmitReset(event) {
        event.preventDefault();
        
        // Remove any previous errors
        onResetError("#resetError");
        
        // The data for registering
        var resetPass1 = $("#resetPassword").val();
        var resetPass2 = $("#resetPassword2").val();
        
        // Put the data in an easier-to-send format
        var data = {
            "pass" : resetPass1,
            "pass2": resetPass2
        };
        
        // Get the parameters from the URL
        var params = new URLSearchParams(document.location.search);
        
        if (params.has("token")) {
            // Insert the token parameter if this is available
            data["token"] = params.get("token");
        }

        // The fetch call
        fetchPost("update", data).then(function(results) {
            // Handle the results of the fetch call

            if (results.error !== "" && results.error !== null) {
                // Something went wrong, show an error message
                onReturnedError(results.error, "#resetError");
            } else {
                // Success, show the confirm content
                onReturnedSuccess();
            }

        }).catch(function(results) {
            // Show an error if anything went wrong
            alert("error: " + results);
        });
    }
    
    function onReturnedSuccess() {
        
        var header = <?php printString("reset.success", true); ?>;
        var title  = <?php printString("global.close", true); ?>;
        var body = `<div class="mb-3 mx-3 row">
                        <a href="/" class="btn btn-primary">
                            <?php printString("verify.home"); ?>
                        </a>
                    </div>`;
            
        // Show the results of the fetch call
        $("#card-header").html(header);
        $("#card-title").html(title);
        $("#card-body").html(body);
        
        // Remove the reset form
        $("#resetForm").html("");
    }

    $(function() {
        // Set prevent page reloading when submitting form
        $("#resetForm").on("submit", function(e) {onSubmitReset(e);});
    });

</script>
