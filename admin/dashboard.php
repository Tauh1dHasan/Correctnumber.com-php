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
<h1><img src="logo.png" width="128" height="128" alt="EWS"> 
  Easy Web Search - Admin Panel</h1>

               
               
            <div class="clients-list">
                              <ul class="nav nav-tabs tab-border-top-danger">
                              <?php include("admin-navi.php"); ?>   
                                 
                              </ul>
                              <div class="tab-content">
                                 <div id="tab-1" class="tab-pane active">
                                    <div class="full-height-scroll">
                                       

<div class="row">
<div style="width: 100%; ">
        <canvas id="canvas" style="height:400px;"></canvas>
    </div>
<?php 
$Date = date("Y-m-d");
$ldays=12;
?>
    <script>
        var chartData = {
            labels: [<?php for($i=1;$i<=$ldays;$i++){?>"<?php echo date('dS D', strtotime($Date. ' - '.($i-1).' days'));?>"<?php if($i!=$ldays){echo ",";}?><?php }?>],
            datasets: [{
                type: 'line',
                label: 'URL Clicks',
                borderColor: window.chartColors.green,
                borderWidth: 2,
                fill: true,
                data: [
<?php for($i=1;$i<=$ldays;$i++){?><?php
$ccdate=date('Y-m-d', strtotime($Date. ' - '.($i-1).' days'));

mysqli_select_db($saha, $database_saha);
$query_pcbook = "SELECT * FROM url_views WHERE url_views.date='$ccdate'"; 
$pcbook = mysqli_query($saha, $query_pcbook) or die(mysqli_error($saha));
$row_pcbook = mysqli_fetch_assoc($pcbook);
$totalRows_pcbook = mysqli_num_rows($pcbook);

			
			 echo round($totalRows_pcbook,2); ?><?php if($i!=$ldays){echo ",";}?><?php }?>
                ]
            }]

        };
        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myMixedChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Statistics of URL Hit Counts'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: true
                    }
                }
            });
        };

        document.getElementById('randomizeData').addEventListener('click', function() {
            chartData.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return randomScalingFactor();
                });
            });
            window.myMixedChart.update();
        });
    </script>            
                      
    </div>										   

<div class="row">

          <div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading"><h5>Browsers</h5></div>
            <div class="panel-body">
<div style="width: 100%">
 <iframe src="dashboard-browsers.php" frameborder="0" scrolling="no" height="200" style="width:100%; height:330px;"></iframe>
</div>
            </div>
          </div>
        </div>

        
<div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading"><h5>Referrers</h5></div>
            <div class="panel-body">
<div style="width: 100%">
 <iframe src="dashboard-source.php" frameborder="0" scrolling="no" height="200" style="width:100%; height:330px; overflow-x:hidden; overflow:auto;"></iframe>
</div>
            </div>
          </div>
        </div>
        
<div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading"><h5>Platforms</h5></div>
            <div class="panel-body">
<div style="width: 100%">
 <iframe src="dashboard-os.php" frameborder="0" scrolling="no" height="200" style="width:100%; height:330px;"></iframe>
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
         
 */?>   </div>
</body>
</html>
<?php
mysqli_free_result($selectedCrawl);
?>
