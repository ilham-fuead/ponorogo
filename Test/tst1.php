<?php
include_once '../vendor/autoload.php';
include_once '../settings/db/dbsettings.php';

use Symfony\Component\Debug\Debug;
use Mandryn\db\Query;
use Mandryn\db\DBQueryi;
use Mandryn\db\constant\QueryType;
use Mandryn\db\constant\ConditionType;
use Mandryn\db\constant\DataType;
use Mandryn\db\constant\AppenderOperator;
use Mandryn\db\constant\SqlStringType;


Debug::enable(E_ALL, true);

$query=new Query(QueryType::UPDATE);
$query->setTable('peribadi');
$query->setUpdateField('peribadi_nama','MOHD SAIFUL NIZAM BIN ABDUL KARIM Mr Mandryn 2',DataType::VARCHAR);
$query->setConditionField('peribadi_nokp', ConditionType::EQUAL, ' 920831145545', DataType::VARCHAR, AppenderOperator::NONE_OPR);

echo $query->getQueryString();

$DBQi=new DBQueryi($host, $username, $password, $database_name);
$DBQi->setQuery($query);
$DBQi->execute();

$DBQi->setParamNewValue('peribadi_nama', 'SYAZWAN BIN MOHAMAD SODIKIN Mandryn 2');
$DBQi->setParamNewValue('peribadi_nokp', ' 920830105437');
$DBQi->executez();




