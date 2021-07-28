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

  // When a visitor has logged into this site, the Session variable MM_UsernameAdMIN set equal to their username. 
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

$_SESSION['crawlRunCode']=base64_encode(time());

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

$rrr1="";
$rrr2="";
$rr="";
$rr2="";
foreach(@$_POST as $key => $value) {
if(strpos($key,'checkbox') !== false) {
	//print_r($value);
foreach(@$value as $key => $value1) {
$rrr1.="settings.id='$value1' OR ";
}
	}
}
$rr= substr($rrr1, 0, -4);

if($rrr1==""){
header("Location: admin-crawl-partial.php");
exit;	
}

mysqli_select_db($saha, $database_saha);
$query_selected = "SELECT * FROM settings WHERE $rr";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);
	
	//echo "SELECT * FROM settings WHERE $rr";
	//exit;

if($totalRows_selected>0){

  $updateSQL = sprintf("UPDATE settings SET crawlRun='NotSelected'");

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));
  
  $updateSQL = sprintf("UPDATE crawl SET crawlRun='NotSelected', crawlRunImages='NotSelected'");

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));
}
	
	

if($totalRows_selected>0){
//Remove Active Crawlings
mysqli_select_db($saha, $database_saha);
$query_CrawlIPending = "SELECT * FROM settings WHERE settings.crawlRun='Y'";
$CrawlIPending = mysqli_query($saha, $query_CrawlIPending) or die(mysqli_error($saha));
$row_CrawlIPending = mysqli_fetch_assoc($CrawlIPending);
$totalRows_CrawlIPending = mysqli_num_rows($CrawlIPending);

	if($totalRows_CrawlIPending>0){
	do{
  $updateSQL = sprintf("UPDATE settings SET crawlRun=%s WHERE id=%s",
                       GetSQLValueString("N", "text"),
                       GetSQLValueString($row_CrawlIPending['id'], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));		
	} while ($row_CrawlIPending = mysqli_fetch_assoc($CrawlIPending));
	}

mysqli_select_db($saha, $database_saha);
$query_CrawlIPending = "SELECT * FROM crawl WHERE crawl.crawlRun='Y'";
$CrawlIPending = mysqli_query($saha, $query_CrawlIPending) or die(mysqli_error($saha));
$row_CrawlIPending = mysqli_fetch_assoc($CrawlIPending);
$totalRows_CrawlIPending = mysqli_num_rows($CrawlIPending);	

	if($totalRows_CrawlIPending>0){
	do{
  $updateSQL = sprintf("UPDATE crawl SET crawlRun=%s WHERE id=%s",
                       GetSQLValueString("N", "text"),
                       GetSQLValueString($row_CrawlIPending['id'], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));		
	} while ($row_CrawlIPending = mysqli_fetch_assoc($CrawlIPending));
	}	
//End Remove Active Crawling
	
do{
//echo $row_selected['id']."/";	
if($_POST["datacrawling"]=="Y"){
  $updateSQL = sprintf("UPDATE settings SET crawlRun=%s WHERE id=%s",
                       GetSQLValueString("Y", "text"),
                       GetSQLValueString($row_selected['id'], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));
}


if($_POST["imagecrawling"]=="Y"){
  $updateSQL = sprintf("UPDATE crawl SET crawlRun=%s, crawlRunImages=%s WHERE `actual_url`=%s",
                       GetSQLValueString("Y", "text"),
                       GetSQLValueString("Y", "text"),
                       GetSQLValueString($row_selected['actual_url'], "text"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));
}
	

	
	} while ($row_selected = mysqli_fetch_assoc($selected));
}



//echo "<br>".$totalRows_selected;

mysqli_select_db($saha, $database_saha);
$query_CrawlIPending = "SELECT * FROM settings WHERE settings.crawlRun='Y'";
$CrawlIPending = mysqli_query($saha, $query_CrawlIPending) or die(mysqli_error($saha));
$row_CrawlIPending = mysqli_fetch_assoc($CrawlIPending);
$totalRows_CrawlIPending = mysqli_num_rows($CrawlIPending);

//echo "<br>".$totalRows_CrawlIPending;
//exit;

header("Location: admin-crawl-step1.php");
exit;
}



//echo "<br>".$totalRows_CrawlIPending;
//exit;
mysqli_select_db($saha, $database_saha);
$query_selected = "SELECT * FROM settings ORDER BY settings.id DESC";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);

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
$MaxLinksPerSite=$row_settings['max_links_per_site'];

// WHERE settings.crawlRun=''

mysqli_select_db($saha, $database_saha);
$query_tablelist = "SELECT * FROM settings ORDER BY settings.id DESC LIMIT 300";
$tablelist = mysqli_query($saha, $query_tablelist) or die(mysqli_error($saha));
$row_tablelist = mysqli_fetch_assoc($tablelist);
$totalRows_tablelist = mysqli_num_rows($tablelist);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<?php $count=0; ?>                                                   
<?php $CrawlingCompleted="true"; ?>
<?php $CrownlingPending="false";	
$_SESSION['CrownlingPending']=NULL; ?>                                                   
<!DOCTYPE html>
<html>
<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Easy Web Search</title>
 <link rel="shortcut icon" href="../icon.png" type="image/png" />
     <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link href="../fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
      <link href="../css/animate.css" rel="stylesheet">
      <link href="../css/style.css" rel="stylesheet">
<script language="JavaScript">
function toggle(source) {
  checkboxes = document.querySelectorAll("input[type=checkbox]");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}</script>
   </head>
<body class="gray-bg" onLoad="document.getElementById('loader').style.display='none';" >

<?php echo "<div id=\"loader\" style=\"text-align:center; padding:10% 10px 10px 10px; color:#FFF; background:#FFF; height:1180000px; position:absolute; width:100%;opacity: 0.6; filter: alpha(opacity=60);display:block; z-index:100000;\">

  <div style=\"color:#060; font-size:18px; margin-top:30px;\">
  <div class=\"progress progress-striped active\">
                              <div style=\"width: 100%\" aria-valuemax=\"100\" aria-valuemin=\"0\" aria-valuenow=\"100\" role=\"progressbar\" class=\"progress-bar progress-bar-danger\">
                                 <span class=\"sr-only\">CRAWLING...</span>
                              </div>
                           </div>
CRAWLING IN PROGRESS - DATA<br>                           
  Please Wait...<br>

This may take more minutes and it depends on how many links in your web sites or sitemaps.<br>

And Your Website may face 503 Error, if your Server/MySQL memory is not sufficient.
<br>


PLEASE DO NOT CLOSE THIS WINDOW UNTILL FINISED<br><br><br>

<strong style=\"background:#000; color:#FFF; padding:10px;\">Current Indexing Batch Count : $BatchCount</strong>

<br><br>

<strong style=\"background:#F00; color:#FFF; padding:10px;\"><a href=\"javascript:void(0);\" onclick=\"this.innerHTML='Stopping... Please Wait...'; window.stop(); window.location='admin-run-stop.php';\">Stop Crawling</a></strong> 
</div>

</div>

<div id=\"loaded\" style=\"text-align:center; padding:10% 10px 10px 10px; color:#FFF; background:#FFF; height:8800px; position:absolute; width:100%; display:none; z-index:10000;\">

  <div style=\"color:#060; font-size:18px; margin-top:30px;\">
  <div class=\"progress\">
                              <div style=\"width: 100%\" aria-valuemax=\"100\" aria-valuemin=\"0\" aria-valuenow=\"35\" role=\"progressbar\" class=\"progress-bar progress-bar-success\">
                                 <span class=\"sr-only\">Completed.</span>
                              </div>
                           </div>
CRAWLING FINISHED
<br>                           
  Thank you.<br>

Please <a href=\"javascript:void(0);\" onClick=\"window.close();\">Close This Window</a> or <a href=\"admin.php\">Go Back</a> </div>

</div>
"; ?>


                        
<div class="loginColumns animated fadeInDown" style="max-width:1000px; padding-top:0px;">
         <div class="row">
            <div>
<h1><img src="logo.png" width="128" height="128" alt="EWS"> 
  Easy Web Search - Admin Panel</h1>


               
               
            <div class="clients-list">
                              <ul class="nav nav-tabs tab-border-top-danger">
                              <?php include("admin-navi.php"); ?>   
                                 
                              </ul>
                              <div class="tab-content">
                                 <div id="tab-1" class="tab-pane active">
                                    <div class="full-height-scroll">
                                       <div class="table-responsive">
<?php
//CrawlURL($database_saha,$saha);
?>
                                       
                                        
<div class="col-md-3">
    					</div>
						<div class="col-md-12">
							<!-- START PANEL -->
							<form method="post" class="form-horizontal" role="form">
												<div class="panel-body panel-body-pricing">
													<table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                  <th>&nbsp;</th>
                                  <th align="right" style="text-align:right;">Run Data Crawling ? </th>
                                  <th align="right" style="text-align:right;">&nbsp;</th>
                                  <th align="right" style="text-align:right;">&nbsp;</th>
                                  <th> 
                                    <select name="datacrawling" id="datacrawling" class="form-control">
                                      <option value="Y" selected>Yes</option>
                                      <option value="N">No</option>
                                  </select></th> 
                                </tr>
                                <tr>
                                  <th>&nbsp;</th>
                                  <th align="right" style="text-align:right;">Run Image Crawling after Data Crawling ? </th>
                                  <th align="right" style="text-align:right;">&nbsp;</th>
                                  <th align="right" style="text-align:right;">&nbsp;</th>
                                  <th> 
                                    <select name="imagecrawling" id="imagecrawling" class="form-control">
                                      <option value="Y">Yes</option>
                                      <option value="N" selected>No</option>
                                  </select></th>
                                </tr>
                                <tr>
                                  <th>&nbsp;</th>
                                  <th>&nbsp;</th>
                                  <th>&nbsp;</th>
                                  <th><input type="button" name="button2" id="button2" value="Back" class="btn btn-warning"  style="width:100px; float:right;" onClick="window.location='admin-crawl.php';"></th>
                                  <th><input type="submit" name="button" id="button" value="Start Crawling" class="btn btn-primary"  style="width:150px;"></th>
                                </tr>
                                
                                <tr>
                                    <th width="50">#</th>
                                    <th>Select Site/URL &nbsp;</th>
                                    <th>Added on</th>
                                    <th>Spide Mode </th>
                                    <th width="50"><input type="checkbox" onClick="toggle(this)" /> Select All</th>
                                </tr>
                            </thead>
                            <tbody>
<?php $i=0;?>                            
                                <?php do { ?>
<?php 

$i=$i+1;
?>                                
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td style="font-size:16px;">
									  <a href="<?php echo $row_tablelist['actual_url']; ?>" target="_blank"><?php echo substr($row_tablelist['actual_url'],0,20);if(strlen($row_tablelist['actual_url'])>20){echo "...".substr($row_tablelist['actual_url'],-10);} ?></a>
									</td>
                                    <td style="font-size:16px;"><?php echo date("Y-m-d H:i:s",$row_tablelist['time']); ?></td>
                                    <td style="font-size:16px;">
                                    <?php if($row_tablelist['spider_mode']=="Y"){ ?>
                                    On
                                    <?php }else{?>
                                    Off
                                    <?php }?>
                                    
                                    </td>
                                    <td>
                                    <input name="checkbox[]" type="checkbox" id="checkbox<?php echo $i; ?>" value="<?php echo $row_tablelist['id']; ?>"  class="form-control" />
                                    </td>
                                </tr>
                                <?php } while ($row_tablelist = mysqli_fetch_assoc($tablelist)); ?>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td align="right" style="font-size:16px;"><input type="button" name="button2" id="button2" value="Back" class="btn btn-warning"  style="width:100px;" onClick="window.location='admin-crawl.php';"></td>
                                  <td align="right" style="font-size:16px;">&nbsp;</td>
                                  <td align="right" style="font-size:16px;">&nbsp;</td>
                                  <td><input type="submit" name="button" id="button" value="Start Crawling" class="btn btn-primary"  style="width:150px;"></td>
                                </tr>
                            </tbody>
                        </table>
												</div>
                                                <input type="hidden" name="MM_update" value="form1">
											</form>
                                 </div>
                                 
                              </div>
                           </div>   
           </div>

        </div>
 
         
   </div>
</div>
</div>
</div>
   
</body>
</html>

