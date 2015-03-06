<?php
//******************************************************************//
// |     MAP - Mumb1e Admin Plugin   |   http://www.mumb1e.de/    | //    
// |              ___      ___   _________   _________            | //
// |             |   \    /   | |   ___   | |   ___   |           | //
// |             |    \__/    | |  |   |  | |  |   |  |           | //
// |             |  |\    /|  | |  |___|  | |  |___|  |           | //
// |             |  | \__/ |  | |   ___   | |   ______|           | //
// |             |  |      |  | |  |   |  | |  |                  | //
// |             |__|      |__| |__|   |__| |__|                  | //
// |                                                              | //
// | --- VERSION ---                                              | //
// | Build: 6194                                                  | //
// | Version: V2.5.2                                              | //
// | Date: 2013-02-24                                             | //
// | --- COPYRIGHT ---                                            | //
// | Build by P.M. and M.H. | Accept the Copyrights!              | //
// | © by Michael Koch 'aka' P.M. <pm@mumb1e.de>                  | //
// | --- LICENSE ---                                              | //
// | MAP - Mumb1e Admin Plugin is a dual-licensed software        | //
// | MAP is released for private end-users under GPLv3            | //
// |   >>  (visit at: http://www.gnu.org/licenses/gpl-3.0.html)   | //
// | MAP is released for commercial use under a commerial license | //
// |   >>  (visit at: http://www.mumb1e.de/en/about/license)      | //
// | --- ATTENTION ---                                            | //
// | Changing, editing or spreading of this sourcecode in other   | //
// | scripts, or on other websites only with permission by P.M.!  | //
//******************************************************************//
//************************************************************************************************//
// Start des Ausgabeinhalts
//************************************************************************************************//

//reCaptcha Public und Private Key´s => Globale Keys!!
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '45'");
$get = mysql_fetch_array($qry);
if ($get["value_2"] == "") {
	/*
	 * Get a key from https://www.google.com/recaptcha/admin/create
	 */
	$GoogleReCaptchaPublicKey = "6LdBYboSAAAAAHkkWRDdhzLgpH8T5a5PovQRMOIP";
	/*
	 *  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	 */
	} else {
		$GoogleReCaptchaPublicKey = $get["value_2"];
}
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '46'");
$get = mysql_fetch_array($qry);
if ($get["value_2"] == "") {
	/*
	 * Get a key from https://www.google.com/recaptcha/admin/create
	 */
	$GoogleReCaptchaPrivateKey = "6LdBYboSAAAAAB1rXuVuNA2lUOfH07xZaCY4e-QC";
	/*
	 *  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	 */
	} else {
		$GoogleReCaptchaPrivateKey = $get["value_2"];
}

// Versionsdefinition
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '1'");
$get = mysql_fetch_array($qry);
$version_num = $get["value_2"];
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '2'");
$get = mysql_fetch_array($qry);
$version_date = $get["value_2"];
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '35'");
$get = mysql_fetch_array($qry);
$version_build = $get["value_2"];

//Definition des Seitentitels
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '8'");
$get = mysql_fetch_array($qry);
if ($get["value_2"] == '1') {
    $version_show = " [".$version_num."] ";
    } elseif ($get["value_2"] == '0') {
        $version_show = " ";
}
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '3'");
$get = mysql_fetch_array($qry);
$seitentitel = "".$get["value_2"]."".$version_show."- ";

// Template Ordner Name
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '5'");
$get_default = mysql_fetch_array($qry);
if (!isset($_COOKIE['user_id'])) {$_COOKIE['user_id'] = FALSE;}
$qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE user_id = '".$_COOKIE['user_id']."' LIMIT 1");
$get_admin = mysql_fetch_array($qry);
if ($get_admin["template"] != $get_default["value_2"] AND $get_admin["template"] != "") {
	$tpldir = $get_admin["template"];
	} else {
		$tpldir = $get_default["value_2"];
}
unset($get_default);
unset($get_admin);
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '4'");
$get = mysql_fetch_array($qry);
if ($get["value_2"] == "0") {
	$comuse = TRUE;
	} elseif ($get["value_2"] == "1") {
		$comuse = FALSE;
}

// Name der Sprachdatei ohne Dateiendung (.php)
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '6'");
$get_default = mysql_fetch_array($qry);
$qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE user_id = '".$_COOKIE["user_id"]."' LIMIT 1");
$get_admin = mysql_fetch_array($qry);
if ($get_admin["language"] != $get_default["value_2"] AND $get_admin["language"] != "") {
	$lang = $get_admin["language"];
	//Für TinyMCE-Editor die Schlüssel "en" und "de" vergeben
	if ($get_admin["language"] == "english") {
		$language_code = "en";
		} else {
			$language_code = "de";
	}
	setcookie("language", $get_admin["language"], time()+$get_admin["coockie"], "/");
	} else {
		$lang = $get_default["value_2"];
		//Für TinyMCE-Editor die Schlüssel "en" und "de" vergeben
		if ($get_default["value_2"] == "english") {
			$language_code = "en";
			} else {
				$language_code = "de";
		}
		setcookie("language", $get_default["value_2"], time()+864000, "/");
}
unset($get_default);
unset($get_admin);

// Definition der Copyrigths
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '7'");
$get = mysql_fetch_array($qry);
$copyrigth = $get["value_2"];

// Definition des Slice Hosts
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '28'");
$get = mysql_fetch_array($qry);
$SliceHost = $get["value_2"];

// Definition des Slice Ports
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '29'");
$get = mysql_fetch_array($qry);
$SlicePort = $get["value_2"];

//Definition des Slice Ports
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '33'");
$get = mysql_fetch_array($qry);
$SliceSecret = array('secret' => $get["value_2"]);

// Definition Ort der Templates
$template_dir = '../inc/tpl/';

//Definition Ort der Avatare
$avatar_dir = '../inc/uploads/avatars/';

//Definition des Language Ordners
$language_dir = '../inc/_lang/';

//Angabe ob derzeit Status Entwicklung (0=Nein; 1=Ja)
$development = "0";
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>