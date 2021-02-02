<?php
use XoopsModules\Tadtools\Utility;

//區塊主函式 (會列出所有常見問題的分類)
function tad_faq_show($options)
{
    global $xoopsDB;
    require_once XOOPS_ROOT_PATH . '/modules/tad_faq/function_block.php';

    $read_power = chk_faq_cate_power('faq_read');

    $counter = get_cate_count();

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_faq_cate') . ' ORDER BY sort';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $content = [];
    $i = 0;
    while (list($fcsn, $of_fcsn, $title, $description, $sort, $cate_pic) = $xoopsDB->fetchRow($result)) {
        if (!in_array($fcsn, $read_power)) {
            continue;
        }

        $num = (empty($counter[$fcsn])) ? 0 : $counter[$fcsn];

        $content[$i]['title'] = $title;
        $content[$i]['fcsn'] = $fcsn;
        $content[$i]['num'] = sprintf(_MB_TADFAQ_FAQ_NUM, $num);
        $i++;
    }

    $block['content'] = $content;

    return $block;
}
