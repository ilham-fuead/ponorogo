<?php
include_once '../vendor/autoload.php';
use Mandryn\http\ConcurrentRequest;

$crObj=new ConcurrentRequest();
$crObj->addUrl('http://www.jpa.gov.my', ['id'=>'852852','page'=>'public']);
$crObj->addUrl('http://www.bpm.gov.my');

foreach ($crObj->getUrlListArray() as $url) {
    echo "$url <br/>";
}