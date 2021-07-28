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

mysqli_select_db($saha, $database_saha);
$query_CrawlPending = "SELECT * FROM settings WHERE settings.crawlRun='Y'";
$CrawlPending = mysqli_query($saha, $query_CrawlPending) or die(mysqli_error($saha));
$row_CrawlPending = mysqli_fetch_assoc($CrawlPending);
$totalRows_CrawlPending = mysqli_num_rows($CrawlPending);

mysqli_select_db($saha, $database_saha);
$query_CrawlIPending = "SELECT * FROM crawl WHERE crawl.crawlRunImages='Y'";
$CrawlIPending = mysqli_query($saha, $query_CrawlIPending) or die(mysqli_error($saha));
$row_CrawlIPending = mysqli_fetch_assoc($CrawlIPending);
$totalRows_CrawlIPending = mysqli_num_rows($CrawlIPending);

mysqli_select_db($saha, $database_saha);
$query_CrawlPendingT = "SELECT * FROM settings";
$CrawlPendingT = mysqli_query($saha, $query_CrawlPendingT) or die(mysqli_error($saha));
$row_CrawlPendingT = mysqli_fetch_assoc($CrawlPendingT);
$totalRows_CrawlPendingT = mysqli_num_rows($CrawlPendingT);

mysqli_select_db($saha, $database_saha);
$query_CrawlIPendingT = "SELECT * FROM crawl";
$CrawlIPendingT = mysqli_query($saha, $query_CrawlIPendingT) or die(mysqli_error($saha));
$row_CrawlIPendingT = mysqli_fetch_assoc($CrawlIPendingT);
$totalRows_CrawlIPendingT = mysqli_num_rows($CrawlIPendingT);

$totalCrawlingItemsPartial=($totalRows_CrawlPending*$MaxLinksPerSite)+($totalRows_CrawlIPending*10);
$totalCrawlingItems=($totalRows_CrawlIPendingT*$MaxLinksPerSite);

if(($totalRows_CrawlPending)>0 || ($totalRows_CrawlIPending)>0){
	$_SESSION['crawlRunCode']=base64_encode(time());
}

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
<script>
/*
    setTimeout(printSomething, 1000);

    function printSomething(){
if(document.getElementById('loader').style.display=="block"){		
        window.scrollTo(0,document.body.scrollHeight);
}
        setTimeout(printSomething, 1000);
    }
	*/
</script>      
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
						<div class="col-md-6">
							<!-- START PANEL -->
							<div class="panel panel-default" style="margin-top: 50px">
								<div class="panel-heading ui-draggable-handle">
									<h3 class="panel-title">Confirm Crawl Run</h3>
								</div>
								<div class="panel-body" style="font-family: ubuntu">

										<div class="block">
											<form class="form-horizontal" role="form">
												<div class="panel-body panel-body-pricing">
													<h2>Are you sure?</h2>
                                                    <h3 class=" text-danger">Estimated Full Crawling Time : <?php echo gmdate("H:i:s", $totalCrawlingItems);?></h3>
<?php if($totalCrawlingItemsPartial>0){?>                                                    
                                                    <h3 class="text-warning">Estimated Partial Crawling Time : <?php echo gmdate("H:i:s", $totalCrawlingItemsPartial);?></h3>
<?php }?> 
<p class="text-success"> 
<i class="fa fa-folder"></i> <strong>Note</strong>: Estimate Time may change depending on your server and how other servers respond to crawler.                                                  
</p>
													<hr>
													<table style="width: 100%">
														<tbody>
                                                        
                                                        <tr>
                                                          <td colspan="2">
<?php if(($totalRows_CrawlPending)>0){?>                                                          
Pending <?php echo $totalRows_CrawlPending; ?> Sites / URLs
<?php }?>
                                                          </td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2">
<?php if(($totalRows_CrawlIPending)>0){?>                                                          
Pending <?php echo $totalRows_CrawlIPending; ?> Links for Image Crawling
<?php }?>
                                                          </td>
                                                        </tr>
														</tbody>
													</table>
												</div>
											</form>
                                            <div style="text-align: center">
<?php if(($totalRows_CrawlPending)>0 && ($totalRows_CrawlIPending)>0){?>                                            
                                            <a class="btn btn-lg btn-warning  full-width" href="admin-crawl-step1.php">
    							Resume Unfinisehd Crawling
							</a>
<?php }else if(($totalRows_CrawlPending)>0){?>                            
                                            <a class="btn btn-lg btn-warning  full-width" href="admin-crawl-step1.php">
    							Resume Unfinisehd Crawling
							</a>
<?php }else if(($totalRows_CrawlIPending)>0){?> 
                                            <a class="btn btn-lg btn-warning full-width" href="admin-crawl-step2.php">
    							Resume Unfinisehd Crawling
							</a>

<?php }?>                           
                                            <a class="btn btn-lg btn-primary" href="admin-crawl-start.php">
    							Start Full Crawl
							</a>  <a class="btn btn-lg btn-success" href="admin-crawl-partial.php">
    							Start Partial Crawl
							</a>
										</div>
                                        </div>

<br style="clear:both;" >                                         
                                      </div>
                                    </div>
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

