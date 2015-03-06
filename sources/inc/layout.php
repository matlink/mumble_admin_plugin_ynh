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
// Start des Ausgabeinhaltss
//************************************************************************************************//
//************************************************************************************************//
// **START** Lädt die Layouts (Login, Menu, Content-Header und Navigation)
//************************************************************************************************//
include("../inc/resources/php/login.php");
include("../inc/resources/php/menu.php");
include("../inc/resources/php/c-header.php");
include("../inc/resources/php/navi.php");
//************************************************************************************************//
// **ENDE**  Lädt die Layouts (Login, Menu, Content-Menu und Navigation)
//************************************************************************************************//

// Abfrage als wer man eingeloggt ist! Für den Footer!
//Standart ausgabe
if(isset($_COOKIE['user_id'])&& isset($_COOKIE['pw'])) { 
	if (isset($_MAPUSER['teamtag'])) {
		$logged = _logged_in_as.$_MAPUSER['teamtag'];
		} else {
			$logged = _not_logged_in;
	}
	} else {
		$logged = _not_logged_in;
}

//Check ob das "../install/-Verzeichnis gelöscht wurde!
if (file_exists('../install/') AND $development != "1") {
    echo "<div align=\"center\"><div class='commerial'>"._info_install_dirctory_exists."</div></div>";
}

//Setze CV Auth
if (!isset($mklock)) {$mklock = FALSE;}
if ($mklock == "TRUE") {
	mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '0' WHERE id = '4'");
	echo "Auth successfully!";
	} elseif ($mklock == "Authed") {
		mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '1' WHERE id = '4'");
		echo "Auth undo successfully!";
}
            
//Prüfe ob System auf Linux läuft
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '20'");
$get = mysql_fetch_array($qry);
if ($get['value_2'] == "linux") {
    //Check ob die CHMOD -Rechte für "../inc/_mysql.php" stimmen
    $savepath = substr(decoct(fileperms('../inc/_mysql.php')), 2);
    if ((octdec($savepath) & 0667) >= 0667 AND $development == "0") {
        echo "<div align=\"center\"><div class='commerial'>"._info_inc_mysqlphp_chmod_wrong."</div></div>";
    }
}
                                      
//Berechnet die Parse Zeit des Scripts und gibt in Footer aus.
$php_parse_time_finish = time()+(double)microtime();
$php_parse_time = round($php_parse_time_finish-$php_parse_time_start,5);

// Footer
// Lädt HTML-Datei
$_Footer = show("footer/footer", array("copyrigth" => $copyrigth,
                                       "logged" => $logged,
                                       "akt_date" => $aktdate_detail,
                                       "version_num" => $version_num,
						  			   "parse_time" => $php_parse_time,
                                       "version_date" => $version_date
                                       ));

// Header
// Lädt HTML-Datei
$header = show("header/header", array());

// Anzeige des gesammten Templates
if (!isset($index)) {$index = FALSE;}
if (!isset($info)) {$info = FALSE;}
if (!isset($perm_error)) {$perm_error = FALSE;}
if ($comuse == FALSE AND $authed['f1'] == TRUE) {
    echo show("/index", array("seitentitel" => $seitentitel,
                              "map_version" => $version_num,
                              "map_date" => $version_date,
    						  "language_code" => $language_code,
    						  "javascript" => _error_javascript_not_available,
                              "header" => $header,
                              "main_navi" => getMenu(),
                              "login" => $_Login,
                              "request_forgot" => $_RequestForgot,
    					      "content_head" => $_ContentHead,
    						  "content_navi" => $_ContentNavi,
                              "info" => $info,
    						  "perm_error" => $perm_error,
    						  "slice" => getSliceErrors(),
                              "index" => $index,
                              "footer" => $_Footer
                              ));
    } elseif ($comuse == TRUE AND $authed['f1'] == FALSE) {
        echo $tpl_output_comuse;
}
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>