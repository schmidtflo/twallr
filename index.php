<?php 
session_start();
$_SESSION['id']=0;
$_SESSION['erster']=true;
require_once ('config.php');
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
     <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
   
    <?php
    if($reload!=0){echo'<meta http-equiv="refresh" content="$reload">';};
	?>
    <script src="js/jquery-2.0.3.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
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
				$('#content').remove('.panel:nth-child(n+20)');
				$('.fa-refresh').removeClass("fa-spin");
			}
		});
		
	}    
	
	$(function(){
    		
			ladetweets();
    		var int = setInterval("ladetweets()", <?php echo $refresh ?>);
			
    });
    </script>
    <meta charset="utf-8" />
</head>
<body>
	<nav class="navbar navbar-default" role="navigation">
  		<div class="container">
        	<p class="navbar-text" ><?php echo $display ?></>
            <p class="navbar-text pull-right"><span id="reload"><i class="fa fa-refresh"></i> | </span>by <a class="navbar-link" href="http://netzleben.com">Florian Schmidt</a></p>
        </div>
    </nav>
    <div class="container" id="content">
    </div>
	
</body>
</html>