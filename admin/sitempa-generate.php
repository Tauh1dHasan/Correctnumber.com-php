<?php 
if(isset($_GET['q'])){
$query=strip_tags(htmlentities(stripslashes($_GET['q'])));
	header("Location:../sitemap_".$query.".xml");
	exit;
	
}else{
	header("Location:".$_SERVER['HTTP_REFRER']);
	exit;
}

?>