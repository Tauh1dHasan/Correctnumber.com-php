<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php require_once('inc.search.php'); ?>
<?php header ("Content-Type:text/xml"); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; ?>
<urlset>
<?php if(@$totalRows_searchResults>0){?>                           
<?php do{ ?>                           

<url>
  <loc>http://<?php echo $_SERVER['HTTP_HOST'];?>/go.php?id=<?php echo $row_searchResults['id']; ?></loc>
</url>
<?php } while ($row_searchResults = mysqli_fetch_assoc($searchResults)); ?>
<?php }?>
</urlset>