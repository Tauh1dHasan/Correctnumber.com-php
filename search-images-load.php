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


$id = preg_replace( '/[^0-9]/', '', $_GET['p1']);


mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT * FROM crawl_images WHERE crawl_images.id='$id'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);


if($totalRows_selectedCrawl>0){
  $updateSQL = sprintf("UPDATE crawl_images SET visits=%s WHERE id=%s",
                       GetSQLValueString($row_selectedCrawl['visits']+1, "int"),
                       GetSQLValueString($_GET['p1'], "int"));

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
if($_SERVER['HTTP_REFERER']==""){
$base="Direct";
$baseref="Direct";
}else{
$base=parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
$baseref=$_SERVER['HTTP_REFERER'];
}
  $insertSQL = sprintf("INSERT INTO url_views (urltype, os, browser, user_agent_language, user_agent_info, referer_base, referer, `date`, urlid, ip) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString("Image", "text"),
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
 <div class="col-lg-8">
 <a class="btn btn-primary" href="<?php echo stripslashes($row_selectedCrawl['image_url']); ?>" target="_blank"><i class="fa fa-link"></i> View Full Image</a>
 </div>
 
 <div  class="col-lg-4">
<h4>Visits: <?php echo $row_selectedCrawl['visits']; ?></h4>
 </div>
