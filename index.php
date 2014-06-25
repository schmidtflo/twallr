<?php 
session_start();
$_SESSION['id']=0;
$_SESSION['erster']=true;
require_once ('config.php');
require_once ('script/codebird.php');
\Codebird\Codebird::setConsumerKey($key, $key_secret); 
$cb = \Codebird\Codebird::getInstance();


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
   
    <?php
    if($reload!=0){echo "<meta http-equiv=\"refresh\" content=\"$reload\">";};
	?>
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    
    <?php
	if (isset($_SESSION['oauth_token'])) {?>
    <script>
    function ladetweets(e) {
		$('.fa-refresh').addClass("fa-spin");
		$.ajax({
			url: 'script/script.php',
			type: 'GET',
			success: function (result) {
				$('#content').prepend(result);
				$('.neu').hide().show("blind", 1000, function() {
  					$('.neu').removeClass("neu");
				});
				$('.panel:nth-child(n+40)').remove();
				$('.fa-refresh').removeClass("fa-spin");
			}
		});
		
	}    
	
	$(function(){
    		
			ladetweets();
    		var int = setInterval("ladetweets()", <? echo $refresh;?>);
			
    });
    </script><? } ?>
    <meta charset="utf-8" />
</head>
<body>
	<nav class="navbar navbar-default" role="navigation">
  		<div class="container">
        	<p class="navbar-text" ><?php if (isset($_SESSION['oauth_token'])) {echo $display;} else {echo "twallr";} ?></>
            <p class="navbar-text pull-right"><?php if (isset($_SESSION['oauth_token'])) echo '<span id="reload"><i class="fa fa-refresh"></i> | </span>';?>by <a class="navbar-link" href="http://netzleben.com">Florian Schmidt</a></p>
        </div>
    </nav>
    <div class="container" id="content">
    <?php
	if (!isset($_SESSION['oauth_token'])) {
		?><div class="well col-md-6 col-md-offset-3">Du bist noch nicht eingeloggt.<a class="pull-right" href="login.php"><img src="https://dev.twitter.com/sites/default/files/images_documentation/sign-in-with-twitter-gray.png"></a></div>
		
	<?}
	?>
    </div>
    
	
</body>
</html>