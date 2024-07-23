#!/usr/bin/env php
<?php

// These must be at the top of your script, not inside a function
use LaswitchTech\coreConfigurator\Configurator;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Initiate Configurator
$Configurator = new Configurator('account');

// cURL Options
$url = $Configurator->get('account','url');
$token = $Configurator->get('account','token');

// Setup a Bearer cURL
$cURL = curl_init();
curl_setopt($cURL, CURLOPT_URL, $url . '/user/list');
curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($cURL, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . base64_encode($token)]);

// Execute cURL
$response = curl_exec($cURL);

// Output Response
if (curl_errno($cURL)) {
    $response = 'Error: ' . curl_error($cURL) . PHP_EOL;
} else {
    $response = 'Response: ' . $response . PHP_EOL;
}

// Close cURL
curl_close($cURL);

//Render
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
                            <h3>Using <strong>cURL</strong></h3>
                            <div class="btn-group w-100 border my-4">
                                <a href="curl.php" class="btn btn-block btn-primary">Refresh</a>
                                <a href="/" class="btn btn-block btn-info">Javascript</a>
                            </div>
                            <pre id="Response" class="mb-4 text-bg-dark p-3 rounded"><?= $response ?></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
