<?php

include_once '../vendor/autoload.php';
include_once '../settings/db/dbsettings.php';

use Mandryn\db\DBQueryi;

function displayRecord(DBQueryi $DBQi) {
    foreach ($DBQi->getCustomSqlRecordset() as $row) {
        echo "{$row['doc_nokp']} - {$row['doc_catitan']}" . '<br/>';
    }
    echo '<hr/>';
}

$DBQi = new DBQueryi($host, $username, $password, $database_name);


/** SELECT query **/

$sql1 = 'SELECT doc_nokp,doc_catitan,doc_id '
        . 'FROM dokumen '
        . 'WHERE doc_nokp = :doc_nokp ';

$plcValArr1 = [
    ':doc_nokp' => 'A1352182'
];

$DBQi->setCustomSql($sql1, $plcValArr1);

displayRecord($DBQi);

/** UPDATE command **/

$sqlUpdate = 'UPDATE dokumen '
        . 'SET doc_catitan=:doc_catitan '
        . 'WHERE doc_nokp = :doc_nokp ';

$plcValArrUpdate = [
    ':doc_catitan' => 'catatan for A1352182',
    ':doc_nokp' => 'A1352182'
];

$DBQi->setCustomSql($sqlUpdate, $plcValArrUpdate);

if ($DBQi->executeCustomSqlCommand()) {
    //affected row returned 0 if (update value) == (updated field value) (Nothing updated eventhough successfull)  
    echo $DBQi->affectedRow . ' rows updated';
    echo '<hr/>';
}

/** UPDATE multiple times **/

$updateMultipleSql = 'UPDATE dokumen '
        . 'SET doc_catitan=:doc_catitan '
        . 'WHERE doc_id=:doc_id';

$plcValArrMul = [
    ':doc_catitan' => '',
    ':doc_id' => ''
];

$DBQiUpd = new DBQueryi($host, $username, $password, $database_name);
$DBQiUpd->setCustomSql($updateMultipleSql, $plcValArrMul);

$i = 1;

$DBQi->setCustomSql($sql1, $plcValArr1);

foreach ($DBQi->getCustomSqlRecordset() as $row) {
    $catatan = "{$row['doc_nokp']} catatan " . $i++;
    $DBQiUpd->setFieldValue(':doc_id', $row['doc_id']);    
    $DBQiUpd->setFieldValue(':doc_catitan', $catatan);
    $status=$DBQiUpd->executeCustomSqlCommand();
}

displayRecord($DBQi);