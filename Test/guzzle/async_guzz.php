<?php

include_once '../../vendor/autoload.php';

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$client = new Client();
$filename=[];
$filename[] = 'memTable.php?ver=12';
$filename[] = 'print_name.php';

$requests = function ($filename) {
    $uri = 'http://127.0.0.1/ponorogo/Test/BCK_PROCCESS/';
    
    foreach ($filename as $cmd) {
        yield new Request('GET', $uri . $cmd);
    }
};

$pool = new Pool($client, $requests($filename), [
    'concurrency' => 100,
    'fulfilled' => function ($response, $index) {
        // this is delivered each successful response
    
        $body = $response->getBody();
        // Implicitly cast the body to a string and echo it
        echo "$index $body<br>";
    },
    'rejected' => function ($reason, $index) {
        // this is delivered each failed request
        
        $code = $response->getStatusCode(); // 200
        $reason = $response->getReasonPhrase(); // OK
        echo "$index $code $reason<br>";
    },
        ]);

// Initiate the transfers and create a promise
$promise = $pool->promise();

// Force the pool of requests to complete.
$promise->wait();

