<?php
session_regenerate_id(true);
session_start(); 
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE);
include("inc/functions.php");
$page = $_GET['p'];
?>
<!DOCTYPE html>
<html lang="fi">
<head>
	<meta charset="utf-8">
	<title>M-Pic</title>
	<meta name="description" content="" />
  	<meta name="keywords" content="" />
	<meta name="robots" content="" />
	<script type="text/javascript" src="./js/jquery.min.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="./js/javascript.js"></script>
	<link rel="stylesheet" href="./fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
</head>
<body>
<?php
$con = connect();
?>
<div id="page">
	<div id="header">
		<div class="head">
			<div class="txt">M-Pic</div>
			<div class="by">by Marko Kaartinen</div>
		</div>
		<ul id="navi">
			<li><a href="index.php">Home</a></li>
			<li><a href="">About</a></li>
			<li><a href="">Contact</a></li>
			<?php
			if(tarkistaLogin()){
				echo "<li><a href=\"index.php?p=admin\">Admin</a></li>";
				echo "<li><a href=\"index.php?p=login&amp;do=out\">Log out</a></li>";
			}else{
				echo "<li><a href=\"index.php?p=login\">Log in</a></li>";
			}
			?>
		</ul>
	</div>
	<div class="clear"></div>

	<div id="content">
		<?php
		if($page == ""){
			include("m-front.php");
		}elseif($page == "browse"){
			include("m-browse.php");
		}elseif($page == "login"){
			include("m-login.php");
		}elseif($page == "admin"){
			include("m-admin.php");
		}else{
			include("m-404.php");
		}
		?>
	</div>

	<div class="clear"></div>
	<div id="footer">
		&copy; Marko Kaartinen - Powered by: <a href="http://m-pic.info">M-Pic</a> 
	</div>
</div>
<?php
disconnect($con);
?>
</body>
</html>