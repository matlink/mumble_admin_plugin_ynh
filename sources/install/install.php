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
include('../install/inc/functions.php');                   //Holt wichtige Basisfunktionen
//************************************************************************************************//
// **ENDE**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
//************************************************************************************************//
// **START** Autoforward zur Startseite wenn System bereits installiert wurde!
//************************************************************************************************//
if (filesize("../inc/_mysql.php") >= "1" AND $_GET['section'] != "install" AND $_GET['section'] != "save_preferences") {
    autoforward("../start/index.php",0);
    error_reporting(0);
}
//************************************************************************************************//
// **START** Autoforward zur Startseite wenn System bereits installiert wurde!
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
// **START**  beschreibe Datenbank aus ../inc/_mysql.php + Abschluss der Installation, gibt Anweisungen!
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'install':
    
    //stellt Verbindung zur Datenbank her
    include('../inc/_mysql.php');
    include('../inc/db_connect.php');

    // Definition Seitentitel
    $seitentitel .= _pagetitel_install_5;
    
    //Übernehme Eingaben
    $slice_secret = $_POST["slice_secret"];
    $map_user_name = $_POST["map_user_name"];
    $map_user_username = $_POST["map_user_username"];
    $map_user_clantag = $_POST["map_user_clantag"];
    $map_user_email = $_POST["map_user_email"];
    $map_user_pwd_1 = $_POST["map_user_pwd_1"];
    $map_user_pwd_2 = $_POST["map_user_pwd_2"];
    
    //Generiere SQl File Array
    $SQLResult = generateDatabaseDump(array("database_prefix" => $database_prefix,
                                            "version_num" => $version_num,
                                            "version_date" => $version_date,
    										"version_build" => $version_build,
                                            "template" => $tpldir,
                                            "language" => $lang, 
    										"aktdate" => $aktdate,
                                            "system" => get_server_system(),
                                            "host" => $_SERVER['SERVER_ADDR'],
    										"slice_secret" => $slice_secret,
                                            "name" => $map_user_name,  
    										"username" => $map_user_username,
    										"clantag" => $map_user_clantag,
                                            "email" => $map_user_email,  
                                            "pw" => md5($map_user_pwd_1),
                                            ), "install");
            
    //Splitte install.sql in einzelne Staements auf
    $statements = explode('-- BEGINN NEW STATEMENT', $SQLResult);
    
	//Speichere Datenbankdaten in DB ab
	foreach ($statements as $statement) {
		$result = mysql_query($statement);
	}
	
    // Info an PM, das Script ohne Probleme Installiert wurde!
    $map_url = $getHTTP . $_SERVER['SERVER_NAME'] . "/" .  trim($_SERVER['SCRIPT_NAME'], "install/install.php");
    $email_subject = "Das MAP ".$map_version." wurde erfolgreich auf " . $_SERVER['SERVER_NAME'] . " installiert";
    $email_text = "URL zur MAP-Instanz: <b><a href=\"". $map_url . "\">" . $map_url . "</a>" . "</b><br><br>" . "Server IP: <b>" . $_SERVER['SERVER_ADDR'] . "</b><br><br>" . "HTTP Host: <b>" . $_SERVER['HTTP_HOST'] . "</b><br><br>" . "User IP: <b>" . $_SERVER['REMOTE_ADDR'] . "</b><br><br>" . "User Name: <b>" . $map_user_name . "</b><br><br>" . "User E-Mail: <b>" . $map_user_email . "</b>";
    $header  = 'MIME-Version: 1.0' . "\r\n";
    $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $header .= 'From: Script MAP '.$map_version.' <installer@mumb1e.de>' . "\r\n";
    @mail('installer@mumb1e.de', $email_subject, $email_text, $header);
    
    //Support-Mail an User
    $header = FALSE;
    $email_subject = FALSE;
    $email_text = FALSE;
    $email_subject = "Thank you for using MAP $map_version";
    $email_text = "We are honored and want to say thank you very much for using MAP!<br>If you have any trouble or need help,<br> please feel free to ask our support-mailing-lists at <a href=\"http://mailing-lists.mumb1e.de/support/\">http://mailing-lists.mumb1e.de/support/</a>.<br>We will help you also via E-Mail Support at <a href=\"mailto:support@mumb1e.de\">support@mumb1e.de</a> or visit our <a href=\"http://www.mumb1e.de/en/community/support\">Support-Page!</a><br>Have a lot of fun with MAP! The developers!";
    $header  = 'MIME-Version: 1.0' . "\r\n";
    $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $header .= 'From: Installer MAP <install-confirmation@mumb1e.de>' . "\r\n";
    @mail($map_user_email, $email_subject, $email_text, $header);
    
    // Ladt HTML-Datei
    $index = show("$dir/install_db", array("install_1" => _install_finished_1,
                                           "install_2" => _install_finished_2,
                                           "install_3" => _install_finished_3,
                                           "install_3_2" => _install_finished_3_2,
                                           "install_4" => _install_finished_4, 
                                           "install_5" => _install_finished_5,
                                           "install_6" => _install_finished_6,
                                           "install_7" => $map_user_name,  
                                           "install_8" => _install_finished_8,  
                                           "install_9" => $map_user_pwd_1,
                                           "install_10" => _install_finished_10,
                                           "install_11" => _install_finished_11,
    							           "install_map_user_name_value" => $map_user_name,
    									   "install_map_user_username_value" => $map_user_username,
    									   "install_map_user_clantag_value" => $map_user_clantag,   
										   "install_map_user_email_value" => $map_user_email,
										   "install_map_user_pwd_1_value" => $map_user_pwd_1,
										   "install_map_user_pwd_2_value" => $map_user_pwd_2,
                                           "install_language_value" => $lang,
                                           "install_template_value" => $tpldir, 
                                           ));
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  beschreibe Datenbank aus ../inc/_mysql.php + Abschluss der Installation, gibt Anweisungen!
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Speichere Einstellungen in ../inc/_mysql.php ab und gehe weiter zur installation
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'save_preferences':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_install_4;
    $input = FALSE;
    $message_1 = FALSE;
    $message_2 = FALSE;
    $message_3 = FALSE;
    $message_4 = FALSE;
    $button_back = FALSE;
    $button_next = FALSE;
    
    //Ã¼bernehme Eingaben
    $mysql_host = $_POST["mysql_host"];
    $mysql_user = $_POST["mysql_user"];
    $mysql_pass = $_POST["mysql_pass"];
    $database_name = $_POST["database_name"];
    $database_prefix = $_POST["database_prefix"];
    $slice_secret = $_POST["slice_secret"];
    $map_user_name = $_POST["map_user_name"];    
    $map_user_username = $_POST["map_user_username"];
    $map_user_clantag = $_POST["map_user_clantag"];
    $map_user_email = $_POST["map_user_email"];
    $map_user_pwd_1 = $_POST["map_user_pwd_1"];
    $map_user_pwd_2 = $_POST["map_user_pwd_2"];
    
    //Angaben zur _mysql.php
    $path = '../inc/_mysql.php';
    $php = '>';
    $input .= '<?php
';
    $input .= '//******************************************************************//
';
    $input .= '// |     MAP - Mumb1e Admin Plugin   |   http://www.mumb1e.de/    | //   
';
    $input .= '// |              ___      ___   _________   _________            | //
';
    $input .= '// |             |   \    /   | |   ___   | |   ___   |           | //
';
    $input .= '// |             |    \__/    | |  |   |  | |  |   |  |           | //
';
    $input .= '// |             |  |\    /|  | |  |___|  | |  |___|  |           | //
';
    $input .= '// |             |  | \__/ |  | |   ___   | |   ______|           | //
';
    $input .= '// |             |  |      |  | |  |   |  | |  |                  | //
';
    $input .= '// |             |__|      |__| |__|   |__| |__|                  | //
';
    $input .= '// |                                                              | //
';
    $input .= '// | --- VERSION ---                                              | //
';
    $input .= '// | Installed at: '.$aktdate.'                            | //
';
    $input .= '// | Version: '.$map_version.'                                              | //
';
    $input .= '// | Date: '.$map_date.'                                             | //
';
    $input .= '// | --- COPYRIGTH ---                                            | //
';
    $input .= '// | Build by P.M. and M.H. | Accept the Copyrigths!              | //
';
    $input .= '// | © by Michael Koch aka P.M. <pm@mumb1e.de>                    | //
';
    $input .= '// | --- LICENSE ---                                              | //
';
    $input .= '// | MAP - Mumb1e Admin Plugin is a dual-licensed software        | //
';
    $input .= '// | MAP is released for private end-users under GPLv3            | //
';
    $input .= '// |   >>  (visit at: http://www.gnu.org/licenses/gpl-3.0.html)   | //
';
    $input .= '// | MAP is released for commercial use under a commerial license | //
';
    $input .= '// |   >>  (visit at: http://www.mumb1e.de/en/about/license)      | //
';
    $input .= '// | --- ATTENTION ---                                            | //
';
    $input .= '// | Changing, editing or spreading of this sourcecode in other   | //
';
    $input .= '// | scripts, or on other websites only with permission by PM!    | //
';
    $input .= '//******************************************************************//
 
';
    $input .= '//Folgende Zeilen 12-14 und 16-17 MÜSSEN konfiguriert sein!
';
    $input .= '//Allgemeine MySQL Daten des Servers
';
    $input .= '$mysql_host       = "'.$mysql_host.'";   //Hier bitte den Host eintragen. z.B.: "localhost"; "127.0.0.1"; "78.48.56.78".
';
    $input .= '$mysql_user       = "'.$mysql_user.'";        //Hier bitte den DB User eintragen. z.B.: "Admin"; "root"; "mysqluser".
';
    $input .= '$mysql_pass       = "'.$mysql_pass.'";      //Hier bitte das DB Passwort eintragen. z.B.: "geheim".
';
    $input .= '//MySQL Daten der Datenbank
';
    $input .= '$database_name    = "'.$database_name.'";  //Hier bitte den DB-Namen eintragen. z.B.: "mumble_server"; "Mumble"; "Murmur".
';
    $input .= '$database_prefix  = "'.$database_prefix.'";  //Hier bitte den gewÃ¼nschten DB-Prefix eintragen: z.B.: "murmur_" oder "mumble_".
';
    $input .= '?'.$php;
    
    // ../inc/_mysql.php mit Daten beschreiben
    if (is_writable($path)) {
        if (!$handle = fopen($path, "a")) {
            $status_1 = "FALSE";
            } else {
                $status_1 = "TRUE";
        
        }
        if (!fwrite($handle, $input)) {
            $status_2 = "FALSE";
            } else {
                $status_2 = "TRUE";
        }
        fclose($handle);
        $status_3 = "TRUE";
        } else {
            $status_3 = "FALSE";
    }

    //Ausgabe vorbereiten
    if ($status_1 == "TRUE" && $status_2 == "TRUE" && $status_3 == "TRUE") {
        $message_1 = _install_save_preferences_sucessfully;
        $button_next = _install_save_preferences_next;
        $button_message_1_a = "";
        $button_message_1_b = "";
        $button_next_1 = "";
        $button_next_2 = "";
        } else {
            $button_message_1_a = '<!--';
            $button_message_1_b = '-->';
            $button_next_1 = '<!--';
            $button_next_2 = '-->';
    }
    if ($status_1 != "TRUE") {
        $message_2 = _install_save_preferences_false_1;
        $button_message_2_a = "";
        $button_message_2_b = "";  
        } else {
            $button_message_2_a = '<!--';
            $button_message_2_b = '-->';
    }
    if ($status_2 != "TRUE") {
        $message_3 = _install_save_preferences_false_2;
        $button_message_3_a = "";  
        $button_message_3_b = "";  
        } else {
            $button_message_3_a = '<!--';
            $button_message_3_b = '-->';
    }
    if ($status_3 != "TRUE") {
        $message_4 = _install_save_preferences_false_3;
        $button_message_4_a = "";   
        $button_message_4_b = "";  
        } else {
            $button_message_4_a = '<!--';
            $button_message_4_b = '-->';
    }
    if ($status_1 == "FALSE" OR $status_2 == "FALSE" OR $status_3 == "FALSE") {
        $button_back = _install_save_preferences_back;  
        $button_back_1 = "";
        $button_back_2 = "";
        } else {
            $button_back_1 = '<!--';
            $button_back_2 = '-->';
    }

    $index = show("$dir/install_save_preferences", array("install_message_1" => $message_1,
                                                         "button_message_1_a" => $button_message_1_a,
                                                         "button_message_1_b" => $button_message_1_b,                                                          
                                                         "install_message_2" => $message_2,
                                                         "button_message_2_a" => $button_message_2_a,
                                                         "button_message_2_b" => $button_message_2_b,                                                          
                                                         "install_message_3" => $message_3,
                                                         "button_message_3_a" => $button_message_3_a,
                                                         "button_message_3_b" => $button_message_3_b,
                                                         "install_message_4" => $message_4,
                                                         "button_message_4_a" => $button_message_4_a,
                                                         "button_message_4_b" => $button_message_4_b,
                                                         "install_back" => $button_back,
                                                         "button_back_1" => $button_back_1,
                                                         "button_back_2" => $button_back_2,
                                                         "mysql_host" => $mysql_host,
                                                         "mysql_user" => $mysql_user,
                                                         "mysql_pass" => $mysql_pass,
                                                         "database_name" => $database_name,
                                                         "database_prefix" => $database_prefix,
    													 "slice_secret" => $slice_secret,
                                                         "install_next" => $button_next,
                                                         "button_next_1" => $button_next_1,
                                                         "button_next_2" => $button_next_2,
    											         "install_map_user_name_value" => $map_user_name,
    												     "install_map_user_username_value" => $map_user_username,
    												     "install_map_user_clantag_value" => $map_user_clantag,   
														 "install_map_user_email_value" => $map_user_email,
														 "install_map_user_pwd_1_value" => $map_user_pwd_1,
														 "install_map_user_pwd_2_value" => $map_user_pwd_2,
                                                         "install_language_value" => $lang,
                                                         "install_template_value" => $tpldir,
                                                         ));

break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Speichere Einstellungen in ../inc/_mysql.php ab und gehe weiter zur installation
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Überprüfe eingegebene Daten, zeige an ob Verbindung mit MySQL DB erfolgreich und gebe links "zuÃ¼ck" oder "weiter"
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'check_preferences':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_install_3;
    
    //Übernehme Eingaben
    $mysql_host = $_POST["mysql_host"];
    $mysql_user = $_POST["mysql_user"];
    $mysql_pass = $_POST["mysql_pass"];
    $database_name = $_POST["database_name"];
    $database_prefix = $_POST["database_prefix"];
    $slice_secret = $_POST["slice_secret"];
    $map_user_name = $_POST["map_user_name"];
    $map_user_username = $_POST["map_user_username"];
    $map_user_clantag = $_POST["map_user_clantag"];
    $map_user_email = $_POST["map_user_email"];
    $map_user_pwd_1 = $_POST["map_user_pwd_1"];
    $map_user_pwd_2 = $_POST["map_user_pwd_2"];
    $addError = array();
    
    //DB Connect Function
    if (function_exists("mysql_connect")) {
    	$connect_server = @mysql_connect($mysql_host,$mysql_user,$mysql_pass);
    	$addError['mysql_connect'] = FALSE;
    	} else {
    		$addError['mysql_connect'] = TRUE;
    }
	if (function_exists("mysql_select_db")) {
    	$connect_database = @mysql_select_db($database_name,$connect_server);
    	$addError['mysql_select_db'] = FALSE;
    	} else {
    		$addError['mysql_select_db'] = TRUE;
    }
    
    //Check ob Verbindung zum MySQL-Server erfolgreich!
    if (!$connect_server OR $mysql_host == "" OR $mysql_user == "" OR $mysql_pass == "" OR $addError['mysql_connect'] == TRUE) {
        $value1 = _install_check_preferences_value_2;
        $value1_ok = FALSE;
        } else {
            $value1 = _install_check_preferences_value_1;
            $value1_ok = TRUE;
    }
    if ($addError['mysql_connect'] == TRUE) {
    	$value1 .= _install_check_preferences_value_2_2;
    }
    
    //Check ob Verbindung zur DB erfolgreich und ob DB Name angegeben wurde!
    if (!$connect_database OR $database_name == "" OR $addError['mysql_select_db'] == TRUE) {
        $value2 = _install_check_preferences_value_2;
        $value2_ok = FALSE;
        } else {
            $value2 = _install_check_preferences_value_1;
            $value2_ok = TRUE;
    }
	if ($addError['mysql_select_db'] == TRUE) {
    	$value2 .= _install_check_preferences_value_2_2;
    }
    
    //Check des globalen MAP Users
    //1. Check der E-Mail Adresse
    $email_validated = check_email($map_user_email);
    //2. Check ob Name eingeben wurde und PWD gleich ist und ob E-Mail validiert ist => Ausgabe $value3_ok TRUE
    if ($map_user_name != "" AND $email_validated == TRUE AND ($map_user_pwd_1 != "" AND ($map_user_pwd_1 == $map_user_pwd_2))) {
    	$value3_ok = TRUE;
    	} else {
    		$value3_ok = FALSE;
    }
    //Gebe Info aus
    if ($map_user_name == "") {
    	$value3 = _install_check_preferences_value_3_uname;
    	} elseif ($email_validated != TRUE) {
    		$value3 = _install_check_preferences_value_3_email;
    		} elseif ($map_user_pwd_1 == "" OR $map_user_pwd_2 == "" OR $map_user_pwd_1 != $map_user_pwd_2) {
    			$value3 = _install_check_preferences_value_3_pwd;
    			} else {
    				$value3 = _install_check_preferences_value_3;
    }

    //Ausgabe des "weiter"-Buttons je nach Status des Verbindungschecks
    if ($value1_ok == TRUE && $value2_ok == TRUE && $value3_ok == TRUE) {
        $button_next_1 = '';
        $button_next_2 = '';
            } else {
            $button_next_1 = '<!--';
            $button_next_2 = '-->';
    }
    
    //Backlink setzten, je nachdem ob fehleingabe
    if ($value1_ok != TRUE) {
    	$urlback = "./install.php?section=insert_preferences";
        } elseif ($value2_ok != TRUE) {
    		$urlback = "./install.php?section=insert_preferences";
            } elseif ($value3_ok != TRUE) {
    			$urlback = "./install.php?section=add_map_user";
                } elseif ($value1_ok != TRUE AND $value2_ok != TRUE) {
    				$urlback = "./install.php?section=insert_preferences";
    				} else {
    					$urlback = "./install.php?section=add_map_user";
    }

    $index = show("$dir/install_check_preferences", array("install_message_1" => _install_check_preferences_message_1,
                                                          "install_value_1" => $value1, 
                                                          "install_message_2" => _install_check_preferences_message_2,
                                                          "install_message_3" => _install_check_preferences_message_3,
                                                          "install_value_2" => $value2,
                                                          "install_message_4" => _install_check_preferences_message_4,
														  "install_message_5" => _install_check_preferences_message_5,
														  "install_value_3" => $value3,
														  "install_message_6" => _install_check_preferences_message_6,
                                                          "button_next_1" => $button_next_1,
                                                          "button_next_2" => $button_next_2,
                                                          "install_back" => _install_check_preferences_back,
                                                          "backlink" => $urlback,
                                                          "install_do" => _install_check_preferences_do,
                                                          "mysql_host" => $mysql_host,
                                                          "mysql_user" => $mysql_user,
                                                          "mysql_pass" => $mysql_pass,
                                                          "database_name" => $database_name,
                                                          "database_prefix" => $database_prefix,
    													  "slice_secret" => $slice_secret,
    													  "install_map_user_name_value" => $map_user_name,
    												      "install_map_user_username_value" => $map_user_username,
    												      "install_map_user_clantag_value" => $map_user_clantag,   
														  "install_map_user_email_value" => $map_user_email,
														  "install_map_user_pwd_1_value" => $map_user_pwd_1,
														  "install_map_user_pwd_2_value" => $map_user_pwd_2,
                                                          "install_language_value" => $lang,
                                                          "install_template_value" => $tpldir,
                                                          ));
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Überprüfe eingegebene Daten, zeige an ob Verbindung mit MySQL DB erfolgreich und gebe links "zuÃ¼ck" oder "weiter"
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Formular um MAP User zu erstellen > Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'add_map_user':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_install_2_2;
    
    //Übernehme Eingaben
    $mysql_host = $_POST["mysql_host"];
    $mysql_user = $_POST["mysql_user"];
    $mysql_pass = $_POST["mysql_pass"];
    $database_name = $_POST["database_name"];
    $database_prefix = $_POST["database_prefix"];
    $slice_secret = $_POST["slice_secret"];
    $map_user_name = $_POST["map_user_name"];
    $map_user_username = $_POST["map_user_username"];
    $map_user_clantag = $_POST["map_user_clantag"];
    $map_user_email = $_POST["map_user_email"];
    $map_user_pwd_1 = $_POST["map_user_pwd_1"];
    $map_user_pwd_2 = $_POST["map_user_pwd_2"];

    $index = show("$dir/install_add_map_user", array("install_discription" => _install_add_map_user_discription,
                                                     "install_name" => _install_add_map_user_name,
                                                     "install_name_value" => $map_user_name,
                                                     "install_name_discription" => _install_add_map_user_name_discription,    
    												 "install_username" => _install_add_map_user_username,
                                                     "install_username_value" => $map_user_username,
                                                     "install_username_discription" => _install_add_map_user_username_discription,
    												 "install_clantag" => _install_add_map_user_clantag,
                                                     "install_clantag_value" => $map_user_clantag,
                                                     "install_clantag_discription" => _install_add_map_user_clantag_discription,    
                                                     "install_email" => _install_add_map_user_email,
                                                     "install_email_value" => $map_user_email,
                                                     "install_email_discription" => _install_add_map_user_email_discription,
                                                     "install_pass_1" => _install_add_map_user_pass_1, 
                                                     "install_pass_value_1" => $map_user_pwd_1,
                                                     "install_pass_discription_1" => _install_add_map_user_pass_discription_1,
                                                     "install_pass_2" => _install_add_map_user_pass_2, 
                                                     "install_pass_value_2" => $map_user_pwd_2,
                                                     "install_pass_discription_2" => _install_add_map_user_pass_discription_2,
                                                     "install_back" => _install_add_map_user_install_back,
                                                     "install_next" => _install_add_map_user_install_next,
                                                     "mysql_host" => $mysql_host,
                                                     "mysql_user" => $mysql_user,
                                                     "mysql_pass" => $mysql_pass,
                                                     "database_name" => $database_name,
                                                     "database_prefix" => $database_prefix,
    												 "slice_secret" => $slice_secret,
                                                     "install_language_value" => $lang,
                                                     "install_template_value" => $tpldir,
                                                     ));
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Formular um MAP User zu erstellen > Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  gibt Script Datenbank Daten > Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'insert_preferences':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_install_2;
    
    //Übernehme Eingaben
    if (isset($_POST["mysql_host"])) {
    	$mysql_host = $_POST["mysql_host"];
    	} else {
    		$mysql_host = FALSE;
    }
    if (isset($_POST["mysql_user"])) {
    	$mysql_user = $_POST["mysql_user"];
    	} else {
    		$mysql_user = FALSE;
    }
    if (isset($_POST["mysql_pass"])) {
    	$mysql_pass = $_POST["mysql_pass"];
    	} else {
    		$mysql_pass = FALSE;
    }
    if (isset($_POST["database_name"])) {
    	$database_name = $_POST["database_name"];
    	} else {
    		$database_name = FALSE;
    }
    if (isset($_POST["database_prefix"])) {
    	$database_prefix = $_POST["database_prefix"];
    	} else {
    		$database_prefix = FALSE;
    }
    if (isset($_POST["slice_secret"])) {
    	$slice_secret = $_POST["slice_secret"];
    	} else {
    		$slice_secret = FALSE;
    }
    if (isset($_POST["map_user_name"])) {
    	$map_user_name = $_POST["map_user_name"];
    	} else {
    		$map_user_name = FALSE;
    }
    if (isset($_POST["map_user_username"])) {
    	$map_user_username = $_POST["map_user_username"];
    	} else {
    		$map_user_username = FALSE;
    }
    if (isset($_POST["map_user_clantag"])) {
    	$map_user_clantag = $_POST["map_user_clantag"];
    	} else {
    		$map_user_clantag = FALSE;
    }
    if (isset($_POST["map_user_email"])) {
    	$map_user_email = $_POST["map_user_email"];
    	} else {
    		$map_user_email = FALSE;
    }
    if (isset($_POST["map_user_pwd_1"])) {
    	$map_user_pwd_1 = $_POST["map_user_pwd_1"];
    	} else {
    		$map_user_pwd_1 = FALSE;
    }
    if (isset($_POST["map_user_pwd_2"])) {
    	$map_user_pwd_2 = $_POST["map_user_pwd_2"];
    	} else {
    		$map_user_pwd_2 = FALSE;
    }
    
    $index = show("$dir/install_insert_preferences", array("install_mysql_head" => _install_insert_preferences_mysql_head,
                                                           "install_mysql_host" => _install_insert_preferences_mysql_host,
                                                           "install_mysql_host_value" => $mysql_host,
                                                           "install_mysql_host_discription" => _install_insert_preferences_mysql_host_discription,
                                                           "install_mysql_user" => _install_insert_preferences_mysql_user,
                                                           "install_mysql_user_value" => $mysql_user,
                                                           "install_mysql_user_discription" => _install_insert_preferences_mysql_user_discription,
                                                           "install_mysql_pass" => _install_insert_preferences_mysql_pass, 
                                                           "install_mysql_pass_value" => $mysql_pass,
                                                           "install_mysql_pass_discription" => _install_insert_preferences_mysql_pass_discription,
                                                           "install_database_head" => _install_insert_preferences_database_head,
                                                           "install_database_name" => _install_insert_preferences_database_name,
                                                           "install_database_name_value" => $database_name,
                                                           "install_database_name_discription" => _install_insert_preferences_database_name_discription,
                                                           "install_database_prefix" => _install_insert_preferences_database_prefix,
                                                           "install_database_prefix_value" => $database_prefix,
                                                           "install_database_prefix_discription" => _install_insert_preferences_database_prefix_discription,
   	 													   "install_slice_head" => _install_insert_preferences_slice_head,
                                                           "install_slice_secret" => _install_insert_preferences_slice_secret,
                                                           "install_slice_secret_value" => $slice_secret,
                                                           "install_slice_secret_discription" => _install_insert_preferences_slice_secret_discription,    
                                                           "install_back" => _install_insert_preferences_back,
                                                           "install_next" => _install_insert_preferences_next,
    												       "install_map_user_name_value" => $map_user_name,
    												       "install_map_user_username_value" => $map_user_username,
    												       "install_map_user_clantag_value" => $map_user_clantag,    
													       "install_map_user_email_value" => $map_user_email,
													       "install_map_user_pwd_1_value" => $map_user_pwd_1,
													       "install_map_user_pwd_2_value" => $map_user_pwd_2,
                                                           "install_language_value" => $lang,
                                                           "install_template_value" => $tpldir,
                                                           ));
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  gibt Script Datenbank Daten > Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Check CHMOD rigths
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'check_chmod':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_install_1_c;
    
	//Check des Verzeichnises "../admin/"
    $path_admin = substr(decoct( fileperms('../admin/') ), 1);
    if ((octdec($path_admin) & 0755) == 0755) {
        $path_output_admin = '<font color="#008000"><b>' . $path_admin . '</b></font>';
        $button_ok_14 = TRUE;
        } else {
            $path_output_admin = '<font color="#FF0000"><b>' . $path_admin . '</b></font>';
            $button_ok_14 = FALSE;
    }
    //Check des Verzeichnises "../inc/"
    $path_inc = substr(decoct( fileperms('../inc/') ), 1);
    if ((octdec($path_inc) & 0755) == 0755) {
        $path_output_inc = '<font color="#008000"><b>' . $path_inc . '</b></font>';
        $button_ok_1 = TRUE;
        } else {
            $path_output_inc = '<font color="#FF0000"><b>' . $path_inc . '</b></font>';
            $button_ok_1 = FALSE;
    }
    //Check der Datei "../inc/_mysql.php"
    $path_inc_mysqlphp = substr(decoct( fileperms('../inc/_mysql.php') ), 2);
    if ((octdec($path_inc_mysqlphp) & 0777) == 0777) {
        $path_output_inc_mysqlphp = '<font color="#008000"><b>' . $path_inc_mysqlphp . '</b></font>';
        $button_ok_2 = TRUE;
        } else {
            $path_output_inc_mysqlphp = '<font color="#FF0000"><b>' . $path_inc_mysqlphp . '</b></font>';
            $button_ok_2 = FALSE;
    }
	//Check des Verzeichnises "../inc/uploads/avatars/"
    $path_inc_uploads_avatars = substr(decoct( fileperms('../inc/uploads/avatars/') ), 1);
    if ((octdec($path_inc_uploads_avatars) & 0777) == 0777) {
        $path_output_inc_uploads_avatars = '<font color="#008000"><b>' . $path_inc_uploads_avatars . '</b></font>';
        $button_ok_15 = TRUE;
        } else {
            $path_output_inc_uploads_avatars = '<font color="#FF0000"><b>' . $path_inc_uploads_avatars . '</b></font>';
            $button_ok_15 = FALSE;
    }
    //Check des Verzeichnises "../install/"
    $path_install = substr(decoct( fileperms('../install/') ), 1);
    if ((octdec($path_install) & 0755) == 0755) {
        $path_output_install = '<font color="#008000"><b>' . $path_install . '</b></font>';
        $button_ok_3 = TRUE;
        } else {
            $path_output_install = '<font color="#FF0000"><b>' . $path_install . '</b></font>';
            $button_ok_3 = FALSE;
    }
    //Check der Datei "../install/install.php"
    $path_install_installphp = substr(decoct( fileperms('../install/install.php') ), 2);
    if ((octdec($path_install_installphp) & 0777) == 0777) {
        $path_output_install_installphp = '<font color="#008000"><b>' . $path_install_installphp . '</b></font>';
        $button_ok_4 = TRUE;
        } else {
            $path_output_install_installphp = '<font color="#FF0000"><b>' . $path_install_installphp . '</b></font>';
            $button_ok_4 = FALSE;
    }
    //Check des Verzeichnises "../log/"
    $path_log = substr(decoct( fileperms('../log/') ), 1);
    if ((octdec($path_log) & 0755) == 0755) {
        $path_output_log = '<font color="#008000"><b>' . $path_log . '</b></font>';
        $button_ok_5 = TRUE;
        } else {
            $path_output_log = '<font color="#FF0000"><b>' . $path_log . '</b></font>';
            $button_ok_5 = FALSE;
    }
    //Check des Verzeichnises "../operate/"
    $path_operate = substr(decoct( fileperms('../operate/') ), 1);
    if ((octdec($path_operate) & 0755) == 0755) {
        $path_output_operate = '<font color="#008000"><b>' . $path_operate . '</b></font>';
        $button_ok_6 = TRUE;
        } else {
            $path_output_operate = '<font color="#FF0000"><b>' . $path_operate . '</b></font>';
            $button_ok_6 = FALSE;
    }
	//Check des Verzeichnises "../permissions/"
    $path_permissions = substr(decoct( fileperms('../permissions/') ), 1);
    if ((octdec($path_permissions) & 0755) == 0755) {
        $path_output_permissions = '<font color="#008000"><b>' . $path_permissions . '</b></font>';
        $button_ok_16 = TRUE;
        } else {
            $path_output_permissions = '<font color="#FF0000"><b>' . $path_permissions . '</b></font>';
            $button_ok_16 = FALSE;
    }
    //Check des Verzeichnises "../request/"
    $path_request = substr(decoct( fileperms('../request/') ), 1);
    if ((octdec($path_request) & 0755) == 0755) {
        $path_output_request = '<font color="#008000"><b>' . $path_request . '</b></font>';
        $button_ok_7 = TRUE;
        } else {
            $path_output_request = '<font color="#FF0000"><b>' . $path_request . '</b></font>';
            $button_ok_7 = FALSE;
    }
    //Check des Verzeichnises "../server/"
    $path_server = substr(decoct( fileperms('../server/') ), 1);
    if ((octdec($path_server) & 0755) == 0755) {
        $path_output_server = '<font color="#008000"><b>' . $path_server . '</b></font>';
        $button_ok_8 = TRUE;
        } else {
            $path_output_server = '<font color="#FF0000"><b>' . $path_server . '</b></font>';
            $button_ok_8 = FALSE;
    }
    //Check des Verzeichnises "../settings/"
    $path_settings = substr(decoct( fileperms('../settings/') ), 1);
    if ((octdec($path_settings) & 0755) == 0755) {
        $path_output_settings = '<font color="#008000"><b>' . $path_settings . '</b></font>';
        $button_ok_9 = TRUE;
        } else {
            $path_output_settings = '<font color="#FF0000"><b>' . $path_settings . '</b></font>';
            $button_ok_9 = FALSE;
    }
    //Check des Verzeichnises "../start/"
    $path_start = substr(decoct( fileperms('../start/') ), 1);
    if ((octdec($path_start) & 0755) == 0755) {
        $path_output_start = '<font color="#008000"><b>' . $path_start . '</b></font>';
        $button_ok_10 = TRUE;
        } else {
            $path_output_start = '<font color="#FF0000"><b>' . $path_start . '</b></font>';
            $button_ok_10 = FALSE;
    }
    //Check des Verzeichnises "../user/"
    $path_user = substr(decoct( fileperms('../user/') ), 1);
    if ((octdec($path_user) & 0755) == 0755) {
        $path_output_user = '<font color="#008000"><b>' . $path_user . '</b></font>';
        $button_ok_11 = TRUE;
        } else {
            $path_output_user = '<font color="#FF0000"><b>' . $path_user . '</b></font>';
            $button_ok_11 = FALSE;
    }
    //Check des Verzeichnises "../view/"
    $path_view = substr(decoct( fileperms('../view/') ), 1);
    if ((octdec($path_view) & 0755) == 0755) {
        $path_output_view = '<font color="#008000"><b>' . $path_view . '</b></font>';
        $button_ok_12 = TRUE;
        } else {
            $path_output_view = '<font color="#FF0000"><b>' . $path_view . '</b></font>';
            $button_ok_12 = FALSE;
    }
    //Check der Datei "../index.php"
    $path_indexphp = substr(decoct( fileperms('../index.php') ), 2);
    if ((octdec($path_indexphp) & 0666) == 0666) {
        $path_output_indexphp = '<font color="#008000"><b>' . $path_indexphp . '</b></font>';
        $button_ok_13 = TRUE;
        } else {
            $path_output_indexphp = '<font color="#FF0000"><b>' . $path_indexphp . '</b></font>';
            $button_ok_13 = FALSE;
    }    

    if (get_server_system() == "linux") {
    //Ausgabe des "weiter"-Buttons je nach Status des CHMOD-Checks unter Linux
        if ($button_ok_1 = TRUE && $button_ok_2 == TRUE && $button_ok_3 == TRUE && $button_ok_4 == TRUE && $button_ok_5 == TRUE && $button_ok_6 == TRUE && $button_ok_7 == TRUE && $button_ok_8 == TRUE && $button_ok_9 == TRUE && $button_ok_10 == TRUE && $button_ok_11 == TRUE && $button_ok_12 == TRUE && $button_ok_13 == TRUE && $button_ok_14 == TRUE && $button_ok_15 == TRUE && $button_ok_16 == TRUE) {
            $button_next_1 = '';
            $button_next_2 = '';
                } else {
                $button_next_1 = '<!--';
                $button_next_2 = '-->';
        }
        } else {
            $button_next_1 = '';
            $button_next_2 = '';
    }    
    
    $index = show("$dir/install_chmod", array("info" => _install_check_chmod_info,
                                              "path_0" => _install_check_chmod_path_1,
                                              "need_0" => _install_check_chmod_need_1,
                                              "chmod_0" => _install_check_chmod_chmod_1,
    										  "path_14" => '../admin/',
                                              "need_14" => '0755',
                                              "chmod_14" => $path_output_admin,
                                              "path_1" => '../inc/',
                                              "need_1" => '0755',
                                              "chmod_1" => $path_output_inc,
                                              "path_2" => '../inc/_mysql.php',
                                              "need_2" => '0777',
                                              "chmod_2" => $path_output_inc_mysqlphp,
    										  "path_15" => '../inc/uploads/avatars/',
                                              "need_15" => '0777',
                                              "chmod_15" => $path_output_inc_uploads_avatars,
                                              "path_3" => '../install/',
                                              "need_3" => '0755',
                                              "chmod_3" => $path_output_install,
                                              "path_4" => '../install/install.php',
                                              "need_4" => '0777',
                                              "chmod_4" => $path_output_install_installphp,
    										  "path_5" => '../log/',
                                              "need_5" => '0755',
                                              "chmod_5" => $path_output_log,
    										  "path_6" => '../operate/',
                                              "need_6" => '0755',
                                              "chmod_6" => $path_output_operate,
    										  "path_16" => '../permissions/',
                                              "need_16" => '0755',
                                              "chmod_16" => $path_output_permissions,
                                              "path_7" => '../request/',
                                              "need_7" => '0755',
                                              "chmod_7" => $path_output_request,
                                              "path_8" => '../server/',
                                              "need_8" => '0755',
                                              "chmod_8" => $path_output_server,
                                              "path_9" => '../settings/',
                                              "need_9" => '0755',
                                              "chmod_9" => $path_output_settings,
                                              "path_10" => '../start/',
                                              "need_10" => '0755',
                                              "chmod_10" => $path_output_start,
                                              "path_11" => '../user/',
                                              "need_11" => '0755',
                                              "chmod_11" => $path_output_user,
    										  "path_12" => '../view/',
                                              "need_12" => '0755',
                                              "chmod_12" => $path_output_request,
                                              "path_13" => '../index.php',
                                              "need_13" => '0666',
                                              "chmod_13" => $path_output_indexphp,
                                              "button_1" => _install_check_chmod_button_1,
                                              "button_next_1" => $button_next_1,
                                              "button_next_2" => $button_next_2,
                                              "button_2" => _install_check_chmod_button_2,
                                              "button_3" => _install_check_chmod_button_3, 
                                              "install_language_value" => $lang,
                                              "install_template_value" => $tpldir,
                                              ));
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Check CHMOD rigths
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Sicherheitsfrage
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'controlling_inquiry':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_install_1;

    $index = show("$dir/install_start", array("info_0" => _install_part_0,
    										  "info_1" => _install_part_1,
    										  "info_2" => _install_part_2,
                                              "info_3" => _install_part_3,
                                              "info_4" => _install_part_4,
                                              "info_5" => _install_part_5,
                                              "install_language_value" => $lang,
                                              "install_template_value" => $tpldir,
                                              ));
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Sicherheitsfrage
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Startseite der Installation
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_install_0;

    $index = show("$dir/install_language", array("install_info" => _install_language_info,
                                                 "install_language" => _install_language_language,
                                                 "install_language_value" => getLanguageOptions($language_dir, $lang),
                                                 "install_template" => _install_language_template,
                                                 "install_template_value" => getTemplateOptions($template_dir, $tpldir),
                                                 "install_do" => _install_language_do,
                                                 ));
}
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Startseite der Installation
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