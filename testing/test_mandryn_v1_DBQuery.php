<?php
include_once '../vendor/autoload.php';
include_once '../settings/db/dbsettings.php';

$DBQueryObj = new DBQuery($host, $username, $password, $database_name);

$sql = 'SELECT kutipan_nokp FROM kutipan WHERE kutipan_nokp like "A%" AND kutipan_pkt="06"';
$DBQueryObj->setSQL_Statement($sql);
$DBQueryObj->runSQL_Query();

PHP_Timer::start();

echo 'Fetching rows..<br/>';
while($row=$DBQueryObj->fetchRow()){
    echo $row['kutipan_nokp'] . '<br/>';
}

$time = PHP_Timer::stop();
$fetchTimer=PHP_Timer::secondsToTimeString($time);

$DBQueryObj->runSQL_Query();


PHP_Timer::start();

$i=0;

echo '<hr/>';

echo 'Yielding rows..<br/>';
foreach($DBQueryObj->yieldRow() as $row) {
    echo ++$i . ' - ' . $row['kutipan_nokp'] . '<br/>';
}

$time = PHP_Timer::stop();
$yieldTimer=PHP_Timer::secondsToTimeString($time);

echo "<hr/>Fetching rows in $fetchTimer, Yielding rows in $yieldTimer";

echo '<hr/>JSON array<pre>';

$sql = 'SELECT kutipan_nokp FROM kutipan WHERE kutipan_nokp like "A%" AND kutipan_pkt="06" LIMIT 10';
$DBQueryObj->setSQL_Statement($sql);
$DBQueryObj->runSQL_Query();

echo $DBQueryObj->getRowsInJSON();

