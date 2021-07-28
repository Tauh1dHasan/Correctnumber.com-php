<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

   // $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($saha,$theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if(!(is_nan(@$_GET['id']))){

//$id = preg_replace( '/[^0-9]/', '', $_GET['id']);
$id=mysqli_real_escape_string($saha,@$_GET['id']);

mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT * FROM crawl WHERE crawl.id='$id'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

if($totalRows_selectedCrawl==0){
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}

  $updateSQL = sprintf("UPDATE crawl SET visits=%s WHERE id=%s",
                       GetSQLValueString($row_selectedCrawl['visits']+1, "int"),
                       GetSQLValueString($_GET['id'], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));

$actual_url=$row_selectedCrawl['actual_url'];

mysqli_select_db($saha, $database_saha);
$query_selectedCrawlT = "SELECT SUM(crawl.visits) FROM crawl WHERE crawl.actual_url='$actual_url'";
$selectedCrawlT = mysqli_query($saha, $query_selectedCrawlT) or die(mysqli_error($saha));
$row_selectedCrawlT = mysqli_fetch_assoc($selectedCrawlT);
$totalRows_selectedCrawlT = mysqli_num_rows($selectedCrawlT);

  $updateSQL = sprintf("UPDATE settings SET visits=%s WHERE base_url=%s",
                       GetSQLValueString($row_selectedCrawlT['SUM(crawl.visits)'], "int"),
                       GetSQLValueString($row_selectedCrawl['actual_url'], "text"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));
  

if($row_selectedCrawl['block_update']=="N" && $row_selectedCrawl['pdf']=="N"){
mysqli_select_db($saha, $database_saha);
$query_settings = "SELECT * FROM crawl_settings";
$settings = mysqli_query($saha, $query_settings) or die(mysqli_error($saha));
$row_settings = mysqli_fetch_assoc($settings);
$totalRows_settings = mysqli_num_rows($settings);

if($totalRows_settings==0){
	echo "Crawl Settings Error !";
	exit;
}

$BatchCount=$row_settings['batch'];
$BodyLengh=$row_settings['body_lengh'];
$ImageHeight=$row_settings['image_height'];
$ImageWidth=$row_settings['image_width'];

	
require("admin/inc.crawl.php");

list($metaTitle,$metaDescription,$metaKeywords,$plainText,$ogimage) =CrawlData($row_selectedCrawl['current_url'], $BodyLengh, $database_saha, $saha); 


$updateSQL = sprintf("UPDATE crawl SET ogimage=%s, last_update=%s, keywords=%s, content=%s,  description=%s, title=%s WHERE id=%s",
                       GetSQLValueString($ogimage, "text"),
                       GetSQLValueString(date("Y-m-d H:i:s"), "text"),
                       GetSQLValueString($metaKeywords, "text"),
                       GetSQLValueString($plainText, "text"),
                       GetSQLValueString($metaDescription, "text"),
                       GetSQLValueString($metaTitle, "text"),
                       GetSQLValueString($row_selectedCrawl['id'], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));

}




?>
<?php 
$user_agent     =   $_SERVER['HTTP_USER_AGENT'];

function getOS() { 

    global $user_agent;

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function getBrowser() {

    global $user_agent;

    $browser        =   "Unknown Browser";

    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/edge/i'       =>  'Edge',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }

    }

    return $browser;

}


$user_os        =   getOS();
$user_browser   =   getBrowser();
/*
$browser="";
$os="";

if(strstr($_SERVER['HTTP_USER_AGENT'],'Android')){
$os="Android";
}else if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone')){
$os="Apple";
}else if(strstr($_SERVER['HTTP_USER_AGENT'],'Windows')){
$os="Windows";
}
*/
if(!isset($_SERVER['HTTP_REFERER'])){
$base="Direct";
$baseref="Direct";
}else{
$base=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
$baseref=$_SERVER['HTTP_REFERER'];
}
  $insertSQL = sprintf("INSERT INTO url_views (urltype, os, browser, user_agent_language, user_agent_info, referer_base, referer, `date`, urlid, ip) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString("Site", "text"),
                       GetSQLValueString($user_os, "text"),
                       GetSQLValueString($user_browser, "text"),
                       GetSQLValueString($_SERVER['HTTP_ACCEPT_LANGUAGE'], "text"),
                       GetSQLValueString($_SERVER['HTTP_USER_AGENT'], "text"),
                       GetSQLValueString($base, "text"),
                       GetSQLValueString($baseref, "text"),
                       GetSQLValueString(date("Y-m-d"), "date"),
                       GetSQLValueString($id, "int"),
                       GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Loading...</title>
<meta http-equiv="refresh" content="0;URL=<?php echo stripslashes($row_selectedCrawl['current_url']); ?>" />
</head>

<body>
Loading....
</body>
</html>
<?php 
}else{
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;
}
?>