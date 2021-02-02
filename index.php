<?php
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tad_faq_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------function區--------------*/

//列出所有tad_faq_cate資料
function list_all()
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsTpl;

    $edit_power = chk_faq_cate_power('faq_edit');
    $xoopsTpl->assign('edit_power', $edit_power);
    $read_power = chk_faq_cate_power('faq_read');

    $counter = get_cate_count();

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_faq_cate') . ' ORDER BY sort';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $data = [];
    $i = 3;
    while (list($fcsn, $of_fcsn, $title, $description, $sort, $cate_pic) = $xoopsDB->fetchRow($result)) {
        if (!in_array($fcsn, $read_power)) {
            continue;
        }

        if (1 == $i % 3) {
            $img = '001_58.gif';
        } elseif (2 == $i % 3) {
            $img = '001_59.gif';
        } else {
            $img = '001_60.gif';
        }
        $num = (empty($counter[$fcsn])) ? 0 : $counter[$fcsn];
        //$data[$i]['num']     = sprintf(_MD_TADFAQ_FAQ_NUM, $num);
        $data[$i]['num'] = $num;
        $data[$i]['fcsn'] = $fcsn;
        $data[$i]['img'] = $img;
        $data[$i]['title'] = $title;
        $data[$i]['counter'] = $counter[$fcsn];
        $i++;
    }

    $xoopsTpl->assign('now_op', 'list_all');
    $xoopsTpl->assign('faq', $data);
    $xoopsTpl->assign('module_title', $xoopsModuleConfig['module_title']);
}

//列出 FAQ 列表
function list_faq($fcsn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $xoopsTpl;
    Utility::get_jquery(true);
    //權限檢查
    $faq_read_power = Utility::power_chk('faq_read', $fcsn);
    $faq_edit_power = Utility::power_chk('faq_edit', $fcsn);
    // die(var_dump($faq_read_power));
    //依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
    if (!$faq_read_power) {
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADFAQ_NO_ACCESS_POWER);
    }

    $xoopsTpl->assign('fcsn', $fcsn);
    $cate = get_tad_faq_cate($fcsn);
    $now_uid = ($xoopsUser) ? $xoopsUser->uid() : 0;

    $sql = 'select * from ' . $xoopsDB->prefix('tad_faq_content') . " where fcsn='$fcsn' order by sort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 1;
    while (list($fqsn, $fcsn, $title, $sort, $uid, $post_date, $content, $enable, $counter) = $xoopsDB->fetchRow($result)) {
        $enable_txt = ('1' == $enable) ? _MD_TADFAQ_UNABLE : _MD_TADFAQ_ENABLE;
        $update_enable = ('1' == $enable) ? '0' : '1';

        $data[$i]['i'] = $i;
        $data[$i]['fqsn'] = $fqsn;
        $data[$i]['counter'] = $counter;
        $data[$i]['title'] = $title;
        $data[$i]['content'] = $content;
        $data[$i]['enable'] = $enable;
        $data[$i]['post_date'] = $post_date;
        $data[$i]['uid'] = $uid;
        $data[$i]['update_enable'] = $update_enable;
        $data[$i]['enable_txt'] = $enable_txt;

        $data[$i]['edit_power'] = ($faq_edit_power and $now_uid == $uid) ? true : false;

        $i++;
    }

    $xoopsTpl->assign('cate_title', $cate['title']);
    $xoopsTpl->assign('now_op', 'list_faq');
    $xoopsTpl->assign('faq', $data);
    $xoopsTpl->assign('faq_edit_power', $faq_edit_power);
}

//刪除tad_faq_content某筆資料資料
function delete_tad_faq_content($fqsn = '')
{
    global $xoopsDB;
    $sql = 'delete from ' . $xoopsDB->prefix('tad_faq_content') . " where fqsn='$fqsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//啟動或關閉
function update_status($fqsn = '', $enable = '')
{
    global $xoopsDB;
    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set enable='{$enable}' where fqsn='$fqsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//分類選單
function get_faq_cate_opt($the_fcsn = '')
{
    global $xoopsDB, $isAdmin;
    $opt = '';
    $edit_fcsn = chk_faq_cate_power('faq_edit');
    $sql = 'SELECT fcsn,title FROM ' . $xoopsDB->prefix('tad_faq_cate') . ' ORDER BY sort';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($fcsn, $title) = $xoopsDB->fetchRow($result)) {
        $selected = ($the_fcsn == $fcsn) ? 'selected' : '';
        if ($isAdmin or in_array($fcsn, $edit_fcsn)) {
            $opt .= "<option value='$fcsn' $selected>$title</option>";
        }
    }

    return $opt;
}

//tad_faq_content編輯表單
function tad_faq_content_form($fcsn = '', $fqsn = '')
{
    global $xoopsDB, $xoopsTpl;

    //抓取預設值
    if (!empty($fqsn)) {
        $DBV = get_tad_faq_content($fqsn);
    } else {
        $DBV = [];
    }

    //預設值設定

    $fqsn = (!isset($DBV['fqsn'])) ? $fqsn : $DBV['fqsn'];
    $fcsn = (!isset($DBV['fcsn'])) ? $fcsn : $DBV['fcsn'];
    $title = (!isset($DBV['title'])) ? '' : $DBV['title'];
    $sort = (!isset($DBV['sort'])) ? '' : $DBV['sort'];
    $uid = (!isset($DBV['uid'])) ? '' : $DBV['uid'];
    $post_date = (!isset($DBV['post_date'])) ? '' : $DBV['post_date'];
    $content = (!isset($DBV['content'])) ? '' : $DBV['content'];
    $enable = (!isset($DBV['enable'])) ? '1' : $DBV['enable'];

    $faq_cate_opt = get_faq_cate_opt($fcsn);

    $CkEditor = new CkEditor('tad_faq', 'content', $content);
    $CkEditor->setHeight(400);
    $editor = $CkEditor->render();

    $op = (empty($fqsn)) ? 'insert_tad_faq_content' : 'update_tad_faq_content';
    //$op="replace_tad_faq_content";

    $xoopsTpl->assign('fqsn', $fqsn);
    $xoopsTpl->assign('faq_cate_opt', $faq_cate_opt);
    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('editor', $editor);
    $xoopsTpl->assign('enable', $enable);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('now_op', 'tad_faq_content_form');
}

//新增資料到tad_faq_content中
function insert_tad_faq_content()
{
    global $xoopsDB, $xoopsUser;
    $myts = \MyTextSanitizer::getInstance();
    $_POST['new_cate'] = $myts->addSlashes($_POST['new_cate']);
    $_POST['title'] = $myts->addSlashes($_POST['title']);
    $_POST['content'] = $myts->addSlashes($_POST['content']);

    if (!empty($_POST['new_cate'])) {
        $fcsn = insert_tad_faq_cate($_POST['new_cate']);
    } else {
        $fcsn = $_POST['fcsn'];
    }
    $uid = ($xoopsUser) ? $xoopsUser->getVar('uid') : '';
    $sort = get_max_faq_sort($_POST['fcsn']);
    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    $sql = 'insert into ' . $xoopsDB->prefix('tad_faq_content') . " (`fcsn`,`title`,`sort`,`uid`,`post_date`,`content`,`enable`) values('{$fcsn}','{$_POST['title']}','{$sort}','{$uid}','{$now}','{$_POST['content']}','{$_POST['enable']}')";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $fcsn;
}

//更新tad_faq_content某一筆資料
function update_tad_faq_content($fqsn = '')
{
    global $xoopsDB;
    $myts = \MyTextSanitizer::getInstance();
    $_POST['new_cate'] = $myts->addSlashes($_POST['new_cate']);
    $_POST['title'] = $myts->addSlashes($_POST['title']);
    $_POST['content'] = $myts->addSlashes($_POST['content']);

    if (!empty($_POST['new_cate'])) {
        $fcsn = insert_tad_faq_cate($_POST['new_cate']);
    } else {
        $fcsn = $_POST['fcsn'];
    }

    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    $sql = 'update ' . $xoopsDB->prefix('tad_faq_content') . " set  `fcsn` = '{$fcsn}', `title` = '{$_POST['title']}', `post_date` = '{$now}', `content` = '{$_POST['content']}', `enable` = '{$_POST['enable']}' where fqsn='$fqsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $fcsn;
}

//自動取得新排序
function get_max_faq_sort($fcsn = '')
{
    global $xoopsDB, $xoopsModule;
    $sql = 'select max(sort) from ' . $xoopsDB->prefix('tad_faq_content') . " where fcsn='{$fcsn}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($sort) = $xoopsDB->fetchRow($result);

    return ++$sort;
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$fcsn = system_CleanVars($_REQUEST, 'fcsn', 0, 'int');
$fqsn = system_CleanVars($_REQUEST, 'fqsn', 0, 'int');

switch ($op) {
    case 'update_status':
        update_status($fqsn, $_GET['enable']);
        header("location: {$_SERVER['PHP_SELF']}?fcsn=$fcsn");
        exit;

    //刪除資料
    case 'delete_tad_faq_content':
        delete_tad_faq_content($fqsn);
        header("location: {$_SERVER['PHP_SELF']}?fcsn=$fcsn");
        exit;

    //新增資料
    case 'insert_tad_faq_content':
        $fcsn = insert_tad_faq_content();
        header("location: {$_SERVER['PHP_SELF']}?fcsn=$fcsn");
        exit;

    //更新資料
    case 'update_tad_faq_content':
        $fcsn = update_tad_faq_content($fqsn);
        header("location: {$_SERVER['PHP_SELF']}?fcsn=$fcsn");
        exit;

    case 'tad_faq_content_form':
        tad_faq_content_form($fcsn, $fqsn);
        break;
    default:
        if (!empty($fcsn)) {
            list_faq($fcsn);
        } else {
            list_all();
        }
        break;
}

$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('isAdmin', $isAdmin);
$xoTheme->addStylesheet('modules/tad_faq/module.css');

require_once XOOPS_ROOT_PATH . '/footer.php';
