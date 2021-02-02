<?php

global $xoopsConfig;

$modversion = [];

//---模組基本資訊---//
$modversion['name'] = _MI_TADFAQ_NAME;
$modversion['version'] = 2.56;
$modversion['description'] = _MI_TADFAQ_DESC;
$modversion['author'] = _MI_TADFAQ_AUTHOR;
$modversion['credits'] = _MI_TADFAQ_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2020/03/14';
$modversion['module_website_url'] = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5';
$modversion['min_tadtools'] = '2.02';

//---paypal資訊---//
$modversion['paypal'] = [];
$modversion['paypal']['business'] = 'tad0616@gmail.com';
$modversion['paypal']['item_name'] = 'Donation : ' . _MI_TAD_WEB;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][1] = 'tad_faq_cate';
$modversion['tables'][2] = 'tad_faq_content';

//---安裝設定---//
$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/tad_faq_search.php';
$modversion['search']['func'] = 'tad_faq_search';

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

//---樣板設定---//
$modversion['templates'] = [];
$i = 1;
$modversion['templates'][$i]['file'] = 'tad_faq_adm_main.tpl';
$modversion['templates'][$i]['description'] = 'tad_faq_adm_main.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_faq_adm_power.tpl';
$modversion['templates'][$i]['description'] = 'tad_faq_adm_power.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_faq_index.tpl';
$modversion['templates'][$i]['description'] = 'tad_faq_index.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tad_faq_adm_sfaq.tpl';
$modversion['templates'][$i]['description'] = 'tad_faq_adm_sfaq.tpl';

//---區塊設定---//
$modversion['blocks'][1]['file'] = 'tad_faq_block.php';
$modversion['blocks'][1]['name'] = _MI_TADFAQ_BNAME1;
$modversion['blocks'][1]['description'] = _MI_TADFAQ_BDESC1;
$modversion['blocks'][1]['show_func'] = 'tad_faq_show';
$modversion['blocks'][1]['template'] = 'tad_faq_block.tpl';

//---偏好設定---//
$modversion['config'][0]['name'] = 'module_title';
$modversion['config'][0]['title'] = '_MI_TADFAQ_MODULE_TITLE';
$modversion['config'][0]['description'] = '_MI_TADFAQ_MODULE_TITLE_DESC';
$modversion['config'][0]['formtype'] = 'textbox';
$modversion['config'][0]['valuetype'] = 'text';
$modversion['config'][0]['default'] = _MI_TADFAQ_MODULE_TITLE_VAL;
