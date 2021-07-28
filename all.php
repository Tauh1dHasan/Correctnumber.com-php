<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php require_once('inc.search.php'); ?>
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

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

//Crawling
/*
require("inc.crawl.php");
CrawlURL($database_saha,$saha);  
*/
//Search
	$lim=10;
if(@$_GET['stt']==""){
	$stt=0;
}else{
	$stt=@$_GET['stt'];
}

if(isset($_GET['stt']) && is_numeric(@$_GET['stt'])!=1){
header("Location: index.php");
exit;
}


mysqli_select_db($saha, $database_saha);
$query_total = "SELECT * FROM crawl WHERE crawl.deleted='N'";
$total = mysqli_query($saha, $query_total) or die(mysqli_error($saha));
$row_total = mysqli_fetch_assoc($total);
$totalRows_total = mysqli_num_rows($total);


mysqli_select_db($saha, $database_saha);
$query_searchResults = "SELECT * FROM crawl WHERE crawl.deleted='N' ORDER BY crawl.id DESC LIMIT $stt,$lim";
$searchResults = mysqli_query($saha, $query_searchResults) or die(mysqli_error($saha));
$row_searchResults = mysqli_fetch_assoc($searchResults);
$totalRows_searchResults = mysqli_num_rows($searchResults);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Easy Web Search</title>
<link rel="shortcut icon" href="icon.png" type="image/png" />
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
      <link href="css/animate.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      
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
   
       <div id="wrapper">
                    
         <div class="animated fadeInRight">
               <div class="row">
                  <div class="col-lg-12" style="padding-top:0px; height:45px; overflow:hidden; border-bottom:1px solid #CCC;">
                  <a href="index.php" class="btn btn-sm btn-link pull-left"><img src="logo2.png" /></a>
                  <a href="admin/" target="_blank" class="btn btn-sm btn-link pull-right"><i class="fa  fa-user-secret"></i> Admin</a>
                  <a href="all.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Latest Crawling</a>
                  <a href="search.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Full Page Search</a>
                  <a href="submit.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-edit"></i> Submit URL</a>
                  <a href="quicksearch.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Page top Search</a>
                  <a href="index.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-home"></i>  Home</a>                  
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-9">
                     <div class="inqbox float-e-margins">
                        <div class="inqbox-content">
                           <div class="search-form">
                             <form action="search.php" method="get">
                                 <div class="input-group">
                                    <input type="text" placeholder="Search Web" name="q" class="form-control input-lg" value="<?php echo strip_tags(strip_tags(htmlentities(stripslashes(@$cid)))); ?>">
                                    <div class="input-group-btn">
                                       <button class="btn btn-lg btn-primary" type="submit">
                                       Search
                                       </button>
                                    </div>
                                 </div>
                             </form>
                           </div>
                           <h2>
                           <?php if(@$cid==""){ ?>
                              <?php echo $totalRows_total; ?> all results found. 
                           <?php }else{?>
                              <?php echo $totalRows_total; ?> results found for: <span class="text-navy">“<?php echo @$cid; ?>”</span>.
                              <?php }?>
                           </h2>
                           <small>Request time  (<?php $time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.'; ?>)</small>
                           <div class="hr-line-dashed"></div>
<?php do{ ?>                           
              <div class="search-result">
                              <h3><a href="go.php?id=<?php echo $row_searchResults['id']; ?>"><?php echo $row_searchResults['title']; ?></a></h3>
                              <a href="go.php?id=<?php echo $row_searchResults['id']; ?>" class="search-link">
<?php if($row_searchResults['ogimage']!=""){?>	
<div style="width:150px; height:100px; overflow:hidden; float:left; margin-right:5px; border:1px solid #CCC;">					
<img src="<?php echo $row_searchResults['ogimage']; ?>" class="image full-width full-height img-rounded">
</div>
<?php }?>
							  <?php echo substr($row_searchResults['current_url'],0,50);if(strlen($row_searchResults['current_url'])>50){echo "...".substr($row_searchResults['current_url'],-30);} ?></a>
                              <p>
                                 <?php echo searchedSample($database_saha, $saha,' ',$row_searchResults['id']); ?>
                              </p>
                              <p>
                                 <strong>Visits</strong> : <?php echo $row_searchResults['visits']; ?> <strong>Last Update</strong> : <?php echo $row_searchResults['last_update']; ?>
                              </p>
              </div>
                           <div class="hr-line-dashed"></div>

<?php } while ($row_searchResults = mysqli_fetch_assoc($searchResults)); ?>
<?php if($totalRows_total>$lim){?>
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
       
<a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=0<?php }?>">
                                 <button class="btn btn-white pull-left" type="button"><i class="fa fa-chevron-left"></i></button>
</a>                                 

  <?php 


	$aa=$allowedmin-1;
	for($s=$allowedmin;$s<=$allowedmax;$s++){
        $aa=$aa+1;

if($lastpage>=$aa){
		
	 ?><a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=<?php echo ($aa-1)*$lim; ?><?php }?>">
<?php }?>

                                 <button class="btn btn-white <?php  if((($aa-1)*$lim)==$stt){  ?>active<?php }?>"><?php echo  sprintf("%0".$roudnoff."s", $aa);  ?></button>
                                 </a>
<?php }?>
  
  <?php if($totap>0){?>        

<a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=<?php echo (floor(($totalRows_total/$lim)*$lim)-$lim); ?><?php }?>">
                               <button class="btn btn-white" type="button"><i class="fa fa-chevron-right"></i> </button>
                               </a>
<?php }?>
                              </div>
                           </div>
<?php }?>                           
                        </div>
                     </div>
                  </div>

<div class="col-sm-3">
<?php
/////////////////////////////////
//////Top Searched Terms/////////
?>
<?php 
mysqli_select_db($saha, $database_saha);
$query_searchTerms = "SELECT * FROM log GROUP BY log.keyword ORDER BY SUM(log.count) DESC LIMIT 20";
$searchTerms = mysqli_query($saha, $query_searchTerms) or die(mysqli_error($saha));
$row_searchTerms = mysqli_fetch_assoc($searchTerms);
$totalRows_searchTerms = mysqli_num_rows($searchTerms);

?>
<?php if($totalRows_searchTerms>0){?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong> Top Searched Terms</strong>
					</div>
					<div class="panel-body text-center">
<?php do{?>
                  <a href="search.php?q=<?php echo $row_searchTerms['keyword']; ?>" class="btn btn-white btn-cons"><?php echo $row_searchTerms['keyword']; ?></a>
<?php } while ($row_searchTerms = mysqli_fetch_assoc($searchTerms)); ?>
						
					</div>
                </div>
<?php }?>
<?php 
//////End Top Searched Terms/////
/////////////////////////////////
?>
</div>

<div class="col-sm-3">

                            
                                                        <hr class="small-highlight">

                            <p>Easy Web Search is engine to use inside a web site. Simple Crawling System is available to submit URLs and Links from submitted URL will be automatically added to search database when admin run crawling. Once crawling is done the links with their contents (Plain text from web link) will be available to search. If the total web site is linked to it's home page, you need jut give home page url to the sytem and run crawling once. You do not need to much work to implement a search system inside your web site.</p>

                 </div>
                        
<div class="col-sm-3">
                            <h2>Discover great features.</h2>
                            <hr class="small-highlight">
                            <ul>
                              <li> Responsive Search Page </li>
                              <li>Crawl Unlimited Pages </li>
                              <li>Link Submit for Crawling </li>
                              <li>Admin Area to Manage Links </li>
                              <li>Easy to Use in your web </li>
                              <li>Installation Documentatio Available </li>
                              <li>Full Page Search and Quick Search </li>
                              <li>Start Search from html Page </li>
                              <li>PHP / MySQL Based </li>
                              <li>Simple to Install </li>
                              <li>PHP 5 and PHP 7</li>
                            </ul>
                            
                                                        

                 </div>
                 
                 <div class="col-sm-3">
                            <h2>Sample Pages</h2>
                            <p><a href="sample1.php" target="_blank" class="navy-link" role="button">Full Page Search</a></p>
                            <p><a href="sample3.php" target="_blank" class="navy-link" role="button">Full Page Search (Non Styled</a></p>
                            <p><a href="sample2.php" target="_blank" class="navy-link" role="button">Quick Search from any other Page</a></p>

                 </div>    
                 
                 <div class="col-sm-3">
                   <h2>Usage</h2>

                   <h3>PHP Code (Put This Code before &lt;html&gt; tag or at very begining of the page</h3>
                   <div style="padding: 10px; color: #F00; height:100px; overflow:scroll; overflow-x:hidden; border:1px solid #999;">&lt;?php require_once('Connections/saha.php'); ?&gt;<br>                   &lt;?php require_once('inc-main.php'); ?&gt;<br>                   &lt;?php require_once('inc.search.php'); ?&gt; </div>

                   <h3>HTML Header (Put this code within &lt;head&gt; to &lt;/head&gt;)</h3>
                   <div style="padding: 10px; color: #F90; height: 150px; overflow: scroll; overflow-x: hidden; border: 1px solid #999;"> &lt;link href=&quot;css/bootstrap.min.css&quot; rel=&quot;stylesheet&quot;&gt;<br>                     &lt;link href=&quot;fonts/font-awesome/css/font-awesome.css&quot; rel=&quot;stylesheet&quot;&gt;<br>                     &lt;link href=&quot;css/animate.css&quot; rel=&quot;stylesheet&quot;&gt;<br>                     &lt;link href=&quot;css/style.css&quot; rel=&quot;stylesheet&quot;&gt; </div>
                              
                              
                              
                              
                  
                  
                   <h3>HTML Body (with PHP embeded)</h3>
                   <div style="padding: 10px; color: #33F; height: 150px; overflow: scroll; overflow-x: hidden; border: 1px solid #999;">&lt;div class=&quot;wrapper wrapper-content animated fadeInRight&quot;&gt;                                                &lt;div class=&quot;row&quot;&gt;                   &lt;div class=&quot;col-lg-12&quot;&gt;                      &lt;div class=&quot;inqbox float-e-margins&quot;&gt;                         &lt;div class=&quot;inqbox-content&quot;&gt;                            &lt;div class=&quot;search-form&quot;&gt;                              &lt;form action=&quot;&quot; method=&quot;get&quot;&gt;                                  &lt;div class=&quot;input-group&quot;&gt;                                     &lt;input type=&quot;text&quot; placeholder=&quot;Search Web&quot; name=&quot;q&quot; class=&quot;form-control input-lg&quot; value=&quot;&lt;?php echo @$cid; ?&gt;&quot;&gt;                                     &lt;div class=&quot;input-group-btn&quot;&gt;                                        &lt;button class=&quot;btn btn-lg btn-primary&quot; type=&quot;submit&quot;&gt;                                        Search                                        &lt;/button&gt;                                     &lt;/div&gt;                                  &lt;/div&gt;                               &lt;/form&gt;                            &lt;/div&gt;...<br>
                   .... <strong style="color:#C30">Copy and Paste Entire Body from sample1.php</strong> .... </div>
                 </div>                    
           </div>
            </div>
      </div>
     
<?php include("inc.footer.php"); ?>   
   </body>
</html>