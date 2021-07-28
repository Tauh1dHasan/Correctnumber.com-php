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
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
      <link href="css/animate.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      
   </head>
   <body>
      <div id="wrapper">
<div class="animated fadeIn">
<div class="col-lg-1">
<img src="logo2.png">
</div>
<div class="col-lg-11">

<div class="col-lg-12">
<div class="col-lg-7">

                           <div class="search-form">
                           
                             <form action="" method="get">
                                 <div class="input-group">
                                    <input type="text" placeholder="Search Web" name="q" class="form-control input-lg" value="<?php echo strip_tags(htmlentities(stripslashes(@$cid))); ?>">
                                     
                                   <div class="input-group-btn">
                                       <button class="btn btn-lg btn-primary" type="submit">
                                       Search
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
</div>
<div class="col-lg-5">
</div>                           
<div class="col-lg-7">
                           <h2>
                           <?php if(@$cid==""){ ?>
                              <?php echo @$totalRows_total; ?> all results found. 
                           <?php }else{?>
                              <?php echo @$totalRows_total; ?> results found for: <span class="text-navy">“<?php echo strip_tags(htmlentities(stripslashes(@$cid))); ?>”</span>.
                              <?php }?>
                           </h2>
                           <small>Request time  (<?php $time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.'; ?>)</small>
<div class="hr-line-dashed"></div>
<div class="btn-group">
          <a type="button" class="btn btn-link"  href="search.php?q=<?php echo @$_GET['q']; ?>">
          All
          </a>
          <a class="btn btn-link"  href="search-images.php?q=<?php echo $_GET['q']; ?>">
          Images
          </a>
</div>
</div>
<div class="col-lg-5">
</div>                           

</div>

<div class="col-lg-12">
<div class="col-lg-7">
<div class="inqbox-content">


                          
<?php if(@$totalRows_imagesFound>0){?> 
<h3>
<?php if($totalRows_imagesFoundTotal>5){?>
<a href="search-images.php?q=<?php echo $_GET['q']; ?>">Images found for <?php echo $_GET['q']; ?></a>
<?php }else{?>
Images found for <?php echo $_GET['q']; ?>
<?php }?>
</h3>                          
<?php do{ ?>                           
<div style="width:150px; height:100px; overflow:hidden; float:left; margin-right:5px; border:1px solid #CCC;"><a href="<?php echo $row_imagesFound['image_url']; ?>" target="_blank"><img src="piccrop.php?w=150&img=<?php echo base64_encode($row_imagesFound['image_url']); ?>" class="image full-width full-height"></a></div>
<?php } while ($row_imagesFound = mysqli_fetch_assoc($imagesFound)); ?>
<div class="clearfix"></div>
<div class="hr-line-dashed"></div>
<?php }?>
<?php if(@$totalRows_searchResults>0){?>                           
<?php do{ ?>                           
              <div class="search-result">
                              <h3><a href="go.php?id=<?php echo $row_searchResults['id']; ?>"><?php echo $row_searchResults['title']; ?></a> <a class="btn" href="go.php?id=<?php echo $row_searchResults['id']; ?>" target="_blank"><i class="fa fa-external-link-square"></i> </a></h3>
                              <a href="go.php?id=<?php echo $row_searchResults['id']; ?>" class="search-link">
							  <?php echo substr($row_searchResults['current_url'],0,50);if(strlen($row_searchResults['current_url'])>50){echo "...".substr($row_searchResults['current_url'],-30);} ?></a>
                              <p style="overflow:hidden;">
                                 <?php 

 echo searchedSample($database_saha, $saha,$_GET['q'],$row_searchResults['id']);								 
								  ?>...
                              </p>
                              <p>
                                 <strong>Visits</strong> : <?php echo $row_searchResults['visits']; ?> <strong>Last Update</strong> : <?php echo $row_searchResults['last_update']; ?>
                              </p>
                           </div>
                           <div class="hr-line-dashed"></div>

<?php } while ($row_searchResults = mysqli_fetch_assoc($searchResults)); ?>
<?php }else if(@$totalRows_total==0 && @$_GET['q']!=""){?>
<?php echo @$totalRows_total; ?> results found for "<?php echo $_GET['q']; ?>". Please try again !
<?php }?>
<?php if(@$totalRows_total>@$lim){?>
                           <div class="text-center">
                              <div class="btn-group">
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
       
<a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo strip_tags(htmlentities(stripslashes(@$cid))); ?>&stt=0<?php }?>">
                                 <button class="btn btn-white pull-left" type="button"><i class="fa fa-chevron-left"></i></button>
</a>                                 

  <?php 


	$aa=$allowedmin-1;
	for($s=$allowedmin;$s<=$allowedmax;$s++){
        $aa=round($aa)+1;

if($lastpage>=$aa){
		
	 ?><a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo strip_tags(htmlentities(stripslashes(@$cid))); ?>&stt=<?php echo ($aa-1)*$lim; ?><?php }?>">
<?php }?>

                                 <button class="btn btn-white <?php  if((($aa-1)*$lim)==$stt){  ?>active<?php }?>"><?php echo  sprintf("%0".$roudnoff."s", $aa);  ?></button>
                                 </a>
<?php }?>
  
  <?php if($totap>0){?>        

<a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo strip_tags(htmlentities(stripslashes(@$cid))); ?>&stt=<?php echo (floor(($totalRows_total/$lim)*$lim)-$lim); ?><?php }?>">
                               <button class="btn btn-white" type="button"><i class="fa fa-chevron-right"></i> </button>
                               </a>
<?php }?>
                              </div>
                           </div>
<?php }?>                          
<?php
/////////////////////////////////
//////Top Searched Terms/////////
 if(@$totalRows_total==0 && @$_GET['q']==""){?>
<?php 
mysqli_select_db($saha, $database_saha);$query_searchTerms = "SELECT * FROM log GROUP BY log.keyword ORDER BY SUM(log.count) DESC LIMIT 20";
$searchTerms = mysqli_query($saha, $query_searchTerms) or die(mysqli_error($saha));
$row_searchTerms = mysqli_fetch_assoc($searchTerms);
$totalRows_searchTerms = mysqli_num_rows($searchTerms);
?>
<?php if($totalRows_searchTerms>0){?>
    <div class="container" style="margin-top:40px">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong> Top Searched Terms</strong>
					</div>
					<div class="panel-body text-center">
<?php do{?>
                  <a href="search.php?q=<?php echo $row_searchTerms['keyword']; ?>" class="btn btn-link btn-cons"><?php echo $row_searchTerms['keyword']; ?></a>
<?php } while ($row_searchTerms = mysqli_fetch_assoc($searchTerms)); ?>
						
					</div>
                </div>
			</div>
		</div>
	</div>
<?php }?>
<?php }
//////End Top Searched Terms/////
/////////////////////////////////
?>
                        </div>
</div>
<div class="col-lg-5">
<!-- Your Ad here -->
--Ad Space --
</div>

</div>

</div>

</div>

      </div>
     
   </body>
</html>