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
                  <form action="search.php" class="form-search form-horizontal pull-right" id="custom-search-form">
                <div class="input-append span12">
                    <input type="text" class="search-query mac-style" placeholder="Search">
                    <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                </div>
            </form>
                  <a href="admin/" target="_blank" class="btn btn-sm btn-link pull-right"><i class="fa  fa-user-secret"></i> Admin</a>
                  <a href="all.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Latest Crawling</a>
                  <a href="search.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Full Page Search</a>
                  <a href="quicksearch.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-search"></i> Page top Search</a>
                  <a href="submit.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-edit"></i> Submit URL</a>
                  <a href="index.php" class="btn btn-sm btn-link pull-right"><i class="fa fa-home"></i>  Home</a>                  
                  </div>
               </div>
               <div class="row">
                  <section class="mcon-otherdemos shadow-top">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3">
                            <h2>PHP code</h2>
                            <p  style="padding: 0px; color: #F00;  ">&lt;?php require_once('Connections/saha.php'); ?&gt;<br>                   &lt;?php require_once('inc-main.php'); ?&gt;<br>                   &lt;?php require_once('inc.search.php'); ?&gt;  </p>
                        </div>
                        <div class="col-sm-3">
                            <h2>HTML Header</h2>
                            <p style="padding: 0px; color: #F90; height: 150px; overflow: scroll; overflow-x: hidden; border: 1px solid #999;"> &lt;link href=&quot;css/bootstrap.min.css&quot; rel=&quot;stylesheet&quot;&gt;<br>                     &lt;link href=&quot;fonts/font-awesome/css/font-awesome.css&quot; rel=&quot;stylesheet&quot;&gt;<br>                     &lt;link href=&quot;css/animate.css&quot; rel=&quot;stylesheet&quot;&gt;<br>                     &lt;link href=&quot;css/style.css&quot; rel=&quot;stylesheet&quot;&gt; </p>
                        </div>
                        <div class="col-sm-3">
                            <h2>HTML Body</h2>
                            <p style="padding: 10px; color: #33F; height: 150px; overflow: scroll; overflow-x: hidden; border: 1px solid #999;">&lt;div class=&quot;wrapper wrapper-content animated fadeInRight&quot;&gt;                                                &lt;div class=&quot;row&quot;&gt;                   &lt;div class=&quot;col-lg-12&quot;&gt;                      &lt;div class=&quot;inqbox float-e-margins&quot;&gt;                         &lt;div class=&quot;inqbox-content&quot;&gt;                            &lt;div class=&quot;search-form&quot;&gt;                              &lt;form action=&quot;&quot; method=&quot;get&quot;&gt;                                  &lt;div class=&quot;input-group&quot;&gt;                                     &lt;input type=&quot;text&quot; placeholder=&quot;Search Web&quot; name=&quot;q&quot; class=&quot;form-control input-lg&quot; value=&quot;&lt;?php echo @$cid; ?&gt;&quot;&gt;                                     &lt;div class=&quot;input-group-btn&quot;&gt;                                        &lt;button class=&quot;btn btn-lg btn-primary&quot; type=&quot;submit&quot;&gt;                                        Search                                        &lt;/button&gt;                                     &lt;/div&gt;                                  &lt;/div&gt;                               &lt;/form&gt;                            &lt;/div&gt;...<br>
                   .... <strong style="color:#C30">Copy and Paste Entire Body from sample1.php</strong> ....  </p>
                            
                        </div>
                        <div class="col-sm-3">
                            <h2>Sample Pages</h2>
                           <p><a href="sample1.php" target="_blank" class="navy-link" role="button">Full Page Search</a></p>
                            <p><a href="sample3.php" target="_blank" class="navy-link" role="button">Full Page Search (Non Styled</a></p>
                            <p><a href="sample2.php" target="_blank" class="navy-link" role="button">Quick Search from any other Page</a></p>
                        </div>
                    </div>
                </div>
            </section>
            
                        <section class="mcon-otherdemos">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h2 style="margin-top:0;">Discover great features.</h2>
                            <hr class="small-highlight">
                            <ul>
                              <li> Responsive Search Page</li>
                              <li>Highlighting search term at search results</li>
                              <li>Crawl Unlimited Pages </li>
                              <li>Link Submit for Crawling </li>
                              <li>Links from Submited Link will be automatically grabs, </li>
                              <li>You need to give only main page or pages from your web only. Script will find links within those pages.</li>
                              <li>Those links will crawl when running crawling</li>
                              <li>Admin Area to Manage Links </li>
                              <li>Easy to Use in your web </li>
                              <li>Installation Documentatio Available </li>
                              <li>Full Page Search and Quick Search </li>
                              <li>Start Search from html Page </li>
                              <li>PHP / MySQL Based </li>
                              <li>Simple to Install</li>
                              <li>Full Documentation for Installation is available with the package.</li>
                              <li>Usage Instructions are also included in the documentation </li>
                              <li>PHP 5 and PHP 7</li>
                              <li>Sample Pages are available to easy setup</li>
                              <li>Run Crawling from Admin Area</li>
                              <li>Automatically get Title, Description, Keywards and Content (2000 Characthers max.)</li>
                              <li>Update Crawled data (Title, Description, Keywards and Content</li>
                              <li>Delete or Disable Auto Update (When Crawling) for links</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="dzsparallaxer auto-init" data-options='{  mode_scroll: "fromtop", animation_duration: "50", direction: "reverse", settings_movexaftermouse: "on"}' style="border: 1px solid #111;">
<div style="padding:10px;">                            
<h3><strong>Description</strong></h3>                                
<p>
Easy Web Search is engine to use inside a web site. Simple Crawling System is available to submit URLs and Links from submitted URL will be automatically added to search database when admin run crawling. Once crawling is done the links with their contents (Plain text from web link) will be available to search. If the total web site is linked to it's home page, you need jut give home page url to the sytem and run crawling once. You do not need to much work to implement a search system inside your web site.
</p>
</div>
                                <div class="divimage dzsparallaxer--target " style="width: 100%; height: 300px; background-image: url(inline3.jpg)"><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mcon-otherdemos">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div style=" border: 0px solid #111;">
                                <img src="inline2.jpg"  style=" width:100%; display: block;"/>
                            </div>
                            <h3 id="Upload"><strong>B) Upload and Run</strong></h3>
		
		<p>Finalizing the work.</p>
		
			<ol>
			  <li>Exat all the files at &quot;package.zip&quot; to your local folder</li>
			  <li>Upload all the files to your server</li>
			  <li>Create Database and Change Databse Connection as explianed above (C).</li>
				<li>Access your code via web browser</li>
				<li>You're done</li>
			</ol>
                        </div>
                        <div class="col-md-6 ">
                            <h2 style="margin-top:0;">How does it work.</h2>
                            <hr class="small-highlight">
                            <div class="clearboth"></div>
                            <p class="text-left">
                       	  <h3 id="ScriptStructure"><strong>A) Database Structure and Setup</strong></h3>
      <p>First Create a database in your server and import sql.sql file (at the root folder) to your database. Please  truncate settings and crawl talbes<strong>. Anyway do not delete administrator user from admin table</strong>. .</p>
        <ul>
          <li> admin</li>
          <li> settings</li>
          <li>crawl</li>
        </ul>
        <p>Now change the database connection file at &quot;Connections/saha.php&quot; as follows </p>
        <p> <code>$hostname_saha = "yourhost"; <br>
          $database_saha = "yourdbname"; <br>
          $username_saha = "yourdbusername"; <br>
          $password_saha = "yourdbpassword"; </code></p>
        <p>Please note that user password can change from the script and you need to change passwords using admin area. .</p>
        <p>&nbsp;</p>
	  <hr>
		
		
                            </p>
                        </div>
                    </div>
                </div>
            </section>

               </div>
            </div>
      </div>
     
<?php include("inc.footer.php"); ?>   
</body>
</html>