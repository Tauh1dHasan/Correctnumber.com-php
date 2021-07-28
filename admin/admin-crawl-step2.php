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


mysqli_select_db($saha, $database_saha);
$query_settings = "SELECT * FROM settings ORDER BY settings.id DESC";
$settings = mysqli_query($saha, $query_settings) or die(mysqli_error($saha));
$row_settings = mysqli_fetch_assoc($settings);
$totalRows_settings = mysqli_num_rows($settings);


if($totalRows_settings==0){
	echo "Crawl Settings Error !";
	exit;
}

mysqli_select_db($saha, $database_saha);
$query_settingsCrawl = "SELECT * FROM crawl_settings";
$settingsCrawl = mysqli_query($saha, $query_settingsCrawl) or die(mysqli_error($saha));
$row_settingsCrawl = mysqli_fetch_assoc($settingsCrawl);
$totalRows_settingsCrawl = mysqli_num_rows($settingsCrawl);

if($totalRows_settingsCrawl==0){
	echo "Crawl Settings Error !";
	exit;
}

$BatchCount=$row_settingsCrawl['batch'];
$BodyLengh=$row_settingsCrawl['body_lengh'];
$ImageHeight=$row_settingsCrawl['image_height'];
$ImageWidth=$row_settingsCrawl['image_width'];
$MaxLinksPerSite=$row_settingsCrawl['max_links_per_site'];
$crawl_interval_between_links=$row_settingsCrawl['crawl_interval_between_links'];

mysqli_select_db($saha, $database_saha);
$query_selected = "SELECT * FROM crawl WHERE crawl.deleted='N'  AND crawl.block_update='N' AND crawl.crawlRunImages='Y' ORDER BY crawl.id DESC LIMIT $BatchCount";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);


mysqli_select_db($saha, $database_saha);
$query_settings = "SELECT * FROM crawl WHERE crawl.deleted='N'  AND crawl.block_update='N' AND crawl.crawlRunImages='Y' ORDER BY crawl.id DESC LIMIT $BatchCount";
$settings = mysqli_query($saha, $query_settings) or die(mysqli_error($saha));
$row_settings = mysqli_fetch_assoc($settings);
$totalRows_settings = mysqli_num_rows($settings);

if($totalRows_settings==0){
	header("Location:admin-crawl-step3.php");
	exit;
}

mysqli_select_db($saha, $database_saha);
$query_CrawlIPending = "SELECT * FROM crawl WHERE crawl.crawlRunImages='Y'";
$CrawlIPending = mysqli_query($saha, $query_CrawlIPending) or die(mysqli_error($saha));
$row_CrawlIPending = mysqli_fetch_assoc($CrawlIPending);
$totalRows_CrawlIPending = mysqli_num_rows($CrawlIPending);



$listCra=""; 
do{
$listCra.=$row_settings['id'].",";
} while ($row_settings = mysqli_fetch_assoc($settings));
$listCra1= substr($listCra, 0, -1);
$coluMs = explode(",", $listCra1);
$coluMs2 = explode(",", $listCra1);

 $count=0; 
 $CrawlingCompleted="true"; 
 $CrownlingPending="false";	
$_SESSION['CrownlingPending']=NULL;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
 ?><!DOCTYPE html>
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
<!-- Ajax -->
<?php 
$i=0;
foreach ($coluMs as $value) {
?>
<script type="text/javascript">
function ajaxFunctionf<?php echo $value; ?>(lp,ld,pl,vw,hd,ur,a)  {
var xmlHttp;  try    {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }  catch (e)    {
  // Internet Explorer
  try      {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }    catch (e)      {
    try        {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }      catch (e)        {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }    xmlHttp.onreadystatechange=function()      {     
 if(xmlHttp.readyState<4)        {    
document.getElementById(pl).innerHTML=document.getElementById(ld).innerHTML;       

 } 
 if(xmlHttp.readyState==4)        { 
 <?php if(@$coluMs2[$i+1]>0){ ?> 
 setTimeout(function() {ajaxFunctionf<?php echo $coluMs2[$i+1];  ?>('load<?php echo $coluMs2[$i+1];  ?>','auto','load<?php echo $coluMs2[$i+1];  ?>','','','admin-crawl-step2-f.php','<?php echo $coluMs2[$i+1];  ?>');},<?php echo $crawl_interval_between_links; ?>);
document.getElementById(pl).innerHTML=xmlHttp.responseText;       
document.getElementById('CrawlDown').innerHTML='No';
<?php }else{?>
document.getElementById('CrawlDown').innerHTML='Yes';
document.getElementById(pl).innerHTML=xmlHttp.responseText;       
<?php }?>
 }  
 
 if(xmlHttp.readyState==4 && xmlHttp.responseText=='')        {       
 document.getElementById(pl).innerHTML=document.getElementById('error').innerHTML;

 }     }    
 var url=ur+"?p1="+a;

xmlHttp.open("GET",url,true);  

  xmlHttp.send(null);
  }

</script>
<?php
$i++;
 }  ?>      
   </head>
<body class="gray-bg" onLoad="
ajaxFunctionf<?php echo $coluMs[0];  ?>('load<?php echo $coluMs[0];  ?>','auto','load<?php echo $coluMs[0];  ?>','','','admin-crawl-step2-f.php','<?php echo $coluMs[0];  ?>');
setTimeout(CrawlingStatus, 3000)
">
<div style="display:none;" id="auto">
<p class="alert-warning">
<div class="progress progress-striped active">
<div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar progress-bar-danger">
<span class="sr-only">CRAWLING...</span>
</div>
</div>
</p>
</div>
<!-- //Ajax -->
<script>
function CrawlingStatus() {
    if(document.getElementById('CrawlDown').innerHTML=="Yes"){
		document.getElementById('loaded').style.display='block';
		document.getElementById('loader').style.display='none';
		window.location=window.location;
	}else{
		document.getElementById('loader').style.display='block';
	}
	setTimeout(CrawlingStatus, 3000)
}
</script>

<?php echo "<div id=\"loader\" style=\"text-align:center; padding:10% 10px 10px 10px; color:#FFF; background:#FFF; height:1180000px; position:absolute; width:100%;opacity: 0.6; filter: alpha(opacity=60);display:block; z-index:100000;\">

  <div style=\"color:#060; font-size:18px; margin-top:30px;\">
  <div class=\"progress progress-striped active\">
                              <div style=\"width: 100%\" aria-valuemax=\"100\" aria-valuemin=\"0\" aria-valuenow=\"100\" role=\"progressbar\" class=\"progress-bar progress-bar-danger\">
                                 <span class=\"sr-only\">CRAWLING...</span>
                              </div>
                           </div>
CRAWLING IN PROGRESS - IMAGES (Step 02 of 03)<br>                           
  Please Wait...<br>
  All Pending Links for Image Crawling : $totalRows_CrawlIPending<br>


This may take more minutes and it depends on how many links in your web sites or sitemaps.<br>


<br>


PLEASE DO NOT CLOSE THIS WINDOW UNTILL FINISED<br><br><br>

<strong style=\"background:#000; color:#FFF; padding:10px;\"> Current Batch Count : $BatchCount </strong>
<br><br>

<strong style=\"background:#000; color:#FFF; padding:10px;\"> Max Images per Site/URL : $MaxLinksPerSite </strong>
<br><br>
<br>

<strong style=\"background:#000; color:#FFF; padding:10px;\"> Indexing Minimum Image Dimenstion : $ImageWidth x $ImageHeight </strong>

<br><br>

<strong style=\"background:#F00; color:#FFF; padding:10px;\"><a href=\"javascript:void(0);\" onclick=\"this.innerHTML='Stopping... Please Wait...'; window.stop(); window.location='admin-crawl-step3.php?stop=true';\">Stop Crawling</a></strong> 
</div>

</div>

<div id=\"loaded\" style=\"text-align:center; padding:10% 10px 10px 10px; color:#FFF; background:#FFF; height:8800px; position:absolute; width:100%; display:none; z-index:10000;\">

  <div style=\"color:#060; font-size:18px; margin-top:30px;\">
  <div class=\"progress\">
                              <div style=\"width: 100%\" aria-valuemax=\"100\" aria-valuemin=\"0\" aria-valuenow=\"35\" role=\"progressbar\" class=\"progress-bar progress-bar-success\">
                                 <span class=\"sr-only\">Batch Completed !</span>
                              </div>
                           </div>
CRAWLING REALOADING FOR NEXT BATCH
<br>                           
  Please Wait...<br>


</div></div>
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
                                       
                                        

						<table class="table table-striped table-hover">
                                          <thead>
                                          <tr>
                                                  <td>URL</td>
                                                  <td>Indexed URL
                                                    <form name="form1" method="post" action="">
                                                      <label for="CrawlDown"></label>
                                                      <textarea name="CrawlDown" id="CrawlDown" style="display:none;">No</textarea>
                                                  </form></td>
                                                  <td>Total Visits</td>
                                                  <td class="client-status">Indexed Images</td>
                                                </tr>
                                          </thead>
                                             <tbody>
                 <?php do { ?>
<?php 
$CURL=$row_selected['current_url'];
//CrawlURLList($database_saha,$saha,$CURL);

mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT * FROM crawl_images WHERE crawl_images.current_url= '$CURL'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

?>                 
                                             
                                                
                                                <tr>
                                                   <td><a href="<?php echo $row_selected['current_url']; ?>" target="_blank" class="client-link" data-toggle="tab"><?php echo substr($row_selected['actual_url'],0,20);if(strlen($row_selected['actual_url'])>20){echo "...".substr($row_selected['actual_url'],-10);} ?> </a>
                                                     
                                                   </td>
                                                   <td> <?php echo $row_selected['actual_url']; ?></td>
                                                   <td><?php echo $row_selected['visits']; ?>&nbsp;</td>
                                                   <td class="client-status" id="load<?php echo $row_selected['id']; ?>"><i class="fa fa-check"></i> <?php echo $totalRows_selectedCrawl; ?> Links Currently Cralwed</td>
                                                </tr>
                   <?php } while ($row_selected = mysqli_fetch_assoc($selected)); ?>
                                                
                                             </tbody>
                                          </table>
                                 
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

