<?php
$log = $_GET['do'];
if($log == ""){
	include("m-loginform.php");
}
if($log == "try"){
	$pass = $_POST['passwd'];
	$user = $_POST['username'];
	if(loggaaja($user, $pass)){
		echo 'You\'ll be redirected in about 5 secs. If not, click <a href="index.php?p=admin">here</a>.';
		ohjaa("index.php?p=admin", 5);
	}else{
		include("m-loginform.php");
		echo "<p><br /></p>";
		echo "<p><strong>Fail!</strong></p>";
	}
}
if($log == "out"){
	if(loggaaUlos()){
		echo "Logged out!<br />";
		echo 'You\'ll be redirected in about 5 secs. If not, click <a href="index.php">here</a>.';
		ohjaa("index.php", 5);

	}else{
		echo "Failure to log out!";
	}
}
?>