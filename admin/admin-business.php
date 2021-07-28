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



$lim=9;

if(@$_GET['stt']==""){
	$stt=0;
}else{
	$stt=@$_GET['stt'];
}


$cid=mysqli_real_escape_string($saha,$_GET['id']);



mysqli_select_db($saha, $database_saha);
$query_selected = "SELECT * FROM business_list WHERE business_list.id='$cid'";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);


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
<body class="gray-bg"  >
                        
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
                                          <table border="0" cellpadding="0" cellspacing="10" class="table table-bordered">
										   <tr>
											  <td>Title :</td>
											   <td><?php echo $row_selected['title']; ?></td>
											</tr>
											  
										   <tr>
											  <td>Description :</td>
											   <td><?php echo $row_selected['description']; ?></td>
											</tr>
											  
										   <tr>
											  <td>Contact Person :</td>
											   <td><?php echo $row_selected['contact_person']; ?></td>
											</tr>
										   <tr>
											  <td>Company Name :</td>
											   <td><?php echo $row_selected['company_name']; ?></td>
											</tr>
											  
										   <tr>
											  <td>Address :</td>
											   <td><?php echo $row_selected['address']; ?></td>
											</tr>

										   <tr>
											  <td>Email :</td>
											   <td><?php echo $row_selected['email']; ?></td>
											</tr>

										   <tr>
											  <td>Mobile Number :</td>
											   <td><?php echo $row_selected['mobile_number']; ?></td>
											</tr>

										   <tr>
											  <td>Phone Number :</td>
											   <td><?php echo $row_selected['phone_number']; ?></td>
											</tr>

										   <tr>
											  <td>Land Mark :</td>
											   <td><?php echo $row_selected['land_mark']; ?></td>
											</tr>

										   <tr>
											  <td>City :</td>
											   <td><?php echo $row_selected['city']; ?></td>
											</tr>
										   <tr>
											  <td>State :</td>
											   <td><?php echo $row_selected['state']; ?></td>
											</tr>
										   <tr>
											  <td>Country :</td>
											   <td><?php echo $row_selected['country']; ?></td>
											</tr>
										   <tr>
											  <td>Category :</td>
											   <td><?php echo $row_selected['category']; ?></td>
											</tr>
										   <tr>
											  <td>Keywords :</td>
											   <td><?php echo $row_selected['keywords']; ?></td>
											</tr>
										   <tr>
											  <td>URL :</td>
											   <td><?php echo $row_selected['base_url']; ?></td>
											</tr>
										   <tr>
											  <td>Search Engine Submit :</td>
											   <td><?php if($row_selected['sesubmit']=="Y"){echo "Yes";}else{echo "No";} ?></td>
											</tr>
											  											  

										   </table>
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
</body>
</html>
<?php
mysqli_free_result($selected);
?>
