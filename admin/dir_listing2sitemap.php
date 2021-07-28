<?php 
mb_http_input("utf-8");
mb_http_output("utf-8");
?>
<?php 
ini_set('error_reporting', E_ALL);

//error_reporting(E_ERROR | E_PARSE);
error_reporting(0);

///HTTP URL
$HTTPUrl="http://codecanyon.nelliwinne.net/EasyGallery/"; //Change HTTP path to your directory
?>
<?php 
$CurrDir="jquery-linedtextarea/";
$fileList= scandir($CurrDir);  //Change your director to this tring
$CFileList="";
foreach ($fileList as $key) {
$ActivecurFile=$key;	
if($key!="."  && $key!=".." && $key!=""){
if($CFileList!=""){
$CFileList.=",".$HTTPUrl.$key;	
}else{
$CFileList.=$HTTPUrl.$key;
}
}
}

$ArrayList=explode(",",$CFileList);

header('Content-Type: application/xml; charset=utf-8');
?>
<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php foreach($ArrayList as $pageURL){?>     	
  <url>
    <loc><?php echo $pageURL; ?></loc>
    <changefreq>weekly</changefreq>
  </url>
<?php }?>	
</urlset>
