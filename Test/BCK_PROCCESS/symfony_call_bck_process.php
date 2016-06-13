<?php

include_once '../../vendor/autoload.php';

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\Exception\ProcessFailedException;

const PHP_EXE_DIR = 'E:\xampp\php\php.exe';

$filename1 = 'E:\xampp\htdocs\ponorogo\Test\BCK_PROCCESS\memTable.php?ver=12';
$filename2 = 'E:\xampp\htdocs\ponorogo\Test\BCK_PROCCESS\print_name.php';

$cmd = [];

//$cmd[] = PHP_EXE_DIR . ' ' . $filename1;
$cmd[] = PHP_EXE_DIR . ' ' . $filename2;

foreach ($cmd as $proc) {
    $process = new Process($proc);
    try {
        $process->mustRun();

        echo $process->getOutput();
    } catch (ProcessFailedException $e) {
        echo $e->getMessage();
    }
}

$process2 = new PhpProcess(<<<EOF
    <?php echo file_get_contents('E:\xampp\htdocs\ponorogo\Test\BCK_PROCCESS\memTable.php?ver=12'); ?>
EOF
);
try {
    $process2->run();
    $process2->getOutput();
} catch (ProcessFailedException $e) {
    echo $e->getMessage();
}
