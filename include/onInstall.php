<?php

use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

function xoops_module_install_tad_faq(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_faq');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_faq/file');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_faq/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_faq/image/.thumbs');

    return true;
}
