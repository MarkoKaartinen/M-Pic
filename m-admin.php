<h1>M-Pic management</h1>
<div id="admin_menu"><a href="index.php?p=admin">Admin index</a> - <a href="index.php?p=admin&amp;do=folders">Folders</a> - <a href="index.php?p=admin&amp;do=settings">Settings</a></div>

<?php
$do = $_GET['do'];
if($do == "folders"){
	$task = $_GET['task'];
	echo "<div id=\"do_menu\">";
	echo '<a href="index.php?p=admin&amp;do=folders&amp;task=add">Add</a>';
	echo "</div>";
	if($task == ""){
		folderManagementList();
	}elseif($task == "add"){
		echo '<form action="index.php?p=admin&do=folders&task=addcheck" method="post">
		Name:<br />
		<input type="text" name="nimi" /><br />
		Parent folder:<br />';
		echo "<select name=\"parent\"><option value=\"0\">None</option>";
		listFolders();
		echo '</select><br />
		<br />
		<input type="submit" value="Add folder" />
		</form>';
	}elseif($task == "addcheck"){
		$nimi = $_POST['nimi'];
		$slug = generateSlug($_POST['nimi']);
		$parent = $_POST['parent'];
		if(addFolder($nimi, $slug, $parent)){
			echo "Folder added";
		}else{
			echo "FAIL!";
		}
	}
}
if($do == "settings"){
	$task = $_GET['task'];
	echo "<h2>Settings</h2>";
	if($task == ""){
		echo "<form action=\"index.php?p=admin&amp;do=settings&amp;task=save\" method=\"post\" id=\"setform\">";
		echo "<p><strong>Admin name</strong><br />
		<input type=\"text\" name=\"adminname\" /></p>";
		echo "<p><strong>Admin e-mail</strong><br />
		<input type=\"text\" name=\"adminmail\" /></p>";
		echo "<p><strong>Admin homepage</strong><br />
		<input type=\"text\" name=\"adminhomepage\" value=\"http://\" /></p>";
		echo "<p><strong>Admin username</strong><br />
		<input type=\"text\" name=\"adminusername\" /></p>";
		echo "<p><strong>Admin password</strong> <small>(if you want to change it)</small><br />
		<input type=\"password\" name=\"adminpass\" /></p>";
		echo "<p><strong>Frontpage message</strong><br />
		<textarea id=\"frontpagetxt\" name=\"frontpagetxt\"></textarea></p>";
		echo "<p><strong>About-page content</strong><br />
		<textarea id=\"aboutpagetxt\" name=\"aboutpagetxt\"></textarea></p>";
		echo "<p><strong>Admin about</strong><br />
		<textarea id=\"adminaboutpagetxt\" name=\"adminaboutpagetxt\"></textarea></p>";
		echo "<p><input type=\"submit\" value=\"Save\" />";
		echo "</form>";
	}
	if($task == "save"){
		$name = $_POST['adminname'];
		$mail = $_POST['adminmail'];
		$www = $_POST['adminhomepage'];
		$user = $_POST['adminusername'];
		if($_POST['adminpass'] != ""){
			$pass = pass($_POST['adminpass']);
		}
		$front = bbcode($_POST['frontpagetxt']);
		$about = bbcode($_POST['aboutpagetxt']);
		$admin = bbcode($_POST['adminaboutpagetxt']);
		echo "$name - $mail - $www - $user - $pass - $front - $about - $admin";
	}
}
?>