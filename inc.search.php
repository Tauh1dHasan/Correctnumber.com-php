<?php require_once('inc-main.php'); ?>
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

//Blocking too long queries
if(strlen(@$_GET['q'])>50){
	header("Location:".$_SERVER['HTTP_REFERER']);
	exit;
}

//Search
if((@$_GET['q'])!=""){
    // Tauhid
        $keyword = $_GET['q'];
        
        
        //duplicate checker
        /*
        $dsql =mysqli_query($saha, "SELECT * FROM search_word WHERE search_word = '$keyword'");
        $dcount = mysqli_num_rows($dsql);
        
        if($dcount < 1){
            $tsql = "INSERT INTO search_word (search_word) VALUES ('$keyword')";
            $run_tsql = mysqli_query($saha, $tsql);
        }
        */
        
        $tsql = "INSERT INTO search_word (search_word) VALUES ('$keyword')";
        $run_tsql = mysqli_query($saha, $tsql);
        
        
    // Tauhid
    
    // poping the after search popup box


mysqli_select_db($saha, $database_saha);
$query_Crawlsettings = "SELECT * FROM crawl_settings";
$Crawlsettings = mysqli_query($saha, $query_Crawlsettings) or die(mysqli_error($saha));
$row_Crawlsettings = mysqli_fetch_assoc($Crawlsettings);
$totalRows_Crawlsettings = mysqli_num_rows($Crawlsettings);

$lim=$row_Crawlsettings['search_results_per_page'];

if(@$_GET['stt']==""){
	$stt=0;
}else{
	$stt=@$_GET['stt'];
}

if(isset($_GET['stt']) && is_numeric(@$_GET['stt'])!=1){
header("Location: index.php");
exit;
}
	
$cid=mysqli_real_escape_string($saha,$_GET['q']);
$cids=mysqli_real_escape_string($saha,$_GET['q']);
$stt=mysqli_real_escape_string($saha,$stt);

//	if(strstr($cid," ",true)!=""){
//$resultQuerry=explode(" ",$cid);
//	}else{
//$resultQuerry=array($cid);		
//	}

$resultQuerryImageResults="";
$resultQuerryResults="";

//foreach($resultQuerry as $cids){

if($row_Crawlsettings['crawl_images_image_url']=="Y"){	
$resultQuerryImageResults.="crawl_images.image_url LIKE '%$cids%' OR ";
}
if($row_Crawlsettings['crawl_images_current_url']=="Y"){	
$resultQuerryImageResults.="crawl_images.current_url LIKE '%$cids%'  OR ";
}
if($row_Crawlsettings['crawl_images_keywords']=="Y"){	
$resultQuerryImageResults.="crawl_images.keywords LIKE '%$cids%' OR ";
}
if($row_Crawlsettings['crawl_title']=="Y"){	
$resultQuerryResults.="crawl.title LIKE '%$cids%' OR ";
}
if($row_Crawlsettings['crawl_description']=="Y"){	
$resultQuerryResults.="crawl.description LIKE '%$cids%'  OR ";
}
if($row_Crawlsettings['crawl_current_url']=="Y"){	
$resultQuerryResults.="crawl.current_url LIKE '%$cids%'  OR ";
}
if($row_Crawlsettings['crawl_keywords']=="Y"){	
$resultQuerryResults.="crawl.keywords LIKE '%$cids%'  OR ";
}
if($row_Crawlsettings['crawl_content']=="Y"){	
$resultQuerryResults.="crawl.content LIKE '%$cids%' OR ";
}
//}


if($resultQuerryImageResults!=""){
$resultQuerryImageResults=substr($resultQuerryImageResults,0,-4);
	
mysqli_select_db($saha, $database_saha);
$query_imagesFound = "SELECT * FROM crawl_images WHERE crawl_images.deleted='N' AND  ($resultQuerryImageResults) LIMIT 5";
$imagesFound = mysqli_query($saha, $query_imagesFound) or die(mysqli_error($saha));
$row_imagesFound = mysqli_fetch_assoc($imagesFound);
$totalRows_imagesFound = mysqli_num_rows($imagesFound);

mysqli_select_db($saha, $database_saha);
$query_imagesFoundTotal = "SELECT * FROM crawl_images WHERE crawl_images.deleted='N' AND  ($resultQuerryImageResults)";
$imagesFoundTotal = mysqli_query($saha, $query_imagesFoundTotal) or die(mysqli_error($saha));
$row_imagesFoundTotal = mysqli_fetch_assoc($imagesFoundTotal);
$totalRows_imagesFoundTotal = mysqli_num_rows($imagesFoundTotal);
}


if($resultQuerryResults!=""){
$resultQuerryResults=substr($resultQuerryResults,0,-4);

mysqli_select_db($saha, $database_saha);
$query_total = "SELECT * FROM crawl WHERE crawl.deleted='N' AND ($resultQuerryResults)";
$total = mysqli_query($saha, $query_total) or die(mysqli_error($saha));
$row_total = mysqli_fetch_assoc($total);
$totalRows_total = mysqli_num_rows($total);

mysqli_select_db($saha, $database_saha);
$query_searchResults = "SELECT * FROM crawl WHERE crawl.deleted='N' AND ($resultQuerryResults) LIMIT $stt,$lim";
$searchResults = mysqli_query($saha, $query_searchResults) or die(mysqli_error($saha));
$row_searchResults = mysqli_fetch_assoc($searchResults);
$totalRows_searchResults = mysqli_num_rows($searchResults);

}
//Writing Log

if(@$_GET['stt']==""){

$keyword=strtolower($cid);

mysqli_select_db($saha, $database_saha);
$query_selectedCrawl1 = "SELECT * FROM log WHERE log.keyword='$keyword'";
$selectedCrawl1 = mysqli_query($saha, $query_selectedCrawl1) or die(mysqli_error($saha));
$row_selectedCrawl1 = mysqli_fetch_assoc($selectedCrawl1);
$totalRows_selectedCrawl1 = mysqli_num_rows($selectedCrawl1);

if(@$totalRows_total>0){	
	    $insertSQL = sprintf("INSERT INTO log (count, ip, results, date, keyword, time) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($totalRows_selectedCrawl1+1, "int"),
                       GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"),
                       GetSQLValueString($totalRows_total, "int"),
                       GetSQLValueString(date("Y-m-d"), "date"),
                       GetSQLValueString(strtolower($cid), "text"),
                       GetSQLValueString(date("H:i:s"), "date"));

  mysqli_select_db($saha, $database_saha);
  $Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
}
}
//End Writing Log

}

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);

function searchedSample($database_saha, $saha,$searcTerm,$queryID){

mb_http_input("utf-8");
mb_http_output("utf-8");

$searcTerm=mysqli_real_escape_string($saha,$searcTerm);
$queryID=mysqli_real_escape_string($saha,$queryID);

mysqli_select_db($saha, $database_saha);
$query_searchResultsSample = "SELECT * FROM crawl WHERE crawl.id='$queryID' AND crawl.deleted='N' AND (crawl.title LIKE '%$searcTerm%' OR crawl.description LIKE '%$searcTerm%'  OR crawl.current_url LIKE '%$searcTerm%'  OR crawl.keywords LIKE '%$searcTerm%'  OR crawl.content LIKE '%$searcTerm%')";
$searchResultsSample = mysqli_query($saha, $query_searchResultsSample) or die(mysqli_error($saha));
$row_searchResultsSample = mysqli_fetch_assoc($searchResultsSample);
$totalRows_searchResultsSample = mysqli_num_rows($searchResultsSample);

$description=NULL;
$descriptionArray=explode(" ",$row_searchResultsSample['description']);
$fileCount=0;
if(is_array($descriptionArray)){
	foreach ($descriptionArray as $key) { 
	$fileCount++;
		if($fileCount<32){
			
			$LowerStr=strtoupper($key);
			$LowersearcTerm=strtoupper($searcTerm);
			if(strpos($LowerStr,$LowersearcTerm)!== false){
				$keyWard="<strong style=\" text-decoration:underline;\">$key</strong>";
			}else{
				$keyWard=$key;
			}
		$description.=$keyWard." ";
		}
	}
}

$content=NULL;
$contentArray=explode(" ",$row_searchResultsSample['content']);
$fileCount=0;
if(is_array($contentArray)){
	foreach ($contentArray as $key) { 
	$fileCount++;
		if($fileCount<32){
			
			$LowerStr=strtoupper($key);
			$LowersearcTerm=strtoupper($searcTerm);
			if(strpos($LowerStr,$LowersearcTerm)!== false){
				$keyWard="<strong style=\" text-decoration:underline;\">$key</strong>";
			}else{
				$keyWard=$key;
			}
		$content.=$keyWard." ";
		}
	}
}

$keywords=NULL;
$keywordsArray=explode(" ",$row_searchResultsSample['keywords']);
$fileCount=0;
if(is_array($keywordsArray)){
	foreach ($keywordsArray as $key) { 
	$fileCount++;
		if($fileCount<32){
			$LowerStr=strtoupper($key);
			$LowersearcTerm=strtoupper($searcTerm);
			if(strpos($LowerStr,$LowersearcTerm)!== false){
				$keyWard="<strong style=\" text-decoration:underline;\">$key</strong>";
			}else{
				$keyWard=$key;
			}
		$keywords.=$keyWard." ";
		}
	}
}
return $description."..".$content."..".$keywords; //."..".substr($row_searchResultsSample['content'],0,100)."..".substr($row_searchResultsSample['keywords'],0,100);
}
?>