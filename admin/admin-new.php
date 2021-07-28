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
  $isValid = False; 

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {


  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
  
  //echo $_POST['links']."<br>";
  
$coluMs = explode("\n",$_POST['links']);

//echo print_r($coluMs);
foreach ($coluMs as $value) {

if($value!="" && substr($value,0,4)=="http"){
  
$file = trim($value);
ini_set('user_agent', 'Mozilla/40.0');
$file_headers = @get_headers($file);
$pos200 = strpos($file_headers[0], "200");
$pos301 = strpos($file_headers[0], "301");
$pos302 = strpos($file_headers[0], "302");

if($pos200>0 || $pos301>0 || $pos302>0) {
    $exists = "false";
}else {
    $exists = "true";
}

//echo $exists;
//exit;
$CURL = trim($value);
if($exists=="false"){
$urlBase=parse_url($CURL,PHP_URL_HOST);
  
mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT * FROM settings WHERE settings.actual_url= '$CURL'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

//echo $totalRows_selectedCrawl." ".$urlBase." ".$CURL."|";

if($totalRows_selectedCrawl==0 && $urlBase!=""  && $CURL!=""){
//echo $CURL;

	    $insertSQL = sprintf("INSERT INTO settings (user, time, spider_mode, actual_url, base_url) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_SESSION['MM_UsernameAdMIN'], "text"),
                       GetSQLValueString(time(), "int"),
                       GetSQLValueString($_POST['spider_mode'], "text"),
                       GetSQLValueString($CURL, "text"),
                       GetSQLValueString($urlBase, "text"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
}
}

}
}
header("Location: admin-crawl.php");
exit;
}




?>
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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script src="jquery-linedtextarea/jquery-linedtextarea.js"></script>
	<link href="jquery-linedtextarea/jquery-linedtextarea.css" type="text/css" rel="stylesheet" />
      
   </head>
<body class="gray-bg">

                        
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
                                          <div>
                              <form name="form" action="<?php echo $editFormAction; ?>" method="POST" onSubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').innerHTML='Indexing... This may take few minutes...';">
                              <fieldset>
                              <legend>New Site / Sitemap</legend>
                                 <div>
                                 <label>Site / Sitemap
                                 
                                    <textarea name="links" rows="10" required class="form-control input-lg lined" id="links" style="width:950px;" placeholder="http://www.myweb.com/page.html OR http://www.myweb.com/sitemap.xml (One link per line)"></textarea>
                                    </label>
                                    <div class="text-warning">Please paste One link per line</div>
                                    <br>
                                 <label>Spider Mode
                                 <select name="spider_mode" class="form-control input-lg"  style="width:100px;">
                                 <option value="Y">On</option>
                                 <option value="N" selected>Off</option>
                                 </select>
                                   
                                    <br>
                                    <div class="text-warning">
                                    <h3>What is Spider Mode?</h3>
                                    Spider Mode is to crawl external links as the sites. If the spider mode is <strong>On</strong> search engine will crawl them as <strong>Available Sites</strong> and all the links within those external sites will be crawled too. If set to No, only internal links within the submitted site/site map will be crawled.
                                    </div>
<br>

                                    <label>
                                       <button type="submit" class="btn btn-lg btn-primary" id="submit">
                                       Submit URL
                                       </button>
                                       </label>
                               </div>    
                                   
                                 </fieldset>
                                 <input type="hidden" name="MM_insert" value="form">
                              </form>
                           </div>
                                       </div>
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>   
           </div>
         </div>
         <hr/>
         <div class="row">
            <div class="col-md-6">
               <small><a href="https://codecanyon.net/user/nelliwinne" target="_blank">Nelliwinne</a> &copy;</small>
            </div>
            <div class="col-md-6 text-right">
               <small><?php echo date("Y"); ?></small>
            </div>
         </div>
   </div>

<script>
$(function() {
	$(".lined").linedtextarea(
		{selectedLine: 1}
	);
});
</script>
   
</body>
</html>

