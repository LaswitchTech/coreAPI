<?php

// These must be at the top of your script, not inside a function
use LaswitchTech\coreConfigurator\Configurator;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Initiate Configurator
$Configurator = new Configurator('account');
?>
<!doctype html>
<html lang="en" class="h-100 w-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <title>Response</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="h-100 w-100">
        <div class="row h-100 w-100 m-0 p-0">
            <div class="col h-100 m-0 p-0">
                <div class="container h-100">
                    <div class="d-flex h-100 row align-items-center justify-content-center">
                        <div class="col py-5">
                            <h3>Using Javascript <strong>API</strong></h3>
                            <div class="btn-group w-100 border my-4">
                                <a href="/" class="btn btn-block btn-primary">Refresh</a>
                                <a href="curl.php" class="btn btn-block btn-info">cURL</a>
                            </div>
                            <button id="Run" type="button" class="btn w-100 btn-block btn-success mb-4">Run</button>
                            <pre id="Response" class="mb-4 text-bg-dark p-3 rounded"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/dist/js/API.js"></script>
        <script>
            const Call = new API()
            Call.setAuth("BEARER","<?= $Configurator->get('account','token') ?>")
            $('#Run').click(function(){
                Call.get("user/list",{
                    success:function(result,status,xhr){
                        $('#Response').text('Response: ' + JSON.stringify(result, null, 2))
                    },
                    error:function(xhr,status,error){
                        $('#Response').text('Response: ' + error)
                    },
                })
            })
        </script>
    </body>
</html>
