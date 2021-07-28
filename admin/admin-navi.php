<?php /*       <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link href="../fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
 */?>
<?php 
function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
?>
<li <?php if(curPageName()=="dashboard.php"){?>class="active"<?php }?>><a  href="dashboard.php"><i class="glyphicon glyphicon-signal"></i>Home</a></li>
<li <?php if(curPageName()=="keywords.php" || curPageName()=="keywords-edit.php" || curPageName()=="keywords-new.php"){?>class="active"<?php }?>><a  href="keywords.php"><i class="glyphicon glyphicon-file"></i>Keywords</a></li>

<li <?php if(curPageName()=="admin.php" || curPageName()=="admin-business.php" || curPageName()=="admin-new.php" || curPageName()=="admin-links.php"){?>class="active"<?php }?>><a  href="admin.php"><i class="glyphicon glyphicon-home"></i>Sites</a></li>



<li <?php if(curPageName()=="admin-log.php"){?>class="active"<?php }?>><a href="admin-log.php"><i class="glyphicon glyphicon-list"></i>Log</a></li>


<li <?php if(curPageName()=="admin-data.php" || curPageName()=="admin-data-update.php"){?>class="active"<?php }?>><a  href="admin-data.php"><i class="glyphicon glyphicon-text-size"></i> Data</a></li>

<li <?php if(curPageName()=="admin-images.php" || curPageName()=="admin-images-update.php"){?>class="active"<?php }?>><a  href="admin-images.php"><i class="glyphicon glyphicon-picture"></i> Images</a></li>
<?php /* 
<li <?php if(curPageName()=="admin-pdf.php" || curPageName()=="admin-pdf-update.php"){?>class="active"<?php }?>><a  href="admin-pdf.php"><i class="glyphicon glyphicon-inbox"></i> PDFs</a></li>

 */?><li <?php if(curPageName()=="admin-crawl.php"  || curPageName()=="admin-crawl-partial.php"  || curPageName()=="admin-crawl-step1.php"  || curPageName()=="admin-crawl-step2.php"  || curPageName()=="admin-crawl-step3.php"){?>class="active"<?php }?>><a  href="admin-crawl.php" ><i class="glyphicon  glyphicon-save"></i>Run Crawling</a></li>

<li class="<?php if(curPageName()=="admin-logout.php"){?>active<?php }?> pull-right"><a  href="admin-logout.php" title="Sign out"><i class="glyphicon glyphicon-log-out"></i></a></li>

<li class="<?php if(curPageName()=="admin-pass.php"){?>active<?php }?> pull-right"><a  href="admin-pass.php"><i class="glyphicon glyphicon-cog"></i>Settings</a></li>

<!--Tauhid-->
<li class="<?php if(curPageName()=="submit2_info.php"){?>active<?php }?> pull-right"><a  href="submit2_info.php"><i class="glyphicon glyphicon-pencil"></i>Submit2</a></li>
<li class="<?php if(curPageName()=="search_word.php"){?>active<?php }?> pull-right"><a  href="search_word.php"><i class="glyphicon glyphicon-file"></i>Search Word</a></li>
<li class="<?php if(curPageName()=="banner_words.php"){?>active<?php }?> pull-right"><a  href="banner_words.php"><i class="glyphicon glyphicon-pencil"></i>Banner Words</a></li>
<li class="<?php if(curPageName()=="client_request.php"){?>active<?php }?> pull-right"><a  href="client_request.php"><i class="glyphicon glyphicon-file"></i>Client Request to Seller</a></li>
<li class="<?php if(curPageName()=="client_request_you.php"){?>active<?php }?> pull-right"><a  href="client_request_you.php"><i class="glyphicon glyphicon-file"></i>Client Request to You</a></li>

