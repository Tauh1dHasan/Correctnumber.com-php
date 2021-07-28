<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php 
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>FIND THE CORRECT NUMBER HERE</title>
      <link rel="shortcut icon" href="icon.png" type="image/png" />
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
      <link href="css/animate.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
	
	
<style type="text/css">
/* centered columns styles */
.row-centered {
    text-align:center;
}
.col-centered {
    display:inline-block;
    float:none;
    /* reset the text-align */
    text-align:left;
    /* inline-block space fix */
    margin-right:-4px;
}
.col-fixed {
    /* custom width */
    width:320px;
}
.col-min {
    /* custom min width */
    min-width:320px;
}
.col-max {
    /* custom max width */
    max-width:320px;
}

/* visual styles */
body {
    padding-bottom:40px;
}
h1 {
    margin:40px 0px 20px 0px;
	color:#95c500;
    font-size:28px;
    line-height:34px;
    text-align:center;
}

.item {
    width:100%;
    height:100%;
	border:1px solid #cecece;
    padding:28px;
	background:#ededed;
	background:-webkit-gradient(linear, left top, left bottom,color-stop(0%, #f4f4f4), color-stop(100%, #ededed));
	background:-moz-linear-gradient(top, #f4f4f4 0%, #ededed 100%);
	background:-ms-linear-gradient(top, #f4f4f4 0%, #ededed 100%);
	border-radius: 15px;
}

/* content styles */
.item {
	display:table;
}
.content {
	display:table-cell;
	vertical-align:middle;
	text-align:center;
}

/* centering styles for jsbin */
html,
body {
    width:100%;
    height:100%;
}
html {
    display:table;
}
body {
    display:table-cell;
}
</style>
	

</head>
<body>
<div id="wrapper">
<div class="animated fadeIn">
               <div class="row bg-primary">
                  <div class="col-lg-12" style="padding-top:0px;">
                  <a href="submit.php" class="btn btn-sm btn-link pull-left"><i class="fa fa-edit"></i> Don't Ever Miss a Client, Submit Your Details Now. </a>
                  <a href="submit2.php" class="btn btn-sm btn-link pull-left"><i class="fa fa-edit"></i> Take a quick survay / submit2 </a>
                  <a href="admin/" target="_blank" class="btn btn-sm btn-link pull-right"><i class="fa  fa-user-secret"></i> Admin</a>
                  <a href="all.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> </a>
                  <a href="search.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Full Page Search</a>
                  <a href="quicksearch.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> </a>
                  </div>
               </div>
                  

<h1><img src="logo.png" width="363" height="59" alt="EWS"><br>
 Find The Correct Number Here </h1>
  
<div class="container">
    <div class="row row-centered">
        <div class="col-xs-6 col-centered col-min">
        <div class="item">&nbsp;
        <div class="content">
        <div class="search-form">
        <form action="search.php" method="get">
        <div class="input-group">
            <input autocomplete="off" id="q" type="text" placeholder="Enter Total Company name Example- ABC India Private Limited" name="q" class="form-control input-lg" value="<?php echo strip_tags(htmlentities(stripslashes(@$cid))); ?>" required>
                                     
                <div class="input-group-btn">
                    <button class="btn btn-lg btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
        </div>
      </form>
    </div>
        </div>
        </div>
        </div>
    </div>
</div>

    <div class="row row-centered">
    <div class="col-xs-6 col-centered col-min circle-border">
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
<div class="panel panel-default" style="border:none; margin-top:50px;">
<fieldset>
<legend style="text-align:center;">Latest Searched Companies For Correct Number</legend>
<div class="panel-body text-center">
<?php do{?>
<a href="search.php?q=<?php echo $row_searchTerms['keyword']; ?>" class="btn btn-white btn-cons" style="margin:2px;"><?php echo $row_searchTerms['keyword']; ?></a>
<?php } while ($row_searchTerms = mysqli_fetch_assoc($searchTerms)); ?>
</div>
</fieldset>
</div>
<?php }?>
<?php 
//////End Top Searched Terms/////
/////////////////////////////////
?>
    </div>
    </div>

</div>
</div> 

<?php include("inc.footer.php"); ?>   
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