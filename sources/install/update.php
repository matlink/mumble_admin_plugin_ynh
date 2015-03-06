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
// **START**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
include('../install/inc/functions.php');		//Holt wichtige Basisfunktionen
include('../inc/_mysql.php');					//Hole MySQL Daten
include('../inc/db_connect.php');				//Verbinde zur Datenbank
//************************************************************************************************//
// **ENDE**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
//************************************************************************************************//
// Start des Ausgabeinhalts
//************************************************************************************************//

// Definiere, bzw. lade Section
if (!isset($_GET['section'])) {
    $section = "";
    } else {
        $section = $_GET['section'];
}
// Gehe zu aufgerufenem Bereich
switch ($section) {
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Update MAP
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'update':

	// Definition Seitentitel
    $seitentitel .= _pagetitel_update_do;
    
    //Generiere SQl File Array
    $SQLResult = generateDatabaseDump(array("database_prefix" => $database_prefix,
                                            "version_num" => $version_num,
                                            "version_date" => $version_date,
    										"aktdate" => $aktdate,
    										"version_build" => $version_build,
    										"copyrigth" => $copyrigth,
                                            ), "update");
            
    //Splitte install.sql in einzelne Staements auf
    $statements = explode('-- BEGINN NEW STATEMENT', $SQLResult);
    
	//Speichere Datenbankdaten in DB ab
	foreach ($statements as $statement) {
		$result = mysql_query($statement);
	}
	
    // Info an PM, das Script ohne Probleme Installiert wurde!
    $map_url = $getHTTP . $_SERVER['SERVER_NAME'] . "/" . trim($_SERVER['SCRIPT_NAME'], "install/update.php");
    $email_subject = "Das MAP ".$map_version." wurde erfolgreich auf " . $_SERVER['SERVER_NAME'] . " aktualisiert";
    $email_text = "URL zur MAP-Instanz: <b><a href=\"". $map_url . "\">" . $map_url . "</a>" . "</b><br><br>" . "Server IP: <b>" . $_SERVER['SERVER_ADDR'] . "</b><br><br>" . "HTTP Host: <b>" . $_SERVER['HTTP_HOST'] . "</b><br><br>" . "User IP: <b>" . $_SERVER['REMOTE_ADDR'] . "</b><br><br>";
    $header  = 'MIME-Version: 1.0' . "\r\n";
    $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $header .= 'From: Script MAP '.$map_version.' <installer@mumb1e.de>' . "\r\n";
    @mail('installer@mumb1e.de', $email_subject, $email_text, $header);
    
    //Support-Mail an User
    $header = FALSE;
    $email_subject = FALSE;
    $email_text = FALSE;
    $email_subject = "Thank you for using MAP $map_version";
    $email_text = "We are honored and want to say thank you very much for using MAP!<br>If you have any trouble or need help,<br> please feel free to ask our support-mailing-list at <a href=\"http://mailing-lists.mumb1e.de/support/\">http://mailing-lists.mumb1e.de/support/</a>.<br>We will help you also via E-Mail Support at <a href=\"mailto:support@mumb1e.de\">support@mumb1e.de</a> or visit our <a href=\"http://www.mumb1e.de/en/community/support\">Support-Page!</a><br>Have a lot of fun with MAP! The developers!";
    $header  = 'MIME-Version: 1.0' . "\r\n";
    $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $header .= 'From: Installer MAP <install-confirmation@mumb1e.de>' . "\r\n";
    @mail($map_user_email, $email_subject, $email_text, $header);
    
    // Ladt HTML-Datei
    $index = show("$dir/update_do", array("head" => _update_do_head,
                                           "install_1" => _update_do_finished_1,
                                           "install_2" => _update_do_finished_2,
                                           "install_3" => _update_do_finished_3,
                                           "install_4" => _update_do_finished_4, 
                                           "install_5" => _update_do_finished_5,
                                           "install_6" => _update_do_finished_6,
                                           "install_7" => _update_do_finished_7,
                                           "install_language_value" => $lang,
                                           "install_template_value" => $tpldir, 
                                           ));
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Update MAP
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Checke System
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'check':

	// Definition Seitentitel
    $seitentitel .= _pagetitel_update_check;
    $button_next_1 = FALSE;
    $button_next_2 = FALSE;
    
    //Checke Datenbankverbindung
    if (!$mysql OR !$mysql_db) {
    	$db_check = FALSE;
    	$db_message = '<font color="#FF0000"><b>' . _update_check_database_error . '</b></font>';
    	} else {
    		$db_check = TRUE;
    		$db_message = '<font color="#008000"><b>' . _update_check_ok . '</b></font>';
    }
    
    //Checke Version
	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '35'");
    $get_version = mysql_fetch_array($qry);
    if ($map_build > $get_version[2]) {
        $version_check = TRUE;
        $version_message = '<font color="#008000"><b>' . _update_check_ok . '</b></font>';
    	} else {
    		$version_check = FALSE;
    		$version_message = '<font color="#FF0000"><b>' . _update_check_version_error . '</b></font>';
    }
    
    //Checke ob weiter Button ausgegeben wird
    if ($db_check == FALSE OR $version_check == FALSE) {
    	$button_next_1 = "<!--";
    	$button_next_2 = "-->";
    }

    // Ladt HTML-Datei
    $index = show("$dir/update_check", array("discription" => _update_check_discr,
    										 "database" => _update_check_database,
    										 "version" => _update_check_version,
    										 "button_next" => _update_check_next,
                                             "button_refresh" => _update_check_refresh,
                                             "button_back" => _update_check_back,
    										 "database_check" => $db_message,
    										 "version_check" => $version_message,
    										 "button_next_1" => $button_next_1,
    										 "button_next_2" => $button_next_2,
                                             "install_language_value" => $lang,
                                             "install_template_value" => $tpldir, 
                                             ));
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Checke System
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Startseite des Updates
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_update_start;
    
    $index = show("$dir/update_language", array("install_info" => _update_language_info,
                                                "install_language" => _install_language_language,
                                                "install_language_value" => getLanguageOptions($language_dir, $lang),
                                                "install_template" => _install_language_template,
                                                "install_template_value" => getTemplateOptions($template_dir, $tpldir),
                                                "install_do" => _install_language_do,
                                                ));
}
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE** Startseite des Updates
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
//************************************************************************************************//
// **START** Lädt das Template
//************************************************************************************************//
include("../install/inc/layout.php");
//************************************************************************************************//
// **ENDE**  Lädt das Template
//************************************************************************************************//
?> 