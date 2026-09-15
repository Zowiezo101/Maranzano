<?php     
    require __DIR__ . "/../tools/base.php";
    
    $page_title = "verify.title";
    
    $result = verifyUser();
    
    // check the result for errors
    if (isset($result) && ($result->error == "")) {
        // This request is valid
        $header = getString("verify.success");
        $title = getString("global.close");
        $text = "";
    } else {
        // The request is not valid
        $header = getString("global.error");
        $title = $result->error;
        $text = getString("verify.again");
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
        
        <!-- TODO: This is for debugging purposes -->
        <?php require __DIR__ . "/../page/debug.php" ?>
        
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
                            <p  id="card-text"  class="card-text">
                                <?php echo $text; ?>
                            </p>
                            
                            <a href="/" class="btn btn-primary">
                                <?php printString("verify.home") ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
