<?php

use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_faq\Update;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
if (!class_exists('XoopsModules\Tad_faq\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}
function xoops_module_update_tad_faq(&$module, $old_version)
{
    global $xoopsDB;

    if (!Update::chk_chk1()) {
        Update::go_update1();
    }

    $old_fckeditor = XOOPS_ROOT_PATH . '/modules/tad_faq/fckeditor';
    if (is_dir($old_fckeditor)) {
        Utility::delete_directory($old_fckeditor);
    }
    if (Update::chk_uid()) {
        Update::go_update_uid();
    }

    Update::chk_tad_faq_block();

    return true;
}
