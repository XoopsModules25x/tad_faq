<?php
use XoopsModules\Tadtools\Utility;

xoops_loadLanguage('main', 'tadtools');

require_once __DIR__ . '/function_block.php';

//以流水號取得某筆tad_faq_cate資料
function get_tad_faq_cate($fcsn = '')
{
    global $xoopsDB;
    if (empty($fcsn)) {
        return;
    }

    $sql = 'select * from ' . $xoopsDB->prefix('tad_faq_cate') . " where fcsn='$fcsn'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//以流水號取得某筆tad_faq_content資料
function get_tad_faq_content($fqsn = '')
{
    global $xoopsDB;
    if (empty($fqsn)) {
        return;
    }

    $sql = 'select * from ' . $xoopsDB->prefix('tad_faq_content') . " where fqsn='$fqsn'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//新增資料到tad_faq_cate中
function insert_tad_faq_cate($new_title = '')
{
    global $xoopsDB;

    $myts = \MyTextSanitizer::getInstance();
    $of_fcsn = (int) $_POST['of_fcsn'];
    $sort = (int) $_POST['sort'];

    $title = $new_title ? $myts->addSlashes($new_title) : $myts->addSlashes($_POST['title']);
    $description = $myts->addSlashes($_POST['description']);
    $cate_pic = $myts->addSlashes($_POST['cate_pic']);

    $sql = 'insert into ' . $xoopsDB->prefix('tad_faq_cate') . " (`of_fcsn`,`title`,`description`,`sort`,`cate_pic`) values('{$of_fcsn}','{$title}','{$description}','{$sort}','{$cate_pic}')";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    //取得最後新增資料的流水編號
    $fcsn = $xoopsDB->getInsertId();

    $faq_read = empty($_POST['faq_read']) ? [1, 2, 3] : $_POST['faq_read'];
    $faq_edit = empty($_POST['faq_edit']) ? [1] : $_POST['faq_edit'];

    //寫入權限
    saveItem_Permissions($faq_read, $fcsn, 'faq_read');
    saveItem_Permissions($faq_edit, $fcsn, 'faq_edit');

    return $fcsn;
}

//儲存權限
function saveItem_Permissions($groups, $itemid, $perm_name)
{
    global $xoopsModule;
    $module_id = $xoopsModule->getVar('mid');

    $gpermHandler = xoops_getHandler('groupperm');

    // First, if the permissions are already there, delete them
    $gpermHandler->deleteByModule($module_id, $perm_name, $itemid);

    // Save the new permissions
    if (count($groups) > 0) {
        foreach ($groups as $group_id) {
            $gpermHandler->addRight($perm_name, $itemid, $group_id, $module_id);
        }
    }
}

//判斷某類別中有哪些觀看或發表的群組 $mode=name or id
function get_cate_enable_group($kind = '', $fcsn = '', $mode = 'id')
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $isAdmin;
    $module_id = $xoopsModule->getVar('mid');

    $sql = 'select a.gperm_groupid,b.name from ' . $xoopsDB->prefix('group_permission') . ' as a left join ' . $xoopsDB->prefix('groups') . " as b on a.gperm_groupid=b.groupid where a.gperm_modid='$module_id' and a.gperm_name='$kind' and a.gperm_itemid='{$fcsn}'";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($gperm_groupid, $name) = $xoopsDB->fetchRow($result)) {
        $ok_group[] = 'name' === $mode ? $name : $gperm_groupid;
    }

    return $ok_group;
}
