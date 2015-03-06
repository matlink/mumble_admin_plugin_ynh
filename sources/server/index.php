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
$dir = "server";
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
// **ENDE**  Massenmail an alle Reseller > Versand der MAILS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'mail_reseller_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_map_admin_mail_server_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("email_mass_reseller", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
		 
        // Eingaben speichern bzw. Ã¼bernehmen
        $server_id = $_POST["server_id"];
        $subject = $_POST["subject"];
        $text = $_POST["text"];
        $anzahl = FALSE;
        
        //DB auslesen Ã¼ber Server-Infos
        $qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE type_id = '1'");
        
        //Nur senden, wenn Felder nicht leer
        if ($subject != "" && $text != "") {
            //Beginne E-Mails zu versenden
        	$i = 0;
            while ($get = mysql_fetch_array($qry)) {
				//Hole User Info
				$admin = user_info($get['user_id']);
    			
            	//Sende Mail, wenn E-Mail vorhanden
    			if (isset($admin['email'])) {
	                //Definiere Platzhalter für E-Mail
				    $Placeholder = array("Subject" => $subject,
				    				     "Body" => $text,
				    					 "AdminName" => $_MAPUSER['teamtag'],			    	
				    					 );    
				    //Sende E-Mail
				    $Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
				    					  $admin['email'], $admin['name'], $admin['user_id'],
				    					  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    					  $WordWrap = "75", $IsHTML = TRUE, $template_id = '35012',
				    					  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    				      $AltSubject = FALSE, $AltBody = FALSE);
					//Erhöhe Zähler, wenn E-Mail vorhanden
    				$i++;
    			}
    				
    			//Setze Status
    			$autoforward = TRUE;  
            }
        	} else {
                $info = "<br><div align=\"center\"><div class='savearea'>"._send_mail_server_error."</div></div>";
                $autoforward = FALSE;
        }
        
        //Loggingfunktion, Übergabe der Werte: Massenmail an globale MAP Admins > Versand der MAILS
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "server_db_1";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $anzahl;						//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $subject;						//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

		//Weiterleitung
        if ($autoforward == TRUE) {
        	$info = "<br><div align=\"center\"><div class='boxsucess'>"._map_admin_mail_server_message_1 . $i . _map_admin_mail_server_message_2."</div></div>";
			autoforward("../server/index.php?section=show_server&server_id=$server_id",5);
        	} elseif ($autoforward == FALSE) {
            	autoforward("../server/index.php?section=mail_reseller&server_id=$server_id",3);
        } 
        
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Massenmail an alle Reseller > Versand der MAILS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Massenmail an alle Reseller > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'mail_reseller':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_map_admin_mail_server;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("email_mass_reseller", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
		//Hole Server Infos
		$server = server_info($_GET['server_id']);

        //Ausgabe
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _map_admin_mail_server_head,
								 "navi_on" => TRUE,
								 "navi_type" => "mail_reseller",
								 );
        $index = show("$dir/mail_reseller", array("name" => $server['name'],
                                                  "subject" => _map_admin_mail_server_subject,
                                                  "text" => _map_admin_mail_server_text,
                                                  "server_id" => $server['server_id']
                                                  ));
                                                         
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Massenmail an alle Reseller > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Massenmail an Admins des Servers > Versand der MAILS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'admin_mail_server_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_admin_mail_server_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("email_mass_customer", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
		
        // Eingaben speichern bzw. Übernehmen
        $server_id = $_POST["server_id"];
        $subject = $_POST["subject"];
        $text = $_POST["text"];
        $anzahl = FALSE;
        
        //DB auslesen Ã¼ber Server-Infos
        $qry = mysql_query("SELECT * FROM `".$database_prefix."user_servers` WHERE server_id = '".$server_id."'");
        
        //Nur senden, wenn Felder nicht leer
        if ($subject != "" && $text != "") {
        	//Beginne E-Mails zu versenden
        	$i = 0;
            while ($get = mysql_fetch_array($qry)) {
				//Hole User Info
				$admin = user_info($get['user_id']);
				
            	//Sende Mail, wenn E-Mail vorhanden
    			if (isset($admin['email'])) {
	                //Definiere Platzhalter für E-Mail
				    $Placeholder = array("Subject" => $subject,
				    				     "Body" => $text,
				    					 "AdminName" => $_MAPUSER['teamtag'],			    	
				    					 );    
				    //Sende E-Mail
				    $Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
				    					  $admin['email'], $admin['name'], $admin['user_id'],
				    					  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    					  $WordWrap = "75", $IsHTML = TRUE, $template_id = '35011',
				    					  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    				      $AltSubject = FALSE, $AltBody = FALSE);
					//Erhöhe Zähler, wenn E-Mail vorhanden
    				$i++;
    			}
    				
    			//Setze Status
    			$autoforward = TRUE;  
            }
            } else {
                $info = "<br><div align=\"center\"><div class='savearea'>"._send_mail_server_error."</div></div>";
                $autoforward = FALSE;
        }
        
        //Loggingfunktion, Übergabe der Werte: Massenmail an MAP User > Versand der MAILS
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "server_db_2";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $anzahl;						//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $subject;						//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

		//Weiterleitung
        if ($autoforward == TRUE) {
        	$info = "<br><div align=\"center\"><div class='boxsucess'>"._admin_mail_server_message_1 . $i . _admin_mail_server_message_2."</div></div>";
			autoforward("../server/index.php?section=show_server&server_id=$server_id",5);
        	} elseif ($autoforward == FALSE) {
            	autoforward("../server/index.php?section=admin_mail_server&server_id=$server_id",3);
        } 
        
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Massenmail an Admins des Servers > Versand der MAILS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Massenmail an Admins des Servers > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'admin_mail_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_admin_mail_server;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("email_mass_customer", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Server Info
        $server = server_info($_GET['server_id']);

        //Ausgabe
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _admin_mail_server_head . $server['name'],
								 "navi_on" => TRUE,
								 "navi_type" => "admin_mail_server",
								 );
        $index = show("$dir/admin_mail_server", array("name" => $server['name'],
                                                      "subject" => _admin_mail_server_subject,
                                                      "text" => _admin_mail_server_text,
                                                      "server_id" => $server['server_id']
                                                      ));
                                                         
        //Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Massenmail an Admins des Servers > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Massenmail an User > Versand der MAILS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'mass_mail_server_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_mass_mail_server_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("email_mass_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
		 
        //Eingaben speichern bzw. Übernehmen
        $server_id = $_POST["server_id"];
        $subject = $_POST["subject"];
        $text = $_POST["text"];

        //Nur senden, wenn Felder nicht leer
        if ($subject != "" && $text != "") {
			//Hole Server Info
			$server = server_info($server_id);
			//Gebe Userliste aus, wenn Server Online ist
			if ($server['online']) {
				$i = 0;
				//Gebe die User des Servers aus
    			foreach (array_keys($server['registered_users']) as $user_id) {
    				//Hole UserInfo
    				$user = getRegistration($server_id, $user_id);

    				//Sende Mail, wenn E-Mail vorhanden
    				if (isset($user['email'])) {
	                    //Definiere Platzhalter für E-Mail
				    	$Placeholder = array("Subject" => $subject,
				    						 "Body" => $text,
				    						 "AdminName" => $_MAPUSER['teamtag'],			    	
				    						 );    	
				    	//Sende E-Mail
				    	$Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
				    						  $user['email'], $user['name'], FALSE,
				    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '35010',
				    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    						  $AltSubject = FALSE, $AltBody = FALSE);
						//Erhöhe Zähler, wenn E-Mail vorhanden
    					$i++;
    				}
    				
    				//Setze Status
    				$autoforward = TRUE;  
	    		}
			}
			} else {
                $info = "<br><div align=\"center\"><div class='savearea'>"._send_mail_server_error."</div></div>";
                $autoforward = FALSE;
        }
        
        //Loggingfunktion, Übergabe der Werte: Massenmail an Server User > Versand der MAILS
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "server_db_3";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $i;							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $subject;						//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

        //Weiterleitung
        if ($autoforward == TRUE) {
        	$info = "<br><div align=\"center\"><div class='boxsucess'>"._mass_mail_server_message_1 . $i . _mass_mail_server_message_2."</div></div>";
			autoforward("../server/index.php?section=show_server&server_id=$server_id",5);
        	} elseif ($autoforward == FALSE) {
            	autoforward("../server/index.php?section=mass_mail_server&server_id=$server_id",3);
        } 
        
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Massenmail an User > Versand der MAILS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Massenmail an User > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'mass_mail_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_mass_mail_server;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("email_mass_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Server Infos
        $server = server_info($_GET['server_id']);

        //Ausgabe
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _mass_mail_server_head . $server['name'],
								 "navi_on" => TRUE,
								 "navi_type" => "mass_mail_server",
								 );
        $index = show("$dir/mass_mail_server", array("name" => $server['name'],
                                                     "subject" => _mass_mail_server_subject,
                                                     "text" => _mass_mail_server_text,
                                                     "server_id" => $server['server_id']
                                                     ));
                                                        
        //Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Massenmail an User > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Weiterleitung der Create-Funktionen (Server-User, MAP User, Server)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_create_something;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (get_perms("create_user", FALSE) OR get_perms("create_admin", FALSE) OR get_perms("create_server", FALSE) OR get_perms("perm_create_group", FALSE)) {
 
		//Prüfe ob nur eine von vier Berechtigungen eintrifft, wenn direkte Weiterleitung!
        if (get_perms("create_user", FALSE) == TRUE AND get_perms("create_admin", FALSE) == FALSE AND get_perms("create_server", FALSE) == FALSE AND get_perms("perm_create_group", FALSE) == FALSE) {
            header("Location: ../user/index.php?section=create_user");
        }
        if (get_perms("create_admin", FALSE) == FALSE AND get_perms("create_admin", FALSE) == TRUE AND get_perms("create_server", FALSE) == FALSE AND get_perms("perm_create_group", FALSE) == FALSE) {
            header("Location: ../admin/index.php?section=create_admin");
        }
        if (get_perms("create_server", FALSE) == FALSE AND get_perms("create_admin", FALSE) == FALSE AND get_perms("create_server", FALSE) == TRUE AND get_perms("perm_create_group", FALSE) == FALSE) {
            header("Location: ../server/index.php?section=create_server");
        }
        if (get_perms("perm_create_group", FALSE) == FALSE AND get_perms("create_admin", FALSE) == FALSE AND get_perms("create_server", FALSE) == FALSE AND get_perms("perm_create_group", FALSE) == TRUE) {
            header("Location: ../permissions/index.php?section=create_group");
        }
            
		//Wenn Berechtigung für User erstellen, zeige Icon an
        if (get_perms("create_user", FALSE) == TRUE) {
        	$c_user_image = "<center><a href=\"../user/index.php?section=create_user\" target=\"_self\"><img src=\"../inc/tpl/".$tpldir."/images/create_server_user.png\" alt=\"\" border=\"0\"></a></center>";
            $c_user_name = "<center><a href=\"../user/index.php?section=create_user\" target=\"_self\">"._server_create_start_server_user_name."</a></center><br>";
			} else {
				$c_user_image = "";
				$c_user_name = "";                            
		}
		//Wenn Berechtigung für Admin erstellen, zeige Icon an
		if (get_perms("create_admin", FALSE) == TRUE) {
			$c_admin_image = "<center><a href=\"../admin/index.php?section=create_admin\" target=\"_self\"><img src=\"../inc/tpl/".$tpldir."/images/create_map_user.png\" alt=\"\" border=\"0\"></a></center>";
			$c_admin_name = "<center><a href=\"../admin/index.php?section=create_admin\" target=\"_self\">"._server_create_start_map_user_name."</a></center><br>";
			} else {
				$c_admin_image = "";
				$c_admin_name = "";                            
		}
   		//Wenn Berechtigung für Server erstellen, zeige Icon an
		if (get_perms("create_server", FALSE) == TRUE) {
			$c_server_image = "<center><a href=\"../server/index.php?section=create_server\" target=\"_self\"><img src=\"../inc/tpl/".$tpldir."/images/server.png\" alt=\"\" border=\"0\"></a></center>";
			$c_server_name = "<center><a href=\"../server/index.php?section=create_server\" target=\"_self\">"._server_create_start_server_name."</a></center><br>";
			} else {
				$c_server_image = "";
				$c_server_name = "";                            
		}
    	//Wenn Berechtigung für Gruppe erstellen, zeige Icon an
		if (get_perms("perm_create_group", FALSE) == TRUE) {
			$c_group_image = "<center><a href=\"../permissions/index.php?section=create_group\" target=\"_self\"><img src=\"../inc/tpl/".$tpldir."/images/group_perm.png\" alt=\"\" border=\"0\"></a></center>";
			$c_group_name = "<center><a href=\"../permissions/index.php?section=create_group\" target=\"_self\">"._server_create_start_group_name."</a></center>";
			} else {
				$c_group_image = "";
				$c_group_name = "";                            
		}
		
		//Ausgabe für mind. 2 Icons
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
					             "head_value" => _server_create_start_head,
								 "navi_on" => TRUE,
								 "navi_type" => "default",
								 );
		$index = show("$dir/create_start", array("discription" => _server_create_start_discription,
                                                 "create_server_user_image" => $c_user_image,
                                                 "create_server_user_name" => $c_user_name,
                                                 "create_map_user_image" => $c_admin_image,
                                                 "create_map_user_name" => $c_admin_name,
                                                 "create_server_image" => $c_server_image,
                                                 "create_server_name" => $c_server_name,
                    							 "create_group_image" => $c_group_image,
                    							 "create_group_name" => $c_group_name,
                                                 ));
                 
		//Ende definition eigentliche Funktionen dieser Section^^
    	} else {
    		$perm_error = "<br><div align=\"center\"><div class='savearea'>"._perm_error_group_user."</div></div>";
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Weiterleitung der Create-Funktion
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server erstellen - create server > in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_server_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_create_server_db;
    $server_id = FALSE;
    
	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("create_server", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Setze Werte aus Formular
    	if (!isset($_POST['certrequired'])) $_POST['certrequired'] = 'FALSE'; 
    	if (!isset($_POST['obfuscate'])) $_POST['obfuscate'] = 'FALSE';
    	if (!isset($_POST['rememberchannel'])) $_POST['rememberchannel'] = 'FALSE';
    	if (!isset($_POST['allowhtml'])) $_POST['allowhtml'] = 'FALSE';
    	if (!isset($_POST['allowping'])) $_POST['allowping'] = 'FALSE'; 
    	if (!isset($_POST['sendversion'])) $_POST['sendversion'] = 'FALSE';
    	if (!isset($_POST['bonjour'])) $_POST['bonjour'] = 'FALSE'; 
    	
    	//Hole Werte aus Formular
    	$form = array("name" => $_POST['name'],
                      "guarantor" => $_POST['guarantor'],
                      "payed_until_y" => $_POST['payed_until_y'],
				      "payed_until_m" => $_POST['payed_until_m'],
				      "payed_until_d" => $_POST['payed_until_d'],
				      "payed_until_h" => $_POST['payed_until_h'],
				      "payed_until_i" => $_POST['payed_until_i'],
				      "payed_until_s" => $_POST['payed_until_s'],
                      "users" => $_POST['users'],
				      "port" => $_POST['port'],
					  "serverpassword" => $_POST['serverpassword'],
					  "timeout" => $_POST['timeout'],                      
					  "channelname" => $_POST['channelname'],                
					  "username" => $_POST['username'],                     
					  "defaultchannel" => $_POST['defaultchannel'],          
					  "registerHostname" => $_POST['registerHostname'],    
				      "registerName" => $_POST['registerName'],             
					  "registerPassword" => $_POST['registerPassword'],     
					  "registerUrl" => $_POST['registerUrl'],    
    				  "registerLocation" => $_POST['registerLocation'],  
    				  "channelnestinglimit" => $_POST['channelnestinglimit'],
			    	  "opusthreshold" => $_POST['opusthreshold'],
			    	  "suggestpositional" => $_POST['suggestpositional'],
			    	  "suggestpushtotalk" => $_POST['suggestpushtotalk'],
			    	  "suggestversion" => $_POST['suggestversion'],        
					  "bandwidth" => $_POST['bandwidth'],                    
					  "imagemessagelength" => $_POST['imagemessagelength'], 
					  "textmessagelength" => $_POST['textmessagelength'],    
					  "usersperchannel" => $_POST['usersperchannel'],
					  "certrequired" => $_POST['certrequired'],            
  					  "obfuscate" => $_POST['obfuscate'],              
  					  "rememberchannel" => $_POST['rememberchannel'],  
					  "allowhtml" => $_POST['allowhtml'],              
					  "allowping" => $_POST['allowping'],
    				  "sendversion" => $_POST['sendversion'],
			    	  "bonjour" => $_POST['bonjour'],
					  "welcometext" => $_POST['welcometext'],
                      "discription" => $_POST['discription'],
    				  );
		
    	//Checke ob eingaben Korrekt und erstelle Server
    	if ($form['name'] != "" && checkPortValue($form['port'], FALSE)) {
    				  
	    	//Lade neuen Server mittels Slice
	    	$server_id = newServer();
	    	
	    	// Wenn Server erfolgreich erstellt werden konnte, erstelle weitere Daten
	    	if ($server_id != FALSE) {
		    	//Speichere MAP interne Daten zum Server
			   	$saveSRV = mysql_query("INSERT INTO `".$database_prefix."servers` (`server_id`, `name`, `discription`, `guarantor`, `started`, `payed_until`, `starts`) VALUES ('$server_id', '".$form['name']."', '".$form['discription']."', '".$form['guarantor']."', '".$aktdate."', '".$form['payed_until_y']."-".$form['payed_until_m']."-".$form['payed_until_d']." ".$form['payed_until_h'].":".$form['payed_until_i'].":".$form['payed_until_s']."', '0')");
			   	
		    	//Erstelle Code für externen Channelviewer in DB
		        $saftey_code = pw_gen($a = '5', $b = '5', $num = '5', $spec = '0');
		        $saveCV = mysql_query("INSERT INTO `".$database_prefix."channelviewer` (`server_id`, `colour`, `font_colour`, `width`, `height`, `external_on`, `safety_code`) VALUES ('$server_id', 'FFFFFF', '000000', '200', '500', '1', '".$saftey_code."')");
		        
		        //Überprüfe und speichere Serverconf
		    	if (is_numeric($form['users'])) { setServer($server_id, $key = "users", $form['users']);}
		    	if (checkPortValue($form['port'], FALSE)) { setServer($server_id, $key = "port", $form['port']);}
				setServer($server_id, $key = "serverpassword", $form['serverpassword']);
		    	if (is_numeric($form['timeout'])) { setServer($server_id, $key = "timeout", $form['timeout']);}
		    	setServer($server_id, $key = "channelname", $form['channelname']);
		    	setServer($server_id, $key = "username", $form['username']);
		    	if (is_numeric($form['defaultchannel'])) { setServer($server_id, $key = "defaultchannel", $form['defaultchannel']);}
		    	setServer($server_id, $key = "registerhostname", $form['registerHostname']);
		    	setServer($server_id, $key = "registername", $form['registerName']);
				setServer($server_id, $key = "registerpassword", $form['registerPassword']);
		    	setServer($server_id, $key = "registerurl", $form['registerUrl']);
		    	setServer($server_id, $key = "registerlocation", $form['registerLocation']);
		    	if (is_numeric($form['bandwidth'])) { setServer($server_id, $key = "bandwidth", $form['bandwidth']);}
		    	if (is_numeric($form['imagemessagelength'])) { setServer($server_id, $key = "imagemessagelength", $form['imagemessagelength']);}
		    	if (is_numeric($form['textmessagelength'])) { setServer($server_id, $key = "textmessagelength", $form['textmessagelength']);}
		    	if (is_numeric($form['usersperchannel'])) { setServer($server_id, $key = "usersperchannel", $form['usersperchannel']);}
		   		setServer($server_id, $key = "channelnestinglimit", $form['channelnestinglimit']);
		   		setServer($server_id, $key = "opusthreshold", $form['opusthreshold']);
		   		setServer($server_id, $key = "suggestpositional", $form['suggestpositional']);
		   		setServer($server_id, $key = "suggestpushtotalk", $form['suggestpushtotalk']);
		   		setServer($server_id, $key = "suggestversion", $form['suggestversion']);
		    	setServer($server_id, $key = "certrequired", $form['certrequired']);
		   		setServer($server_id, $key = "obfuscate", $form['obfuscate']);
		   		setServer($server_id, $key = "rememberchannel", $form['rememberchannel']);
		   		setServer($server_id, $key = "allowhtml", $form['allowhtml']);
		   		setServer($server_id, $key = "allowping", $form['allowping']);
		   		setServer($server_id, $key = "sendversion", $form['sendversion']);
		   		setServer($server_id, $key = "bonjour", $form['bonjour']);
		    	setServer($server_id, $key = "welcometext", $form['welcometext']);
		    	setServer($server_id, $key = "host", defineHostValue());
		    	
		    	$autoforward = TRUE;
	    	}
    	}
    	
        //Loggingfunktion, Übergabe der Werte: Server erstellt
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "server_db_4";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $form['name'];			     	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        
    	//Meldung, wenn nichts geändert wurde!
        if ($autoforward == FALSE) {
            $info = "<br><div align=\"center\"><div class='savearea'>"._create_server_fail."</div></div>";
            autoforward("../server/index.php?section=create_server",5);
        }
        
    	//Meldung, wenn was geändert wurde!
        if ($autoforward == TRUE) {
            $info = "<br><div align=\"center\"><div class='boxsucess'>"._create_server_success_1.$form['name']._create_server_success_2.$form['port']._create_server_success_3."</div></div>";
            autoforward("../server/index.php?section=show_server&server_id=$server_id",2);
        }
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Server erstellen - create server > in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server erstellen - create server > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_create_server;

	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("create_server", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
            
    	//Definiere IP des Servers
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '34'");
        $host = mysql_fetch_array($qry);
        if (isset($host[2]) && $host[2] != "") {
        	$ip_value = $host[2];
        	} else {
        		$ip_value = $_SERVER['SERVER_NAME'];
        }
		
    	//MAP Admins ausgeben
        $admins = getAdmins();
        foreach ($admins as $user_id) {
        	$admin = user_info($user_id);
            if ($admin['user_id'] == $_MAPUSER['user_id']) {
                $list .= "<option value=\"".$admin['user_id']."\" selected=\"selected\">".$admin['teamtag']."</option>\n";
                } else {
                    $list .= "<option value=\"".$admin['user_id']."\">".$admin['teamtag']."</option>\n";
            }
        }
        
        //DB nach vorhandenen Ports auslesen um bei der Server erstellen Seite einen Portvorschlag anbieten zu können        
        $port_value = definePortValue(FALSE);
		
		//Definiere den Wert von "nächstes Jahr"
		$nY = date("Y");
		$nY = $nY + "1";

		//suggestpositional
		$output_sp = FALSE;
		$output_sp .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
		$output_sp .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
		$output_sp .= "<option value=\"\" selected=\"selected\">". _edit_server_val_no_message ."</option>\n";
		//suggestpushtotalk
		$output_sptt = FALSE;
		$output_sptt .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
		$output_sptt .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
		$output_sptt .= "<option value=\"\" selected=\"selected\">". _edit_server_val_no_message ."</option>\n";
		//suggestversion
		$output_sv = FALSE;
		$output_sv .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
		$output_sv .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
		$output_sv .= "<option value=\"\" selected=\"selected\">". _edit_server_val_no_message ."</option>\n";
		
		//Hole Werte aus Formular
    	$server = array("name" => "",
                      "guarantor" => $list,
                      "payed_until_y" => generiere_auswahlboxen("2010", "2025", $nY),
				      "payed_until_m" => generiere_auswahlboxen("1", "12", date("n")),
				      "payed_until_d" => generiere_auswahlboxen("1", "31", date("j")),
				      "payed_until_h" => generiere_auswahlboxen("0", "23", date("G")),
				      "payed_until_i" => generiere_auswahlboxen("0", "59", date("i")),
				      "payed_until_s" => generiere_auswahlboxen("0", "59", date("s")),
                      "users" => "10",
				      "port" => $port_value,
					  "serverpassword" => "",
					  "timeout" => "30",                      
					  "channelname" => "[ \\-=\\w\\#\\[\\]\\{\\}\\(\\)\\@\\|]+",                
					  "username" => "[-=\\w\\[\\]\\{\\}\\(\\)\\@\\|\\.]+",                     
					  "defaultchannel" => "0",          
					  "registerHostname" => $ip_value,    
				      "registerName" => "",             
					  "registerPassword" => "",     
					  "registerUrl" => "",
    				  "registerLocation" => "",
    				  "channelnestinglimit" => "10",
			    	  "opusthreshold" => "100",
			    	  "suggestpositional" => $output_sp,
			    	  "suggestpushtotalk" => $output_sptt,
			    	  "suggestversion" => $output_sv,               
					  "bandwidth" => "134400",                    
					  "imagemessagelength" => "131072", 
					  "textmessagelength" => "5000",    
					  "usersperchannel" => "0",
					  "certrequired" => "checked",            
  					  "obfuscate" => "checked",              
  					  "rememberchannel" => "checked",  
					  "allowhtml" => "checked",              
					  "allowping" => "checked",
			    	  "sendversion" => "checked",
			    	  "bonjour" => "checked",
					  "welcometext" => "This Server is hosted and powered by Mumb1e Admin Plugin!",
                      "discription" => "This Server stands in Default settings. Please edit!",
    				  );
		
    		
		//channelnestinglimit
		$i_cnl = "0";
		$output_cnl = FALSE;
    	while($i_cnl <= 15) {
    		if (is_numeric($server['channelnestinglimit'])) {
			    if ($server['channelnestinglimit'] == $i_cnl) {
			  	    $output_cnl .= "<option value=\"".$i_cnl."\" selected=\"selected\">".$i_cnl."</option>\n";
			    	} else {
			  	       	$output_cnl .= "<option value=\"".$i_cnl."\">".$i_cnl."</option>\n";
			    }
    			} else {
					if ($i_cnl == "10") {
		    			$output_cnl .= "<option value=\"".$i_cnl."\" selected=\"selected\">".$i_cnl."</option>\n";
		    			} else {
		  	       			$output_cnl .= "<option value=\"".$i_cnl."\">".$i_cnl."</option>\n";
		    		}
    		}
		    $i_cnl++;
		}
		$server['channelnestinglimit'] = $output_cnl;
		//opusthreshold
		$i_oth = "0";
		$output_oth = FALSE;
    	while($i_oth <= 100) {
    		if (is_numeric($server['opusthreshold'])) {
			    if ($server['opusthreshold'] == $i_oth) {
			  	    $output_oth .= "<option value=\"".$i_oth."\" selected=\"selected\">".$i_oth."%</option>\n";
			    	} else {
			  	       	$output_oth .= "<option value=\"".$i_oth."\">".$i_oth."%</option>\n";
			    }
    			} else {
					if ($i_oth == "100") {
		    			$output_oth .= "<option value=\"".$i_oth."\" selected=\"selected\">".$i_oth."%</option>\n";
		    			} else {
		  	       			$output_oth .= "<option value=\"".$i_oth."\">".$i_oth."%</option>\n";
		    		}
    		}
		    $i_oth++;
		}
		$server['opusthreshold'] = $output_oth;
		
		//Ausgabe
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
								 "head_value" => _create_server_head,
								 "navi_on" => TRUE,
								 "navi_type" => "create_server",
								 );
		$index = show("$dir/create_server", array("name" => $server['name'],
                                                "guarantor" => $list,
                                                "payed_until_y" => $server['payed_until_y'],
												"payed_until_m" => $server['payed_until_m'],
												"payed_until_d" => $server['payed_until_d'],
												"payed_until_h" => $server['payed_until_h'],
												"payed_until_i" => $server['payed_until_i'],
												"payed_until_s" => $server['payed_until_s'],
                                                "users" => $server['users'],
												"port" => $server['port'],
												"serverpassword" => $server['serverpassword'],
												"timeout" => $server['timeout'],                      
												"channelname" => $server['channelname'],                
												"username" => $server['username'],                     
												"defaultchannel" => $server['defaultchannel'],          
												"registerHostname" => $server['registerHostname'],    
											    "registerName" => $server['registerName'],             
												"registerPassword" => $server['registerPassword'],     
												"registerUrl" => $server['registerUrl'],        
												"registerLocation" => $server['registerLocation'],
												"channelnestinglimit" => $server['channelnestinglimit'],
												"opusthreshold" => $server['opusthreshold'],
												"suggestpositional" => $server['suggestpositional'],
												"suggestpushtotalk" => $server['suggestpushtotalk'],
												"suggestversion" => $server['suggestversion'],       
												"bandwidth" => $server['bandwidth'],                    
												"imagemessagelength" => $server['imagemessagelength'], 
												"textmessagelength" => $server['textmessagelength'],    
												"usersperchannel" => $server['usersperchannel'],
												"certrequired" => $server['certrequired'],            
				  								"obfuscate" => $server['obfuscate'],              
				  								"rememberchannel" => $server['rememberchannel'],  
												"allowhtml" => $server['allowhtml'],              
												"allowping" => $server['allowping'],
												"sendversion" => $server['sendversion'],              
												"bonjour" => $server['bonjour'],
												"welcometext" => $server['welcometext'],
                                                "discription_text" => $server['discription'],
                                                "head_name" => _edit_server_name,
                                                "head_guarantor" => _edit_server_guarantor,
												"head_payed_until" => _edit_server_payed_until,
                                                "head_users" => _edit_server_users,
                                                "head_port" => _edit_server_port,
												"head_serverpassword" => _edit_server_serverpassword,
												"head_timeout" => _edit_server_timeout,
												"head_channelname" => _edit_server_channelname,
												"head_username" => _edit_server_username,
												"head_defaultchannel" => _edit_server_defaultchannel,
												"head_registerHostname" => _edit_server_registerHostname,
												"head_registerName" => _edit_server_registerName,
												"head_registerPassword" => _edit_server_registerPassword,
												"head_registerUrl" => _edit_server_registerUrl,
												"head_registerLocation" => _edit_server_registerLocation,
												"head_bandwidth" => _edit_server_bandwidth,
												"head_imagemessagelength" => _edit_server_imagemessagelength,
												"head_textmessagelength" => _edit_server_textmessagelength,
												"head_usersperchannel" => _edit_server_usersperchannel,
												"head_channelnestinglimit" => _edit_server_channelnestinglimit,
												"head_opusthreshold" => _edit_server_opusthreshold,
												"head_suggestpositional" => _edit_server_suggestpositional,
												"head_suggestpushtotalk" => _edit_server_suggestpushtotalk,
												"head_suggestversion" => _edit_server_suggestversion,
												"head_certrequired" => _edit_server_certrequired,
												"head_obfuscate" => _edit_server_obfuscate,
												"head_rememberchannel" => _edit_server_rememberchannel,
												"head_allowhtml" => _edit_server_allowhtml,
												"head_allowping" => _edit_server_allowping,
												"head_sendversion" => _edit_server_sendversion,
												"head_bonjour" => _edit_server_bonjour,
												"head_welcometext" => _edit_server_welcometext,
                                                "head_discription" => _edit_server_discription,			
                                                "discription_name" => _edit_server_discr_name,
                                                "discription_guarantor" => _edit_server_discr_guarantor,
												"discription_payed_until" => _edit_server_discr_payed_until,
                                                "discription_users" => _edit_server_discr_users,
                                                "discription_port" => _edit_server_discr_port,
												"discription_serverpassword" => _edit_server_discr_serverpassword,
												"discription_timeout" => _edit_server_discr_timeout,
												"discription_channelname" => _edit_server_discr_channelname,
												"discription_username" => _edit_server_discr_username,
												"discription_defaultchannel" => _edit_server_discr_defaultchannel,
												"discription_registerHostname" => _edit_server_discr_registerHostname,
												"discription_registerName" => _edit_server_discr_registerName,
												"discription_registerPassword" => _edit_server_discr_registerPassword,
												"discription_registerUrl" => _edit_server_discr_registerUrl,
												"discription_registerLocation" => _edit_server_discr_registerLocation,
												"discription_bandwidth" => _edit_server_discr_bandwidth,
												"discription_imagemessagelength" => _edit_server_discr_imagemessagelength,
												"discription_textmessagelength" => _edit_server_discr_textmessagelength,
												"discription_usersperchannel" => _edit_server_discr_usersperchannel,
												"discription_sslca" => _edit_server_discr_sslca,
												"discription_sslcert" => _edit_server_discr_sslcert,
												"discription_sslkey" => _edit_server_discr_sslkey,
												"discription_sslpassphrase" => _edit_server_discr_sslpassphrase,
												"discription_channelnestinglimit" => _edit_server_discr_channelnestinglimit,
												"discription_opusthreshold" => _edit_server_discr_opusthreshold,
												"discription_suggestpositional" => _edit_server_discr_suggestpositional,
												"discription_suggestpushtotalk" => _edit_server_discr_suggestpushtotalk,
												"discription_suggestversion" => _edit_server_discr_suggestversion,
												"discription_certrequired" => _edit_server_discr_certrequired,
												"discription_obfuscate" => _edit_server_discr_obfuscate,
												"discription_rememberchannel" => _edit_server_discr_rememberchannel,
												"discription_allowhtml" => _edit_server_discr_allowhtml,
												"discription_allowping" => _edit_server_discr_allowping,
												"discription_sendversion" => _edit_server_discr_sendversion,
												"discription_bonjour" => _edit_server_discr_bonjour,
												"discription_welcometext" => _edit_server_discr_welcometext,
                                                "discription_discription" => _edit_server_discr_discription,
												"variable" => _edit_server_variable,
												"defaultvalue" => _edit_server_defaultvalue,
												"discription" => _edit_server_discriptions,
                                                ));
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Server erstellen - create server > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server editieren - edit server > in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_server_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_edit_server_db;
    $val_error = FALSE;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("edit_server", FALSE)) {
    	
    	//Setze Werte aus Formular
    	if (!isset($_POST['certrequired'])) $_POST['certrequired'] = 'FALSE';
    	if (!isset($_POST['obfuscate'])) $_POST['obfuscate'] = 'FALSE';
    	if (!isset($_POST['rememberchannel'])) $_POST['rememberchannel'] = 'FALSE';
    	if (!isset($_POST['allowhtml'])) $_POST['allowhtml'] = 'FALSE';
    	if (!isset($_POST['allowping'])) $_POST['allowping'] = 'FALSE';
    	if (!isset($_POST['sendversion'])) $_POST['sendversion'] = 'FALSE';
    	if (!isset($_POST['bonjour'])) $_POST['bonjour'] = 'FALSE';
    	
    	//Hole Werte aus Formular
    	$form = array("server_id" => $_POST['server_id'],
					  "name" => $_POST['name'],
                      "guarantor" => $_POST['guarantor'],
                      "payed_until_y" => $_POST['payed_until_y'],
				      "payed_until_m" => $_POST['payed_until_m'],
				      "payed_until_d" => $_POST['payed_until_d'],
				      "payed_until_h" => $_POST['payed_until_h'],
				      "payed_until_i" => $_POST['payed_until_i'],
				      "payed_until_s" => $_POST['payed_until_s'],
                      "users" => $_POST['users'],
				      "port" => $_POST['port'],
					  "serverpassword" => $_POST['serverpassword'],
					  "timeout" => $_POST['timeout'],                      
					  "channelname" => $_POST['channelname'],                
					  "username" => $_POST['username'],                     
					  "defaultchannel" => $_POST['defaultchannel'],          
					  "registerHostname" => $_POST['registerHostname'],    
				      "registerName" => $_POST['registerName'],             
					  "registerPassword" => $_POST['registerPassword'],     
					  "registerUrl" => $_POST['registerUrl'],  
    				  "registerLocation" => $_POST['registerLocation'],               
					  "bandwidth" => $_POST['bandwidth'],                    
					  "imagemessagelength" => $_POST['imagemessagelength'], 
					  "textmessagelength" => $_POST['textmessagelength'],    
					  "usersperchannel" => $_POST['usersperchannel'],
			    	  "sslca" => $_POST['sslca'],
			    	  "sslcert" => $_POST['sslcert'],
			    	  "sslkey" => $_POST['sslkey'],
			    	  "sslpassphrase" => $_POST['sslpassphrase'],
			    	  "channelnestinglimit" => $_POST['channelnestinglimit'],
			    	  "opusthreshold" => $_POST['opusthreshold'],
			    	  "suggestpositional" => $_POST['suggestpositional'],
			    	  "suggestpushtotalk" => $_POST['suggestpushtotalk'],
			    	  "suggestversion" => $_POST['suggestversion'], 
					  "certrequired" => $_POST['certrequired'],            
  					  "obfuscate" => $_POST['obfuscate'],              
  					  "rememberchannel" => $_POST['rememberchannel'],  
					  "allowhtml" => $_POST['allowhtml'],              
					  "allowping" => $_POST['allowping'],
    				  "sendversion" => $_POST['sendversion'],
    				  "bonjour" => $_POST['bonjour'],
					  "welcometext" => $_POST['welcometext'],
                      "discription" => $_POST['discription'],
    				  );
        
        //Hole Date des Servers
    	$server = server_info($_POST['server_id']);

    	//Vergleiche und Update ggf. Name
    	if ($form['name'] != $server['name'] && perm_handler("server_conf_name", FALSE)) {
    		mysql_query("UPDATE `".$database_prefix."servers` SET name = '".$form['name']."' WHERE server_id = '".$form['server_id']."'");
    		$true_change = TRUE;
    	}
	    	
    	//Vergleiche und Update ggf. guarantor
    	if ($form['guarantor'] != $server['guarantor'] && perm_handler("server_conf_guarantor", FALSE)) {
    		mysql_query("UPDATE `".$database_prefix."servers` SET guarantor = '".$form['guarantor']."' WHERE server_id = '".$form['server_id']."'");
    		$true_change = TRUE;
    	}
	    	
    	//Vergleiche und Update ggf. payed_until
    	$chk_payed = checkdate($form['payed_until_m'], $form['payed_until_d'], $form['payed_until_y']);
    	$payed_until_new = $form['payed_until_y']."-".$form['payed_until_m']."-".$form['payed_until_d']." ".$form['payed_until_h'].":".$form['payed_until_i'].":".$form['payed_until_s'];
    	if ($chk_payed == TRUE && $payed_until_new != $server['payed_until'] && perm_handler("server_conf_payed_until", FALSE)) {
    		mysql_query("UPDATE `".$database_prefix."servers` SET payed_until = '".$payed_until_new."' WHERE server_id = '".$form['server_id']."'");
    		$true_change = TRUE;
    		} elseif ($chk_payed == FALSE && perm_handler("server_conf_payed_until", FALSE)) {
    			$val_error = TRUE;
    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_date_payed_until."</div></div>";
    	}
    	
    	//Vergleiche und Update ggf. discription
	    if ($form['discription'] != $server['discription'] && perm_handler("server_conf_discription", FALSE)) {
	    	mysql_query("UPDATE `".$database_prefix."servers` SET discription = '".$form['discription']."' WHERE server_id = '".$form['server_id']."'");
	    	$true_change = TRUE;
	    }
    	
    	//Ändere Server-Spezifische Vaiablen, wenn dieser Online ist (um Slice error zu vermeiden)
    	if (($server['online'] == FALSE AND $_SLICE_ERR == FALSE) OR $server['online'] == TRUE) {
	    
	    	//Vergleiche und Update ggf. users
	    	if ($form['users'] != $server['users'] && perm_handler("server_conf_users", FALSE) && is_numeric($form['users'])) {
	    		$setServer = setServer($form['server_id'], $key = "users", $form['users']);
	    		if ($setServer) $true_change = TRUE;
	    		} elseif (is_numeric($form['users']) == FALSE && perm_handler("server_conf_users", FALSE)) {
	    			$val_error = TRUE;
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_users."</div></div>";
	    	}
	    
	    	//Vergleiche und Update ggf. port
	    	if ($form['port'] != $server['port'] && perm_handler("server_conf_port", FALSE) && checkPortValue($form['port'], $server['server_id'])) {
	    		$setServer = setServer($form['server_id'], $key = "port", $form['port']);
	    		if ($setServer) $true_change = TRUE;
	    		} elseif (checkPortValue($form['port'], $server['server_id']) == FALSE && perm_handler("server_conf_port", FALSE)) {
	    			$val_error = TRUE;
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_port."</div></div>";
	    	}
	    
	   		//Vergleiche und Update ggf. serverpassword
	    	if ($form['serverpassword'] != $server['serverpassword'] && perm_handler("server_conf_password", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "serverpassword", $form['serverpassword']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. timeout
	    	if ($form['timeout'] != $server['timeout'] && perm_handler("server_conf_timeout", FALSE) && is_numeric($form['timeout'])) {
	    		$setServer = setServer($form['server_id'], $key = "timeout", $form['timeout']);
	    		if ($setServer) $true_change = TRUE;
	    		} elseif (is_numeric($form['timeout']) == FALSE && perm_handler("server_conf_timeout", FALSE)) {
	    			$val_error = TRUE;
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_timeout."</div></div>";
	    	}
	    
	   		//Vergleiche und Update ggf. channelname
	    	if ($form['channelname'] != $server['channelname'] && perm_handler("server_conf_channelname", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "channelname", $form['channelname']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. username
	    	if ($form['username'] != $server['username'] && perm_handler("server_conf_username", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "username", $form['username']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. defaultchannel
	    	if ($form['defaultchannel'] != $server['defaultchannel'] && perm_handler("server_conf_defaultchannel", FALSE) && is_numeric($form['defaultchannel'])) {
	    		$setServer = setServer($form['server_id'], $key = "defaultchannel", $form['defaultchannel']);
	    		if ($setServer) $true_change = TRUE;
	    		} elseif (is_numeric($form['defaultchannel']) == FALSE && perm_handler("server_conf_defaultchannel", FALSE)) {
	    			$val_error = TRUE;
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_defaultchannel."</div></div>";
	    	}
	    
	   		//Vergleiche und Update ggf. registerHostname
	    	if ($form['registerHostname'] != $server['registerHostname'] && perm_handler("server_conf_registerHostname", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "registerhostname", $form['registerHostname']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. registerName
	    	if ($form['registerName'] != $server['registerName'] && perm_handler("server_conf_registerName", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "registername", $form['registerName']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. registerPassword
	    	if ($form['registerPassword'] != $server['registerPassword'] && perm_handler("server_conf_registerPassword", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "registerpassword", $form['registerPassword']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. registerUrl
	    	if ($form['registerUrl'] != $server['registerUrl'] && perm_handler("server_conf_registerUrl", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "registerurl", $form['registerUrl']);
	    		if ($setServer) $true_change = TRUE;
	    	}
    	
	   		//Vergleiche und Update ggf. registerLocation
	    	if ($form['registerLocation'] != $server['registerLocation'] && perm_handler("server_conf_registerLocation", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "registerlocation", $form['registerLocation']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    	
	   		//Vergleiche und Update ggf. bandwidth
	    	if ($form['bandwidth'] != $server['bandwidth'] && perm_handler("server_conf_bandwidth", FALSE) && is_numeric($form['bandwidth'])) {
	    		$setServer = setServer($form['server_id'], $key = "bandwidth", $form['bandwidth']);
	    		if ($setServer) $true_change = TRUE;
	    		} elseif (is_numeric($form['bandwidth']) == FALSE && perm_handler("server_conf_bandwidth", FALSE)) {
	    			$val_error = TRUE;
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_bandwidth."</div></div>";
	    	}
	    
	   		//Vergleiche und Update ggf. imagemessagelength
	    	if ($form['imagemessagelength'] != $server['imagemessagelength'] && perm_handler("server_conf_imagemessagelength", FALSE) && is_numeric($form['imagemessagelength'])) {
	    		$setServer = setServer($form['server_id'], $key = "imagemessagelength", $form['imagemessagelength']);
	    		if ($setServer) $true_change = TRUE;
	    		} elseif (is_numeric($form['imagemessagelength']) == FALSE && perm_handler("server_conf_imagemessagelength", FALSE)) {
	    			$val_error = TRUE;
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_imagemessagelength."</div></div>";
	    	}
	    
	   		//Vergleiche und Update ggf. textmessagelength
	    	if ($form['textmessagelength'] != $server['textmessagelength'] && perm_handler("server_conf_textmessagelength", FALSE) && is_numeric($form['textmessagelength'])) {
	    		$setServer = setServer($form['server_id'], $key = "textmessagelength", $form['textmessagelength']);
	    		if ($setServer) $true_change = TRUE;
	    		} elseif (is_numeric($form['textmessagelength']) == FALSE && perm_handler("server_conf_textmessagelength", FALSE)) {
	    			$val_error = TRUE;
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_textmessagelength."</div></div>";
	    	}
	    
	   		//Vergleiche und Update ggf. usersperchannel
	    	if ($form['usersperchannel'] != $server['usersperchannel'] && perm_handler("server_conf_usersperchannel", FALSE) && is_numeric($form['usersperchannel'])) {
	    		$setServer = setServer($form['server_id'], $key = "usersperchannel", $form['usersperchannel']);
	    		if ($setServer) $true_change = TRUE;
	    		} elseif (is_numeric($form['usersperchannel']) == FALSE && perm_handler("server_conf_usersperchannel", FALSE)) {
	    			$val_error = TRUE;
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_server_wrong_usersperchannel."</div></div>";
	    	}
	    	
    		//Vergleiche und Update ggf. sslca
	    	if ($form['sslca'] != $server['sslca'] && perm_handler("server_conf_sslca", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "ca", $form['sslca']);
	    		if ($setServer) $true_change = TRUE;
	    	}
    	
    		//Vergleiche und Update ggf. sslcert
	    	if ($form['sslcert'] != $server['sslcert'] && perm_handler("server_conf_sslcert", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "certificate", $form['sslcert']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    	
    		//Vergleiche und Update ggf. sslkey
	    	if ($form['sslkey'] != $server['sslkey'] && perm_handler("server_conf_sslkey", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "key", $form['sslkey']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    	
    		//Vergleiche und Update ggf. sslpassphrase
	    	if ($form['sslpassphrase'] != $server['sslpassphrase'] && perm_handler("server_conf_sslpassphrase", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "passphrase", $form['sslpassphrase']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    	
    		//Vergleiche und Update ggf. channelnestinglimit
	    	if ($form['channelnestinglimit'] != $server['channelnestinglimit'] && perm_handler("server_conf_channelnestinglimit", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "channelnestinglimit", $form['channelnestinglimit']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    	
	    	//Vergleiche und Update ggf. opusthreshold
	    	if ($form['opusthreshold'] != $server['opusthreshold'] && perm_handler("server_conf_opusthreshold", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "opusthreshold", $form['opusthreshold']);
	    		if ($setServer) $true_change = TRUE;
	    	}
    	
	    	//Vergleiche und Update ggf. suggestpositional
	    	if ($form['suggestpositional'] != $server['suggestpositional'] && perm_handler("server_conf_suggestpositional", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "suggestpositional", $form['suggestpositional']);
	    		if ($setServer) $true_change = TRUE;
	    	}
    	
	    	//Vergleiche und Update ggf. suggestpushtotalk
	    	if ($form['suggestpushtotalk'] != $server['suggestpushtotalk'] && perm_handler("server_conf_suggestpushtotalk", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "suggestpushtotalk", $form['suggestpushtotalk']);
	    		if ($setServer) $true_change = TRUE;
	    	}
    	
	    	//Vergleiche und Update ggf. suggestversion
	    	if ($form['suggestversion'] != $server['suggestversion'] && perm_handler("server_conf_suggestversion", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "suggestversion", $form['suggestversion']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    	
	   		//Vergleiche und Update ggf. certrequired
	    	if ($form['certrequired'] != $server['certrequired'] && perm_handler("server_conf_certrequired", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "certrequired", $form['certrequired']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. obfuscate
	    	if ($form['obfuscate'] != $server['obfuscate'] && perm_handler("server_conf_obfuscate", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "obfuscate", $form['obfuscate']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. rememberchannel
	    	if ($form['rememberchannel'] != $server['rememberchannel'] && perm_handler("server_conf_rememberchannel", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "rememberchannel", $form['rememberchannel']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. allowhtml
	    	if ($form['allowhtml'] != $server['allowhtml'] && perm_handler("server_conf_allowhtml", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "allowhtml", $form['allowhtml']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    
	   		//Vergleiche und Update ggf. allowping
	    	if ($form['allowping'] != $server['allowping'] && perm_handler("server_conf_allowping", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "allowping", $form['allowping']);
	    		if ($setServer) $true_change = TRUE;
	    	}
    	
	   		//Vergleiche und Update ggf. sendversion
	    	if ($form['sendversion'] != $server['sendversion'] && perm_handler("server_conf_sendversion", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "sendversion", $form['sendversion']);
	    		if ($setServer) $true_change = TRUE;
	    	}
    	
	   		//Vergleiche und Update ggf. bonjour
	    	if ($form['bonjour'] != $server['bonjour'] && perm_handler("server_conf_bonjour", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "bonjour", $form['bonjour']);
	    		if ($setServer) $true_change = TRUE;
	    	}
	    	
	   		//Vergleiche und Update ggf. welcometext
	    	if ($form['welcometext'] != $server['welcometext'] && perm_handler("server_conf_welcometext", FALSE)) {
	    		$setServer = setServer($form['server_id'], $key = "welcometext", $form['welcometext']);
	    		if ($setServer) $true_change = TRUE;
	    	}    	
    	}
    		
    	//Loggingfunktion, Übergabe der Werte: Server editieren
        //Definiert ob etwas geloggt werden soll
        if ($true_change == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "server_db_5";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $form['server_id'];			//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $form['name'];			     	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        
    	//Meldung, wenn nichts geändert wurde!
        if ($true_change == FALSE AND $val_error == FALSE) {
            $info = "<br><div align=\"center\"><div class='boxsucess'>"._no_change_edit_funktion."</div></div>";
            $autoforward = FALSE;
        }
        
    	//Meldung, wenn was geändert wurde!
        if ($true_change == TRUE AND $val_error == FALSE) {
            $info = "<br><div align=\"center\"><div class='boxsucess'>"._edit_server_edit_success."</div></div>";
            $autoforward = TRUE;
        }
        
        //Direktweiterleitung
        if ($autoforward == TRUE) {
            autoforward("../server/index.php?section=show_server&server_id=$form[server_id]",2);
        }    
        //Weiterleitung nach 3 Sekunden
        if ($autoforward == FALSE) {
            autoforward("../server/index.php?section=edit_server&server_id=$form[server_id]",5);
        }
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Server editieren - edit server > in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server editieren - edit server > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_edit_server;
    $list_admins = FALSE;
    $list_defaultchannel = FALSE;

	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['server_id'])) {
    	$server_id = $_GET['server_id'];
    	} elseif (count($_SESSION['server_ids']) == "1") {
    		$server_id = $_SESSION['server_ids'][0];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("edit_server", $server_id)) {
    	
    	//Checke übergebene server_ids und gebe ggf. Fehlermeldung aus, wenn zu viele Server markiert wurden.
    	if (count($_SESSION['server_ids']) == "1" OR isset($_GET['server_id'])) {
    		
    		//Hole Date des Servers
    		$server = server_info($server_id);

    		//MAP Admins ausgeben
	        $admins = getAdmins();
	        foreach ($admins as $user_id) {
	        	$admin = user_info($user_id);
	        	if (getAdminViewPerms($_MAPUSER['user_id'], $admin['user_id'])) {
		            if ($admin['user_id'] == $server['guarantor']) {
						$ok = TRUE;
						break;
		            }
	        	}
	        }
	        if ($ok == TRUE) {
		        foreach ($admins as $user_id) {
		        	$admin = user_info($user_id);
		        	if (getAdminViewPerms($_MAPUSER['user_id'], $admin['user_id'])) {
			            if ($admin['user_id'] == $server['guarantor']) {
			                $list_admins .= "<option value=\"".$admin['user_id']."\" selected=\"selected\">".$admin['teamtag']."</option>\n";
			            	} else {
			                    $list_admins .= "<option value=\"".$admin['user_id']."\">".$admin['teamtag']."</option>\n";
			            }
		        	}
		        }
        		} elseif ($ok == FALSE) {
        			$admin = user_info($server['guarantor']);
        			$list_admins = "<option value=\"".$admin['user_id']."\" selected=\"selected\">".$admin['teamtag']."</option>\n";
        	}
        	
        	//Definiere Ausgabe für Port
        	if ($server['port'] == "") {
        		$port = definePortValue($server_id);
        		} else {
        			$port = $server['port'];
        	}
        	
        	//Definiere Ausgabe für Defaultchannel
        	if (isset($server['channels'])) {
        		$channels = object2array($server['channels']);
	        	foreach ($channels as $index => $channel) {
	        		foreach ($channel as $property => $value) {
	        			if ($property == "id") {
	        				$id = $value;
	        			}
	        			if ($property == "name") {
	        				if ($id != 0) {
	        					$name = htmlentities($value, ENT_QUOTES, "UTF-8");
	        					} else {
	        						if ($server['registerName'] != "") {
	        							$name = $server['registerName'];
	        							} else {
	        								$name = htmlentities($value, ENT_QUOTES, "UTF-8");
	        						}
	        				}
	        			}
	        		}
	        		if ($server['defaultchannel'] == $id) {
	        				$list_defaultchannel .=  "<option value=\"".$id."\" selected=\"selected\">".$name."</option>\n";
	        			} else {
	        			$list_defaultchannel .= "<option value=\"".$id."\">".$name."</option>\n";
	        		}
	        	}
        		} else {
        			if ($server['registerName'] != "") {
        				$list_defaultchannel .= "<option value=\"0\">".$server['registerName']."</option>\n";
	        			} else {
	        				$list_defaultchannel .= "<option value=\"0\">Root</option>\n";
	        		}	
        	}
        	
        	//Generiere Auswahlmöglichkeiten zu select
        	//channelnestinglimit
			$i_cnl = "0";
			$output_cnl = FALSE;
	    	while($i_cnl <= 15) {
	    		if (is_numeric($server['channelnestinglimit'])) {
				    if ($server['channelnestinglimit'] == $i_cnl) {
				  	    $output_cnl .= "<option value=\"".$i_cnl."\" selected=\"selected\">".$i_cnl."</option>\n";
				    	} else {
				  	       	$output_cnl .= "<option value=\"".$i_cnl."\">".$i_cnl."</option>\n";
				    }
	    			} else {
						if ($i_cnl == "10") {
			    			$output_cnl .= "<option value=\"".$i_cnl."\" selected=\"selected\">".$i_cnl."</option>\n";
			    			} else {
			  	       			$output_cnl .= "<option value=\"".$i_cnl."\">".$i_cnl."</option>\n";
			    		}
	    		}
			    $i_cnl++;
			}
			$server['channelnestinglimit'] = $output_cnl;
			//opusthreshold
			$i_oth = "0";
			$output_oth = FALSE;
	    	while($i_oth <= 100) {
	    		if (is_numeric($server['opusthreshold'])) {
				    if ($server['opusthreshold'] == $i_oth) {
				  	    $output_oth .= "<option value=\"".$i_oth."\" selected=\"selected\">".$i_oth."%</option>\n";
				    	} else {
				  	       	$output_oth .= "<option value=\"".$i_oth."\">".$i_oth."%</option>\n";
				    }
	    			} else {
						if ($i_oth == "100") {
			    			$output_oth .= "<option value=\"".$i_oth."\" selected=\"selected\">".$i_oth."%</option>\n";
			    			} else {
			  	       			$output_oth .= "<option value=\"".$i_oth."\">".$i_oth."%</option>\n";
			    		}
	    		}
			    $i_oth++;
			}
			$server['opusthreshold'] = $output_oth;
			//suggestpositional
			$output_sp = FALSE;
			if ($server['suggestpositional'] == 'TRUE') {
				$output_sp .= "<option value=\"TRUE\" selected=\"selected\">". _edit_server_val_yes ."</option>\n";
				$output_sp .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
				$output_sp .= "<option value=\"\">". _edit_server_val_no_message ."</option>\n";
				} elseif ($server['suggestpositional'] == 'FALSE') {
					$output_sp .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
					$output_sp .= "<option value=\"FALSE\" selected=\"selected\">". _edit_server_val_no ."</option>\n";
					$output_sp .= "<option value=\"\">". _edit_server_val_no_message ."</option>\n";
					} elseif ($server['suggestpositional'] == '') {
						$output_sp .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
						$output_sp .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
						$output_sp .= "<option value=\"\" selected=\"selected\">". _edit_server_val_no_message ."</option>\n";
			}
			$server['suggestpositional'] = $output_sp;
			//suggestpushtotalk
			$output_sptt = FALSE;
			if ($server['suggestpushtotalk'] == 'TRUE') {
				$output_sptt .= "<option value=\"TRUE\" selected=\"selected\">". _edit_server_val_yes ."</option>\n";
				$output_sptt .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
				$output_sptt .= "<option value=\"\">". _edit_server_val_no_message ."</option>\n";
				} elseif ($server['suggestpushtotalk'] == 'FALSE') {
					$output_sptt .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
					$output_sptt .= "<option value=\"FALSE\" selected=\"selected\">". _edit_server_val_no ."</option>\n";
					$output_sptt .= "<option value=\"\">". _edit_server_val_no_message ."</option>\n";
					} elseif ($server['suggestpushtotalk'] == '') {
						$output_sptt .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
						$output_sptt .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
						$output_sptt .= "<option value=\"\" selected=\"selected\">". _edit_server_val_no_message ."</option>\n";
			}
			$server['suggestpushtotalk'] = $output_sptt;
			//suggestversion
			$output_sv = FALSE;
			if ($server['suggestversion'] == 'TRUE') {
				$output_sv .= "<option value=\"TRUE\" selected=\"selected\">". _edit_server_val_yes ."</option>\n";
				$output_sv .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
				$output_sv .= "<option value=\"\">". _edit_server_val_no_message ."</option>\n";
				} elseif ($server['suggestversion'] == 'FALSE') {
					$output_sv .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
					$output_sv .= "<option value=\"FALSE\" selected=\"selected\">". _edit_server_val_no ."</option>\n";
					$output_sv .= "<option value=\"\">". _edit_server_val_no_message ."</option>\n";
					} elseif ($server['suggestversion'] == '') {
						$output_sv .= "<option value=\"TRUE\">". _edit_server_val_yes ."</option>\n";
						$output_sv .= "<option value=\"FALSE\">". _edit_server_val_no ."</option>\n";
						$output_sv .= "<option value=\"\" selected=\"selected\">". _edit_server_val_no_message ."</option>\n";
			}
			$server['suggestversion'] = $output_sv;
			
		    //Setzte Checks TRUE/FALSE zu checked
			if ($server['certrequired'] == 'TRUE') {
				$server['certrequired'] = "checked";
				} else {
					$server['certrequired'] = "";
			}
			if ($server['obfuscate'] == 'TRUE') {
				$server['obfuscate'] = "checked";
				} else {
					$server['obfuscate'] = "";
			}
			if ($server['rememberchannel'] == 'TRUE') {
				$server['rememberchannel'] = "checked";
				} else {
					$server['rememberchannel'] = "";
			}
			if ($server['allowhtml'] == 'TRUE') {
				$server['allowhtml'] = "checked";
				} else {
					$server['allowhtml'] = "";
			}
			if ($server['allowping'] == 'TRUE') {
				$server['allowping'] = "checked";
				} else {
					$server['allowping'] = "";
			}
    		if ($server['sendversion'] == 'TRUE') {
				$server['sendversion'] = "checked";
				} else {
					$server['sendversion'] = "";
			}
    		if ($server['bonjour'] == 'TRUE') {
				$server['bonjour'] = "checked";
				} else {
					$server['bonjour'] = "";
			}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_name", FALSE)) {
				$setAname = ''; $setBname = '';
    			} else {
    				$setAname = '<!--'; $setBname = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_guarantor", FALSE)) {
				$setAguarantor = ''; $setBguarantor = '';
    			} else {
    				$setAguarantor = '<!--'; $setBguarantor = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_payed_until", FALSE)) {
				$setApayed_until = ''; $setBpayed_until = '';
    			} else {
    				$setApayed_until = '<!--'; $setBpayed_until = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_users", FALSE)) {
				$setAusers = ''; $setBusers = '';
    			} else {
    				$setAusers = '<!--'; $setBusers = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_port", FALSE)) {
				$setAport = ''; $setBport = '';
    			} else {
    				$setAport = '<!--'; $setBport = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_password", FALSE)) {
				$setApassword = ''; $setBpassword = '';
    			} else {
    				$setApassword = '<!--'; $setBpassword = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_timeout", FALSE)) {
				$setAtimeout = ''; $setBtimeout = '';
    			} else {
    				$setAtimeout = '<!--'; $setBtimeout = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_channelname", FALSE)) {
				$setAchannelname = ''; $setBchannelname = '';
    			} else {
    				$setAchannelname = '<!--'; $setBchannelname = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_username", FALSE)) {
				$setAusername = ''; $setBusername = '';
    			} else {
    				$setAusername = '<!--'; $setBusername = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_defaultchannel", FALSE)) {
				$setAdefaultchannel = ''; $setBdefaultchannel = '';
    			} else {
    				$setAdefaultchannel = '<!--'; $setBdefaultchannel = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_registerHostname", FALSE)) {
				$setAregisterHostname = ''; $setBregisterHostname = '';
    			} else {
    				$setAregisterHostname = '<!--'; $setBregisterHostname = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_registerName", FALSE)) {
				$setAregisterName = ''; $setBregisterName = '';
    			} else {
    				$setAregisterName = '<!--'; $setBregisterName = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_registerPassword", FALSE)) {
				$setAregisterPassword = ''; $setBregisterPassword = '';
    			} else {
    				$setAregisterPassword = '<!--'; $setBregisterPassword = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_registerUrl", FALSE)) {
				$setAregisterUrl = ''; $setBregisterUrl = '';
    			} else {
    				$setAregisterUrl = '<!--'; $setBregisterUrl = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_registerLocation", FALSE)) {
				$setAregisterLocation = ''; $setBregisterLocation = '';
    			} else {
    				$setAregisterLocation = '<!--'; $setBregisterLocation = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_bandwidth", FALSE)) {
				$setAbandwidth = ''; $setBbandwidth = '';
    			} else {
    				$setAbandwidth = '<!--'; $setBbandwidth = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_imagemessagelength", FALSE)) {
				$setAimagemessagelength = ''; $setBimagemessagelength = '';
    			} else {
    				$setAimagemessagelength = '<!--'; $setBimagemessagelength = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_textmessagelength", FALSE)) {
				$setAtextmessagelength = ''; $setBtextmessagelength = '';
    			} else {
    				$setAtextmessagelength = '<!--'; $setBtextmessagelength = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_usersperchannel", FALSE)) {
				$setAusersperchannel = ''; $setBusersperchannel = '';
    			} else {
    				$setAusersperchannel = '<!--'; $setBusersperchannel = '-->';
    		}
    	
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_sslca", FALSE)) {
				$setAsslca = ''; $setBsslca = '';
    			} else {
    				$setAsslca = '<!--'; $setBsslca = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_sslcert", FALSE)) {
				$setAsslcert = ''; $setBsslcert = '';
    			} else {
    				$setAsslcert = '<!--'; $setBsslcert = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_sslkey", FALSE)) {
				$setAsslkey = ''; $setBsslkey = '';
    			} else {
    				$setAsslkey = '<!--'; $setBsslkey = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_sslpassphrase", FALSE)) {
				$setAsslpassphrase = ''; $setBsslpassphrase = '';
    			} else {
    				$setAsslpassphrase = '<!--'; $setBsslpassphrase = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_channelnestinglimit", FALSE)) {
				$setAchannelnestinglimit = ''; $setBchannelnestinglimit = '';
    			} else {
    				$setAchannelnestinglimit = '<!--'; $setBchannelnestinglimit = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_opusthreshold", FALSE)) {
				$setAopusthreshold = ''; $setBopusthreshold = '';
    			} else {
    				$setAopusthreshold = '<!--'; $setBopusthreshold = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_suggestpositional", FALSE)) {
				$setAsuggestpositional = ''; $setBsuggestpositional = '';
    			} else {
    				$setAsuggestpositional = '<!--'; $setBsuggestpositional = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_suggestpushtotalk", FALSE)) {
				$setAsuggestpushtotalk = ''; $setBsuggestpushtotalk = '';
    			} else {
    				$setAsuggestpushtotalk = '<!--'; $setBsuggestpushtotalk = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_suggestversion", FALSE)) {
				$setAsuggestversion = ''; $setBsuggestversion = '';
    			} else {
    				$setAsuggestversion = '<!--'; $setBsuggestversion = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_certrequired", FALSE)) {
				$setAcertrequired = ''; $setBcertrequired = '';
    			} else {
    				$setAcertrequired = '<!--'; $setBcertrequired = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_obfuscate", FALSE)) {
				$setAobfuscate = ''; $setBobfuscate = '';
    			} else {
    				$setAobfuscate = '<!--'; $setBobfuscate = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_rememberchannel", FALSE)) {
				$setArememberchannel = ''; $setBrememberchannel = '';
    			} else {
    				$setArememberchannel = '<!--'; $setBrememberchannel = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_allowhtml", FALSE)) {
				$setAallowhtml = ''; $setBallowhtml = '';
    			} else {
    				$setAallowhtml = '<!--'; $setBallowhtml = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_allowping", FALSE)) {
				$setAallowping = ''; $setBallowping = '';
    			} else {
    				$setAallowping = '<!--'; $setBallowping = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_sendversion", FALSE)) {
				$setAsendversion = ''; $setBsendversion = '';
    			} else {
    				$setAsendversion = '<!--'; $setBsendversion = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_bonjour", FALSE)) {
				$setAbonjour = ''; $setBbonjour = '';
    			} else {
    				$setAbonjour = '<!--'; $setBbonjour = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_welcometext", FALSE)) {
				$setAwelcometext = ''; $setBwelcometext = '';
    			} else {
    				$setAwelcometext = '<!--'; $setBwelcometext = '-->';
    		}
    		
    		//Darstellungsberechtigungen überprüfen
			if (perm_handler("server_conf_discription", FALSE)) {
				$setAdiscription = ''; $setBdiscription = '';
    			} else {
    				$setAdiscription = '<!--'; $setBdiscription = '-->';
    		}
    		
    		//Daten ans Formular schicken und / bzw. es aufrufen   
			$content_headers = array("head_on" => TRUE,
								 	 "head_type" => "default",
			                         "head_value" => _edit_server_head . $server['name'],
								     "navi_on" => TRUE,
								     "navi_type" => "edit_server",
								     );  			
			$index = show("$dir/edit_server", array("server_id" => $server['server_id'],
													"name" => $server['name'],
                                                    "guarantor" => $list_admins,
                                                    "payed_until_y" => $server['selectbox']['y'],
													"payed_until_m" => $server['selectbox']['m'],
													"payed_until_d" => $server['selectbox']['d'],
													"payed_until_h" => $server['selectbox']['h'],
													"payed_until_i" => $server['selectbox']['i'],
													"payed_until_s" => $server['selectbox']['s'],
                                                    "users" => $server['users'],
													"port" => $port,
													"serverpassword" => $server['serverpassword'],
													"timeout" => $server['timeout'],                      
													"channelname" => $server['channelname'],                
													"username" => $server['username'],                     
													"defaultchannel" => $list_defaultchannel,          
													"registerHostname" => $server['registerHostname'],    
												    "registerName" => $server['registerName'],             
													"registerPassword" => $server['registerPassword'],     
													"registerUrl" => $server['registerUrl'],   
													"registerLocation" => $server['registerLocation'],               
													"bandwidth" => $server['bandwidth'],                    
													"imagemessagelength" => $server['imagemessagelength'], 
													"textmessagelength" => $server['textmessagelength'],    
													"usersperchannel" => $server['usersperchannel'],
													"sslca" => $server['sslca'],
													"sslcert" => $server['sslcert'],
													"sslkey" => $server['sslkey'],
													"sslpassphrase" => $server['sslpassphrase'],
													"channelnestinglimit" => $server['channelnestinglimit'],
													"opusthreshold" => $server['opusthreshold'],
													"suggestpositional" => $server['suggestpositional'],
													"suggestpushtotalk" => $server['suggestpushtotalk'],
													"suggestversion" => $server['suggestversion'],
													"certrequired" => $server['certrequired'],            
					  								"obfuscate" => $server['obfuscate'],              
					  								"rememberchannel" => $server['rememberchannel'],  
													"allowhtml" => $server['allowhtml'],              
													"allowping" => $server['allowping'],
													"sendversion" => $server['sendversion'],              
													"bonjour" => $server['bonjour'],
													"welcometext" => $server['welcometext'],
                                                    "discription_text" => $server['discription'],
													"setAname" => $setAname,
													"setBname" => $setBname,
													"setAguarantor" => $setAguarantor,
													"setBguarantor" => $setBguarantor,
													"setApayed_until" => $setApayed_until,
													"setBpayed_until" => $setBpayed_until,
													"setAusers" => $setAusers,
													"setBusers" => $setBusers,
													"setAport" => $setAport,
													"setBport" => $setBport,
													"setApassword" => $setApassword,
													"setBpassword" => $setBpassword,
													"setAtimeout" => $setAtimeout,
													"setBtimeout" => $setBtimeout,
													"setAchannelname" => $setAchannelname,
													"setBchannelname" => $setBchannelname,
													"setAusername" => $setAusername,
													"setBusername" => $setBusername,
													"setAdefaultchannel" => $setAdefaultchannel,
													"setBdefaultchannel" => $setBdefaultchannel,
													"setAregisterHostname" => $setAregisterHostname,
													"setBregisterHostname" => $setBregisterHostname,
													"setAregisterName" => $setAregisterName,
													"setBregisterName" => $setBregisterName,
													"setAregisterPassword" => $setAregisterPassword,
													"setBregisterPassword" => $setBregisterPassword,
													"setAregisterUrl" => $setAregisterUrl,
													"setBregisterUrl" => $setBregisterUrl,
													"setAregisterLocation" => $setAregisterLocation,
													"setBregisterLocation" => $setBregisterLocation,
													"setAbandwidth" => $setAbandwidth,
													"setBbandwidth" => $setBbandwidth,
													"setAimagemessagelength" => $setAimagemessagelength,
													"setBimagemessagelength" => $setBimagemessagelength,
													"setAtextmessagelength" => $setAtextmessagelength,
													"setBtextmessagelength" => $setBtextmessagelength,
													"setAusersperchannel" => $setAusersperchannel,
													"setBusersperchannel" => $setBusersperchannel,
													"setAsslca" => $setAsslca,
													"setBsslca" => $setBsslca,
													"setAsslcert" => $setAsslcert,
													"setBsslcert" => $setBsslcert,
													"setAsslkey" => $setAsslkey,
													"setBsslkey" => $setBsslkey,
													"setAsslpassphrase" => $setAsslpassphrase,
													"setBsslpassphrase" => $setBsslpassphrase,
													"setAchannelnestinglimit" => $setAchannelnestinglimit,
													"setBchannelnestinglimit" => $setBchannelnestinglimit,
													"setAopusthreshold" => $setAopusthreshold,
													"setBopusthreshold" => $setBopusthreshold,
													"setAsuggestpositional" => $setAsuggestpositional,
													"setBsuggestpositional" => $setBsuggestpositional,
													"setAsuggestpushtotalk" => $setAsuggestpushtotalk,
													"setBsuggestpushtotalk" => $setBsuggestpushtotalk,
													"setAsuggestversion" => $setAsuggestversion,
													"setBsuggestversion" => $setBsuggestversion,
													"setAcertrequired" => $setAcertrequired,
													"setBcertrequired" => $setBcertrequired,
													"setAobfuscate" => $setAobfuscate,
													"setBobfuscate" => $setBobfuscate,
													"setArememberchannel" => $setArememberchannel,
													"setBrememberchannel" => $setBrememberchannel,
													"setAallowhtml" => $setAallowhtml,
													"setBallowhtml" => $setBallowhtml,
													"setAallowping" => $setAallowping,
													"setBallowping" => $setBallowping,
													"setAsendversion" => $setAsendversion,
													"setBsendversion" => $setBsendversion,
													"setAbonjour" => $setAbonjour,
													"setBbonjour" => $setBbonjour,
													"setAwelcometext" => $setAwelcometext,
													"setBwelcometext" => $setBwelcometext,
													"setAdiscription" => $setAdiscription,
													"setBdiscription" => $setBdiscription,
                                                    "head_name" => _edit_server_name,
                                                    "head_guarantor" => _edit_server_guarantor,
													"head_payed_until" => _edit_server_payed_until,
                                                    "head_users" => _edit_server_users,
                                                    "head_port" => _edit_server_port,
													"head_serverpassword" => _edit_server_serverpassword,
													"head_timeout" => _edit_server_timeout,
													"head_channelname" => _edit_server_channelname,
													"head_username" => _edit_server_username,
													"head_defaultchannel" => _edit_server_defaultchannel,
													"head_registerHostname" => _edit_server_registerHostname,
													"head_registerName" => _edit_server_registerName,
													"head_registerPassword" => _edit_server_registerPassword,
													"head_registerUrl" => _edit_server_registerUrl,
													"head_registerLocation" => _edit_server_registerLocation,
													"head_bandwidth" => _edit_server_bandwidth,
													"head_imagemessagelength" => _edit_server_imagemessagelength,
													"head_textmessagelength" => _edit_server_textmessagelength,
													"head_usersperchannel" => _edit_server_usersperchannel,
													"head_sslca" => _edit_server_sslca,
													"head_sslcert" => _edit_server_sslcert,
													"head_sslkey" => _edit_server_sslkey,
													"head_sslpassphrase" => _edit_server_sslpassphrase,
													"head_channelnestinglimit" => _edit_server_channelnestinglimit,
													"head_opusthreshold" => _edit_server_opusthreshold,
													"head_suggestpositional" => _edit_server_suggestpositional,
													"head_suggestpushtotalk" => _edit_server_suggestpushtotalk,
													"head_suggestversion" => _edit_server_suggestversion,
													"head_certrequired" => _edit_server_certrequired,
													"head_obfuscate" => _edit_server_obfuscate,
													"head_rememberchannel" => _edit_server_rememberchannel,
													"head_allowhtml" => _edit_server_allowhtml,
													"head_allowping" => _edit_server_allowping,
													"head_sendversion" => _edit_server_sendversion,
													"head_bonjour" => _edit_server_bonjour,
													"head_welcometext" => _edit_server_welcometext,
                                                    "head_discription" => _edit_server_discription,			
                                                    "discription_name" => _edit_server_discr_name,
                                                    "discription_guarantor" => _edit_server_discr_guarantor,
													"discription_payed_until" => _edit_server_discr_payed_until,
													"discription_payed_until_y" => _edit_server_discr_payed_until_y,
													"discription_payed_until_m" => _edit_server_discr_payed_until_m,
													"discription_payed_until_d" => _edit_server_discr_payed_until_d,
													"discription_payed_until_h" => _edit_server_discr_payed_until_h,
													"discription_payed_until_i" => _edit_server_discr_payed_until_i,
													"discription_payed_until_s" => _edit_server_discr_payed_until_s,
                                                    "discription_users" => _edit_server_discr_users,
                                                    "discription_port" => _edit_server_discr_port,
													"discription_serverpassword" => _edit_server_discr_serverpassword,
													"discription_timeout" => _edit_server_discr_timeout,
													"discription_channelname" => _edit_server_discr_channelname,
													"discription_username" => _edit_server_discr_username,
													"discription_defaultchannel" => _edit_server_discr_defaultchannel,
													"discription_registerHostname" => _edit_server_discr_registerHostname,
													"discription_registerName" => _edit_server_discr_registerName,
													"discription_registerPassword" => _edit_server_discr_registerPassword,
													"discription_registerUrl" => _edit_server_discr_registerUrl,
													"discription_registerLocation" => _edit_server_discr_registerLocation,
													"discription_bandwidth" => _edit_server_discr_bandwidth,
													"discription_imagemessagelength" => _edit_server_discr_imagemessagelength,
													"discription_textmessagelength" => _edit_server_discr_textmessagelength,
													"discription_usersperchannel" => _edit_server_discr_usersperchannel,
													"discription_sslca" => _edit_server_discr_sslca,
													"discription_sslcert" => _edit_server_discr_sslcert,
													"discription_sslkey" => _edit_server_discr_sslkey,
													"discription_sslpassphrase" => _edit_server_discr_sslpassphrase,
													"discription_channelnestinglimit" => _edit_server_discr_channelnestinglimit,
													"discription_opusthreshold" => _edit_server_discr_opusthreshold,
													"discription_suggestpositional" => _edit_server_discr_suggestpositional,
													"discription_suggestpushtotalk" => _edit_server_discr_suggestpushtotalk,
													"discription_suggestversion" => _edit_server_discr_suggestversion,
													"discription_certrequired" => _edit_server_discr_certrequired,
													"discription_obfuscate" => _edit_server_discr_obfuscate,
													"discription_rememberchannel" => _edit_server_discr_rememberchannel,
													"discription_allowhtml" => _edit_server_discr_allowhtml,
													"discription_allowping" => _edit_server_discr_allowping,
													"discription_sendversion" => _edit_server_discr_sendversion,
													"discription_bonjour" => _edit_server_discr_bonjour,
													"discription_welcometext" => _edit_server_discr_welcometext,
                                                    "discription_discription" => _edit_server_discr_discription,
													"variable" => _edit_server_variable,
													"defaultvalue" => _edit_server_defaultvalue,
													"discription" => _edit_server_discriptions,
                                                    ));
             
			//Ende definition eigentliche Funktionen dieser Section^^
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._server_show_selected_wrong."</div></div>";
    			autoforward("../server/index.php?section=list_server",3);
    	}
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Server editieren - edit server > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server löschen - delete server > Schreibe in DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_server_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_delete_server_db;
    $deleteServer = FALSE;
    
	//Hole server_id aus Session(Liste) oder aus URL
	if ($_SESSION['servers'] != FALSE) {
		$servers = $_SESSION['servers'];
		} else {
			$servers[0] = "1";  //Wenn Session abgelaufen, mache ungültig und gebe fehler aus!
	}
	unset($_SESSION['servers']);
	
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_server", FALSE)) {
    	
    	//Arbeite Array ab
    	foreach ($servers as $server_id) {

	        //Serverdaten auslesen
	        $server = server_info($server_id);

	        //Sämtliche MAP Struckturen zu diesem Server löschen
	         if ($server['server_id'] != "1") {
	         	//Lösche Server nur, wenn Murmur auch läuft sonst gibts nen error
    			if (($server['online'] == FALSE AND $_SLICE_ERR != TRUE) OR $server['online'] == TRUE) {
		         	if ($server['online'] == TRUE) {
		         		setServerStop($server['server_id']);
		         	}
		         	$deleteServer = deleteServer($server['server_id']);
		         	if ($deleteServer == TRUE) {
			         	mysql_query("DELETE FROM `".$database_prefix."channelviewer` WHERE server_id = '".$server['server_id']."'");
			         	mysql_query("DELETE FROM `".$database_prefix."log` WHERE server_id = '".$server['server_id']."'");
						mysql_query("DELETE FROM `".$database_prefix."requsr` WHERE server_id = '".$server['server_id']."'");	
						mysql_query("DELETE FROM `".$database_prefix."servers` WHERE server_id = '".$server['server_id']."' LIMIT 1");
						mysql_query("DELETE FROM `".$database_prefix."user_servers` WHERE server_id = '".$server['server_id']."'");		
			         	$info .= "<br><div align=\"center\"><div class='boxsucess'>"._delete_server_sucsessful_1.$server['name']._delete_server_sucsessful_2."</div></div>";
			         	$autoforward = TRUE;
		         		} else {
	         				$info .= "<br><div align=\"center\"><div class='savearea'>"._slice_error_server_offline."</div></div>";     
	         				$autoforward = FALSE;
		         	}
	         		} else {
	         			$info .= "<br><div align=\"center\"><div class='savearea'>"._server_delete_not_sucsessful_1.$server['name']._server_delete_not_sucsessful_2."</div></div>";     
	         			$autoforward = FALSE;
    			}
	         	} else {
	         		$info .= "<br><div align=\"center\"><div class='savearea'>"._server_delete_not_sucsessful_1.$server['name']._server_delete_not_sucsessful_2."</div></div>";     
	         		$autoforward = FALSE;
	        }
         	
    	}

        //Loggingfunktion, Übergabe der Werte: Server löschen
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "server_db_6";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server['server_id'];		//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $server['name'];			     //Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

        //Direktweiterleitung
        if ($autoforward == TRUE) {
            autoforward("../server/index.php?section=list_server",2);
        }    
        //Weiterleitung nach 5 Sekunden
        if ($autoforward == FALSE) {
            autoforward("../server/index.php?section=list_server",5);
        }
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Server löschen - delete server > Schreibe in DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server löschen - delete server > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_delete_server;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['server_id'])) {
    	$server_ids[0] = $_GET['server_id'];
    	} elseif (isset($_SESSION['server_ids'])) {
			$server_ids = $_SESSION['server_ids'];
			} elseif (!isset($_SESSION['server_ids'])) {
				$server_ids = FALSE;
    }
    unset($_SESSION['server_ids']);

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_server", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Wenn Server_id aus Formular / Liste übergeben wurde, ansonsten fehler!
    	if(count($server_ids) != "0") {
			
    		$i = "0";
	    	//Arbeite Server_ids ab
	    	foreach($server_ids as $server_id) {
	    	
				//Hole Server Infos
				$server = server_info($server_id);
				
				//Nicht löschen, wenn server_id = 1
				if ($server['server_id'] != "1") {
					
					//Schreibe Session
					$_SESSION['servers'][$i] = $server_id;
					$i++;
				
					//Setze Trennbalken
		    		if(count($server_ids) > "1") {
						$line = '<hr />';
						} else {
							$line = '';
					}
			                           
					//Daten ans Formular schicken und / bzw. es aufrufen
					$content_headers = array("head_on" => TRUE,
											 "head_type" => "default",
											 "head_value" => _delete_server_head . $server['name'],
											 "navi_on" => TRUE,
											 "navi_type" => "delete_server",
											 );         
					$index .= show("$dir/delete_server", array("server_id" => $server['server_id'],
															  "name" => $server['name'],
															  "guarantor" => $server['linked_name'],
															  "ip" => $server['ip'],
															  "port" => $server['port'],
															  "payed_until" => $server['payed_until'],
															  "status" => $server['status_icon'],
															  "users" => count($server['conected_users']) . " / " . $server['users'],
															  "delete_question" => _delete_server_question,
															  "delete_id" => _delete_server_id,
															  "delete_name" => _delete_server_name,
															  "delete_guarantor" => _delete_server_guarantor,
															  "delete_adress" => _delete_server_adress,
															  "delete_payed_until" => _delete_server_payed_until,
															  "delete_status" => _delete_server_status,
															  "delete_users" => _delete_server_users,
															  "line" => $line,
															  ));
					} else {
						$info .= "<br><div align=\"center\"><div class='savearea'>"._server_delete_not_sucsessful_1.$server['name']._server_delete_not_sucsessful_2."</div></div>";
						if(count($server_ids) == "1") {
							autoforward("../server/index.php?section=show_server&server_id=".$server['server_id'],3);
						}
				}
	    	}
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._server_show_selected_wrong."</div></div>";
    			autoforward("../server/index.php?section=list_server",3);
		}
    	
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Server löschen - delete server > FORMULAR
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  virtuellen Server starten - start virtual server
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'start_virtual_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_start_virtual_server;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['server_id'])) {
    	$server_id[0] = $_GET['server_id'];
    	} elseif (isset($_SESSION['server_ids'])) {
			$server_id = $_SESSION['server_ids'];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("server_start", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Arbeite Array ab
    	foreach ($server_id as $id) {
	    	//Hole Server Infos
	    	$server = server_info($id);
	    	//Hole Zahlungsinformationen
			$pay = getServerPayTime($id);
			
			//Wenn Server bezahlt, Starten möglich, ansonsten fehler
			if ($pay['payed'] == TRUE) {
	    	
		    	//Wenn Server Offline, starte diesen
		    	if ($server['online'] == FALSE) {
		    	
			    	//Starte virtuellen Server
			    	$start = setServerStart($id);
			    	setServer($id, $key = "boot", 'TRUE');
	
			    	//Werte aus
			    	if ($start) {
			    		//Wenn Server-Start erfolgreich, gebe Meldug aus
			    		$info .= "<br><div align=\"center\"><div class='boxsucess'>"._start_virtual_server_sucsessful.$server['name']."</div></div>";
			    		
			    		//Aktualisiere DB (Startzeit des Servers, letzter Start)
			    		updateServerStartUp($id);		    		
			    		
			    		//Loggingfunktion, Übergabe der Werte: Server starten
						//Definiert ob etwas geloggt werden soll
						$log_values["on"] = TRUE;
						//Pflichtwerte
						$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
						$log_values["action_id"] = "server_db_10";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
						$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
						$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
						//Definierbare Werte (optional)
						$log_values["server_id"] = $id;							//Definiert die Server_ID (kann frei gelassen werden)
						$log_values["value_1"] = $server['name']; 				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
	
			    		} else {
			    			//Wenn Server-Start nicht erfolgreich, gebe Meldung aus
			    			$info .= "<br><div align=\"center\"><div class='savearea'>"._start_virtual_server_error.$server['name']."</div></div>";
			    	}
		    		} else {
		    			$info .= "<br><div align=\"center\"><div class='savearea'>"._start_virtual_server_allready_running.$server['name']."</div></div>";
		    	}
				} else {
					$info .= "<br><div align=\"center\"><div class='savearea'>"._start_virtual_server_error_pay.$server['name']."</div></div>";
			}
    	}
		//Ende definition eigentliche Funktionen dieser Section^^	
    }
    
	//Weiterleitung nach x Sekunden
	if (strpos($_SERVER['HTTP_REFERER'], "list_server") == TRUE) {
		autoforward("../server/index.php?section=list_server",5);
		} else {
			autoforward("../server/index.php?section=show_server&server_id=$server_id[0]",4);
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  virtuellen Server starten - start virtual server
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  virtuellen Server stoppen - stop virtual server
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'stop_virtual_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_start_virtual_server;
    	
    //Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['server_id'])) {
    	$server_id[0] = $_GET['server_id'];
    	} elseif (isset($_SESSION['server_ids'])) {
			$server_id = $_SESSION['server_ids'];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("server_stop", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Arbeite Array ab
    	foreach ($server_id as $id) {
	    	//Hole Server Infos
	    	$server = server_info($id);
	    	
	    	//Wenn Server Online, stoppe diesen
	    	if ($server['online'] == TRUE ) {
	    		
		    	//Stoppe virtuellen Server
		    	$stop = setServerStop($id);
		    	setServer($id, $key = "boot", 'FALSE');
		    	
		    	//Werte aus
		    	if ($stop) {
		    		//Wenn Server-Stopp erfolgreich, gebe Meldug aus
		    		$info .= "<br><div align=\"center\"><div class='boxsucess'>"._stop_virtual_server_sucsessful.$server['name']."</div></div>";
		    		
		    		//Loggingfunktion, Übergabe der Werte: Server stoppen
					//Definiert ob etwas geloggt werden soll
					$log_values["on"] = TRUE;
					//Pflichtwerte
					$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
					$log_values["action_id"] = "server_db_11";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
					$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
					$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
					//Definierbare Werte (optional)
					$log_values["server_id"] = $id;							//Definiert die Server_ID (kann frei gelassen werden)
					$log_values["value_1"] = $server['name']; 				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

		    		} else {
		    			//Wenn Server-Stopp nicht erfolgreich, gebe Meldung aus
		    			$info .= "<br><div align=\"center\"><div class='savearea'>"._stop_virtual_server_error.$server['name']."</div></div>";
		    	}
	    		} else {
	    			$info .= "<br><div align=\"center\"><div class='savearea'>"._stop_virtual_server_allready_stopped.$server['name']."</div></div>";
	    	}
    	}
		//Ende definition eigentliche Funktionen dieser Section^^	
    }
    
	//Weiterleitung nach x Sekunden
	if (strpos($_SERVER['HTTP_REFERER'], "list_server") == TRUE) {
		autoforward("../server/index.php?section=list_server",5);
		} else {
			autoforward("../server/index.php?section=show_server&server_id=$server_id[0]",4);
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  virtuellen Server stoppen - stop virtual server
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  virtuellen Server restarten - restart virtual server
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'restart_virtual_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_restart_virtual_server;
    $stop = FALSE;
    $start = FALSE;
    
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['server_id'])) {
    	$server_id[0] = $_GET['server_id'];
    	} elseif (isset($_SESSION['server_ids'])) {
			$server_id = $_SESSION['server_ids'];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("server_restart", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Arbeite Array ab
    	foreach ($server_id as $id) {
	    	//Hole Server Infos
	    	$server = server_info($id);
	    	//Hole Zahlungsinformationen
			$pay = getServerPayTime($id);
			
			//Wenn Server bezahlt, Starten möglich, ansonsten fehler
			if ($pay['payed'] == TRUE) {
		    	
				//Wenn Server Online, stoppe diesen
	    		if ($server['online'] == TRUE ) {
			    	//Stoppe virtuellen Server
			    	$stop = setServerStop($id);
	    			} else {
	    				$stop = TRUE;
	    		}
		    	
		    	//Mache Pause...
		    	usleep(300000);
		    	
		    	//Aktualisiere Server Infos
	    		$server2 = server_info($id);
		    	
	    		//Wenn Server Online, stoppe diesen
	    		if ($server2['online'] == FALSE ) {
				    //Starte virtuellen Server
				    $start = setServerStart($id);
				    setServer($id, $key = "boot", 'TRUE');
	    			} else {
	    				$start = TRUE;
	    		}
				    
		    	//Werte aus
		    	if ($stop AND $start) {
		    		//Wenn Server-Start erfolgreich, gebe Meldug aus
		    		$info .= "<br><div align=\"center\"><div class='boxsucess'>"._restart_virtual_server_sucsessful.$server['name']."</div></div>";
		    		
		    		//Aktualisiere DB (Startzeit des Servers, letzter Start)
		    		updateServerStartUp($id);
		    		
		    		//Loggingfunktion, Übergabe der Werte: Server starten
					//Definiert ob etwas geloggt werden soll
					$log_values["on"] = TRUE;
					//Pflichtwerte
					$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
					$log_values["action_id"] = "server_db_12";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
					$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
					$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
					//Definierbare Werte (optional)
					$log_values["server_id"] = $id;							//Definiert die Server_ID (kann frei gelassen werden)
					$log_values["value_1"] = $server['name']; 				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
					$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		
		    		} else {
		    			//Wenn Server-Start nicht erfolgreich, gebe Meldung aus
		    			$info .= "<br><div align=\"center\"><div class='savearea'>"._restart_virtual_server_error.$server['name']."</div></div>";
		    	}
			} else {
					$info .= "<br><div align=\"center\"><div class='savearea'>"._restart_virtual_server_error_pay.$server['name']."</div></div>";
			}
    	}
		//Ende definition eigentliche Funktionen dieser Section^^	
    }
    
	//Weiterleitung nach x Sekunden
	if (strpos($_SERVER['HTTP_REFERER'], "list_server") == TRUE) {
		autoforward("../server/index.php?section=list_server",5);
		} else {
			autoforward("../server/index.php?section=show_server&server_id=$server_id[0]",4);
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  virtuellen Server restarten - restart virtual server
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server Detauls anzeigen, show server
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'show_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_show;
    $start = FALSE;
    $stop = FALSE;
    $restart = FALSE;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['server_id'])) {
    	$server_id = $_GET['server_id'];
    	} elseif (count($_SESSION['server_ids']) == "1") {
    		$server_id = $_SESSION['server_ids'][0];
    		} elseif (!isset($_SESSION['server_ids'])) {
    			$server_id = FALSE;
    }
    if (!isset($_SESSION['server_ids'])) {
    	$_SESSION['server_ids'] = FALSE;
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("show_server", $server_id)) {
    	
    	//Checke übergebene server_ids und gebe ggf. Fehlermeldung aus, wenn zu viele Server markiert wurden.
    	if ((count($_SESSION['server_ids']) == "1" OR isset($_GET['server_id'])) AND ((ctype_digit($server_id)) AND ($server_id != ""))) {
    		
    		//Prüfe ob es die übergebene Server ID wirklich gibt (man könnte sonst in die url unvalide ids eingeben)
    		$servers = getServers();
    		foreach ($servers as $id) {
    			if ($id == $server_id) {
    				$IDcheck = TRUE;
    				break;
    				} else {
    					$IDcheck = FALSE;
    			}
    		}
	    	if ($IDcheck == TRUE) {
    		
	    		//Hole Date des Servers
	    		$server = server_info($server_id);
	    		
	    		//Definiere, ob User Server starten, stoppen und neu starten darf
				if (get_perms("server_start", FALSE) == TRUE OR get_perms("server_stop", FALSE) == TRUE OR get_perms("server_restart", FALSE) == TRUE) {
					$ctrl_head = "<b><u>" . _server_show_head_6 . "</u></b>";
				} else {$ctrl_head = FALSE;}
	    		if (get_perms("server_start", FALSE) == TRUE AND $server['online'] == FALSE) {
					$start = "<br><img src=\"../inc/tpl/".$tpldir."/images/start_virtual_server.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=start_virtual_server&server_id=$server_id\" target=\"_self\">"._server_show_start_server_link."</a>";
				} else {$start = FALSE;}
	    		if (get_perms("server_stop", FALSE) == TRUE AND $server['online'] == TRUE) {
					$stop = "<br><img src=\"../inc/tpl/".$tpldir."/images/stop_virtual_server.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=stop_virtual_server&server_id=$server_id\" target=\"_self\">"._server_show_stop_server_link."</a>";
				} else {$stop = FALSE;}
	    		if (get_perms("server_restart", FALSE) == TRUE AND $server['online'] == TRUE) {
					$restart = "<br><img src=\"../inc/tpl/".$tpldir."/images/restart_virtual_server.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=restart_virtual_server&server_id=$server_id\" target=\"_self\">"._server_show_restart_server_link."</a>";
				} else {$restart = FALSE;}
	    		if (get_perms("edit_server", FALSE) == TRUE OR get_perms("delete_server", FALSE) == TRUE) {
					$cfg_head = "<br><b><u>" . _server_show_head_7 . "</u></b>";
				} else {$cfg_head = FALSE;}
	    		if (get_perms("edit_server", FALSE) == TRUE) {
					$edit = "<br><img src=\"../inc/tpl/".$tpldir."/images/server_data.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=edit_server&server_id=$server_id\" target=\"_self\">"._server_show_config_link."</a>";
				} else {$edit = FALSE;}
				if (get_perms("delete_server", FALSE) == TRUE) {
					$delete = "<br><img src=\"../inc/tpl/".$tpldir."/images/server_delete.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=delete_server&server_id=$server_id\" target=\"_self\">"._server_show_delete_link."</a>";
				} else {$delete = FALSE;}
	    		if (get_perms("list_user", FALSE) == TRUE OR get_perms("list_admin", FALSE) == TRUE OR get_perms("show_channelviewer", FALSE) == TRUE OR get_perms("show_log", FALSE) == TRUE) {
					$ref_head = "<br><b><u>" . _server_show_head_8 . "</u></b>";
				} else {$ref_head = FALSE;}
	    		if (get_perms("list_user", FALSE) == TRUE) {
					$user = "<br><img src=\"../inc/tpl/".$tpldir."/images/user.png\" alt=\"\" border=\"0\"><a href=\"../user/index.php?server_id=$server_id\" target=\"_self\">"._server_show_userlist_link."</a>";
				} else {$user = FALSE;}
	    		if (get_perms("list_admin", FALSE) == TRUE) {
					$admin = "<br><img src=\"../inc/tpl/".$tpldir."/images/admin.png\" alt=\"\" border=\"0\"><a href=\"../admin/index.php?server_id=$server_id\" target=\"_self\">"._server_show_map_user_link."</a>";
				} else {$admin = FALSE;}
	    		if (get_perms("view_channelviewer", $server_id) == TRUE) {
					$channelviewer = "<br><img src=\"../inc/tpl/".$tpldir."/images/channelviewer.png\" alt=\"\" border=\"0\"><a href=\"../view/index.php?section=channelviewer&server_id=$server_id\" target=\"_self\">"._server_show_channelviewer_link."</a>";
				} else {$channelviewer = FALSE;}
	    		if (get_perms("show_log", FALSE) == TRUE) {
					$syslog = "<br><img src=\"../inc/tpl/".$tpldir."/images/logging.png\" alt=\"\" border=\"0\"><a href=\"../log/index.php?section=show_log&view=server&server_id=$server_id\" target=\"_self\">"._server_show_system_log_link."</a>";
				} else {$syslog = FALSE;}
	    		if (get_perms("email_mass_user", FALSE) == TRUE OR get_perms("email_mass_customer", FALSE) == TRUE OR get_perms("email_mass_reseller", FALSE) == TRUE) {
					$mail_head = "<br><b><u>" . _server_show_head_9 . "</u></b>";
				} else {$mail_head = FALSE;}
	    		if (get_perms("email_mass_user", FALSE) == TRUE) {
					$mail_user = "<br><img src=\"../inc/tpl/".$tpldir."/images/mail.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=mass_mail_server&server_id=$server_id\" target=\"_self\">"._server_show_mass_mail_link."</a>";
				} else {$mail_user = FALSE;}
				if (get_perms("email_mass_customer", FALSE) == TRUE) {
					$mail_customer = "<br><img src=\"../inc/tpl/".$tpldir."/images/mail.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=admin_mail_server&server_id=$server_id\" target=\"_self\">"._server_show_admin_mail_link."</a>";
				} else {$mail_customer = FALSE;}
	    		if (get_perms("email_mass_reseller", FALSE) == TRUE) {
					$mail_reseller = "<br><img src=\"../inc/tpl/".$tpldir."/images/mail.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=mail_reseller&server_id=$server_id\" target=\"_self\">"._server_show_map_admin_mail_link."</a>";
				} else {$mail_reseller = FALSE;}
	
				//Daten ans Formular schicken und / bzw. es aufrufen 
				$content_headers = array("head_on" => TRUE,
										 "head_type" => "default",
					                     "head_value" => _server_show_head . $server['name'],
										 "navi_on" => TRUE,
										 "navi_type" => "default",
										 );       
				$index = show("$dir/show_server", array("head_1" => _server_show_head_1,
														"head_2" => _server_show_head_2,
														"head_3" => _server_show_head_3,
														"head_4" => _server_show_head_4,
														"head_5" => _server_show_head_5,			
	                									"head_6" => $ctrl_head,       
														"head_7" => $cfg_head,
														"head_8" => $ref_head,
														"head_9" => $mail_head,
														"head_10" => _server_show_head_10,
	               										"head_11" => _server_show_head_11,
	                									"server_id" => $server['server_id'],
														"servername" => $server['name'],
														"discription" => $server['discription'],
														"email" => $server['email'],
														"guarantor" => $server['linked_name'],
														"ip" => $server['ip'],
														"port" => $server['port'],
														"server_onlinetime" => _server_onlinetime,
														"onlinetime" => $server['onlinetime'],
														"server_onlinedate" => _server_onlinedate,
														"onlinedate" => $server['onlinedate'],
														"server_conected_users" => _server_conected_users,
														"conected_users" => $server['num_conected_users'],
														"server_registered_users" => _server_registered_users,
														"registered_users" => $server['num_registered_users'],
														"server_channels" => _server_channels,
														"channels" => $server['num_channels'],
														"server_starts" => _server_starts,
														"starts" => $server['starts'],
														"server_admins" => _server_admins,
														"admins" => $server['admins'],
														"server_version" => _server_version,
														"version" => $server['version_detailed'],
														"server_url" => _server_url,
														"server_url_connect" => _server_url_connect,
														"url" => $server['url'],
														"server_zahlstatus" => _server_zahlstatus,
														"zahlstatus" => $server['zahlstatus'],
														"server_payed_until" => _server_payed_until,
														"payed_until" => $server['payed_until'],
														"server_TimeToGo" => _server_TimeToGo,
														"TimeToGo" => $server['TimeToGo'],
														"server_server_id" => _server_show_id,
														"server_servername" => _server_show_name,
														"status_server" => $server['status_icon'],
														"start_server" => $start,
														"stop_server" => $stop,
														"restart_server" => $restart,
														"server_email" => _server_show_email,
														"server_guarantor" => _server_show_guarantor,
														"server_ip" => _server_show_ip,
														"server_port" => _server_show_port,
														"server_edit_link" => $edit,
														"server_delete_link" => $delete,
														"server_mail_user_link" => $mail_user,
														"server_mail_customer_link" => $mail_customer,
														"server_mail_reseller_link" => $mail_reseller,
														"user_link" => $user,
														"admin_link" => $admin,
	                									"channelviewer_link" => $channelviewer,
	               										"map_log_link" => $syslog . "<br><img src=\"../inc/tpl/".$tpldir."/images/logging.png\" alt=\"\" border=\"0\">",
	                									"server_log" => "server_log",
														"request_account_custom_user" => "<img src=\"../inc/tpl/".$tpldir."/images/request.png\" alt=\"\" border=\"0\"><a href=\"../request/index.php?section=request_custom&account_type=user&server_id=$server_id\" target=\"_self\">"._server_request_custom_serveruser_link."</a>",
														"request_account_custom_admin" => "<img src=\"../inc/tpl/".$tpldir."/images/request.png\" alt=\"\" border=\"0\"><a href=\"../request/index.php?section=request_custom&account_type=admin&server_id=$server_id\" target=\"_self\">"._server_request_custom_mapuser_link."</a>",
														));
	                                                    
				//Loggingfunktion, Übergabe der Werte: Server Detauls anzeigen, show server
				//Definiert ob etwas geloggt werden soll
				$log_values["on"] = TRUE;
				//Pflichtwerte
				$log_values["user_id"] = $_MAPUSER['user_id'];    		//Definiert den User (die User_id) der gerade Aktiv war
				$log_values["action_id"] = "server_show_13";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
				$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
				$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
				//Definierbare Werte (optional)
				$log_values["server_id"] = $server['server_id'];		//Definiert die Server_ID (kann frei gelassen werden)
				$log_values["value_1"] = $server['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
	            
				//Ende definition eigentliche Funktionen dieser Section^^
		    	} else {
	    			$info = "<br><div align=\"center\"><div class='savearea'>"._server_show_selected_wrong."</div></div>";
	    			autoforward("../server/index.php?section=list_server",3);
	    	}
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._server_show_selected_wrong."</div></div>";
    			autoforward("../server/index.php?section=list_server",3);
    	}
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Server Detauls anzeigen, show server
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Listenanzeige Server, nur fÃ¼r MAP User where server_id =0
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'list_server':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_list;

  	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("list_server", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
            
		//Hole Server mit Perm und gebe aus
		$servers = getServers();
		foreach ($servers as $server_id) {
			
			if (get_perms("show_server", $server_id)) {
                
				//Hole verantwortlichen des Servers
				$server = server_info($server_id);
	                  
				$list .= show("$dir/server_list", array("status" => $server['status_icon_small'],
														"server_id" => $server['server_id'],
														"servername" => $server['name'],
	                									"discription" => $server['discription'],
														"guarantor" => $server['linked_name'],
														"users" => $server['users'],
														"ip" => $server['ip'],
														"port" => $server['port'],
														"action" => '<input type="checkbox" name="server_id[]" value="'.$server['server_id'].'">',
														));
	            }
	            
			}
            
            $content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
				                     "head_value" => _serverlist_head,
									 "navi_on" => TRUE,
									 "navi_type" => "list_server",
									 );   
            $index = show("$dir/server", array("list" => $list,
                                               "status" => _serverlist_status,
                                               "servername" => _serverlist_servername,
            								   "discription" => _serverlist_discription,
                                               "guarantor" => _serverlist_guarantor,
                                               "users" => _serverlist_users,
                                               "ip" => _serverlist_ip,
                                               "port" => _serverlist_port,
                                               "action" => _serverlist_action,
                                               ));
                                               
            //Loggingfunktion, Übergabe der Werte: Server restarten
            //Definiert ob etwas geloggt werden soll
			$log_values["on"] = TRUE;
			//Pflichtwerte
			$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
			$log_values["action_id"] = "server_show_14";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
			$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
			$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
			//Definierbare Werte (optional)
			$log_values["server_id"] = "";							//Definiert die Server_ID (kann frei gelassen werden)
			$log_values["value_1"] = ""; 							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
			$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
			$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
			$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
			$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
			$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
			$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

            //Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Listenanzeige Server, nur fÃ¼r MAP User where server_id =0
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Übergabescript an Anzeigen , Starten, stoppen, restarten und Löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'action':
	
	//Hole Daten aus Listenanzeige: Serverliste und leite weiter
	//Übergebe ID´s	
	$_SESSION['server_ids'] = $_POST['server_id'];
	
	//Definiere Sections
	$avaliableActions = array("show_x" => "show_server",	
							  "start_x" => "start_virtual_server",
							  "stop_x" => "stop_virtual_server",
							  "restart_x" => "restart_virtual_server",
							  "edit_x" => "edit_server",
							  "delete_x" => "delete_server",
							  );
							  
	//Leite weiter
	foreach ($avaliableActions as $key => $value) {
		if (isset($_POST[$key])) {
			header("Location: index.php?section=".$value);
			
		}
	}

break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Übergabescript an Anzeigen , Starten, stoppen, restarten und Löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Weiterleitung zur Server-Details-Seite oder Server-Liste
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_overview;

    //Weiterleitung für Admin mit mehreren Servern
    if ($_MAPUSER['type_id'] == 1) {
    	header("Location: index.php?section=list_server");
    	} elseif ($_MAPUSER['type_id'] == 0) {
    		if (count($_MAPUSER['servers']) > "1") {
				header("Location: index.php?section=list_server");
				//Weiterleitung für Admin mit nur einem Server
				} elseif (count($_MAPUSER['servers']) == "1") {
					header("Location: index.php?section=show_server&server_id=".$_MAPUSER['servers'][1]);
			}
    }

//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Weiterleitung zur Server-Details-Seite oder Server-Liste
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
}
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
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