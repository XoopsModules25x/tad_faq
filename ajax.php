<?php
session_start();
include_once '../../mainfile.php';

$sn = (int)mb_substr($_POST['sn'], 3);

$sql = 'select counter from ' . $xoopsDB->prefix('tad_faq_content') . " where fqsn='{$sn}'";
$result = $xoopsDB->query($sql);
list($counter) = $xoopsDB->fetchRow($result);

if (!in_array($sn, $_SESSION['ok_sn'], true)) {
    $counter++;

    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set counter=$counter where fqsn='{$sn}'";
    $xoopsDB->queryF($sql);
    $_SESSION['ok_sn'][$sn] = $sn;
}
echo $counter;
