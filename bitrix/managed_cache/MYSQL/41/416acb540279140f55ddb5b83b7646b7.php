<?
if($INCLUDE_FROM_CACHE!='Y')return false;
$datecreate = '001634129022';
$dateexpire = '001634132622';
$ser_content = 'a:2:{s:7:"CONTENT";s:0:"";s:4:"VARS";a:1:{s:2:"s1";a:6:{i:0;a:3:{s:9:"CONDITION";s:29:"CSite::InDir(\'/desktop_app/\')";s:8:"TEMPLATE";s:11:"desktop_app";s:7:"SITE_ID";s:2:"s1";}i:1;a:3:{s:9:"CONDITION";s:93:"preg_match("#^/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#", $GLOBALS[\'APPLICATION\']->GetCurPage(0))";s:8:"TEMPLATE";s:8:"call_app";s:7:"SITE_ID";s:2:"s1";}i:2;a:3:{s:9:"CONDITION";s:116:"preg_match("#^/desktop_app/router.php\\?alias=([\\.\\-0-9a-zA-Z]+)&videoconf#", $GLOBALS[\'APPLICATION\']->GetCurPage(0))";s:8:"TEMPLATE";s:8:"call_app";s:7:"SITE_ID";s:2:"s1";}i:3;a:3:{s:9:"CONDITION";s:94:"preg_match("#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#", $GLOBALS[\'APPLICATION\']->GetCurPage(0))";s:8:"TEMPLATE";s:3:"pub";s:7:"SITE_ID";s:2:"s1";}i:4;a:3:{s:9:"CONDITION";s:19:"$_GET[\'print\']==\'Y\'";s:8:"TEMPLATE";s:5:"print";s:7:"SITE_ID";s:2:"s1";}i:5;a:3:{s:9:"CONDITION";s:0:"";s:8:"TEMPLATE";s:5:"books";s:7:"SITE_ID";s:2:"s1";}}}}';
return true;
?>