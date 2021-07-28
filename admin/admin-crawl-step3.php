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
									<h3 class="panel-title">Crawling <?php if(@$_GET['stop']=="true"){?>Stopped<?php }else{?>Completed<?php }?></h3>
								</div>
								<div class="panel-body" style="font-family: ubuntu">

										<div class="block">
											<form class="form-horizontal" role="form">
												<div class="panel-body panel-body-pricing">
													<h2>You're done...</h2>
													<hr>
													<table style="width: 100%">
														<tbody>
                                                        <tr>
        											    <h3>Crawling System Successfully <?php if(@$_GET['stop']=="true"){?>Stopped<?php }else{?>Completed<?php }?> !<br></h3>
													    </tr>
                                                        <tr>
                                                        <br>
                                                        <br>
                                                        <br>
    														<td width="" colspan="2"><h3 style="color: #828c95"><b><i class="fa fa-"></i></b></h3></td>
															 
														</tr>
														</tbody>
													</table>
												</div>
											</form>
                                            <div style="text-align: center">
                                            <a class="btn btn-lg btn-warning" href="admin.php">
    							Thank you
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

<?php 

//Removing URL with Blank or Empty Contents
mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT * FROM crawl WHERE crawl.deleted='N'  AND crawl.block_update='N'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

do{
if(strlen($row_selectedCrawl['content'])<100){// && strlen($row_selectedCrawl['description'])<5 

if($row_selectedCrawl['pdf']!="Y"){
	
  $deleteSQL = sprintf("DELETE FROM crawl WHERE id=%s",
                       GetSQLValueString($row_selectedCrawl['id'], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $deleteSQL) or die(mysqli_error($saha));
}

}
} while ($row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl)); 
 
//Optimizing Tables

  $deleteSQL = sprintf("OPTIMIZE TABLE `settings`");

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $deleteSQL) or die(mysqli_error($saha));

  $deleteSQL = sprintf("OPTIMIZE TABLE `crawl`");

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $deleteSQL) or die(mysqli_error($saha));

  $deleteSQL = sprintf("OPTIMIZE TABLE `log`");

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $deleteSQL) or die(mysqli_error($saha));
 
?>