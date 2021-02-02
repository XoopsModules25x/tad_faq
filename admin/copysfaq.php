<?php
//newbb 3.07
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tad_faq_adm_sfaq.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------function區--------------*/

//列出所有tad_faq_board資料
function list_faq()
{
    global $xoopsDB, $xoopsModule, $isAdmin, $xoopsTpl;

    //取得某模組編號

    $moduleHandler = xoops_getHandler('module');
    $ThexoopsModule = $moduleHandler->getByDirname('smartfaq');

    if ($ThexoopsModule) {
        $mod_id = $ThexoopsModule->getVar('mid');
        $xoopsTpl->assign('show_error', '0');
    } else {
        $xoopsTpl->assign('show_error', '1');
        $xoopsTpl->assign('msg', _MA_TADDISCUS_NO_NEWBB);

        return;
    }

    //轉移權限(原權限)
    $sql = 'SELECT gperm_groupid,gperm_itemid,gperm_name FROM `' . $xoopsDB->prefix('group_permission') . "` WHERE `gperm_modid` ='{$mod_id}' ";
    $result = $xoopsDB->queryF($sql) or redirect_header('index.php', 3, $sql);
    while (list($gperm_groupid, $gperm_itemid, $gperm_name) = $xoopsDB->fetchRow($result)) {
        $power[$gperm_itemid][$gperm_name][$gperm_groupid] = $gperm_groupid;
    }

    //轉移權限（新權限）
    $mid = $xoopsModule->getVar('mid');
    $sql = 'SELECT gperm_groupid,gperm_itemid,gperm_name FROM `' . $xoopsDB->prefix('group_permission') . "` WHERE `gperm_modid` ='{$mid}' ";

    $result = $xoopsDB->queryF($sql) or redirect_header('index.php', 3, $sql);
    while (list($gperm_groupid, $gperm_itemid, $gperm_name) = $xoopsDB->fetchRow($result)) {
        $now_power[$gperm_itemid][$gperm_name][$gperm_groupid] = $gperm_groupid;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('smartfaq_categories') . '`';
    $result = $xoopsDB->query($sql) or redirect_header('index.php', 3, $sql);

    $all_content = [];
    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： `categoryid`, `parentid`, `name`, `description`, `total`, `weight`, `created`
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $all_content[$i]['categoryid'] = $categoryid;
        $all_content[$i]['parentid'] = $parentid;
        $all_content[$i]['name'] = $name;
        $all_content[$i]['description'] = $description;
        $all_content[$i]['total'] = $total;
        $all_content[$i]['weight'] = $weight;
        $all_content[$i]['created'] = $created;
        $all_content[$i]['exist'] = tad_faq_cate_exist($categoryid);
        $all_content[$i]['faq_number'] = get_faq_number($categoryid);
        $all_content[$i]['i'] = $i;
        $i++;
    }

    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('add_button', $add_button);
    $xoopsTpl->assign('bar', $bar);
}

function get_faq_number($categoryid = '')
{
    global $xoopsDB;

    $sql = 'select count(*) from `' . $xoopsDB->prefix('tad_faq_content') . "` where fcsn ='$categoryid'";
    $result = $xoopsDB->query($sql) or redirect_header('index.php', 3, $sql);
    list($sn) = $xoopsDB->fetchRow($result);

    return $sn;
}

function chkcopy($forum_id)
{
    global $xoopsDB, $xoopsUser;

    $sql = 'select categoryid from `' . $xoopsDB->prefix('tad_faq_board') . "` where categoryid ='$forum_id'";
    $result = $xoopsDB->query($sql) or redirect_header('index.php', 3, $sql);
    list($sn) = $xoopsDB->fetchRow($result);

    return $sn;
}

//新增資料到 tad_faq_cate 中
function copyfaq($categoryid = '')
{
    global $xoopsDB, $xoopsUser;

    $where_categoryid = empty($categoryid) ? '' : "where categoryid ='$categoryid'";
    $sql = 'select * from `' . $xoopsDB->prefix('smartfaq_categories') . "` $where_categoryid";
    $result = $xoopsDB->query($sql) or redirect_header('index.php', 3, $sql);
    $myts = \MyTextSanitizer::getInstance();
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： `categoryid`, `parentid`, `name`, `description`, `total`, `weight`, `created`
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $name = $myts->addSlashes($name);
        $description = $myts->addSlashes($description);

        $sql = 'replace into `' . $xoopsDB->prefix('tad_faq_cate') . "`
    (`fcsn`, `of_fcsn` ,`title` , `description` , `sort`)
    values('{$categoryid}' , '{$parentid}' ,  '{$name}' , '{$description}' , '{$weight}')";
        $xoopsDB->queryF($sql) or redirect_header('index.php', 3, $sql);
    }

    return $categoryid;
}

//有無該分類
function tad_faq_cate_exist($categoryid)
{
    global $xoopsDB;

    $sql = 'select title from `' . $xoopsDB->prefix('tad_faq_cate') . "` where `fcsn`='$categoryid'";
    $result = $xoopsDB->query($sql) or redirect_header('index.php', 3, $sql);
    list($title) = $xoopsDB->fetchRow($result);
    if (!empty($title)) {
        $sql = 'select count(*) from `' . $xoopsDB->prefix('smartfaq_faq') . "` where `categoryid`='$categoryid'";
        $result = $xoopsDB->query($sql) or redirect_header('index.php', 3, $sql);
        list($count) = $xoopsDB->fetchRow($result);

        return $count;
    }

    return false;
}

//列出常見問答
function listfaq($categoryid = '')
{
    global $xoopsDB, $xoopsModule, $isAdmin, $xoopsTpl;

    $where_categoryid = empty($categoryid) ? '' : "where a.categoryid ='$categoryid'";
    $sql = 'select a.* , b.answer from `' . $xoopsDB->prefix('smartfaq_faq') . '` as a left join `' . $xoopsDB->prefix('smartfaq_answers') . "` as b on a.faqid=b.faqid $where_categoryid";
    $result = $xoopsDB->query($sql) or redirect_header('index.php', 3, $sql);
    $myts = \MyTextSanitizer::getInstance();
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： `faqid`, `categoryid`, `question`, `howdoi`, `diduno`, `uid`, `datesub`, `status`, `counter`, `weight`, `html`, `smiley`, `xcodes`, `image`, `linebreak`, `cancomment`, `comments`, `notifypub`, `modulelink`, `contextpage`, `exacturl`, `partialview`
        foreach ($all as $k => $v) {
            $$k = $v;
            $all_content[$i][$k] = $v;
        }
        $all_content[$i]['datesub'] = date('Y-m-d H:i:s', $datesub);
        $all_content[$i]['question'] = nl2br($question);
        $all_content[$i]['answer'] = nl2br($answer);
        $i++;
    }

    $xoopsTpl->assign('categoryid', $categoryid);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('op', 'listfaq');
}

//匯入常見問答
function import_faq($categoryid = '')
{
    global $xoopsDB, $xoopsModule, $isAdmin, $xoopsTpl;

    $where_categoryid = empty($categoryid) ? '' : "where a.categoryid ='$categoryid'";
    $sql = 'select a.* , b.answer from `' . $xoopsDB->prefix('smartfaq_faq') . '` as a left join `' . $xoopsDB->prefix('smartfaq_answers') . "` as b on a.faqid=b.faqid $where_categoryid";
    $result = $xoopsDB->query($sql) or redirect_header('index.php', 3, $sql);
    $myts = \MyTextSanitizer::getInstance();
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： `faqid`, `categoryid`, `question`, `howdoi`, `diduno`, `uid`, `datesub`, `status`, `counter`, `weight`, `html`, `smiley`, `xcodes`, `image`, `linebreak`, `cancomment`, `comments`, `notifypub`, `modulelink`, `contextpage`, `exacturl`, `partialview`
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $datesub = date('Y-m-d H:i:s', $datesub);

        $question = $myts->addSlashes(nl2br($question));
        $answer = $myts->addSlashes(nl2br($answer));

        $sql = 'replace into `' . $xoopsDB->prefix('tad_faq_content') . "`
    (`fqsn`, `fcsn`, `title`, `sort`, `uid`, `post_date`, `content`, `enable`, `counter`)
    values('{$faqid}' , '{$categoryid}' ,  '{$question}' , '{$weight}' , '{$uid}' , '{$datesub}' , '{$answer}' , '1' , '{$counter}')";
        $xoopsDB->queryF($sql) or redirect_header('index.php', 3, $sql);
    }
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$categoryid = system_CleanVars($_REQUEST, 'categoryid', 0, 'int');
$fcsn = system_CleanVars($_REQUEST, 'fcsn', 0, 'int');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case 'copyfaq':
        copyfaq($categoryid);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'listfaq':
        listfaq($categoryid);
        break;
    case 'import_faq':
        import_faq($categoryid);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //預設動作
    default:
        list_faq();
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
