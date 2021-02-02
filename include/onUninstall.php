<?php

function xoops_module_uninstall_tad_faq(&$module)
{
    global $xoopsDB;
    $date = date('Ymd');

    rename(XOOPS_ROOT_PATH . '/uploads/tad_faq', XOOPS_ROOT_PATH . "/uploads/tad_faq_bak_{$date}");

    return true;
}
