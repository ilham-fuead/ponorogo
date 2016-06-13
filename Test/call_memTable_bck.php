<?php
include_once '../vendor/autoload.php';

const PHP_EXE_DIR='E:\xampp\php\php.exe';
$filename='E:\xampp\htdocs\ponorogo\Test\memTable.php';

$cmd = "{PHP_EXE_DIR} {$filename}";

execInBackground($cmd);

function execInBackground($cmd) { 
    if (substr(php_uname(), 0, 7) == "Windows"){ 
        echo 'sini a ';
        exit;
        pclose(popen("start /B ". $cmd, "r"));  
    } 
    else { 
        echo 'sini b ';
        exit;
        exec($cmd . " > /dev/null &");   
    } 
}

