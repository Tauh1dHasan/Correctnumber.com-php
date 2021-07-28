<?php require_once('../Connections/saha.php'); ?>
<?php require_once('../inc-main.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = false; 

  // When a visitor has logged into this site, the Session variable MM_UsernameAdMIN  set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_UsernameAdMIN'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_UsernameAdMIN'], $_SESSION['MM_UserGroupADmin'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}


?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  // // $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($saha,$theValue) : mysqli_escape_string($theValue);

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

$id=$_GET['p1'];
$PDFFound=0;

mysqli_select_db($saha, $database_saha);
$query_selected = "SELECT actual_url, spider_mode, id FROM settings WHERE settings.id='$id'";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);

mysqli_select_db($saha, $database_saha);
$query_settings = "SELECT batch, body_lengh, image_height, image_width, max_links_per_site FROM crawl_settings";
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
$MaxLinksPerSite=$row_settings['max_links_per_site'];

require("inc.crawl.php");
$CrownlingPending="false";
$CURL=$row_selected['actual_url'];
//echo $BatchCount;
$count=0;
if($count<$BatchCount){
CrawlURLList($database_saha,$saha,$CURL,$count,$CrownlingPending,$BodyLengh,$BatchCount,$MaxLinksPerSite,$row_selected['spider_mode']);
$CrawlingCompleted="true";
}else{
$CrawlingCompleted="false";
}

mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT id FROM crawl WHERE crawl.actual_url= '$CURL' AND crawl.deleted='N'  AND crawl.block_update='N'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);


  $updateSQL = sprintf("UPDATE settings SET crawlRun=%s WHERE id=%s",
                       GetSQLValueString(time(), "text"),
                       GetSQLValueString($row_selected['id'], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


?> <i class="fa fa-check"></i> <?php echo $totalRows_selectedCrawl; ?>  Links Crawled Sucessfully ! <?php echo $PDFFound; ?>