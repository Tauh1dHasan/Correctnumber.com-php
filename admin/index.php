<?php require_once('../Connections/saha.php'); ?>
<?php require_once('../inc-main.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=md5($_POST['password']);
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "dashboard.php";
  $MM_redirectLoginFailed = "index.php?login=failed";
  $MM_redirecttoReferrer = true;
  mysqli_select_db($saha, $database_saha);
  
  $LoginRS__query=sprintf("SELECT un, pw FROM `admin` WHERE un=%s AND pw=%s",
    GetSQLValueString($loginUsername, "text"), 
	GetSQLValueString($password, "text")); 
   
  $LoginRS = $LoginRS = mysqli_query($saha, $LoginRS__query) or die(mysqli_error($saha));
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_UsernameAdMIN'] = $loginUsername;
    $_SESSION['MM_UserGroupADmin'] = $loginStrGroup;	      


    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
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
   </head>
<body class="gray-bg">
<!-- Ajax -->
<script type="text/javascript" src="js/ajax.php?funame=f1"></script>
<div style="display:none;" id="auto">
<input value="Sign in" name="Search" id="Search" class="btn btn-lg" type="submit" disabled="disabled">
<p class="alert-warning">Waiting...</p>
</div>
<!-- //Ajax -->


<div class="loginColumns animated fadeInDown">
         <div class="row">
            <div class="col-md-6">
               <h2 class="font-bold">Easy Web Search - Admin Area</h2>
               <p>
                  Easy Web Search is engine to use inside a web site. Simple Crawling System is available to submit URLs and Links from submitted URL will be automatically added to search database when admin run crawling. Once crawling is done the links with their contents (Plain text from web link) will be available to search. If the total web site is linked to it's home page, you need jut give home page url to the sytem and run crawling once. You do not need to much work to implement a search system inside your web site. 
               </p>
               
               <p>
                  <small>Version 5.0</small>
               </p>
           </div>
            <div class="col-md-6">
               <div class="inqbox-content">
                  <form action="<?php echo $loginFormAction; ?>" method="POST" class="m-t" role="form">
                     <div class="form-group">
                        <input name="username" type="text" required class="form-control" id="username" placeholder="Username" value="administrator">
                     </div>
                     <div class="form-group">
                        <input name="password" type="password" required class="form-control" id="password" placeholder="Password" value="admin">
                     </div>
 <label>Captica Verification</label><br />
         <img src="verificationimage.php?<?php echo rand(0,9999);?>" alt="verification image, type it in the box" align="absbottom" />
        <input name="verif_box" type="text" autocomplete="off" id="verif_box"  class="form-control input-sm" style="width:200px;" required onkeyup="ajaxFunctionf1('load','auto','load','','','admin-verify.php',this.value);" />
              
              <br style="clear:both" />
              
            
             
              
              <label class="divider" id="load">
              <input value="Sign in" name="Search" id="Search" class="btn btn-lg" type="submit" disabled="disabled">
              </label>  
                                   
                  </form>
                  <p class="m-t">
                     <small><a href="https://codecanyon.net/user/nelliwinne" target="_blank">Nelliwinne</a> &copy; <?php echo date("Y"); ?></small>
                  </p>
              </div>
            </div>
         </div>
         <hr/>
         
   </div>
</body>
</html>