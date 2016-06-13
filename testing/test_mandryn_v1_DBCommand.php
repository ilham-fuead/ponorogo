<?php
include_once '../vendor/autoload.php';
include_once '../settings/db/dbsettings.php';

//Let try using DBCommand class for db command execution

$DBCmdObj=new DBCommand(new DBQuery($host, $username, $password, $database_name));

$DBCmdObj->setDELETEfromTable('test');

$DBCmdObj->addConditionStatement('val',4, IFieldType::INTEGER_TYPE, IConditionOperator::NONE);

$DBCmdObj->executeQueryCommand();

//check if no error happened
if($DBCmdObj->getExecutionStatus()==true){
    //successfull execution, get affected row
    echo 'No error occured, numbers of row affected ' . $DBCmdObj->getAffectedRowCount();
}else{
    //display error number and message
    echo "{$DBCmdObj->getErrno()} : {$DBCmdObj->getError()}";
}

