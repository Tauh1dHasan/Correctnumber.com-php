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

// Tauhid
// Form submission function
if(isset($_POST['submit'])) {
    $first_part = mysqli_real_escape_string($saha, $_POST['first_part']);
    $second_part = mysqli_real_escape_string($saha, $_POST['second_part']);
    $website_name = mysqli_real_escape_string($saha, $_POST['website_name']);
    $website_url = mysqli_real_escape_string($saha, $_POST['website_url']);
    
    $bw_update_sql = "UPDATE banner_word SET first_part = '$first_part', second_part = '$second_part', website_name = '$website_name', website_url = '$website_url' WHERE id = 1 ";
    $run_bw_update_sql = mysqli_query($saha, $bw_update_sql);
    
    if($run_bw_update_sql) {
        echo "<script> location = 'banner_words.php' </script>";
    }else{
        echo "<script> alert('Sorry, Please try again') </script>";
        echo "<script> location = 'banner_words.php' </script>";
    }
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

$lim=10;

if(@$_GET['stt']==""){
	$stt=0;
}else{
	$stt=@$_GET['stt'];
}

mysqli_select_db($saha, $database_saha);
$query_total = "SELECT * FROM log GROUP BY log.keyword ORDER BY log.count DESC";
$total = mysqli_query($saha, $query_total) or die(mysqli_error($saha));
$row_total = mysqli_fetch_assoc($total);
$totalRows_total = mysqli_num_rows($total);

mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT * FROM log GROUP BY log.keyword ORDER BY SUM(log.count) DESC LIMIT $stt,$lim";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

$un=$_SESSION['MM_UsernameAdMIN'];

mysqli_select_db($saha, $database_saha);
$query_selectedUser = "SELECT * FROM admin WHERE admin.un='$un'";
$selectedUser = mysqli_query($saha, $query_selectedUser) or die(mysqli_error($saha));
$row_selectedUser = mysqli_fetch_assoc($selectedUser);
$totalRows_selectedUser = mysqli_num_rows($selectedUser);

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
<!-- Chart -->
<script src="chart/Chart.bundle.js"></script>
<script src="chart/utils.js"></script>
    <style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    </style>
<!-- End Chart -->		
   </head>
<body class="gray-bg">

    <div class="loginColumns animated fadeInDown" style="max-width:1000px; padding-top:0px;">
        <div class="row">
            <div>
                <h1><img src="logo.png" width="128" height="128" alt="EWS"> Easy Web Search - Admin Panel</h1>
                <div class="clients-list">
                    <ul class="nav nav-tabs tab-border-top-danger">
                        <?php include("admin-navi.php"); ?>   
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="full-height-scroll">
                                <div class="row">
                                    
                    <?php
                        // getting banner words from db
                        $bw_sql = mysqli_query($saha, "SELECT * FROM banner_word");
                        $bw = mysqli_fetch_assoc($bw_sql);
                    ?>
                                    <form action="" method="post" style="margin-top: 20px">
                                        
                                      <div class="form-group">
                                        <label for="first_part">First Part</label>
                                        <input name="first_part" type="text" class="form-control" id="first_part" value="<?= $bw['first_part'] ?>" required>
                                      </div>
                                      
                                      <div class="form-group">
                                        <label for="second_part">Second Part</label>
                                        <input name="second_part" type="text" class="form-control" id="second_part" value="<?= $bw['second_part'] ?>" required>
                                      </div>
                                      
                                      <div class="form-group">
                                        <label for="website_name">Website Name</label>
                                        <input name="website_name" type="text" class="form-control" id="website_name" value="<?= $bw['website_name'] ?>" required>
                                      </div>
                                      
                                      <div class="form-group">
                                        <label for="website_url">Website link</label>
                                        <input name="website_url" type="url" class="form-control" id="website_url" value="<?= $bw['website_url'] ?>" required>
                                      </div>
                                      
                                      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                                      
                                    </form>
                                    
                                    
                                    
                                    
                                    
                                </div>										
                                         
                            </div>
                        </div>
                                 
                    </div>
                </div>   
           </div>
        </div>
<?php /*  
         <hr/>
         <div class="row">
            <div class="col-md-6">
               <small><a href="https://codecanyon.net/user/nelliwinne" target="_blank">Nelliwinne</a> &copy;</small>
            </div>
            <div class="col-md-6 text-right">
               <small><?php echo date("Y"); ?></small>
            </div>
         </div>
         
 */?>   
    </div>
</body>
</html>
<?php
mysqli_free_result($selectedCrawl);
?>
