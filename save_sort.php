<?php
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$updateRecordsArray = $_POST['tr'];

$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set `sort`='{$sort}' where `fqsn`='{$recordIDValue}'";
    $xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
