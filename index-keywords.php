<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php
//fetch.php
$connect = mysqli_connect($hostname_saha, $username_saha, $password_saha,$database_saha);
$request = mysqli_real_escape_string($connect, $_POST["query"]);
$query = "
 SELECT keyword FROM keywords WHERE keyword LIKE '%".$request."%' LIMIT 10
";

$result = mysqli_query($connect, $query);

$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["keyword"];
 }
 echo json_encode($data);
}

?>