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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE crawl_settings SET crawl_content=%s, crawl_current_url=%s, crawl_description=%s, crawl_images_current_url=%s, crawl_images_image_url=%s, crawl_images_keywords=%s, crawl_keywords=%s, crawl_title=%s, search_results_per_page=%s, crawl_interval_between_links=%s, ogimage=%s, max_links_per_site=%s, batch=%s, image_width=%s, image_height=%s, body_lengh=%s WHERE id=%s",
                       GetSQLValueString($_POST['crawl_content'], "text"),
                       GetSQLValueString($_POST['crawl_current_url'], "text"),
                       GetSQLValueString($_POST['crawl_description'], "text"),
                       GetSQLValueString($_POST['crawl_images_current_url'], "text"),
                       GetSQLValueString($_POST['crawl_images_image_url'], "text"),
                       GetSQLValueString($_POST['crawl_images_keywords'], "text"),
                       GetSQLValueString($_POST['crawl_keywords'], "text"),
                       GetSQLValueString($_POST['crawl_title'], "text"),
                       GetSQLValueString($_POST['search_results_per_page'], "int"),
                       GetSQLValueString($_POST['crawl_interval_between_links'], "int"),
                       GetSQLValueString($_POST['ogimage'], "text"),
                       GetSQLValueString($_POST['max_links_per_site'], "int"),
                       GetSQLValueString($_POST['batch'], "int"),
                       GetSQLValueString($_POST['image_width'], "int"),
                       GetSQLValueString($_POST['image_height'], "int"),
                       GetSQLValueString($_POST['body_lengh'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {

  $updateSQL = sprintf("UPDATE admin SET pw=%s WHERE id=%s",
                       GetSQLValueString(md5($_POST["pws"]), "text"),
                       GetSQLValueString($_POST["id"], "int"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));


}

mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT * FROM admin";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

mysqli_select_db($saha, $database_saha);
$query_settings = "SELECT * FROM crawl_settings";
$settings = mysqli_query($saha, $query_settings) or die(mysqli_error($saha));
$row_settings = mysqli_fetch_assoc($settings);
$totalRows_settings = mysqli_num_rows($settings);

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
                                       <?php if($row_selectedCrawl['demo']=="Y"){ ?><br>
                                       <div>

 <span class="badge badge-warning">Settings are Disabled to  Update in this Demo Version</span><br>

                                       
                                       </div>
                                       <?php }?>
                           <div class="col-lg-12">
                              <form action="<?php echo $editFormAction; ?>" method="POST" name="form3" id="form3">
<div class="col-lg-4">
<fieldset>
<legend>Crawl Settings</legend>

<input name="id" type="hidden" id="id" value="<?php echo $row_settings['id']; ?>">

<label style="clear:both;">
Batch Count
</label>
<input name="batch" type="number" required class="form-control input-lg" id="batch" placeholder="Batch Count"   value="<?php echo $row_settings['batch']; ?>" <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />
                                   
                                     
<label style="clear:both;">
Minimum Image Width to Crawl (Pixels)
</label>
<input name="image_width" type="number" required class="form-control input-lg" id="image_width" placeholder="Image Width"   value="<?php echo $row_settings['image_width']; ?>" <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />

                                     
<label style="clear:both;">
Minimum Image Height to Crawl (Pixels)
</label>
<input name="image_height" type="number" required class="form-control input-lg" id="image_height" placeholder="Image Height"   value="<?php echo $row_settings['image_height']; ?>" <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />
                                     
                                     
<label style="clear:both;">
Maximum Body Character Lengh to Crawl
</label>
<input name="body_lengh" type="number" max="500" required class="form-control input-lg" id="body_lengh" placeholder="Image Height"   value="<?php echo $row_settings['body_lengh']; ?>" <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />
                                     

 <label style="clear:both;">
 Maximum Links / Images per Site/URL
 </label>
 <input name="max_links_per_site" type="number" required class="form-control input-lg" id="max_links_per_site" placeholder="Links Count"   value="<?php echo $row_settings['max_links_per_site']; ?>" <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />
                                      

 <label style="clear:both;">
 Site Crawling Interval (Milliseconds)
 </label>
 <input name="crawl_interval_between_links" type="number" required class="form-control input-lg" id="crawl_interval_between_links" placeholder="Interval Count"   value="<?php echo $row_settings['crawl_interval_between_links']; ?>" <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />
   
                                     
<label style="clear:both;">Activate OG Image
</label>
<select name="ogimage" required class="form-control input-lg" id="ogimage" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['ogimage']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['ogimage']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>


 <label style="clear:both;">
 Search Results Per Page  </label>
 <input name="search_results_per_page" type="number" required class="form-control input-lg" id="search_results_per_page" placeholder="Interval Count"   value="<?php echo $row_settings['search_results_per_page']; ?>" <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />
<br>  

</fieldset>
<?php if($row_selectedCrawl['demo']=="N"){ ?>
<input type="hidden" name="MM_update" value="form3">
<?php }?>
</div>
<div class="col-lg-4">
<fieldset>
<legend>&nbsp;</legend>
<label style="clear:both;">Crawl and Search Title</label>
<select name="crawl_title" required class="form-control input-lg" id="crawl_title" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['crawl_title']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['crawl_title']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>


<label style="clear:both;">Crawl  and Search Description</label>
<select name="crawl_description" required class="form-control input-lg" id="crawl_description" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['crawl_description']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['crawl_description']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>


<label style="clear:both;">Crawl  and Search Keywords</label>
<select name="crawl_keywords" required class="form-control input-lg" id="crawl_keywords" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['crawl_keywords']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['crawl_keywords']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>


<label style="clear:both;">Crawl  and Search Content</label>
<select name="crawl_content" required class="form-control input-lg" id="crawl_content" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['crawl_content']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['crawl_content']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>


<label style="clear:both;">Search URL</label>
<select name="crawl_current_url" required class="form-control input-lg" id="crawl_current_url" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['crawl_current_url']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['crawl_current_url']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>


<label style="clear:both;">Search Image Page URL</label>
<select name="crawl_images_current_url" required class="form-control input-lg" id="crawl_images_current_url" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['crawl_images_current_url']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['crawl_images_current_url']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>


<label style="clear:both;">Search Image URL</label>
<select name="crawl_images_image_url" required class="form-control input-lg" id="crawl_images_image_url" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['crawl_images_image_url']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['crawl_images_image_url']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>


<label style="clear:both;">Search Image ALT Tag</label>
<select name="crawl_images_keywords" required class="form-control input-lg" id="crawl_images_keywords" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled="disabled"<?php }?>>
<option value="Y" <?php if($row_settings['crawl_images_keywords']=="Y"){ ?>selected="selected"<?php }?>>Yes</option>
<option value="N" <?php if($row_settings['crawl_images_keywords']=="N"){ ?>selected="selected"<?php }?>>No</option>
</select>



<label style="clear:both;">&nbsp;<br>
<button class="btn btn-lg btn-primary" type="submit" <?php if($row_selectedCrawl['demo']=="Y"){ ?>disabled<?php }?>>Update Settings</button>
</label>

</fieldset>
</div>
</form>

<div class="col-lg-4">
<form name="form" action="<?php echo $editFormAction; ?>" method="POST">

<fieldset>
<legend>Administrator Settings</legend>
<div class="form-group">
<input name="id" type="hidden" id="id" value="<?php echo $row_selectedCrawl['id']; ?>">
<label style="clear:both;">Username </label>
<input name="q" type="text" disabled class="form-control input-lg" placeholder="http://www.myweb.com"  value="<?php echo $row_selectedCrawl['un']; ?>" />
<label style="clear:both;">Password </label>
<input name="pws" type="password" class="form-control input-lg" id="pws" placeholder="Password"    value="" required <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />                                 
<br>
<?php if($row_selectedCrawl['demo']=="Y"){ ?><br>
 <label>
 <button class="btn btn-lg btn-primary disabled" type="button">
 Update Password
 </button>
 </label>
 <?php }else{?>
 <label>
 <button class="btn btn-lg btn-primary" type="submit">
 Update Password
 </button>
 </label>
<?php }?>                                       
                               </div>    
                                   
</fieldset>
<fieldset>
<legend>PHP Settings</legend>
<p>
<?php if (function_exists('file_get_contents')) { ?>
    <strong style="color: #064504; "><?php echo "URL Content Reading Functions Available"; ?></strong>
<?php } else { ?>
    <strong style="color: #CC0407; "><?php echo "URL Content Reading Functions not Available"; ?></strong>
<?php } ?>	
</p>
<p>
<?php if (function_exists('imagecreatetruecolor')) { ?>
    <strong style="color: #064504; "><?php echo "GD Library Functions Available"; ?></strong>
<?php } else { ?>
    <strong style="color: #CC0407; "><?php echo "GD Library Functions not Available"; ?></strong>
<?php } ?>	
</p>
<p>
<?php if (function_exists('get_headers')) { ?>
    <strong style="color: #064504; "><?php echo "Get Headers Functions Available"; ?></strong>
<?php } else { ?>
    <strong style="color: #CC0407; "><?php echo "Get Headers Functions not Available"; ?></strong>
<?php } ?>	
</p>
<p>
<?php if (function_exists('session_start')) { ?>
    <strong style="color: #064504; "><?php echo "Session Functions Available"; ?></strong>
<?php } else { ?>
    <strong style="color: #CC0407; "><?php echo "Session Functions not Available"; ?></strong>
<?php } ?>	
</p>	
<p>
<?php if (function_exists('parse_url')) { ?>
    <strong style="color: #064504; "><?php echo "URL Parsing Functions Available"; ?></strong>
<?php } else { ?>
    <strong style="color: #CC0407; "><?php echo "URL Parsing Functions not Available"; ?></strong>
<?php } ?>	
</p>
<p>
<?php if (function_exists('preg_match_all')) { ?>
    <strong style="color: #064504; "><?php echo "String Parsing Functions Available"; ?></strong>
<?php } else { ?>
    <strong style="color: #CC0407; "><?php echo "String Parsing Functions not Available"; ?></strong>
<?php } ?>	
</p>	
</fieldset>	
                                 <?php if($row_selectedCrawl['demo']=="N"){ ?>
                                 <input type="hidden" name="MM_insert" value="form">
                                 <?php }?>
                              </form>
</div>
<div>
<h1>_______________________</h1>

<h3>Generate Sitemaps</h3>	
<form action="sitempa-generate.php" method="get" target="_blank">
  <div class="input-group">
                                    <input type="text" placeholder="Search Web" name="q" class="form-control input-lg" value="<?php echo strip_tags(htmlentities(stripslashes(@$cid))); ?>" required>
                                     
                                   <div class="input-group-btn">
                                       <button class="btn btn-lg btn-primary" type="submit">
                                       <i class="fa fa-search"></i>
                                       </button>
                                    </div>
                                 </div>
    </form>	
</div>							   

</div>

<div class="col-lg-12">
<br class="clearfix">
</div>
                           <div class="col-lg-12">
                              
                           </div>
                                       </div>
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>   
           </div>
         </div>
         <hr/>
         
   </div>
</body>
</html>
<?php
mysqli_free_result($selectedCrawl);
?>
