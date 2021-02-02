<?php
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require dirname(__DIR__) . '/function.php';
$sort = 1;
foreach ($_POST['tr'] as $recordIDValue) {
    $sql = 'update ' . $xoopsDB->prefix('tad_faq_cate') . " set `sort`='{$sort}' where `fcsn`='{$recordIDValue}'";
    $xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
