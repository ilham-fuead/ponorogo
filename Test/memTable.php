<?php
include_once '../vendor/autoload.php';
include_once '../settings/db/dbsettings.php';

$DBQueryObj=new DBQuery($host, $username, $password, $database_name);

$tblName='kutipan_mem_bckafulldddd';
$sqlStatement='(SELECT * FROM kutipan)';
$cmd1 = "CREATE TABLE {$tblName} SELECT * FROM {$sqlStatement} AS tbl_used WHERE 1=2;";
$cmd2 = "ALTER TABLE {$tblName} ENGINE=MEMORY;";
$cmd3 = "INSERT INTO {$tblName} SELECT * FROM {$sqlStatement} AS tbl_used;";

if (executeTableLevelCommand($DBQueryObj, $cmd1, 'cmd1 err : ' . $cmd1)) {
    if (executeTableLevelCommand($DBQueryObj, $cmd2, 'cmd2 err')) {
        if (executeTableLevelCommand($DBQueryObj, $cmd3, 'cmd3 err')) {
            $memTableName = $tblName;
        }
    }
}

echo $memTableName;

function executeTableLevelCommand(DBQuery $DBQueryObj, $cmdSql, $customErrorString = 'DB setup failed execution.') {
    $DBQueryObj->setSQL_Statement($cmdSql);
    $DBQueryObj->executeNon_Query();

    if ($DBQueryObj->getCommandStatus()) {
        return true;
    } else {
        throw new Exception($customErrorString);
    }
}
