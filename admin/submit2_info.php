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
    <div class="row">
        <div style="margin-left: 10px; overflow: auto;" class="col-md-12">
            <h2 style="text-align: center; margin-bottom: 25px; font-weight: bold;">Submit2 form data</h2>
        <!--Delete this table to make a blank page-->
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Web URL</th>
              <th scope="col">Title</th>
              <th scope="col">Description</th>
              <th scope="col">Contact Person</th>
              <th scope="col">Company Name</th>
              <th scope="col">Address</th>
              <th scope="col">Email</th>
              <th scope="col">Mobile Number</th>
              <th scope="col">Phone Number</th>
              <th scope="col">Land Mark</th>
              <th scope="col">City</th>
              <th scope="col">State</th>
              <th scope="col">Country</th>
              <th scope="col">Category</th>
              <th scope="col">Keyword</th>
              <th scope="col">Targeted Client</th>
              <th scope="col">Short Note</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
    <?php
        $tsql = mysqli_query($saha, "SELECT * FROM submit2");
        while($trow = mysqli_fetch_assoc($tsql)) {
    ?>
            <tr>
              <td><?= $trow['web_url'] ?></td>
              <td><?= $trow['title'] ?></td>
              <td><?= $trow['description'] ?></td>
              <td><?= $trow['contact_person'] ?></td>
              <td><?= $trow['company_name'] ?></td>
              <td><?= $trow['address'] ?></td>
              <td><?= $trow['email'] ?></td>
              <td><?= $trow['mobile_number'] ?></td>
              <td><?= $trow['phone_number'] ?></td>
              <td><?= $trow['land_mark'] ?></td>
              <td><?= $trow['city'] ?></td>
              <td><?= $trow['state'] ?></td>
              <td><?= $trow['country'] ?></td>
              <td><?= $trow['category'] ?></td>
              <td><?= $trow['keyword'] ?></td>
              <td><?= $trow['targeted_client'] ?></td>
              <td><?= $trow['short_note'] ?></td>
              <td>
                  <a href="del_fun.php?id=<?= $trow['id'] ?>" class="btn btn-danger">Delete</a>
              </td>
            </tr>
    <?php
        }
    ?>
          </tbody>
        </table>
        <!--/ End of table-->
        </div>
    </div>
</body>
</html>
<?php
mysqli_free_result($selectedCrawl);
?>
