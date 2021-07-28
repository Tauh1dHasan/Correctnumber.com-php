<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php require_once('inc.search.php'); ?>
<?php
// getting client request to seller
    if(isset($_POST['submit'])){
        $tq = $_GET['q'];
        $seller_id = mysqli_real_escape_string($saha, $_POST['seller_id']);
        $name = mysqli_real_escape_string($saha, $_POST['name']);
        $mobile = mysqli_real_escape_string($saha, $_POST['mobile']);
        $email = mysqli_real_escape_string($saha, $_POST['email']);
        $short_note = mysqli_real_escape_string($saha, $_POST['short_note']);
        
        $popup_sql = "INSERT INTO client_request (seller_id, search_word, client_name, client_mobile, client_email, client_short_note) VALUES ('$seller_id', '$tq', '$name', '$mobile', '$email', '$short_note')";
        $run_popup_sql = mysqli_query($saha, $popup_sql);
        
        if(!$run_popup_sql){
            echo "<script> alert('Something is not right, Please try again..!!!') </script>";
            echo "<script> location = 'search.php?q=$tq' </script>";
        }else{
            echo "<script> alert('Thank you, We will response soon..!!!') </script>";
            echo "<script> location = 'search.php?q=$tq' </script>";
        }
    }
?>
<?php
// getting client request to you
    if(isset($_POST['search_modal_submit'])){
        $tq = $_GET['q'];

        $name = mysqli_real_escape_string($saha, $_POST['name']);
        $mobile = mysqli_real_escape_string($saha, $_POST['mobile']);
        $email = mysqli_real_escape_string($saha, $_POST['email']);
        $short_note = mysqli_real_escape_string($saha, $_POST['short_note']);
        
        $popup_sql_search = "INSERT INTO client_request_you (search_word, client_name, client_mobile, client_email, client_short_note) VALUES ('$tq', '$name', '$mobile', '$email', '$short_note')";
        $run_popup_sql_search = mysqli_query($saha, $popup_sql_search);
        
        if(!$run_popup_sql_search){
            echo "<script> alert('Something is not right, Please try again..!!!') </script>";
            echo "<script> location = 'search.php?q=$tq' </script>";
        }else{
            echo "<script> alert('Thank you, We will response soon..!!!') </script>";
            echo "<script> location = 'search.php?q=$tq' </script>";
        }
    }
?>
<?php
    // poping the after search popup box
    $keyword = $_GET['q'];
        if(!isset($_COOKIE["keyword"]) || $_COOKIE["keyword"] !== $keyword){
            setcookie("keyword", $keyword);
            // echo "<script> document.getElementById('search_modal').style.display = 'grid'; </script>";
        }
    // poping the after search popup box

?>
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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<style>
    @media only screen and (max-width: 995px) {
  .modal_body {
    margin: 20px 0px 0px 12%;
    width: 90vw !important;
  }
  .search_modal_body {
    margin: 20px 0px 0px 12%;
    width: 90vw !important;
  }
  
}
</style>



   </head>
   <body>
<!-- Ajax -->
<script type="text/javascript" src="js/ajax.php?funame=f1"></script>
<div style="display:none;" id="auto">
                <div class="text-center">
Processing...
                </div>
</div>
<!-- //Ajax -->  













<!--Client Request Popup Modal-->
                <div class="row">
                    <div id="modal" calss="modal col-md-12" style="height: 100vh; width: 100vw; background: rgba(0,0,0,0.5); position: fixed; z-index: 9; display: none;">
                        <div class="row">
                            <div class="col-md-3"></div>
                            
                            <div class="modal_body col-md-6" style="height:65vh; width:45vw; background: white; position: inherit; z-index: 10; margin-top: 6vh; border-radius: 30px;">
                                
                                
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                    <?php
                        $tq = $_GET['q'];
                    ?>
                                        <h2 style="font-weight: 700">Please fill the form to contract the seller</h2>
                                        <form action="#" method="post">
                                            <!--submitting hidden values-->
                                            <span id="popup_id_display"></span>
                                            <input type="hidden" name="seller_id" id="input_popId" val="">
                                            
                                          <div class="form-group">
                                            <label for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="Enter your name" required>
                                          </div>
                                          
                                          <div class="form-group">
                                            <label for="mobile">Mobile Number</label>
                                            <input name="mobile" type="number" class="form-control" id="mobile" placeholder="Enter mobile number" required>
                                          </div>
                                          
                                          <div class="form-group">
                                            <label for="email">Email address</label>
                                            <input name="email" type="email" class="form-control" id="email" placeholder="Enter valid E-mail address" required>
                                          </div>
                                          
                                          <div class="form-group">
                                            <label for="short_note">State your requirement in brief</label>
                                            <textarea name="short_note" class="form-control" id="short_note" required></textarea>
                                          </div>
                                          
                                          <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                                          <button class="btn btn-danger dismiss_modal">Cancel</button>
                                          
                                        </form>
                                        
                                    </div>
                                    <div class="col-md-1"><span class="dismiss_modal" style="float: right; font-size: 30px; cursor: pointer; color:red;">x</span></div>
                                </div>
                                
                                




                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </div>
                
<!--Client Request Popup Modal-->










<!--Search keyword Popup Modal-->
                <div class="row">
                    <div id="search_modal" calss="modal col-md-12" style="height: 100vh; width: 100vw; background: rgba(0,0,0,0.5); position: fixed; z-index: 9; display: none;">
                        <div class="row">
                            <div class="col-md-3"></div>
                            
                            <div class="search_modal_body col-md-6" style="height:65vh; width:45vw; background: white; position: inherit; z-index: 10; margin-top: 6vh; border-radius: 30px;">
                                
                                
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                    <?php
                        $tq = $_GET['q'];
                    ?>
                                        <h2 style="font-weight: 700">Fill the for to get the best deal for <span style="color: red;">"<?= $tq ?>"</span></h2>
                                        <form action="#" method="post">
                                            <!--submitting hidden values-->
                                            <span id="popup_id_display"></span>
                                            <input type="hidden" name="seller_id" id="input_popId" val="">
                                            
                                          <div class="form-group">
                                            <label for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="Enter your name" required>
                                          </div>
                                          
                                          <div class="form-group">
                                            <label for="mobile">Mobile Number</label>
                                            <input name="mobile" type="number" class="form-control" id="mobile" placeholder="Enter mobile number" required>
                                          </div>
                                          
                                          <div class="form-group">
                                            <label for="email">Email address</label>
                                            <input name="email" type="email" class="form-control" id="email" placeholder="Enter valid E-mail address" required>
                                          </div>
                                          
                                          <div class="form-group">
                                            <label for="short_note">State your requirement in brief</label>
                                            <textarea name="short_note" class="form-control" id="short_note" required></textarea>
                                          </div>
                                          
                                          <button name="search_modal_submit" type="submit" class="btn btn-primary">Submit</button>
                                          <button class="btn btn-danger dismiss_search_modal">Cancel</button>
                                          
                                        </form>
                                        
                                    </div>
                                    <div class="col-md-1"><span class="dismiss_search_modal" style="float: right; font-size: 30px; cursor: pointer; color:red;">x</span></div>
                                </div>
                                
                                




                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </div>
                
<!--Search keyword Popup Modal-->











	   
      <div id="wrapper">
		  
        <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12" style="padding-top:0px; height:45px; overflow:hidden; border-bottom:1px solid #CCC;">
                  <a href="index.php" class="btn btn-sm btn-link pull-left"><img src="logo2.png" /></a>
                  
                  <a href="admin/" target="_blank" class="btn btn-sm btn-link pull-right"><i class="fa  fa-user-secret"></i> Admin</a>
                  <a href="all.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Latest Results</a>
                  <a href="search.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Full Page Search</a>
                  <a href="quicksearch.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Page top Search</a>
                  <a href="submit.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-edit"></i> Submit URL</a>
                  <a href="index.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-home"></i>  Home</a>                  
                  </div>
               </div>
               
               <div class="row">
                   <div class="col-md-3"></div>
                   <div class="col-md-6">
<!-- Getting data for banner words from db -->
<?php
    $bw_sql = mysqli_query($saha, "SELECT * FROM banner_word");
    $bw = mysqli_fetch_assoc($bw_sql);
?>
                   </div>
                   <div class="col-md-3"></div>
                </div>

               <div class="row">
<div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="search.php?q=<?php echo @$_GET['q']; ?>" >Web</a></li>
                            <li><a href="search-images.php?q=<?php echo @$_GET['q']; ?>" >Images</a></li>
                        </ul>
                </div>

                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        <div class="col-lg-12">
                     <div class="inqbox float-e-margins">
                        <div class="inqbox-content">
                           <div class="search-form">
                           
                             <form action="" method="get">
                                 <div class="input-group">
                                    <input autocomplete="off" type="text" placeholder="Search Web" id="q" name="q" class="form-control input-lg" value="<?php echo strip_tags(htmlspecialchars(stripslashes(@$cid))); ?>">
                                    
                                     
                                   <div class="input-group-btn">
                                       <button class="btn btn-lg btn-primary" type="submit">
                                       Search
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                           <h2>
<?php if(@$totalRows_searchResults>0){?>                           
                           <?php if(@$cid==""){ ?>
                              <?php echo $totalRows_total; ?> all results found. 
                           <?php }else{?>
                              <?php echo $totalRows_total; ?> results found for: <span class="text-navy">“<?php echo strip_tags(htmlspecialchars(stripslashes(@$cid))); ?>”</span>.
                              <?php }?>
                           </h2>
                           <small>Request time  (<?php $time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.'; ?>)</small>
                          <div class="hr-line-dashed"></div>
<?php }?>                           
<?php if(@$totalRows_imagesFound>0){?> 
<h3>
<?php if($totalRows_imagesFoundTotal>5){?>
<a href="search-images.php?q=<?php echo $_GET['q']; ?>">Images found for <?php echo mysqli_real_escape_string($saha,$_GET['q']); ?></a>
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
				  <div class="col-lg-12">
					  <div class="col-lg-8">
                              <h3><a href="go.php?id=<?php echo $row_searchResults['id']; ?>"><?php echo $row_searchResults['title']; ?></a> <a class="btn" href="go.php?id=<?php echo $row_searchResults['id']; ?>" target="_blank"><i class="fa fa-external-link-square"></i> </a></h3>
                                
                                

                                
                                <!-- Tauhid -->
                                <!-- Popup button (Contact seller) -->
                                
                                <button type="submit" class="btn btn-danger grow_modal" style="border-radius: 30px; background: #d90015;" id="<?= $row_searchResults['id']; ?>">Contact Seller</button>
                                <!-- Tauhid -->
                                <!-- Popup button (Contact seller) -->




                              <a href="go.php?id=<?php echo $row_searchResults['id']; ?>" class="search-link">
<?php if($row_searchResults['ogimage']!=""){?>	
<div style="width:150px; height:100px; overflow:hidden; float:left; margin-right:5px; border:1px solid #CCC;">					
<img src="<?php echo $row_searchResults['ogimage']; ?>" class="image full-width full-height img-rounded">
</div>

<?php }?>
							  <?php echo substr($row_searchResults['current_url'],0,50);if(strlen($row_searchResults['current_url'])>50){echo "...".substr($row_searchResults['current_url'],-30);} ?></a>
                              <p>
                                 <?php 

 echo searchedSample($database_saha, $saha,$_GET['q'],$row_searchResults['id']);								 
								  ?>...
                              </p>
                              <p>
                                 <strong>Visits</strong> : <?php echo $row_searchResults['visits']; ?> <strong>Last Update</strong> : <?php echo $row_searchResults['last_update']; ?>
                              </p>					  
					  </div>
					  
<?php 
$base_url=$row_searchResults['base_url'];


mysqli_select_db($saha, $database_saha);
$query_selectedBusiness = "SELECT email, title, address, mobile_number, contact_person, base_url FROM business_list WHERE business_list.base_url= '$base_url'";
$selectedBusiness = mysqli_query($saha, $query_selectedBusiness) or die(mysqli_error($saha));
$row_selectedBusiness = mysqli_fetch_assoc($selectedBusiness);
$totalRows_selectedBusiness = mysqli_num_rows($selectedBusiness);
					  
?>					  
					  <div class="col-lg-4">
					  <div class="container-fluid well span6">
	<div class="row-fluid">
        
        <div class="span8">
            
            
            
            
            
            <h3><?php echo $row_selectedBusiness['title']; ?></h3>
            <h5>Contact: <?php echo $row_selectedBusiness['contact_person']; ?></h5>
            <h6>Email: <?php echo $row_selectedBusiness['email']; ?></h6>
            <h6>Address: <?php echo $row_selectedBusiness['address']; ?></h6>
            <h6>Mobile: <a href="tel:<?php echo $row_selectedBusiness['mobile_number']; ?>"><?php echo $row_selectedBusiness['mobile_number']; ?></a></h6>
            <h6>Web:  <a href="//<?php echo $row_selectedBusiness['base_url']; ?>" target="_blank"><?php echo $row_selectedBusiness['base_url']; ?></a></h6>
<?php /*             <h6><a href="#">More... </a></h6> */?>
        </div>
        
        
</div>
</div>
					  </div>
				  </div>
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
mysqli_select_db($saha, $database_saha);
$query_searchTerms = "SELECT * FROM log GROUP BY log.keyword ORDER BY SUM(log.count) DESC LIMIT 20";
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
                  </div>
                        </div>
                        <div class="tab-pane fade" id="tab2default">Default 2</div>
                    </div>
                </div>
            </div>               
                  
               </div>
            </div>
      </div>

     
<?php include("inc.footer.php"); ?>  







<?php
    // poping the after search popup box
    $keyword = $_GET['q'];
        if(!isset($_COOKIE["keyword"]) || $_COOKIE["keyword"] !== $keyword){
            // setcookie("keyword", $keyword);
            echo "<script> document.getElementById('search_modal').style.display = 'grid'; </script>";
        }
    // poping the after search popup box

?>










<script>

$(document).ready(function(){

  $(".grow_modal").click(function(){
	$("#modal").css("display", "grid");
    var popId = $(this).attr("id");
    $("#input_popId").val(popId);
  });
  

  $(".dismiss_modal").click(function(){
    $("#modal").css("display", "none"); 
  });
  
  $(".dismiss_search_modal").click(function(){
    $("#search_modal").css("display", "none"); 
  });

});
	
</script>













<!--	Auto Complete -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
 <script>
$(document).ready(function(){
 
 $('#q').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"index-keywords.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
  }
 });
 
});
</script>
	
<!--	EndAuto Complete -->





</body>
</html>