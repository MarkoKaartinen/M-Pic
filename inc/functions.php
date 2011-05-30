<?php 
include("config.php");

function connect(){
	global $db_host;
	global $db_user; 
	global $db_pass; 
	global $db_db;
	$connection = mysql_connect($db_host, $db_user, $db_pass) or die("No connection to database");
	mysql_select_db($db_db, $connection);
	return $connection;
}

function disconnect($connection){
	mysql_close($connection) or die("ERROR: Closing connection");
}

function pass($phrase){
	$pass = hash("sha512",$phrase);
	return $pass;
}

function haeValue($asetus){
	$sql = "SELECT * FROM settings WHERE setting = '$asetus'";
	$res = mysql_query($sql);
	while($rivi = mysql_fetch_object($res)){
		return $rivi->value;
	}
}

function loggaaja($user, $pass){
	$pass = pass($pass);
	if(haeValue("set_username") == $user && haeValue("set_pass") == $pass){
		loggaaSisaan($pass);
		return true;
	}else{
		return false;
	}
}

function tarkistaLogin(){
	if($_SESSION['mpic_user_hash'] == ""){
		return false;
	}else{
		return true;
	}
}

function loggaaSisaan($pass){
	$_SESSION['mpic_user_hash'] = $pass;
}

function loggaaUlos(){
	unset($_SESSION['mpic_user_hash']);
	if(tarkistaLogin()){
		return false;
	}else{
		return true;
	}
}

function ohjaa($url, $timeout){
	$timeout = $timeout * 1000;
	echo '<script type="text/javascript">setTimeout(function(){ window.location="'.$url.'"; }, '.$timeout.');</script>';
}

function addFolder($nimi, $slug, $parent){
	$sql = "INSERT INTO folders(f_nimi, f_slug, f_parent) VALUES('$nimi', '$slug', '$parent')";
	$res = mysql_query($sql);
	$last_id =  mysql_insert_id();
	$polku = getFolderPath($last_id);
	$dir = mkdir("images".$polku);
	if($res && $dir){
		return true;
	}else{
		return false;
	}
}

function folderManagementList(){
	$sql = "SELECT * FROM folders WHERE f_parent = 0";
	$res = mysql_query($sql);
	echo "<ul>";
	while($rivi = mysql_fetch_object($res)){
		echo '<li>'.$rivi->f_nimi.' [<a href="index.php?p=admin&amp;do=folders&amp;task=del&amp;f='.$rivi->f_id.'">x</a>]';
		subfolderManagementList($rivi->f_id);
		echo '</li>';
	}
	echo "</ul>";
}

function subfolderManagementList($id){
	$sql = "SELECT * FROM folders WHERE f_parent = $id";
	$res = mysql_query($sql);
	echo "<ul>";
	while($rivi = mysql_fetch_object($res)){
		echo '<li>'.$rivi->f_nimi.' [<a href="index.php?p=admin&amp;do=folders&amp;task=del&amp;f='.$rivi->f_id.'">x</a>]';
		if(mysql_num_rows(mysql_query("SELECT * FROM folders WHERE f_parent = ".$rivi->f_id)) > 0){
			subfolderManagementList($rivi->f_id);
		}
		echo '</li>';
	}
	echo "</ul>";
}

function listFolders(){
	$sql = "SELECT * FROM folders WHERE f_parent = 0";
	$res = mysql_query($sql);
	while($rivi = mysql_fetch_object($res)){
		echo '<option value="'.$rivi->f_id.'">'.$rivi->f_nimi.'</option>';
		listSubFolders($rivi->f_id, 1);
	}
}

function listSubFolders($id, $taso){
	$sql = "SELECT * FROM folders WHERE f_parent = $id";
	$res = mysql_query($sql);
	$valit = "";
	for($i = 0; $i < $taso; $i++){
		$valit .= "&nbsp;&nbsp;";
	}
	while($rivi = mysql_fetch_object($res)){
		echo '<option value="'.$rivi->f_id.'">'.$valit.$rivi->f_nimi.'</option>';
		if(mysql_num_rows(mysql_query("SELECT * FROM folders WHERE f_parent = ".$rivi->f_id)) > 0){
			listSubFolders($rivi->f_id, $taso+1);
		}
	}
}

function getFolderSlug($id){
	$sql = "SELECT * FROM folders WHERE f_id = $id";
	$res = mysql_query($sql);
	while($rivi = mysql_fetch_object($res)){
		return $rivi->f_slug;
	}
}

function getTopFolderSlug($id){
	$sql = "SELECT * FROM folders WHERE f_id = $id";
	$res = mysql_query($sql);
	while($rivi = mysql_fetch_object($res)){
		$tuloste .= $rivi->f_slug;
		if($rivi->f_parent != 0){
			$tuloste .= "/".getTopFolderSlug($rivi->f_parent);
		}
	}
	return $tuloste;
}

function getFolderPath($id){
	$tuloste = "";
	$sql = "SELECT * FROM folders WHERE f_id = $id";
	$res = mysql_query($sql);
	while($rivi = mysql_fetch_object($res)){
		if($rivi->f_parent != 0){
			$tuloste = "/".getTopFolderSlug($rivi->f_parent);
		}
		$tuloste = $tuloste."/".$rivi->f_slug."/";
	}
	return $tuloste;
}

function generateSlug($str){
    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}

function BBCode($teksti){
	$teksti = trim(stripslashes($teksti));

	$teksti = nl2br($teksti);


	$code_search = array(
		'/\[b\](.*?)\[\/b\]/is',
	    '/\[i\](.*?)\[\/i\]/is',
	    '/\[image\](.*?)\[\/image\]/is',
	    '/\[url\](.*?)\[\/url\]/is',
	    '/\[url\=(.*?)\](.*?)\[\/url\]/is',
	);

	$code_replace = array( 
	    '<strong>$1</strong>',
	    '<em>$1</em>',
	    'kuva_$1',
	    '<a href="$1" target="_blank">$1</a>',
	    '<a href="$1" target="_blank">$2</a>',
	);

	$teksti = preg_replace ($code_search, $code_replace, $teksti);
	$osat = explode("_", $teksti);
	if($osat[0] == "kuva"){
		return getPicCode($osat[1]);
	}else {
		return $teksti;
	}
}

function getPicCode($id){
	return "Kuva: $id";
}
?>