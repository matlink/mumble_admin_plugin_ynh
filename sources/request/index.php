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
$dir = "request";
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
// **START**  Beantragte Accounts freigeben und in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'activate_account_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_activate_account_db;
    
    //Hole ids aus Session(Liste)
	if (isset($_SESSION['activate_account'])) {
		$accounts = $_SESSION['activate_account'];
	}
	unset($_SESSION['id']);
	unset($_SESSION['activate_account']);
    	
	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("activate_account", FALSE)) {
    	
    	//Wenn Session nicht abgelaufen
    	if (isset($accounts)) {    	
	    	//Arbeite Array ab
	    	foreach ($accounts as $id) {
	    		
	    		//Hole Infos
                $qry = mysql_query("SELECT * FROM `".$database_prefix."request` WHERE id = '".$id."'");
                $account = mysql_fetch_array($qry);	
                $server = server_info($account['server_id']);
                
	    		//Passwort verarbeiten
		        if ($account['pw'] == "") {
		            //Passwortgenerator
		            $pw_generator = pw_gen($a = '3', $b = '2', $num = '3', $spec = '0');
		            //Umwandeln in md5 (für Admin DB)
		            $passwort_generated = md5($pw_generator); 
		            //Übergabe PW in Variablen einmal unverschlüsselt, und einmal verschlüsselt!
		            $passwort_md5 = $passwort_generated;
		            $passwort_clean = $pw_generator;
		            } elseif ($account['pw'] != "") {
		                //Umwandeln in md5 (für Admin DB)
		                $passwort_generated = md5($account['pw']);
		                //Übergabe PW in Variablen einmal unverschlüsselt, und einmal verschlüsselt!
		                $passwort_md5 = $passwort_generated;
		                $passwort_clean = $account['pw'];
		        }

                //Definiere ob User oder Admin und erstelle entsprechendes Konto
                if ($account['type'] == 1) { //User
                	//Checke ob Username nicht bereits genutzt
			    	if (checkIfUserExists($account['server_id'], $account['name']) != TRUE) {
			    		//Erstelle $UserInfoMAP-Array
			    		$UserInfoMap = array("0" => $account['name'],
			    							 "1" => $account['email'],
			    							 "2" => "User created with Mumb1e Admin Plugin at ".$aktdate." by Request form.",
			    							 "4" => $passwort_clean,
			    							);
			    		
			    		//Speichere User ab
			    		$user_id = registerUser($account['server_id'], $UserInfoMap);
					}
					
					//Gebe Meldung aus
                	if ($user_id) {
                		//Definiere Platzhalter für E-Mail
				    	$Placeholder = array("ServerName" => $server['name'],
				    						 "ServerIP" => $server['ip'],		
				    						 "ServerPort" => $server['port'],   
				    						 "UserName" => $account['name'],   
				    						 "UserPassword" => $passwort_clean, 
				    						 "AdminName" => $_MAPUSER['teamtag'],
				    						 );    	
				    	//Sende E-Mail
				    	$Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
				    						  $account['email'], $account['name'], FALSE,
				    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '19004',
				    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    						  $AltSubject = FALSE, $AltBody = FALSE);
	                    //Konto löschen
                		$delete = mysql_query("DELETE FROM `".$database_prefix."request` WHERE id = '".$id."' LIMIT 1");
	                    
						//Gebe Meldung aus
		    			$info .= "<br><div align=\"center\"><div class='boxsucess'>"._request_account_sucsess_do_1.$account['name']._request_account_sucsess_do_2."</div></div>";
	                	$autoforward = TRUE;
	                	} else {
	                    	$info .= "<br><div align=\"center\"><div class='savearea'>"._request_account_false_1.$account['name']._request_account_false_2."</div></div>";
	                    	$autoforward = FALSE;
            		}
                	} elseif ($account['type'] == 2) { //Admin
                		//Check ob Adminname nicht bereits besteht
                		if (checkIfAdminExists($account['name'], FALSE) == FALSE) {
                			//Generiere neue perm_id
			    			$perm_id = pw_gen("3", "3", "3", "0");
			    			//Speicher Userdaten
			                $save_user = "INSERT INTO `".$database_prefix."user` (`type_id`, `group_id`, `perm_id`, `customer_id`, `name`, `username`, `clantag`, `email`, `pw`, `logins`, `last_active`, `lock`, `language`, `template`, `coockie`, `icq`, `discr`, `phone`, `street`, `postalcode`, `city`, `country`) VALUES ('0','$_MAPUSER[group_id]','$perm_id','','$account[name]','$account[name]','','$account[email]','".$passwort_md5."','0','$aktdate','1','$_MAPUSER[language]','$_MAPUSER[template]','$_MAPUSER[coockie]','','"."Admin created with Mumb1e Admin Plugin at ".$aktdate." by Request form"."','','','','','')";
			                $qry = mysql_query($save_user);
			                $user_id = mysql_insert_id();
			                //zuständigen Server adden
			        		mysql_query("INSERT INTO `".$database_prefix."user_servers` (`user_id`, `server_id`) VALUES ('".$user_id."', '".$account['server_id']."');");
                			//User-Perms in DB schreiben
							$sections = getSectionIDs();
			            	foreach ($sections as $section) {
								mysql_query("INSERT INTO `".$database_prefix."user_perm` (`perm_id`, `section_id`, `value`) VALUES ('".$perm_id."', '".$section."', '0');");
							}
                		}
						
                		//Gebe Meldung aus
	                	if ($user_id) {
		                    //Definiere Platzhalter für E-Mail
					    	$Placeholder = array("ServerName" => $server['name'],
					    						 "MAPURL" => $getHTTP . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'],		   
					    						 "UserName" => $account['name'],   
					    						 "UserPassword" => $passwort_clean, 
					    						 "AdminName" => $_MAPUSER['teamtag'],
					    						 );  
					    	//Sende E-Mail
					    	$Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
					    						  $account['email'], $account['name'], FALSE,
					    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
					    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '19005',
					    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
					    						  $AltSubject = FALSE, $AltBody = FALSE);
		                    //Konto löschen
               	 			$delete = mysql_query("DELETE FROM `".$database_prefix."request` WHERE id = '".$id."' LIMIT 1");
	                		
							//Gebe Meldung aus
			    			$info .= "<br><div align=\"center\"><div class='boxsucess'>"._request_account_sucsess_do_1.$account['name']._request_account_sucsess_do_2."</div></div>";
		                	$autoforward = TRUE;
		                	} else {
		                    	$info .= "<br><div align=\"center\"><div class='savearea'>"._request_account_false_1.$account['name']._request_account_false_2."</div></div>";
		                    	$autoforward = FALSE;
	            		}               		
                }
    		}
    		} else {
    			//Meldung und Weiterleitung
    			$info .= "<br><div align=\"center\"><div class='savearea'>"._user_selected_wrong."</div></div>";
	         	$autoforward = FALSE;
    	}
    	       
        //Loggingfunktion, Übergabe der Werte: Settings geändert
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "request_db_1";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $account['server_id'];		//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $account['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
 
        //Weiterleitung nach 3 Sekunden
        if ($autoforward == TRUE) {
            autoforward("../request/index.php?section=list_accounts",5);
        }
        
        //Weiterleitung nach 5 Sekunden
        if ($autoforward == FALSE) {
            autoforward("../request/index.php?section=list_accounts",5);
        }
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Beantragte Accounts freigeben und in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Formular um beantragte Accounts freizugeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'activate_account':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_activate_account;

    //Hole server_id und user_id aus Session(Liste) oder aus URL
    if (isset($_GET['id'])) {
    	$ids[0] = $_GET['id'];
    	} elseif (isset($_SESSION['id'])) {
			$ids = $_SESSION['id'];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("activate_account", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:

    	//Daten ans Formular schicken und / bzw. es aufrufen
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _activate_account_head,
								 "navi_on" => TRUE,
								 "navi_type" => "request_activate",
								 );
		
    	//Wenn id aus Formular / Liste übergeben wurde, ansonsten fehler!
    	if (isset($ids)) {
    		
    		$i = "0";
    		//Arbeite Accounts ab
    		foreach($ids as $id) {
   			
	    		//Hole Infos
                $qry = mysql_query("SELECT * FROM `".$database_prefix."request` WHERE id = '".$id."'");
                $account = mysql_fetch_array($qry);	
                $server = server_info($account['server_id']);
		    	   			
		    	//Schreibe Session
				$_SESSION['activate_account'][$i] = $id;
	
				//Setze Trennbalken
			    if($i > "0") {
					$line = '<hr />';
					} else {
						$line = '';
				}   
				$i++;     
	
    			//Kontoart für beantragten Account vorgeben (User oder Admin)
                if ($account['type'] == "1") {
                    $type = _request_account_status_server;
                    } else {
                        $type = _request_account_status_map;
                }
                
    			// PW Ausgabe definieren
                if ($account['pw'] == "") {
                    $pw_info = _activate_account_email_status_1;
                    } elseif ($account['pw'] != "") {
                        $pw_info = _activate_account_email_status_2;
                }
						        
				$index .= show("$dir/activate_account", array("type" => $type,
                                                             "server" => $server['name'],
                                                             "at" => $account['request_time'],
                                                             "name" => $account['name'],
                                                             "email" => $account['email'],
                                                             "pw" => $pw_info,
															 "line" => $line,
                                                             "activate_account_question" => _activate_account_question,
                                                             "activate_account_username" => _activate_account_username,
                                                             "activate_account_type" => _activate_account_type,
                                                             "activate_account_server" => _activate_account_server,
                                                             "activate_account_email" => _activate_account_email,
                                                             "activate_account_pw" => _activate_account_pw,
                                                             "activate_account_at" => _activate_account_at                                      
                                                             ));
	                            
	        	//Loggingfunktion, Übergabe der Werte: Settings geändert
				//Definiert ob etwas geloggt werden soll
				$log_values["on"] = TRUE;
				//Pflichtwerte
				$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
				$log_values["action_id"] = "request_show_2";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
				$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
				$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
				//Definierbare Werte (optional)
				$log_values["server_id"] = $account['server_id'];			//Definiert die Server_ID (kann frei gelassen werden)
				$log_values["value_1"] = $account['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				
		    }
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._user_selected_wrong."</div></div>";
    			autoforward("../request/index.php?section=list_accounts",3);
		}
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Formular um beantragte Accounts freizugeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  beantragte Accounts aus DB löschen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_account_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_delete_account_db;
    
	//Hole server_ids und user_ids aus Session(Liste) oder aus URL
	if (isset($_SESSION['delete_account'])) {
		$accounts = $_SESSION['delete_account'];
	}
	unset($_SESSION['id']);
	unset($_SESSION['delete_account']);
	
	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_account", FALSE)) {
    	
    	//Wenn Session nicht abgelaufen
    	if (isset($accounts)) {    	
	    	//Arbeite Array ab
	    	foreach ($accounts as $id) {
	    		
	    		//Hole Infos
                $qry = mysql_query("SELECT * FROM `".$database_prefix."request` WHERE id = '".$id."'");
                $account = mysql_fetch_array($qry);	
                $server = server_info($account['server_id']);
                
                //Definiere Grund der Ablehnung aus Formular aus $_POST[]
	    		$reason = $_POST["$id-reason"];   
		        if ($reason == "") {
		            $reason = _send_email_text_33;
		        }             
                
                //User löschen
                $do = mysql_query("DELETE FROM `".$database_prefix."request` WHERE id = '".$id."' LIMIT 1");
                
                //Sende E-Mail
		    	if ($do == TRUE) {        
		            //Definiere Platzhalter für E-Mail
			    	$Placeholder = array("UserName" => $account['name'],
			    						 "RequestTime" => $account['request_time'],
			    						 "Reason" => $reason,
			    						 "AdminName" => $_MAPUSER['teamtag'],		    	
			    						 );    	
			    	//Sende E-Mail
			    	$Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
			    						  $account['email'], $account['name'], FALSE,
			    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
			    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '19003',
			    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
			    						  $AltSubject = FALSE, $AltBody = FALSE);
		    	}
		    	
	    		//Meldung und Weiterleitung
	         	$info .= "<br><div align=\"center\"><div class='boxsucess'>". _delete_account_sucsessful_1 .$account['name']._delete_account_sucsessful_2."</div></div>";
        		$autoforward = TRUE;	 
	         	        	         	
	    	}
    		} else {
    			//Meldung und Weiterleitung
    			$info .= "<br><div align=\"center\"><div class='savearea'>"._user_selected_wrong."</div></div>";
	         	$autoforward = FALSE;
    	}
    	       
        //Loggingfunktion, Übergabe der Werte: Delete MAP User, Löschfunktion > in DB schreiben
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "request_db_3";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $account['server_id'];		//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $account['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $reason;				     	//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
    
        //Weiterleitung nach 3 Sekunden
        if ($autoforward == TRUE) {
            autoforward("../request/index.php?section=list_accounts",3);
        }
        
        //Weiterleitung nach 5 Sekunden
        if ($autoforward == FALSE) {
            autoforward("../request/index.php?section=list_accounts",5);
        }
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  beantragte Accounts aus DB löschen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Formular um beantragte Accounts in DB zu löschen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_account':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_delete_account;
    
    //Hole server_id und user_id aus Session(Liste) oder aus URL
    if (isset($_GET['id'])) {
    	$ids[0] = $_GET['id'];
    	} elseif (isset($_SESSION['id'])) {
			$ids = $_SESSION['id'];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_account", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Daten ans Formular schicken und / bzw. es aufrufen
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _defeat_account_head,
								 "navi_on" => TRUE,
								 "navi_type" => "request_delete",
								 );
		
    	//Wenn id aus Formular / Liste übergeben wurde, ansonsten fehler!
    	if (isset($ids)) {
    		
    		$i = "0";
    		//Arbeite Accounts ab
    		foreach($ids as $id) {
   			
	    		//Hole Infos
                $qry = mysql_query("SELECT * FROM `".$database_prefix."request` WHERE id = '".$id."'");
                $account = mysql_fetch_array($qry);	
                $server = server_info($account['server_id']);
		    	   			
		    	//Schreibe Session
				$_SESSION['delete_account'][$i] = $id;
	
				//Setze Trennbalken
			    if($i > "0") {
					$line = '<hr />';
					} else {
						$line = '';
				}   
				$i++;     
	
    			//Kontoart für beantragten Account vorgeben (User oder Admin)
                if ($account['type'] == "1") {
                    $type = _request_account_status_server;
                    } else {
                        $type = _request_account_status_map;
                }
						        
				$index .= show("$dir/delete_account", array("type" => $type,
	                                                       "server" => $server['name'],
	                                                       "at" => $account['request_time'],
	                                                       "name" => $account['name'],
	                                                       "id" => $account['id'],
														   "line" => $line,
	                                                       "defeat_account_question" => _defeat_account_question,
	                                                       "defeat_account_name" => _defeat_account_name,
	                                                       "defeat_account_type" => _defeat_account_type,
	                                                       "defeat_account_server" => _defeat_account_server,
	                                                       "defeat_account_at" => _defeat_account_at,
	                                                       "defeat_account_reason" => _defeat_account_reason,
	                                                       "defeat_account_reason_explain" => _defeat_account_reason_explain                              
	                                                       ));
	                            
	        	//Loggingfunktion, Übergabe der Werte: Settings geändert
				//Definiert ob etwas geloggt werden soll
				$log_values["on"] = TRUE;
				//Pflichtwerte
				$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
				$log_values["action_id"] = "request_show_5";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
				$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
				$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
				//Definierbare Werte (optional)
				$log_values["server_id"] = $account['server_id'];		//Definiert die Server_ID (kann frei gelassen werden)
				$log_values["value_1"] = $account['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
				
		    }
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._user_selected_wrong."</div></div>";
    			autoforward("../request/index.php?section=list_accounts",3);
		}
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Formular um beantragte Accounts in DB zu löschen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Beantragte Accounts in DB editieren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_account_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_edit_account_db;
    $errorUser = FALSE;
    $errorAdmin = FALSE;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("edit_account", FALSE)) {    
    	
        // Eingaben speichern, aus Formular übernehmen
        $id = $_POST["id"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $passwort1 = $_POST["pw_1"];
        $passwort2 = $_POST["pw_2"];
        $type = $_POST["type"];
        $server_id = $_POST["server_id"]; 
               
        //DB nach alten Userdaten auslesen
        $qry = mysql_query("SELECT * FROM `".$database_prefix."request` WHERE id = '".$id."' LIMIT 1");
        $account = mysql_fetch_array($qry);
        
        //Prüft ob User bereits vorhanden, wenn Name geändert wurde und speichere, wenn OK
        if ($account['name'] != $name && $name != "") {
        	//Checke ob User nicht bereits existiert
        	if ($type == "1") {
        		$errorUser = checkIfUserExists($server_id, $name);
        		if ($errorUser == FALSE) {
        			mysql_query("UPDATE `".$database_prefix."request` SET name = '$name' WHERE id = '$id'");
                	$autoforward = TRUE;
        			} else {
        				$info .= "<br><div align=\"center\"><div class='savearea'>"._user_allready_exists_1.$name._user_allready_exists_2."</div></div>";
        				$autoforward = FALSE;
        		}
        	}
        	//Checke ob Admin nicht bereits existiert
        	if ($type == "2") {
        		$errorAdmin = checkIfAdminExists($name, FALSE);
        		if ($errorAdmin == FALSE) {
        			mysql_query("UPDATE `".$database_prefix."request` SET name = '$name' WHERE id = '$id'");
                	$autoforward = TRUE;
        			} else {
        				$info .= "<br><div align=\"center\"><div class='savearea'>"._user_allready_exists_1.$name._user_allready_exists_2."</div></div>";
        				$autoforward = FALSE;
        		}
        	}
        	} else {
        	$autoforward = FALSE;
        }
        
    	//Überprüft ob E-Mail Adresse richtig eingegeben wurde bzw. ob diese existiert
        $check_email = check_email($email);
        // Ergebnisauswertung des E-Mail-Checks und entspr. Vergabe der Werte
        if ($check_email == TRUE) {
            //E-Mail, falls geändert in DB schreiben
            if ($account['email'] != $email) {
                mysql_query("UPDATE `".$database_prefix."request` SET email = '$email' WHERE id = '$id'");
                $autoforward = TRUE;  
            }
            } else {
                $info .= "<br><div align=\"center\"><div class='savearea'>"._wrong_email_entry."</div></div>";
                $autoforward = FALSE;
        }
        
        //Überprüft und ändert, wenn valid die Kontoart
        if ($type != $account['type']) {
        	if (($errorUser == FALSE AND $type == "1") OR ($errorAdmin == FALSE AND $type == "2")) {
                mysql_query("UPDATE `".$database_prefix."request` SET type = '$type' WHERE id = '$id'");
                $autoforward = TRUE;    
            }
        }
    
        //Server_ID, falls geändert in DB schreiben
        if ($server_id != $account['server_id']) {
            if (($errorUser == FALSE AND $type == "1") OR ($errorAdmin == FALSE AND $type == "2")) {
                mysql_query("UPDATE `".$database_prefix."request` SET server_id = '$server_id' WHERE id = '$id'");
                $autoforward = TRUE; 
            }
        }
    
        //Passwort, falls geändert in DB schreiben
        if($passwort1 != "") {
            if($passwort2 != "") {
                if($passwort1 == $passwort2) {
                    if($passwort1 != $account['pw']) {
                        if($passwort2 != $account['pw']) {
                            mysql_query("UPDATE `".$database_prefix."request` SET pw = '$passwort1' WHERE id = '".$id."'");
                            $autoforward = TRUE;
                        } 
                    	} elseif ($passwort1 == $account['pw']) {
                       		$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_pwd_false_2."</div></div>";
                        	$autoforward = FALSE;
                	}
                	} elseif ($passwort1 != $passwort2) {
                  		$info .= "<br><div align=\"center\"><div class='savearea'>"._edit_pwd_false_1."</div></div>";
                  		$autoforward = FALSE;
	            }
            }
        }
        
        // Wenn Änderung vorgenommen, das Feld "beantragt am" beschreiben, damit Datum nicht verfälscht wird
        if ($autoforward == TRUE) {
            mysql_query("UPDATE `".$database_prefix."request` SET request_time = '$account[request_time]' WHERE id = '".$id."'");
        	$info .= "<br><div align=\"center\"><div class='boxsucess'>" . _edit_account_sucsessful_1 . $name . _edit_account_sucsessful_2 ."</div></div>";
        }
    
        //Meldung, wenn nichts geändert wurde!
        if ($autoforward == FALSE) {
            $info .= "<br><div align=\"center\"><div class='boxsucess'>"._no_change_edit_funktion."</div></div>";
        }
        
        //Loggingfunktion, Übergabe der Werte: Settings geändert
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "request_db_6";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $name;							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
    
        //Direktweiterleitung
        if ($autoforward == TRUE){
            autoforward("../request/index.php?section=list_accounts",3);
        }    
        //Weiterleitung nach 5 Sekunden
        if ($autoforward == FALSE){
            autoforward("../request/index.php?section=list_accounts",5);
        }
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Beantragte Accounts in DB editieren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Forumlar beantragte Accounts editieren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_account':
    // Formular um beantragte accounts zu editeren
    $seitentitel .= _pagetitel_edit_account;
    
	//Hole user_id und server_id aus Session(Liste) oder aus URL
    if (isset($_GET['id'])) {
    	$ids = $_GET['id'];
    	} elseif (isset($_SESSION['id'])) {
    		$ids = $_SESSION['id'];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("edit_account", FALSE)) {     
		//Start definition eigentliche Funktionen dieser Section:
                
    	//Check ob nur ein User ausgewählt wurde, ansonsten Fehlermldung und weiter leiten
    	if (count($ids) == "1") {
    		
    		//Nach der Zählung, der selektierten User, mache Array zum String
    		$ids = $ids[0];

    		//Hole beanragtes Konto
			$qry = mysql_query("SELECT * FROM `".$database_prefix."request` WHERE id = '".$ids."'");
			$account = mysql_fetch_array($qry);
			
    		//Status für beantragten Account vorgeben (Admin oder User)
    		$type = FALSE;
			if ($account['type'] == "1") {
				$type .= "<option value=\"1\" selected=\"selected\">"._request_account_status_server."</option>\n";
				$type .= "<option value=\"2\">"._request_account_status_map."</option>\n";
				} else {
					$type .= "<option value=\"1\">"._request_account_status_server."</option>\n";
					$type .= "<option value=\"2\" selected=\"selected\">"._request_account_status_map."</option>\n";
			}
			
			//Gebe liste von Server aus, die dem Account zugeordnet werden können
			$serverOutput = FALSE;
			$servers = getServers();
			foreach($servers as $server_id) {
				$server = server_info($server_id);
				if (get_perms("show_server", $server_id)) {
					if ($account['server_id'] == $server_id) {
						$serverOutput .= "<option value=\"".$server_id."\" selected=\"selected\">".$server['name']."</option>\n";
						} else {
							$serverOutput .= "<option value=\"".$server_id."\">".$server['name']."</option>\n";
					}
				}
			}

			// Ausgabe Passwort
			if ($account['pw'] == "") {  
				$output_pass1 = "<tr><td>" . _edit_account_pass_1 . "</td><td><input type=\"password\" size=\"24\" maxlength=\"50\" name=\"pw_1\" class=\"input\"></td></tr>";
				$output_pass2 = "<tr><td>" . _edit_account_pass_2 . "</td><td><input type=\"password\" size=\"24\" maxlength=\"50\" name=\"pw_2\" class=\"input\"></td></tr>";
				} else {
					$output_pass1 = "";
					$output_pass2 = "";
			}
			
			// Speichere in Ausgabe
            $content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
				                     "head_value" => _edit_account_head . $account['name'],
									 "navi_on" => TRUE,
									 "navi_type" => "request_edit",
									 );
			$index = show("$dir/edit_account", array("server_id" => $account['server_id'],
													 "id" => $account['id'],
													 "name" => $account['name'],
													 "email" => $account['email'],
													 "type" => $type,
													 "server" => $serverOutput,
													 "edit_name" => _edit_account_username,
													 "edit_email" => _edit_account_email,
													 "edit_type" => _edit_account_type,
													 "edit_server" => _edit_account_server,
													 "edit_pass1" => $output_pass1,
													 "edit_pass2" => $output_pass2,
													 "edit_do" => _edit_account_do,
													 ));
    		
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._user_selected_wrong."</div></div>";
    			autoforward("../request/index.php?section=list_accounts",3);
    	}
                                                         
        //Loggingfunktion, Übergabe der Werte: Settings geändert
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "request_show_7";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $account['server_id'];		//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $account['name'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
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
// **ENDE**  Forumlar beantragte Accounts editieren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Listenanzeige: Anzeige der aktuell beantragen Accounts
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'list_accounts':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_requested_accounts_list;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("list_accounts", FALSE)) {       
		//Start definition eigentliche Funktionen dieser Section: 
 		
    	//Hole beanragte Konten aus DB
		$qry = mysql_query("SELECT * FROM `".$database_prefix."request` ORDER BY request_time ASC");
		$check = mysql_fetch_array($qry);
		
		//Checke ob Accounts vorhanden
		if ($check != FALSE) {
			//Arbeite Accounts ab
			$qry = mysql_query("SELECT * FROM `".$database_prefix."request` ORDER BY request_time ASC");
			while ($account = mysql_fetch_array($qry)) {
				//Checke ob nur ein Account bantragt und er Admin keine Berechtigung
				if(count($check) == "1" && get_perms("list_accounts", $account['server_id']) == FALSE) {
					$list = show("$dir/no_accounts", array("empty" => _list_requested_accounts_empty));
					} else {
					//Hole Server Info
					$server = server_info($account['server_id']);
					
					//Checke ob der Admin diesen Account bearbeiten darf
					if(get_perms("list_accounts", $account['server_id'])) {
					
						//Status für beantragten Account vorgeben (Admin oder User)
						if ($account['type'] == "1") {
							$type = _request_account_status_server_short;
							} else {
								$type = _request_account_status_map_short;
						}
						
						//Zuweisung Email für leeres Feld
						if ($account['email'] == "") {
							$account['email'] = _no_email_available;
						}
						
						//Speichern der Userliste in Array
						$list .= show("$dir/list_accounts_elements", array("name" => $account['name'],
																		   "email" => $account['email'],
																		   "type" => $type,
																		   "server" => $server['name'],
																		   "request_time" => $account['request_time'],
																		   "ip" => long2ip($account['ip']),
																		   "action" => '<input type="checkbox" name="id[]" value="'.$account['id'].'">',
			                                                               ));
					}
				} 
			}
			//Ausgabe wenn keine beantragten Accounts vorhanden   
			} else {
				$list = show("$dir/no_accounts", array("empty" => _list_requested_accounts_empty,
                                                      ));
		}
		
		// Speichere in Ausgabe
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _list_requested_accounts_head,
								 "navi_on" => TRUE,
								 "navi_type" => "list_accounts",
								 );
		$index = show("$dir/list_accounts_head", array("list" => $list,
													   "name" => _list_requested_accounts_name,
													   "email" => _list_requested_accounts_email,
													   "type" => _list_requested_accounts_type,
													   "server" => _list_requested_accounts_server,
													   "request_time" => _list_requested_accounts_lactiv,
													   "ip" => _list_requested_accounts_ip,
                                                       "action" => _list_requested_accounts_action,
                                                       ));
            
		//Loggingfunktion, Übergabe der Werte: Settings geändert
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "request_show_8";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
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
    
	}  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Listenanzeige: Anzeige der aktuell beantragen Accounts
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Erweitertes beantragen von User und Admin Accounts => In DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'request_custom_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_request_account_custom_db;

    //Check ob der Frontend Request eingeschaltet ist (request_access)
	$perm = getPermRequestFunctions();	
	if ($perm['backend'] == TRUE) {
		
		//Hole Daten aus Forumlar
		if (isset($_POST['securePassword'])) {
			$_POST['securePassword'] = $_POST['securePassword'];
			} else {
				$_POST['securePassword'] = FALSE;
		}
		$form = array("name" => mysql_real_escape_string($_POST['name']),
					  "email" => mysql_real_escape_string($_POST['email']),
					  "type" => mysql_real_escape_string($_POST['type']),
					  "server_id" => mysql_real_escape_string($_POST['server_id']),
					  "pw_1" => $_POST['pw_1'],
					  "pw_2" => $_POST['pw_2'],
					  "securePassword" => $_POST["securePassword"],
					  );
		
		//Hole IP des Users
		$user_ip = $_SERVER['REMOTE_ADDR'];
		
		//Definiere Account Type
		if($form['type'] == "user") {
			$form['type'] = "1";
			} elseif($form['type'] == "admin") {
				$form['type'] = "2";
		}
		
		//Hole Server Info
		$server = server_info($form['server_id']);
		
		//Setze errors auf false
		$error1 = FALSE;
		$error2 = FALSE;
		$error3 = FALSE;
		$error4 = FALSE;
		$error5 = FALSE;
		$error6 = FALSE;
		$error7 = FALSE;

		//Kontrolle ob Eingaben identisch sind und ob keine Eingabe fehlt
        if ($form['name'] == "" OR $form['email'] == "" OR $form['pw_1'] != $form['pw_2']) {
            $info .= "<br><div align=\"center\"><div class='savearea'>"._request_wrong_entry_try_again."</div></div>";
            $autoforward = FALSE;
            $error1 = TRUE;
        }
        
        //DB Abfrage ob im Formuar secureCode oder securePassword verwendet wurde und Werte entsprechend aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '19'");
        $secure = mysql_fetch_array($qry);
        //Wenn Passwort Abfrage aktiv
        if ($secure[2] == '1') { 
        	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '18'");
            $SecurePass = mysql_fetch_array($qry);
            if ($form['securePassword'] != $SecurePass[2] OR $form['securePassword'] == "") {
                $info .= "<br><div align=\"center\"><div class='savearea'>"._request_wrong_secure_password."</div></div>";
                $autoforward = FALSE;
                $error2 = TRUE;
            }
        	//Wenn Code Abfrage Aktiv
            } elseif ($secure[2] == '0') {
            	$captcha = getCaptchaStatus();
	            if ($captcha != TRUE) {
	                $info .= "<br><div align=\"center\"><div class='savearea'>"._request_wrong_secure_code."</div></div>";
	                $autoforward = FALSE;
	                $error2 = TRUE;
	            }          	
        }
        
        //Überprüft ob E-Mail Adresse richtig eingegeben wurde bzw. ob diese existiert
        $checkEmail = check_email($form['email']);
        // Ergebnisauswertung des E-Mail-Checks und entspr. Vergabe der Werte
        if ($checkEmail == FALSE) {
            $info .= "<br><div align=\"center\"><div class='savearea'>"._request_wrong_email_entry."</div></div>";
            $autoforward = FALSE;
            $error3 = TRUE;
        }
        
        //Checke ob User nicht bereits existiert
		$error4 = checkIfUserExists($form['server_id'], $form['name']);
		
		//Checke ob Admin nicht bereits existiert
		$error5 = checkIfAdminExists($form['name'], FALSE);
		
		//Checke ob Account mit $form[name] nicht bereits beantragt wurde
		$qry = mysql_query("SELECT id FROM `".$database_prefix."request` WHERE name = '$form[name]'");
        $qryAccName = mysql_num_rows($qry);
        if ($qryAccName != 0) {
        	$error6 = TRUE;
        	} else {
        		$error6 = FALSE;
        }
        
		//Checke ob Account mit $form[email] nicht bereits beantragt wurde
		$qry = mysql_query("SELECT id FROM `".$database_prefix."request` WHERE email = '$form[email]'");
        $qryAccEmail = mysql_num_rows($qry);
        $qry = mysql_query("SELECT user_id FROM `".$database_prefix."user` WHERE email = '$form[email]'");
        $qryUserEmail = mysql_num_rows($qry);
        if ($qryAccEmail != 0 OR $qryUserEmail != 0) {
        	$error7 = TRUE;
        	} else {
        		$error7 = FALSE;
        }
  		
        //Wenn Eingaben + Auth (GoogleCode oder Passwort) und E-Mail OK, gehe weiter
        if ($error1 != TRUE && $error2 != TRUE && $error3 != TRUE) {
        	//Checke ob User mit dem Namen $form[name] irgendwo bereits vorhanden ist, also Req-DB, Admin-DB oder auf Server
            if ($error4 != TRUE && $error5 != TRUE && $error6 != TRUE) {
                //Checke ob bereits ein Account mit $form[email] - Email beantragt wurde
            	if ($error7 != TRUE) {
                    $save_account = "INSERT INTO `".$database_prefix."request` (server_id, type, name, email, pw, ip) VALUES ('$form[server_id]','$form[type]','$form[name]','$form[email]','$form[pw_1]', '" . ip2long($user_ip) . "')";
                    $qry = mysql_query($save_account);
                    if ($qry == TRUE) {
						//Mail an User der Konto beantragt hat
						if ($form['pw_1'] == "") {
							$form['pw_1'] = _send_email_text_25;
						}
						//Definiere Platzhalter für E-Mail
				    	$Placeholder = array("UserName" => $form['name'],
				    						 "ServerName" => $server['name'],
				    						 "Password" => $form['pw_1'],			    	
				    						 );    	
				    	//Sende E-Mail
				    	$Connection = mapmail($form['email'], $form['name'], FALSE, 
				    						  $form['email'], $form['name'], FALSE,
				    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '19001',
				    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    						  $AltSubject = FALSE, $AltBody = FALSE);
						//Benachrichtigungs-E-Mail an zuständigen Admin senden!
                        $admin = user_info($server['guarantor']); 
						$mapUrl = "<a href=\"" . $getHTTP . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . "\" target=\"_blank\">" . _send_email_text_54 . "</a>";
						//Definiere Platzhalter für E-Mail
				    	$Placeholder = array("UserName" => $form['name'],
				    						 "ServerURL" => $mapUrl,			    	
				    						 );    	
				    	//Sende E-Mail
				    	$Connection = mapmail($admin['email'], $admin['teamtag'], $admin['user_id'], 
				    						  $admin['email'], $admin['teamtag'], $admin['user_id'],
				    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '19002',
				    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    						  $AltSubject = FALSE, $AltBody = FALSE);
                        $info = "<br><div align=\"center\"><div class='boxsucess'>"._request_create_sucsess_1.$form['name']._request_create_sucsess_2."</div></div>";
                        $autoforward = TRUE;
                        } else {
                            $info = "<br><div align=\"center\"><div class='savearea'>"._request_create_false_1.$form['name']._request_create_false_2."</div></div>";
                            $autoforward = FALSE;
                    }
                    } else {
                        $info = "<br><div align=\"center\"><div class='savearea'>"._request_email_allready_exists_1.$form['email']._request_email_allready_exists_2."</div></div>";
                        $autoforward = FALSE;
                }
                } else {
                    $info = "<br><div align=\"center\"><div class='savearea'>"._request_user_allready_exists_1.$form['name']._request_user_allready_exists_2."</div></div>";
                    $autoforward = FALSE;
            }
        }
        
        //Loggingfunktion, Übergabe der Werte: Settings geändert
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "request_db_9";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $form['server_id'];			//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $form['name'];					//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
         
        //Weiterleitung nach 7 Sekunden! zu start
        if ($autoforward == TRUE) {
            autoforward("../start/index.php",7);
        }  
        //Weiterleitung nach 4 Sekunden! zu request
        if ($autoforward == FALSE) {
            autoforward("../request/index.php?section=request_custom&account_type=" . $form['type'] . "&server_id=" . $form['server_id'], 4);
        }

        } else {
            $info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_request_account."</div></div>";
            autoforward("../start/index.php",5);
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Erweitertes beantragen von User und Admin Accounts => In DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Erweitertes beantragen von User und Admin Accounts => Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'request_custom':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_request_account_custom;
    $head_custom = FALSE;

    //Check ob der Frontend Request eingeschaltet ist (request_access)
	$perm = getPermRequestFunctions();	
	if ($perm['backend'] == TRUE) {
        
		//Check ob Admin Passwort ein oder aus geschaltet ist und entsprechende Ausgabe!
		//Check ob Admin Passwort ein oder aus geschaltet ist und entsprechende ausgabe!
		$codePasswordViewA = FALSE;
        $codePasswordViewB = FALSE;
        $codeCaptchaViewA = FALSE;
        $codeCaptchaViewB = FALSE;
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '19'");
        $get = mysql_fetch_array($qry);
        if ($get[2] == '1') {
        	$codeHead = _request_account_password;
            $codeCaptchaViewA = "<!--";
        	$codeCaptchaViewB = "-->";
            $rule = _request_account_rule_1;
            } elseif ($get[2] == '0') {
				$codeHead = _request_account_secure_code;
				$codeCaptcha = recaptcha_get_html($GoogleReCaptchaPublicKey, FALSE);
            	$codePasswordViewA = "<!--";
        		$codePasswordViewB = "-->";
            	$rule = _request_account_rule_2;
        }
        
        //Definiere Head
        $server = server_info($_GET['server_id']);
		if ($_GET['account_type'] == "user") {
            $head_custom = _request_account_custom_head_serveruser . $server['name'];
            } elseif ($_GET['account_type'] == "admin") {
                $head_custom = _request_account_custom_head_mapuser . $server['name'];
        }
        
        //Daten ans Formular schicken und / bzw. es aufrufen
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
				                 "head_value" => $head_custom,
								 "navi_on" => TRUE,
								 "navi_type" => "request_backend",
								 );
								 
		$index = show("$dir/form_custom", array("request_name" => _request_account_username,
	                                            "request_email" => _request_account_email,
	                                            "request_pw_1" => _request_account_new_pass_1,
	                                            "request_pw_2" => _request_account_new_pass_2,
	                                            "request_code_head" => $codeHead,
        								 		"request_code_captcha" => $codeCaptcha,
        								 		"request_code_PasswordViewA" => $codePasswordViewA,
        								 		"request_code_PasswordViewB" => $codePasswordViewB,
        								 		"request_code_CaptchaViewA" => $codeCaptchaViewA,
        								 		"request_code_CaptchaViewB" => $codeCaptchaViewB,
                                         		"request_rule" => $rule,
	                                            "request_server_id" => $_GET['server_id'],
	                                            "request_account_type" => $_GET['account_type'],
	                                            ));						 
        } else {
            $info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_request_account."</div></div>";
            autoforward("../start/index.php",5);
    }                           
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Erweitertes beantragen von User und Admin Accounts => Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Beantragter Account in DB schreiben + Info
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'request_account_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_request_account_db;
    
    //Check ob der Frontend Request eingeschaltet ist (request_access)
	$perm = getPermRequestFunctions();	
	if ($perm['frontend'] == TRUE) {

		//Hole Daten aus Forumlar
		if (isset($_POST['securePassword'])) {
			$_POST['securePassword'] = $_POST['securePassword'];
			} else {
				$_POST['securePassword'] = FALSE;
		}
		$form = array("name" => mysql_real_escape_string($_POST['name']),
					  "email" => mysql_real_escape_string($_POST['email']),
					  "type" => $_POST['type'],
					  "server_id" => $_POST['server_id'],
					  "pw_1" => $_POST['pw_1'],
					  "pw_2" => $_POST['pw_2'],
					  "securePassword" => $_POST['securePassword'],
					  );
		
		//Hole IP des Users
		$user_ip = $_SERVER['REMOTE_ADDR'];
		
		//Hole Server Info
		$server = server_info($form['server_id']);

		//Setze errors auf false
		$error1 = FALSE;
		$error2 = FALSE;
		$error3 = FALSE;
		$error4 = FALSE;
		$error5 = FALSE;
		$error6 = FALSE;
		$error7 = FALSE;
		
		//Kontrolle ob Eingaben identisch sind und ob keine Eingabe fehlt
        if ($form['name'] == "" OR $form['email'] == "" OR $form['pw_1'] != $form['pw_2']) {
            $info .= "<br><div align=\"center\"><div class='savearea'>"._request_wrong_entry_try_again."</div></div>";
            $autoforward = FALSE;
            $error1 = TRUE;
        }
        
        //DB Abfrage ob im Formuar secureCode oder securePassword verwendet wurde und Werte entsprechend aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '19'");
        $secure = mysql_fetch_array($qry);
        //Wenn Passwort Abfrage aktiv
        if ($secure[2] == '1') { 
        	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '18'");
            $SecurePass = mysql_fetch_array($qry);
            if ($form['securePassword'] != $SecurePass[2] OR $form['securePassword'] == "") {
                $info .= "<br><div align=\"center\"><div class='savearea'>"._request_wrong_secure_password."</div></div>";
                $autoforward = FALSE;
                $error2 = TRUE;
            }
        	//Wenn Code Abfrage Aktiv
            } elseif ($secure[2] == '0') {
            	$captcha = getCaptchaStatus();
	            if ($captcha != TRUE) {
	                $info .= "<br><div align=\"center\"><div class='savearea'>"._request_wrong_secure_code."</div></div>";
	                $autoforward = FALSE;
	                $error2 = TRUE;
	            }          	
        }
        
        //Überprüft ob E-Mail Adresse richtig eingegeben wurde bzw. ob diese existiert
        $checkEmail = check_email($form['email']);
        // Ergebnisauswertung des E-Mail-Checks und entspr. Vergabe der Werte
        if ($checkEmail == FALSE) {
            $info .= "<br><div align=\"center\"><div class='savearea'>"._request_wrong_email_entry."</div></div>";
            $autoforward = FALSE;
            $error3 = TRUE;
        }
        
        //Checke ob User nicht bereits existiert
		$error4 = checkIfUserExists($form['server_id'], $form['name']);
		
		//Checke ob Admin nicht bereits existiert
		$error5 = checkIfAdminExists($form['name'], FALSE);
		
		//Checke ob Account mit $form[name] nicht bereits beantragt wurde
		$qry = mysql_query("SELECT id FROM `".$database_prefix."request` WHERE name = '$form[name]'");
        $qryAccName = mysql_num_rows($qry);
        if ($qryAccName != 0) {
        	$error6 = TRUE;
        	} else {
        		$error6 = FALSE;
        }
        
		//Checke ob Account mit $form[email] nicht bereits beantragt wurde
		$qry = mysql_query("SELECT id FROM `".$database_prefix."request` WHERE email = '$form[email]'");
        $qryAccEmail = mysql_num_rows($qry);
        $qry = mysql_query("SELECT user_id FROM `".$database_prefix."user` WHERE email = '$form[email]'");
        $qryUserEmail = mysql_num_rows($qry);
        if ($qryAccEmail != 0 OR $qryUserEmail != 0) {
        	$error7 = TRUE;
        	} else {
        		$error7 = FALSE;
        }
  		
        //Wenn Eingaben + Auth (GoogleCode oder Passwort) und E-Mail OK, gehe weiter
        if ($error1 != TRUE && $error2 != TRUE && $error3 != TRUE) {
        	//Checke ob User mit dem Namen $form[name] irgendwo bereits vorhanden ist, also Req-DB, Admin-DB oder auf Server
            if ($error4 != TRUE && $error5 != TRUE && $error6 != TRUE) {
                //Checke ob bereits ein Account mit $form[email] - Email beantragt wurde
            	if ($error7 != TRUE) {
                    $save_account = "INSERT INTO `".$database_prefix."request` (server_id, type, name, email, pw, ip) VALUES ('$form[server_id]','$form[type]','$form[name]','$form[email]','$form[pw_1]', '" . ip2long($user_ip) . "')";
                    $qry = mysql_query($save_account);
                    if ($qry == TRUE) {
						//Mail an User der Konto beantragt hat
						if ($form['pw_1'] == "") {
							$form['pw_1'] = _send_email_text_25;
						}
						//Definiere Platzhalter für E-Mail
				    	$Placeholder = array("UserName" => $form['name'],
				    						 "ServerName" => $server['name'],
				    						 "Password" => $form['pw_1'],			    	
				    						 );    	
				    	//Sende E-Mail
				    	$Connection = mapmail($form['email'], $form['name'], FALSE, 
				    						  $form['email'], $form['name'], FALSE,
				    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '19001',
				    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    						  $AltSubject = FALSE, $AltBody = FALSE);
						//Benachrichtigungs-E-Mail an zuständigen MAP-User senden!
                        $admin = user_info($server['guarantor']); 
						$mapUrl = "<a href=\"" . $getHTTP . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . "\" target=\"_blank\">" . _send_email_text_54 . "</a>";
						//Definiere Platzhalter für E-Mail
				    	$Placeholder = array("UserName" => $form['name'],
				    						 "ServerURL" => $mapUrl,			    	
				    						 );    	
				    	//Sende E-Mail
				    	$Connection = mapmail($admin['email'], $admin['teamtag'], $admin['user_id'], 
				    						  $admin['email'], $admin['teamtag'], $admin['user_id'],
				    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
				    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '19002',
				    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				    						  $AltSubject = FALSE, $AltBody = FALSE);
						$info = "<br><div align=\"center\"><div class='boxsucess'>"._request_create_sucsess_1.$form['name']._request_create_sucsess_2."</div></div>";
                        $autoforward = TRUE;
                        } else {
                            $info = "<br><div align=\"center\"><div class='savearea'>"._request_create_false_1.$form['name']._request_create_false_2."</div></div>";
                            $autoforward = FALSE;
                    }
                    } else {
                        $info = "<br><div align=\"center\"><div class='savearea'>"._request_email_allready_exists_1.$form['email']._request_email_allready_exists_2."</div></div>";
                        $autoforward = FALSE;
                }
                } else {
                    $info = "<br><div align=\"center\"><div class='savearea'>"._request_user_allready_exists_1.$form['name']._request_user_allready_exists_2."</div></div>";
                    $autoforward = FALSE;
            }
        }
        
        //Loggingfunktion, Übergabe der Werte: Settings geändert
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "request_db_10";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $form['server_id'];			//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $form['name'];					//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
         
        //Weiterleitung nach 7 Sekunden! zu start
        if ($autoforward == TRUE) {
            autoforward("../start/index.php",7);
        }  
        //Weiterleitung nach 4 Sekunden! zu request
        if ($autoforward == FALSE) {
            autoforward("../request/index.php?default",4);
        }
    
        } else {
            $info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_request_account."</div></div>";
            autoforward("../start/index.php",5);
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Beantragter Account in DB schreiben + Info
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Übergabescript an bearbeiten und Löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'action':
	
	//Hole Daten aus Listenanzeige: Adminliste und leite weiter
	//Übergebe ID´s	
	$_SESSION['id'] = $_POST['id'];
	
	//Definiere Sections
	$avaliableActions = array("edit_x" => "edit_account",
							  "delete_x" => "delete_account",
							  "activate_x" => "activate_account",
							  );
							  
	//Leite weiter
	foreach ($avaliableActions as $key => $value) {
		if (isset($_POST[$key])) {
			header("Location: index.php?section=".$value);
			
		}
	}

break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Übergabescript an bearbeiten und Löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Anzeige des Formulars um User Accounts zu beantragen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_request_account;

	//Check ob der Frontend Request eingeschaltet ist (request_access_page)
	$perm = getPermRequestFunctions();	
	if ($perm['frontend'] == TRUE) {
		
		//Hole alle Server
		$servers = getServers();
		
		//Erstelle Server Liste
		$serverList = FALSE;
		foreach($servers as $server_id) {
			
			//Hole Info zu Server
			$server = server_info($server_id);
			
			//Schreibe Liste
			$serverList .= "<option value=\"".$server_id."\">".$server['name']."</option>\n";
			
		}

		//Check ob Admin Passwort ein oder aus geschaltet ist und entsprechende ausgabe!
		$codePasswordViewA = FALSE;
        $codePasswordViewB = FALSE;
        $codeCaptchaViewA = FALSE;
        $codeCaptchaViewB = FALSE;
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '19'");
        $get = mysql_fetch_array($qry);
        if ($get[2] == '1') {
        	$codeHead = _request_account_password;
            $codeCaptchaViewA = "<!--";
        	$codeCaptchaViewB = "-->";
            $rule = _request_account_rule_1;
            } elseif ($get[2] == '0') {
				$codeHead = _request_account_secure_code;
				$codeCaptcha = recaptcha_get_html($GoogleReCaptchaPublicKey, FALSE);
            	$codePasswordViewA = "<!--";
        		$codePasswordViewB = "-->";
            	$rule = _request_account_rule_2;
        }
        
        //Daten ans Formular schicken und / bzw. es aufrufen
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
				                 "head_value" => _request_account_head,
								 "navi_on" => TRUE,
								 "navi_type" => "request_frontend",
								 );
        $index = show("$dir/form", array("type" => "<option value=\"1\" selected=\"selected\">"._request_account_status_server."</option>\n<option value=\"2\">"._request_account_status_map."</option>\n",
                                         "server" => $serverList,
                                         "request_name" => _request_account_username,
                                         "request_email" => _request_account_email,
                                         "request_type" => _request_account_status,
                                         "request_server" => _request_account_server,
                                         "request_pw_1" => _request_account_new_pass_1,
                                         "request_pw_2" => _request_account_new_pass_2,
                                         "request_code_head" => $codeHead,
        								 "request_code_captcha" => $codeCaptcha,
        								 "request_code_PasswordViewA" => $codePasswordViewA,
        								 "request_code_PasswordViewB" => $codePasswordViewB,
        								 "request_code_CaptchaViewA" => $codeCaptchaViewA,
        								 "request_code_CaptchaViewB" => $codeCaptchaViewB,
                                         "request_rule" => $rule,
                                         )); 
		} else {
			$info = "<br><div align=\"center\"><div class='savearea'>"._no_permission_request_account."</div></div>";
			autoforward("../start/index.php",5);
	}
break;                           
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Anzeige des Formulars um User Accounts zu beantragen
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