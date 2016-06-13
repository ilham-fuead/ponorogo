<?php

include_once '../vendor/autoload.php';
include_once '../settings/db/dbsettings.php';
require_once 'VirtualPaging.php';

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

ini_set('output_buffering', 0);
ini_set('max_execution_time', 0);
set_time_limit(0);

const ROWS_PER_PAGE = 500;

$DBQueryObj = new DBQuery($host, $username, $password, $database_name);

$namaTable = 'kutipan';
$kodSuratTerlibat = '11,14';
$kodSuratTidakTerlibat = '';
$kodSuratConfirmTakPerluBuat = '91,41,23,92,12,51,97'; //selesaibayar, meninggaldunia, bankrupt, pembatalantuntutan, penangguhantuntutan, pindahkontrak, selesai&terlebihbayar
$jenisTuntutan = 'P';

if (isset($_GET['nokpPenama'])) {
    $nokpPenama = addslashes($_GET['nokpPenama']);
} else {
    $nokpPenama = '';
}

//---------------clear table--------------------------<<---whatever
if ($nokpPenama == '') {
    //truncate table jika proses semua rekod penama
    $sql1 = "TRUNCATE $namaTable";
} else {
    //delete data lama penama sahaja
    $sql1 = "DELETE FROM $namaTable WHERE kutipan_nokp = '$nokpPenama'";
}

$DBQueryObj->setSQL_Statement($sql1);
$DBQueryObj->executeNon_Query();

$obj = new MagicObject();

if ($DBQueryObj->getCommandStatus()) {
    $sql2 = "(SELECT DISTINCT doc_nokp, doc_peringkat FROM dokumen WHERE doc_kod IN (14,24) AND doc_nokp NOT IN 
				(SELECT DISTINCT dokumen2.doc_nokp 
					FROM dokumen dokumen2
					WHERE dokumen2.doc_kod IN ($kodSuratConfirmTakPerluBuat) 
					AND dokumen2.doc_nokp IS NOT NULL 
					AND dokumen2.doc_jenistuntutan = '$jenisTuntutan'
					AND dokumen2.doc_peringkat = dokumen.doc_peringkat)
				AND dokumen.doc_nokp IS NOT NULL AND dokumen.doc_jenistuntutan = '$jenisTuntutan'
				AND 
					dokumen.doc_tkh_surat = (SELECT max(dokumen3.doc_tkh_surat) FROM dokumen dokumen3 WHERE dokumen3.doc_kod IN (14,24) AND dokumen3.doc_nokp = dokumen.doc_nokp))";


    //echo $sql2 . '<br>'; 
    /*  TODO: sql2 - Paging for DB operation    */
    $virtualPagingObj = new VirtualPaging(new DBQuery($host, $username, $password, $database_name));
    $virtualPagingObj->setSQLStatement($sql2);
    $virtualPagingObj->setPagingProperty(IPagingType::MANUAL, ROWS_PER_PAGE, UniversalPaging::USE_MEM_ENG);

    //echo json_encode($virtualPagingObj->getPagingInfo());
    $pagingInfoObj = $virtualPagingObj->getPagingInfo();

    $client = new Client();
    $params = [];
    
    for ($i = 1; $i <= $pagingInfoObj->totalPage; $i++) {
        $params[] = "?no={$i}&totalRow={$pagingInfoObj->totalRow}&totalPage={$pagingInfoObj->totalPage}&memTableName={$pagingInfoObj->memTableName}";
    }

    $requests = function ($params) {
        $uri = 'http://127.0.0.1/sourceFiles/modul/400_kutipan_balik/14_ABT/RND/step0_per_paging.php';

        foreach ($params as $cmd) {
            
            yield new Request('GET', $uri . $cmd);
        }
    };

    $arr=[];
    $pool = new Pool($client, $requests($params), [
        'concurrency' => 10,
        'fulfilled' => function ($response, $index) use (&$arr) {
            // this is delivered each successful response

            $body = $response->getBody();
            $arr[]=(string)$body;
            // Implicitly cast the body to a string and echo it
            echo "$index $body<br>";
        },
        'rejected' => function ($reason, $index) {
            // this is delivered each failed request
            echo "Error at $index $reason<br>";
        },
    ]);

// Initiate the transfers and create a promise
    $promise = $pool->promise();
    
// Force the pool of requests to complete.
    $promise->wait();
    
    var_dump($arr);
} else {
    //echo "<hr />Oo.. No table truncate have taken place";

    echo json_encode(new PagingInfo());
}
?>