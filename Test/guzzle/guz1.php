<?php
include_once '../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

$client = new Client(['base_uri' => 'http://localhost/ponorogo/Test/BCK_PROCCESS/']);

// Initiate each request but do not block
$promises = [
    'pname' => $client->getAsync('print_name.php'),
    'memtable'   => $client->getAsync('memTable.php?ver=1')
];

// Wait on all of the requests to complete. Throws a ConnectException
// if any of the requests fail
$results = Promise\unwrap($promises);

// Wait for the requests to complete, even if some of them fail
$results = Promise\settle($promises)->wait();

// You can access each result using the key provided to the unwrap
// function.
var_dump($results['pname']);
//echo $results['memtable'];
