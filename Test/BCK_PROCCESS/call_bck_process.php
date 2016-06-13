<?php
include_once '../../vendor/autoload.php';

const PHP_EXE_DIR='E:\xampp\php\php.exe';
$filename='E:\xampp\htdocs\ponorogo\Test\BCK_PROCESS\print_name.php';

$cmd = PHP_EXE_DIR . ' ' . $filename;

execInBackground($cmd);

function execInBackground($cmd) { 
    if (substr(php_uname(), 0, 7) == "Windows"){ 
        echo 'sini a ';
        echo pclose(popen("start /B ". $cmd, "r"));  
    } 
    else { 
        echo 'sini b ';
        exec($cmd . " > /dev/null &");   
    } 
}