<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php require_once('inc.search.php'); ?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Easy Web Search</title>
<link rel="shortcut icon" href="icon.png" type="image/png" />
       
   </head>
   <body>

<form action="" method="get">

<input type="text" placeholder="Search Web" name="q" value="<?php echo strip_tags(htmlentities(stripslashes(@$cid))); ?>">
<button  type="submit"> Search </button>
</form>

 
<?php if(@$totalRows_searchResults>0){?>                           
                           <?php if(@$cid==""){ ?>
                              <?php echo $totalRows_total; ?> all results found. 
                           <?php }else{?>
                              <?php echo $totalRows_total; ?> results found for: <span class="text-navy">“<?php echo @$cid; ?>”</span>.
                              <?php }?>
                           </h2>
                           <small>Request time  (<?php 
echo 'Page generated in '.$total_time.' seconds.'; ?>)</small>
<?php }?>  

<?php if(@$totalRows_imagesFound>0){?> 
<h3>
<?php if($totalRows_imagesFoundTotal>5){?>
<a href="sample3-images.php?q=<?php echo $_GET['q']; ?>">Images found for <?php echo $_GET['q']; ?></a>
<?php }else{?>
Images found for <?php echo $_GET['q']; ?>
<?php }?>
</h3>                          
<?php do{ ?>                           
<div style="width:150px; height:100px; overflow:hidden; float:left; margin-right:5px; border:1px solid #CCC;"><a href="<?php echo $row_imagesFound['image_url']; ?>" target="_blank"><img src="piccrop.php?w=150&img=<?php echo base64_encode($row_imagesFound['image_url']); ?>" class="image full-width full-height"></a></div>
<?php } while ($row_imagesFound = mysqli_fetch_assoc($imagesFound)); ?>
<br style="clear:both;">
<hr>

<?php }?>
                         
<?php if(@$totalRows_searchResults>0){?>                           
<?php do{ ?>                           
              <div>
                              <h3><a href="go.php?id=<?php echo $row_searchResults['id']; ?>"><?php echo $row_searchResults['title']; ?></a></h3>
                              <a href="go.php?id=<?php echo $row_searchResults['id']; ?>" class="search-link"><?php echo substr($row_searchResults['current_url'],0,50);if(strlen($row_searchResults['current_url'])>50){echo "...".substr($row_searchResults['current_url'],-30);} ?></a>
                              <p>
                                 <?php if($row_searchResults['content']==""){echo substr($row_searchResults['description'],0,500);}else{ echo substr($row_searchResults['description'],0,500);} ?>...
                              </p>
                              <p>
                                 <strong>Visits</strong> : <?php echo $row_searchResults['visits']; ?> <strong>Last Update</strong> : <?php echo $row_searchResults['last_update']; ?>
                              </p>
                           </div>


<?php } while ($row_searchResults = mysqli_fetch_assoc($searchResults)); ?>
<?php }else if(@$totalRows_total==0 && @$_GET['q']!=""){?>
<?php echo $totalRows_total; ?> results found for "<?php echo $_GET['q']; ?>". Please try again !
<?php }?>
<?php if(@$totalRows_total>@$lim){?>
                           <div>
                              <div>
<?php 
$aa=0;
$totap= (($totalRows_total-($totalRows_total%$lim))/$lim);
		if(($totalRows_total%$lim)>0){
			$totap=$totap+1;
		}
		
$crrpage=(($stt/$lim)+1);
$lastpage=floor($totalRows_total/$lim)+1;
if($totap>=18){
	$allowedmin=$crrpage-9;
	if($allowedmin<1){
		$allowedmin=1;
	}
	$allowedmax=$crrpage+9;
	if($allowedmax<18){
	if($totalRows_total<18){	
	$allowedmax=$totalRows_total;
	}else{
	$allowedmax=18;	
	}
	}
	
}else{
	$allowedmin=1;
	$allowedmax=$totap;
}

if($allowedmax<100){
	$roudnoff=2;
}else if($allowedmax<1000){
	$roudnoff=3;
}else if($allowedmax<10000){
	$roudnoff=4;
}else if($allowedmax<100000){
	$roudnoff=5;
}else if($allowedmax<1000000){
	$roudnoff=6;
}

?>         
       
<a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=0<?php }?>">
First Page
</a>      |                            

  <?php 


	$aa=$allowedmin-1;
	for($s=$allowedmin;$s<=$allowedmax;$s++){
        $aa=$aa+1;

if($lastpage>=$aa){
		
	 ?><a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=<?php echo ($aa-1)*$lim; ?><?php }?>">
<?php }?>

<?php echo  sprintf("%0".$roudnoff."s", $aa);  ?>
                                 </a> | 
<?php }?>

|   
  <?php if($totap>0){?>        
 
<a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=<?php echo (floor(($totalRows_total/$lim)*$lim)-$lim); ?><?php }?>">
Last Page                               </a>
<?php }?>
                              </div>
                           </div>
<?php }?>                           
                        
     
   </body>
</html>