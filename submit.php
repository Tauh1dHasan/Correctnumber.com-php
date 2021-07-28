<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
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


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {

$verif_box = $_POST["verif_box"];

$file = $_POST['q'];
ini_set('user_agent', 'Mozilla/40.0');
$file_headers = @get_headers($file);
$pos200 = strpos($file_headers[0], "200");
$pos301 = strpos($file_headers[0], "301");
$pos302 = strpos($file_headers[0], "302");

if($pos200>0 || $pos301>0 || $pos302>0) {
    $exists = "false";
}else {
    $exists = "true";
}

if ($exists== "true") {
header("Location: submit.php?validate=false");
exit;
}

if(md5($verif_box).'a4xn' == $_COOKIE['tntcon']){
  
$CURL= mysqli_real_escape_string($saha,$_POST["q"]);

$urlBase=parse_url($CURL,PHP_URL_HOST);
  
mysqli_select_db($saha, $database_saha);
$query_selectedCrawl = "SELECT * FROM settings WHERE settings.actual_url= '$CURL'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

if($totalRows_selectedCrawl==0 && $urlBase!=""  && $CURL!=""){
	    $insertSQL = sprintf("INSERT INTO settings (user, time, spider_mode, actual_url, base_url) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString("WebUser", "text"),
                       GetSQLValueString(time(), "int"),
                       GetSQLValueString("N", "text"),
                       GetSQLValueString($CURL, "text"),
                       GetSQLValueString($urlBase, "text"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
	
	$sesubmit="N";
	if($_POST['sesubmit']=="Y"){
	$sesubmit="Y";
	}
	    $insertSQL = sprintf("INSERT INTO business_list (sesubmit, address, email, mobile_number, phone_number, land_mark, city, state, country, category, keywords, title, description, contact_person, company_name, base_url) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($sesubmit, "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['mobile_number'], "text"),
                       GetSQLValueString($_POST['phone_number'], "text"),
                       GetSQLValueString($_POST['land_mark'], "text"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['country'], "text"),
                       GetSQLValueString($_POST['category'], "text"),
                       GetSQLValueString($_POST['keywords'], "text"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['contact_person'], "text"),
                       GetSQLValueString($_POST['company_name'], "text"),
                       GetSQLValueString($urlBase, "text"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));

	
header("Location: submit.php?submit=true&url="); //
exit;
}else{
header("Location: submit.php?submit=exists&url=".urlencode($_POST["q"]));
}
}else{
	
header("Location: submit.php?submit=verify&url=".urlencode($_POST["q"]));
exit;
}


}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
      <title>Easy Web Search</title>
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
<!-- Ajax -->
<script type="text/javascript" src="js/ajax.php?funame=f1"></script>
<div style="display:none;" id="auto">
                <div class="text-center">
Processing...
                </div>
</div>
<!-- //Ajax -->   
	
<div id="wrapper">
<div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12" style="padding-top:0px; height:45px; overflow:hidden; border-bottom:1px solid #CCC;">
                  <a href="index.php" class="btn btn-sm btn-link pull-left"><img src="logo2.png" /></a>
                  
                  <a href="admin/" target="_blank" class="btn btn-sm btn-link pull-right"><i class="fa  fa-user-secret"></i> Admin</a>
                  <a href="all.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Latest Crawling</a>
                  <a href="search.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Full Page Search</a>
                  <a href="quicksearch.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Page top Search</a>
                  <a href="submit.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-edit"></i> Submit URL</a>
                  <a href="index.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-home"></i>  Home</a>                  
                  </div>
               </div>
               
                  

<h1><img src="logo.png" width="128" height="128" alt="EWS"><br>
  Easy Web Search</h1>

<h2 style="text-align:center;">URL Submission</h2>  

<?php if(@$_GET['submit']=="true"){?>
<p class=" text-primary" style="text-align:center;">URL Submitted Successfully !</p>
<?php }else if(@$_GET['submit']=="exists"){?>
<p class=" text-info" style="text-align:center;">URL Already Submitted !</p>
<?php }else if(@$_GET['submit']=="verify"){?>
<p class="text-warning" style="text-align:center;">Human Verification  Error !</p>
<?php }?>

  
<div class="container">
    <div class="row row-centered">
        <div class="col-xs-6 col-centered col-min">
        <div class="item">&nbsp;
        <div class="content">
        <div>
        <form  method="post" action="<?php echo $editFormAction; ?>">
        <div class="input-group full-width">
        
        <fieldset>
            <!-- edited by tauhid -->
			<div class="form-group row">
                <label for="title" class="col-md-3 col-form-label text-md-right">Web URL</label>
                <div class="col-md-9">
                    <input type="text" placeholder="http://www" name="q" id="q" class="form-control" value="<?php echo urldecode(@$_GET["url"]); ?>" pattern="https?://.+" required autofocus>
                </div>
             </div>
		
<div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                       
                            <div class="form-group row">
                                <label for="title" class="col-md-3 col-form-label text-md-right">Title</label>
                                <div class="col-md-9">
                                    <input type="text" id="title" class="form-control" name="title" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="title" class="col-md-3 col-form-label text-md-right">Description</label>
                                <div class="col-md-9">
                                    <textarea id="description" class="form-control" name="description" required></textarea>
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="title" class="col-md-3 col-form-label text-md-right">Contact Person</label>
                                <div class="col-md-9">
                                    <input type="text" id="contact_person" class="form-control" name="contact_person" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="company_name" class="col-md-3 col-form-label text-md-right">Company Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="company_name" class="form-control" name="company_name" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="address" class="col-md-3 col-form-label text-md-right">Address</label>
                                <div class="col-md-9">
                                    <textarea id="address" class="form-control" name="address" required></textarea>
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                                <div class="col-md-9">
                                    <input type="email" id="email" class="form-control" name="email" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="mobile_number" class="col-md-3 col-form-label text-md-right">Mobile</label>
                                <div class="col-md-9">
                                    <input type="text" id="mobile_number" class="form-control" name="mobile_number" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="phone_number" class="col-md-3 col-form-label text-md-right">Phone</label>
                                <div class="col-md-9">
                                    <input type="text" id="phone_number" class="form-control" name="phone_number" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="land_mark" class="col-md-3 col-form-label text-md-right">Land Mark</label>
                                <div class="col-md-9">
                                    <input type="text" id="land_mark" class="form-control" name="land_mark" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="city" class="col-md-3 col-form-label text-md-right">City</label>
                                <div class="col-md-9">
                                    <input type="text" id="city" class="form-control" name="city" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="state" class="col-md-3 col-form-label text-md-right">State</label>
                                <div class="col-md-9">
                                    <input type="text" id="state" class="form-control" name="state" required >
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="country" class="col-md-3 col-form-label text-md-right">Country</label>
                                <div class="col-md-9">
                                    <input type="text" id="country" class="form-control" name="country" required >
                                </div>
                            </div>
                       <!-- edited by tauhid -->
                            <div class="form-group row">
                                <label for="category" class="col-md-3 col-form-label text-md-right">Category</label>
                                <div class="col-md-9">
									<textarea type="text" id="category" class="form-control" name="category" required > </textarea>
								</div>
                            </div>
                       <!-- edited by tauhid -->
                            <div class="form-group row">
                                <label for="keywords" class="col-md-3 col-form-label text-md-right">Keywords</label>
                                <div class="col-md-9">
                                    <textarea type="text" id="keywords" class="form-control" name="keywords" required > </textarea>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="sesubmit" class="col-md-3 col-form-label text-md-right"> Agree
</label>
                                <div class="col-md-9">
                                    <input name="sesubmit" type="checkbox" class="form-control" id="sesubmit" value="Y" ><br>
As This is a submission To Various Search Engines, Hence we will be sharing your total details with all 3rd parties, Please agree to this to proceed.
                                </div>
                            </div>
						<div class="form-group row">
                                <label for="agree" class="col-md-3 col-form-label text-md-right"> 
</label>
                                <div class="col-md-9">
                                    <input type="checkbox" id="agree" class="form-control" name="agree" required ><br>
Agree to pay Rs.600 INR Or 10 USD Per Month As Admin Charges

                                </div>
                               
                            </div>

					</div>
                    
                </div>
            </div>
        </div>
    </div>
    		
        
			</fieldset>
        <label>Verification:
        <img src="verificationimage.php?<?php echo rand(0,9999);?>" alt="verification image, type it in the box" align="absbottom" /><br>
        <input type="text" placeholder="Code" name="verif_box" id="verif_box" class="form-control" value="" required>
        </label>
        </div>
        <div class="input-group full-width">
        <button class="btn btn-primary" type="submit">Submit URL</button>
        </div>
        <input type="hidden" name="MM_insert" value="form">
        </form>
      </div>
        </div>
        </div>
        </div>
    </div>
</div>

    <div class="row row-centered">
    <div class="col-xs-6 col-centered col-min circle-border">
<div class="panel panel-default" style="border:none; margin-top:50px;">
<fieldset>
<legend style="text-align:center;">Help</legend>
<div class="panel-body text-center">
All the URLs submitted from this form will be crawled in next crawling date. Untill then your URL will not be cralwed and so will not display in search results. Also please note that Wrong or unreachable URLs will not be crawled and so they also will not be displayed in search resutls in search engine.
</div>
</fieldset>
</div>
    </div>
    </div>

</div>
</div>      
<?php include("inc.footer.php"); ?>   
</body>
</html>