<?php require_once('../Connections/saha.php'); ?>
<?php require_once('../inc-main.php'); ?>
<?php 
header('Content-Type: text/html; charset=utf-8');
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

if (!isset($_SESSION)) {
  session_start();
}

mysqli_select_db($saha, $database_saha);
$query_selectedURL = "SELECT referer_base,SUM(url_views.counter) FROM url_views  GROUP BY url_views.referer_base ORDER BY SUM(url_views.counter) DESC";
$selectedURL = mysqli_query($saha, $query_selectedURL) or die(mysqli_error($saha));
$row_selectedURL = mysqli_fetch_assoc($selectedURL);
$totalRows_selectedURL = mysqli_num_rows($selectedURL);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>os</title>
<link href='//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css' rel='stylesheet'>

</head>

<body>
   <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Site</th>
          <th>Hits</th>
        </tr>
      </thead>
      <tbody>
<?php 
$i=0;
 do{
$i++;	 

?>
        <tr>
          <td><?php echo $i; ?></td>
          <td><?php echo $row_selectedURL['referer_base']; ?></td>
          <td><?php echo $row_selectedURL['SUM(url_views.counter)']; ?></td>
        </tr>
 <?php } while ($row_selectedURL = mysqli_fetch_assoc($selectedURL));?>       
      </tbody>
    </table>

</body>
</html>