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
// Definiert Ort des Scripts
$dir = "operate";
// Lädt Globale Funktionen
include("../inc/functions.php");
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
// **START**  Authentifizierung wenn erfolgreich in Db schreiben und abschließen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_pwd_backroute_db':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_pwd_backroute;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
    if (getPermOperateFunctions()) {      
        //Start definition eigentliche Funktionen dieser Section:
        
    	//Übernehme Daten aus Formular
    	$saftey_code = $_POST["saftey_code"];
		$email = $_POST["email"];
		$new_pwd_1 = $_POST["pwd_1"];
		$new_pwd_2 = $_POST["pwd_2"];
        
		//Hole Passwort zwischenspeicher aus Datenbank
    	$qry = mysql_query("SELECT * FROM `".$database_prefix."operate_pwd` WHERE saftey_code = '$saftey_code' AND email = '$email' LIMIT 1");
        $operateData = mysql_fetch_array($qry);
        
		//Checke ob PW richtig eingegeben wurde
		if ($new_pwd_1 == $new_pwd_2 && $new_pwd_1 != "") {
			//Speichere neues Paswort in DB
			if ($operateData['type'] == "1") {				
				//Für Admin
				mysql_query("UPDATE `".$database_prefix."user` SET pw = '".md5($new_pwd_1)."' WHERE user_id = '$operateData[user_id]'");
				} elseif ($operateData['type'] == "2") {
					//Für User				
					$UserInfoMap[4] = $new_pwd_1;
					setRegistration($operateData['server_id'], $operateData['user_id'], $UserInfoMap);
			}			
			//Lösche Zwischenspeicher (map_operate_pwd)
			mysql_query("DELETE FROM `".$database_prefix."operate_pwd` WHERE email = '".$email."'");
			$info = "<br><div align=\"center\"><div class='boxsucess'>"._operate_forgot_pwd_backroute_db_success."</div></div>";
            $autoforward = TRUE;			
			} else {
				//Gebe Error aus und gehe zurück zur PW eingabe
				$info = "<br><div align=\"center\"><div class='savearea'>"._operate_forgot_pwd_backroute_db_email_error."</div></div>";
	            $autoforward = FALSE;
		}
		
		//Loggingfunktion, Übergabe der Werte: Authentifizierung wenn erfolgreich in Db schreiben und abschließen
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $operateData['user_id'];		//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_db_1";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $operateData['server_id'];	//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = "";							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

		//Weiterleitung nach 8 Sekunden! zu start (erfolgreich)
        if ($autoforward == TRUE) {
            autoforward("../start/index.php",8);
        }  
        //Weiterleitung nach 5 Sekunden! zu operate (fehler)
        if ($autoforward == FALSE) {
            autoforward("../operate/index.php?section=forgot_pwd_backroute&s=" . $saftey_code . "&e=" . $email,5);
        }  
        	
        //Ende definition eigentliche Funktionen dieser Section^^
		} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
                    				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Authentifizierung wenn erfolgreich in Db schreiben und abschließen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Authentifizierung durch Backlink aus E-Mail => Eingabe des neuen Passwortes
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_pwd_backroute':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_pwd_backroute;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
    if (getPermOperateFunctions()) {      
        //Start definition eigentliche Funktionen dieser Section:
        
    	//Übernehme Daten aus URL (aus der Bestätigungsmail)
    	$saftey_code = $_GET["s"];
    	$email = $_GET["e"];
    	
    	//Checke ob übergebene Werte aus URL echt sind und in DB vorhanden, bzw. hole daten um sie später unten auszuwerten
    	$qry = mysql_query("SELECT * FROM `".$database_prefix."operate_pwd` WHERE saftey_code = '$saftey_code' AND email = '$email' LIMIT 1");
        $check = mysql_num_rows($qry);
    	
    	//Hole Passwort zwischenspeicher aus Datenbank
    	$qry = mysql_query("SELECT * FROM `".$database_prefix."operate_pwd` WHERE saftey_code = '$saftey_code' AND email = '$email' LIMIT 1");
        $operateData = mysql_fetch_array($qry);

        //Hole Userspezifische Daten aus realer Userdatenbank
        if ($operateData['type'] == "1") {
        	//Hole Daten wenn Admin
        	$user = user_info($operateData['user_id']);
        	if (count($user['server_id']) > 1 OR count($user['server_id']) == 0) {
        		$server['name'] = _operate_forgot_pwd_backroute_account_type_global;
        		} elseif (count($user['server_id']) == 1) {
        			$server = server_info($user['server_id'][0]);
        	}
        	$type = _operate_forgot_pwd_backroute_account_type_map; 
        	} elseif ($operateData['type'] == "2") {
        		//Hole Daten wenn User
        		$user = getRegistration($operateData['server_id'], $operateData['user_id']);
        		$server = server_info($operateData['server_id']);
        		$type = _operate_forgot_pwd_backroute_account_type_server;  
        }
        
        //Vergleiche Werte aus URL, ob User authentifiziert ist, ansonsten gebe Error aus.
		if ($check != "0") {
			//Lade Template
            $content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
			                         "head_value" => _operate_forgot_pwd_backroute_head,
									 "navi_on" => TRUE,
									 "navi_type" => "forgot_pwd_backroute",
									 );
								 
    		$index = show("$dir/forgot_pwd_backroute", array("discription" => _operate_forgot_pwd_backroute_discription,
    														 "name" => _operate_forgot_pwd_backroute_uname,
    														 "email" => _operate_forgot_pwd_backroute_email,
    														 "server" => _operate_forgot_pwd_backroute_server,
    														 "type" => _operate_forgot_pwd_backroute_type,
    											    	     "enter_new_pwd_1" => _operate_forgot_pwd_backroute_enter_new_pwd_1,
    									   		    	     "enter_new_pwd_2" => _operate_forgot_pwd_backroute_enter_new_pwd_2,
    														 "name_value" => $user['name'],
    														 "email_value" => $email,
    														 "server_value" => $server['name'],
    														 "type_value" => $type,
    														 "saftey_code_hidden" => $saftey_code,
                						   		      		 )); 
			} elseif ($check == "0") {
				//Gebe error aus, dass er nicht authentifiziert ist. und leite auf startseite
				$info = "<br><div align=\"center\"><div class='savearea'>"._operate_forgot_pwd_backroute_no_auth."</div></div>";
            	$autoforward = TRUE;
		}
		
		//Loggingfunktion, Übergabe der Werte: Authentifizierung durch Backlink aus E-Mail => Eingabe des neuen Passwortes
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == FALSE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $user['user_id'];				//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_show_2";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server['server_id'];		//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $user['name'];			        //Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		
        //Weiterleitung nach 5 Sekunden! zu operate (fehler)
        if ($autoforward == TRUE) {
            autoforward("../start/index.php",5);
        }        
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Authentifizierung durch Backlink aus E-Mail => Eingabe des neuen Passwortes
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Passwort vergessen für Server User Auswertung
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_pwd_server_db':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_pwd_server_db;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {     
        //Start definition eigentliche Funktionen dieser Section:
        
    	//Übernehme Werte aus Form
    	$name = $_POST["uname"];
    	$email = $_POST["email"];
    	$secure_code = getCaptchaStatus();
    	
        //Kontrolle ob Sicherheitscode identisch bzw. richtig eingegeben wurde
        if ($secure_code == FALSE) {
            $info = "<br><div align=\"center\"><div class='savearea'>"._wrong_secure_code."</div></div>";
            $autoforward = TRUE;
        }
        
		//Hole Server
		$servers = getServers();    	
		foreach ($servers as $server_id) {
			//Hole Server Info um Exeption zu vermeiden
			$server = server_info($server_id);
			//Gebe E-Mails aus, wenn Server Online ist
			if ($server['online']) {
				//Gebe die User des Servers aus
    			foreach (array_keys($server['registered_users']) as $user_id) {
    				//Hole UserInfo
    				$user = getRegistration($server_id, $user_id);
					//Vergleiche
    				if ($user['name'] == $name AND $user['email'] == $email AND $secure_code == TRUE) {
    					$user1 = $user;
			    		//Erstelle Code für externen Channelviewer
			        	$gen_saftey_code = pw_gen($a = '25', $b = '25', $num = '10', $spec = '0');
			        	//Schreibe generierten Sicherheitscode und nötige Infos in DB zur Zwischenspeicherung
			        	$safe_access = mysql_query("INSERT INTO `".$database_prefix."operate_pwd` (`server_id`, `user_id`, `type`, `saftey_code`, `email`) VALUES ('$user[server_id]', '$user[user_id]', '2', '$gen_saftey_code', '$email')");
			        	//Sende E-Mail
			        	$backlink = "<a href=\"" . $getHTTP . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . "?section=forgot_pwd_backroute" . "&s=" . $gen_saftey_code . "&e=" . $email . "\" target=\"_blank\">" . _operate_forgot_pwd_server_db_email_text . "</a>";
			            //Definiere Platzhalter für E-Mail
				    	$Placeholder = array("Backlink" => $backlink,			    	
				    						 );    	
				    	//Sende E-Mail
				    	$Connection = mapmail($email, _email_mapmail_locked_username_autoscript, $FromUserID = FALSE, 
				    						  $email, $name, $ToUserID = FALSE,
				    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '23001',
				    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    						  $AltSubject = FALSE, $AltBody = FALSE);	  
			            $sucess = TRUE;
        			}
	    		}
			}
		}
		
		//Gebe Info aus
		if ($sucess) {
			$info = "<br><div align=\"center\"><div class='boxsucess'>"._operate_forgot_pwd_server_db_send_true."</div></div>";
            $autoforward = TRUE;        	
        	} elseif ($check_un == "0" OR $get_email['value'] == "" OR $SecureCodeError == "TRUE") {
        		$info = "<br><div align=\"center\"><div class='savearea'>"._operate_forgot_pwd_server_db_empty."</div></div>";
            	$autoforward = FALSE;
    	}

        //Loggingfunktion, Übergabe der Werte: Passwort vergessen für MAP User Auswertung
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $user1['user_id'];				//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_db_3";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $user1['server_id'];			//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $user1['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        
        //Weiterleitung nach 5 Sekunden! zu start (erfolgreich)
        if ($autoforward == TRUE) {
            autoforward("../start/index.php",8);
        }  
        //Weiterleitung nach 3 Sekunden! zu operate (fehler)
        if ($autoforward == FALSE) {
            autoforward("../operate/index.php?section=forgot_pwd_server",5);
        }       
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Passwort vergessen für Server User Auswertung
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Formular Passwort vergessen für User
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_pwd_server':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_pwd_server;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {     
        //Start definition eigentliche Funktionen dieser Section:

		//Lade Template
        $content_headers = array("head_on" => TRUE,
					 			 "head_type" => "default",
                  			     "head_value" => _operate_forgot_pwd_server_head,
								 "navi_on" => TRUE,
								 "navi_type" => "forgot_pwd_server",
								 );
    	$index = show("$dir/forgot_pwd_server", array("discription" => _operate_forgot_pwd_server_discription,
    											      "enter_uname" => _operate_forgot_pwd_server_enter_uname,
    									   		      "enter_email" => _operate_forgot_pwd_server_enter_email,
    												  "enter_captcha_head" => _operate_forgot_pwd_server_enter_sc,
    									              "enter_captcha" => recaptcha_get_html($GoogleReCaptchaPublicKey, FALSE)
                						   		      ));        
                						   		      
        //Loggingfunktion, Übergabe der Werte: Formular Passwort vergessen für Server User
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_show_4";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = "";							//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = "";							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Formular Passwort vergessen für User
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Passwort vergessen für Admin Auswertung
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_pwd_map_db':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_pwd_map_db;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {   
        //Start definition eigentliche Funktionen dieser Section:
        
    	//Übernehme Werte aus Form
    	$name = $_POST["uname"];
    	$email = $_POST["email"];
    	$secure_code = getCaptchaStatus();
    	$sucess = FALSE;
    	
        //Kontrolle ob Sicherheitscode identisch bzw. richtig eingegeben wurde
        if ($secure_code == FALSE) {
            $info = "<br><div align=\"center\"><div class='savearea'>"._wrong_secure_code."</div></div>";
            $autoforward = FALSE;
        }
        
		foreach (getAdmins() as $user_id) {
			//Hole UserInfo
			$user = user_info($user_id);
			if ($user['name'] == $name AND $user['email'] == $email AND $secure_code == TRUE) {
				$user1 = $user;
				//Erstelle Backlink Code
	        	$gen_saftey_code = pw_gen($a = '25', $b = '25', $num = '10', $spec = '0');
	        	//Schreibe generierten Sicherheitscode und nötige Infos in DB zur Zwischenspeicherung
	        	$safe_access = mysql_query("INSERT INTO `".$database_prefix."operate_pwd` (`server_id`, `user_id`, `type`, `saftey_code`, `email`) VALUES ('0', '$user[user_id]', '1', '$gen_saftey_code', '$user[email]')");
	        	//Sende E-Mail
	        	$backlink = "<a href=\"" . $getHTTP . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . "?section=forgot_pwd_backroute" . "&s=" . $gen_saftey_code . "&e=" . $email . "\" target=\"_blank\">" . _operate_forgot_pwd_map_db_email_text . "</a>";
	            //Definiere Platzhalter für E-Mail
		    	$Placeholder = array("Backlink" => $backlink,			    	
		    						 );    	
		    	//Sende E-Mail
		    	$Connection = mapmail($email, $name, $user['user_id'], 
		    						  $email, $name, $user['user_id'],
		    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
		    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '23002',
		    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
		    						  $AltSubject = FALSE, $AltBody = FALSE);				  
				$sucess = TRUE;
				} else {
				$user1 = FALSE;
			}
		}
        
		//Gebe positive Info aus
		if ($sucess) {
        	$info = "<br><div align=\"center\"><div class='boxsucess'>"._operate_forgot_pwd_map_db_send_true."</div></div>";
            $autoforward = TRUE;        	
        	} else {
        		$info = "<br><div align=\"center\"><div class='savearea'>"._operate_forgot_pwd_map_db_empty."</div></div>";
            	$autoforward = FALSE;
        }

        //Loggingfunktion, Übergabe der Werte: Passwort vergessen für MAP User Auswertung
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $user1['user_id'];				//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_db_5";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $user1['server_id'];			//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $user1['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        
        //Weiterleitung nach 5 Sekunden! zu start (erfolgreich)
        if ($autoforward == TRUE) {
            autoforward("../start/index.php",8);
        }  
        //Weiterleitung nach 3 Sekunden! zu operate (fehler)
        if ($autoforward == FALSE) {
            autoforward("../operate/index.php?section=forgot_pwd_map",5);
        }       
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Passwort vergessen für Admin Auswertung
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Formular Passwort vergessen für Admin
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_pwd_map':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_pwd_map;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {     
        //Start definition eigentliche Funktionen dieser Section:

		//Lade Template
		$content_headers = array("head_on" => TRUE,
					 			 "head_type" => "default",
                  			     "head_value" => _operate_forgot_pwd_map_head,
								 "navi_on" => TRUE,
								 "navi_type" => "forgot_pwd_map",
								 );
    	$index = show("$dir/forgot_pwd_map", array("discription" => _operate_forgot_pwd_map_discription,
    											   "enter_uname" => _operate_forgot_pwd_map_enter_uname,
    									   		   "enter_email" => _operate_forgot_pwd_map_enter_email,
    											   "enter_captcha_head" => _operate_forgot_pwd_map_enter_sc,
    									           "enter_captcha" => recaptcha_get_html($GoogleReCaptchaPublicKey, FALSE)
                						   		   )); 

        //Loggingfunktion, Übergabe der Werte: Formular Passwort vergessen für MAP User
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_show_6";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = "";							//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = "";							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}  
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Formular Passwort vergessen für Admin
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Benutzername vergessen für User Auswertung
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_uname_server_db':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_uname_server_db;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {  
        //Start definition eigentliche Funktionen dieser Section:
        
    	//Übernehme Werte aus Form
    	$email = $_POST["email"];
    	$secure_code = getCaptchaStatus();
    	
        //Kontrolle ob Sicherheitscode identisch bzw. richtig eingegeben wurde
        if ($secure_code == FALSE) {
            $info = "<br><div align=\"center\"><div class='savearea'>"._wrong_secure_code."</div></div>";
            $autoforward = FALSE;
        }
        
		//Hole Server
		$servers = getServers();    	
		foreach ($servers as $server_id) {
			//Hole Server Info um Exeption zu vermeiden
			$server = server_info($server_id);
			//Gebe E-Mails aus, wenn Server Online ist
			if ($server['online']) {
				//Gebe die User des Servers aus
    			foreach (array_keys($server['registered_users']) as $user_id) {
    				//Hole UserInfo
    				$user = getRegistration($server_id, $user_id);

    				if ($user['email'] == $email AND $secure_code == TRUE) {
			            //Definiere Platzhalter für E-Mail
				    	$Placeholder = array("UserName" => $user['name'],			    	
				    						 );    	
				    	//Sende E-Mail
				    	$Connection = mapmail($email, _email_mapmail_locked_username_autoscript, $FromUserID = FALSE, 
				    						  $email, $user['name'], $ToUserID = FALSE,
				    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '23003',
				    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    						  $AltSubject = FALSE, $AltBody = FALSE);	  
			            $sucess = TRUE;
        				} else {
        					$sucess = FALSE;
        			}
	    		}
			}
		}
		
		//Gebe Info aus
		if ($sucess) {
			$info = "<br><div align=\"center\"><div class='boxsucess'>"._operate_forgot_uname_server_db_send_true."</div></div>";
			$autoforward = TRUE;    
			} else {
				$info = "<br><div align=\"center\"><div class='savearea'>"._operate_forgot_uname_server_db_empty."</div></div>";
				$autoforward = FALSE;
    	}

        //Loggingfunktion, Übergabe der Werte: Benutzername vergessen für Server User Auswertung
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $user['user_id'];				//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_db_7";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $user['server_id'];			//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $user['name'];					//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        
        //Weiterleitung nach 5 Sekunden! zu start (erfolgreich)
        if ($autoforward == TRUE) {
            autoforward("../operate/index.php?default",5);
        }  
        //Weiterleitung nach 3 Sekunden! zu operate (fehler)
        if ($autoforward == FALSE) {
            autoforward("../operate/index.php?section=forgot_uname_server",3);
        }       
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Benutzername vergessen für User Auswertung
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Formular Benutzername vergessen für User
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_uname_server':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_uname_server;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {     
        //Start definition eigentliche Funktionen dieser Section:
        
		//Lade Template
		$content_headers = array("head_on" => TRUE,
					 			 "head_type" => "default",
                  			     "head_value" => _operate_forgot_uname_server_head,
								 "navi_on" => TRUE,
								 "navi_type" => "forgot_uname_server",
								 );
    	$index = show("$dir/forgot_uname_server", array("discription" => _operate_forgot_uname_server_discription,
    									   			    "enter_email" => _operate_forgot_uname_server_enter_email,
    													"enter_captcha_head" => _operate_forgot_uname_server_enter_sc,
    									                "enter_captcha" => recaptcha_get_html($GoogleReCaptchaPublicKey, FALSE)
                						   			    ));  

        //Loggingfunktion, Übergabe der Werte: Formular Benutzername vergessen für Server User
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_show_8";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = "";							//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = "";							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}   
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Formular Benutzername vergessen für User
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Benutzername vergessen für Admin Auswertung
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_uname_map_db':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_uname_map_db;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {  
        //Start definition eigentliche Funktionen dieser Section:
        
    	//Übernehme Werte aus Form
    	$email = $_POST["email"];
    	$secure_code = getCaptchaStatus();
    	
        //Kontrolle ob Sicherheitscode identisch bzw. richtig eingegeben wurde
        if ($secure_code == FALSE) {
            $info = "<br><div align=\"center\"><div class='savearea'>"._wrong_secure_code."</div></div>";
            $autoforward = FALSE;
        }
        
        //Checke ob MAP User mit eingegebener E-Mail Adresse existiert, falls Ja, versende MAIL, falls nein, gebe error aus.
        $check_uname = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE email = '$email'");
        $check_un = mysql_num_rows($check_uname);
        if ($check_un != "0" && $secure_code == TRUE) {
        	$qry_uname = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE email = '$email'");
        	while($uname = mysql_fetch_array($qry_uname)) {
	            //Definiere Platzhalter für E-Mail
		    	$Placeholder = array("AdminName" => $uname['name'],			    	
		    						 );    	
		    	//Sende E-Mail
		    	$Connection = mapmail($email, $uname['name'], $uname['user_id'], 
		    						  $email, $uname['name'], $uname['user_id'],
		    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
		    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '23004',
		    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
		    						  $AltSubject = FALSE, $AltBody = FALSE);
        	}
        	//Gebe positive Info aus
        	$info = "<br><div align=\"center\"><div class='boxsucess'>"._operate_forgot_uname_map_db_send_true."</div></div>";
            $autoforward = TRUE;        	
        	} elseif ($check_un == "0" && $SecureCodeError != "TRUE") {
        		$info = "<br><div align=\"center\"><div class='savearea'>"._operate_forgot_uname_map_db_empty."</div></div>";
            	$autoforward = FALSE;
        }  
        
        //Loggingfunktion, Übergabe der Werte: Benutzername vergessen für MAP User Auswertung
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $uname['user_id'];				//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_db_9";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $uname['server_id'];			//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $uname['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        
        //Weiterleitung nach 5 Sekunden! zu start (erfolgreich)
        if ($autoforward == TRUE) {
            autoforward("../operate/index.php?default",5);
        }  
        //Weiterleitung nach 3 Sekunden! zu operate (fehler)
        if ($autoforward == FALSE) {
            autoforward("../operate/index.php?section=forgot_uname_map",3);
        }        
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Benutzername vergessen für Admin Auswertung
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Formular Benutzername vergessen für Admin
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'forgot_uname_map':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_uname_map;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {      
        //Start definition eigentliche Funktionen dieser Section:

		//Lade Template
		$content_headers = array("head_on" => TRUE,
					 			 "head_type" => "default",
                  			     "head_value" => _operate_forgot_uname_map_head,
								 "navi_on" => TRUE,
								 "navi_type" => "forgot_uname_map",
								 );
    	$index = show("$dir/forgot_uname_map", array("discription" => _operate_forgot_uname_map_discription,
    									   			 "enter_email" => _operate_forgot_uname_map_enter_email,
    												 "enter_captcha_head" => _operate_forgot_uname_map_enter_sc,
    									             "enter_captcha" => recaptcha_get_html($GoogleReCaptchaPublicKey, FALSE)
                						   			 )); 

        //Loggingfunktion, Übergabe der Werte: Formular Benutzername vergessen für MAP User
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "operate_show_10";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = "";							//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = "";							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Formular Benutzername vergessen für Admin
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Startseite Passwort vergessen Funktion (Auswahl Server oder MAP User)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
	// Definition Seitentitel
	$seitentitel .= _pagetitel_operate_forgot_default_page;

    // Check ob User berechtigt ist diesen zu sehen, ansonsten wird Fehlermeldung angezeigt
	if (getPermOperateFunctions()) {     
        //Start definition eigentliche Funktionen dieser Section:
		
    	//Lade Template
    	$content_headers = array("head_on" => TRUE,
					 			 "head_type" => "default",
                  			     "head_value" => _operate_default_head,
								 "navi_on" => TRUE,
								 "navi_type" => "default",
								 );
    	$index = show("$dir/start", array("discription" => _operate_default_discription,
    									  "srv_head" => _operate_default_srv_head,
    									  "map_head" => _operate_default_map_head,
                                          "srv_name" => _operate_default_srv_name,
                                          "map_name" => _operate_default_map_name,
    									  "srv_pwd" => _operate_default_srv_pwd,
    	                                  "map_pwd" => _operate_default_map_pwd,
                						   ));

        //Lösche Einträge aus der Datenbank, wenn Password zwischenspeicher älter als 15 Minuten ist
		//Definiere Datum, wie alt die Logs sein düfen
		$max_days = time()-60*30;
		$unix_time = date("Y-m-d H:i:s",$max_days);
	
		//Hole alle Zwischenspeicher und Werte diese in einer While Schleife aus.
		$qry = mysql_query("SELECT id, date FROM `".$database_prefix."operate_pwd` WHERE date < '".$unix_time."'");
		while ($get = mysql_fetch_array($qry)) {
			//Wenn älter als 15 min, löschen!
			mysql_query("DELETE FROM `".$database_prefix."operate_pwd` WHERE id = '".$get['id']."' LIMIT 1");	
		}
        	
        //Ende definition eigentliche Funktionen dieser Section^^
    	} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_operate_functions."</div></div>";
			autoforward("../start/index.php",5);
	}
       
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Startseite Passwort vergessen Funktion (Auswahl Server oder MAP User)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
}
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
// **START** Lädt das Template
//************************************************************************************************//
include("../inc/layout.php");
//************************************************************************************************//
// **ENDE**  Lädt das Template
//************************************************************************************************//
//************************************************************************************************//
// **START** Lädt die Loggingfunktionen
//************************************************************************************************//
include("../inc/logging.php");
//************************************************************************************************//
// **ENDE**  Lädt die Loggingfunktionen
//************************************************************************************************//
?>