<?php
//常見問答搜尋程式
function tad_faq_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    //處理許功蓋
    if (get_magic_quotes_gpc()) {
        foreach ($queryarray as $k => $v) {
            $arr[$k] = addslashes($v);
        }
        $queryarray = $arr;
    }
    $sql = 'SELECT `fcsn`,`title`,`post_date`, `uid` FROM ' . $xoopsDB->prefix('tad_faq_content') . ' WHERE 1';
    if (0 != $userid) {
        $sql .= ' AND uid=' . $userid . ' ';
    }
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((`title` LIKE '%{$queryarray[0]}%'  OR `content` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(`title` LIKE '%{$queryarray[$i]}%' OR  `content` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ') ';
    }
    $sql .= 'ORDER BY  `sort` DESC';
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret = [];
    $i = 0;
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'images/comment_edit.png';
        $ret[$i]['link'] = 'index.php?fcsn=' . $myrow['fcsn'];
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time'] = strtotime($myrow['post_date']);
        $ret[$i]['uid'] = $myrow['uid'];
        $i++;
    }

    return $ret;
}
