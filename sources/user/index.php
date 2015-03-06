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
$dir = "user";
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
// **START**  User der verbunden ist registrieren Abschluss
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'register_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_register_user_db;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("create_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Globale Daten
        $server_id = $_POST['server_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        
        //DB nach Daten auslesen
        $server = server_info($server_id);   
        
        if ($email != "") {
            	
        	//Generiere Pwd
        	$pw = pw_gen($a = '3', $b = '2', $num = '3', $spec = '0');
            	
        	//Registriere User
        	$UserInfoMap[0] = $name;
        	$UserInfoMap[1] = $email;
        	$UserInfoMap[4] = $pw;
        	registerUser($server_id, $UserInfoMap);                

            //Definiere Platzhalter für E-Mail
	    	$Placeholder = array("ServerName" => $server['name'],
	    						 "ServerIP" => $server['ip'],		
	    						 "ServerPort" => $server['port'],   
	    						 "UserName" => $name,   
	    						 "UserPassword" => $pw,
	    	 					 "UserEMail" => $email,
	    						 "AdminName" => $_MAPUSER['teamtag'],
	    						 );    	
	    	//Sende E-Mail
	    	$Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
	    						  $email, $name, FALSE,
	    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
	    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '14005',
	    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
	    						  $AltSubject = FALSE, $AltBody = FALSE);

            //Message + Weiterleitung bei erfolgreicher Registrierung
            $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_register_sucsess.$name."</div></div>";
            autoforward("../view/index.php?section=channelviewer&server_id=". $server_id,3);
        	} else {
        		//Message + Weiterleitung bei erfolgreicher Registrierung
            	$info = "<br><div align=\"center\"><div class='savearea'>"._server_user_register_no_sucsess_1.$name._server_user_register_no_sucsess_2."</div></div>";
            	autoforward("../user/index.php?section=register_user&server_id=". $server_id . "&name=" . $name,8);
        }
                
        //Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_13";			    //Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $name;         			 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $email;	       				//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";				     		//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

                				
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  User der verbunden ist registrieren Abschluss
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  User der verbunden ist registrieren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'register_user':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_register_user;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("create_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Globale Daten
        $server_id = $_GET['server_id'];
        $name = $_GET['name'];
        
        if (checkIfUserExists($server_id, $name) == FALSE) {
            	
	        //DB nach Daten auslesen
	        $server = server_info($server_id);      
	                
	        //Daten ans Formular schicken und / bzw. es aufrufen
	        $content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
				                     "head_value" => _register_server_user_head.$name,
									 "navi_on" => TRUE,
									 "navi_type" => "register_user",
									 );
	        $index = show("$dir/register_user", array("server_id" => $server_id,
			                                          "name" => $name,
			                                          "server" => $server['name'],
			                                          "discription" => _register_server_user_discription,
			                                          "name_head" => _register_server_user_uname_head,
			                                          "server_head" => _register_server_user_server_head,
	          										  "email_head" => _register_server_user_email_head
			                                          )); 
        	} else {
        		$info = "<br><div align=\"center\"><div class='savearea'>"._register_server_user_allready_registered."</div></div>";
            	autoforward("../view/index.php?section=channelviewer&server_id=". $server_id,5);
        }
        
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  User der verbunden ist registrieren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Erstellen eines Kommerntars eines Users Abschluss
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_comment_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_create_comment_user_db;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("edit_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Globale Daten
        $server_id = $_POST['server_id'];
        $user_id = $_POST['user_id'];
        $text = $_POST['text'];
        
        //Hole User Info
        $user = getRegistration($server_id, $user_id); 
            	
        //Comment erstellen, wenn nicht =0
        if ($text != "" OR $text != $user['comment']) {

        	//setze den Kommentar
        	$UserInfoMap[2] = $text;
        	setRegistration($server_id, $user_id, $UserInfoMap);

	        //Message für erfolg + Weiterleitung
	        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_create_comment_sucsess.$user['name']."</div></div>";
	        autoforward("../view/index.php?section=channelviewer&server_id=". $server_id,3);
        	} else {
            	//Message dass kein kommentar eingegeben wurde + Weiterleitung
		        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_create_comment_false."</div></div>";
		        autoforward("../view/index.php?section=channelviewer&server_id=". $server_id,3);
		}
                
        //Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_15";			    //Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $user_id;         			 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $text;	         				//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";				     		//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
			
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Erstellen eines Kommerntars eines Users Abschluss
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Erstellen eines Kommerntars eines Users
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_comment_user':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_create_comment_user;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("edit_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Globale Daten
        $server_id = $_GET['server_id'];
        $user_id = $_GET['user_id'];
            	
        //DB nach Daten auslesen
        $server = server_info($server_id);
                
        //Hole User Info
        $user = getRegistration($server_id, $user_id);         
                
        //Daten ans Formular schicken und / bzw. es aufrufen
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _create_comment_server_user_head.$user['name'],
								 "navi_on" => TRUE,
								 "navi_type" => "create_comment_user",
								 );
        $index = show("$dir/create_comment_user", array("server_id" => $server_id,
              											"user_id" => $user_id,
		                                                "discription" => _create_comment_server_user_discription,
		                                                "name_head" => _create_comment_server_user_uname_head,
		                                                "server_head" => _create_comment_server_user_server_head,
                 		                                "text_head" => _create_comment_server_user_text_head,
		                                                "name" => $user['name'],
		                                                "server" => $server['name']
		                                                )); 
                				
         //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Erstellen eines Kommerntars eines Users
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Ändern des Kommerntars eines Users Abschluss
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_comment_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_edit_comment_user_db;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("edit_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Globale Daten
        $server_id = $_POST['server_id'];
        $user_id = $_POST['user_id'];
        $text = $_POST['text'];
            	
		//Hole User Info
        $user = getRegistration($server_id, $user_id); 
            	
        //Kommentar ändern
        if ($text != $user['comment']) {

        	//setze den Kommentar
        	$UserInfoMap[2] = $text;
        	setRegistration($server_id, $user_id, $UserInfoMap);

	        //Message für erfolg + Weiterleitung
	        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_edit_comment_sucsess.$user['name']."</div></div>";
	        autoforward("../view/index.php?section=channelviewer&server_id=". $server_id,3);
        	} else {
            	//Message dass kein kommentar eingegeben wurde + Weiterleitung
		        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_create_comment_false."</div></div>";
		        autoforward("../view/index.php?section=channelviewer&server_id=". $server_id,3);
		}

		//Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_13";			    //Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $user_id;         			 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $text;	         				//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";				     		//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
               				
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Ändern des Kommerntars eines Users Abschluss
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Ändern des Kommerntars eines Users
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_comment_user':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_edit_comment_user;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("edit_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Globale Daten
        $server_id = $_GET['server_id'];
        $user_id = $_GET['user_id'];
            	
        //DB nach Daten auslesen
		$server = server_info($server_id);
		
		//Hole User Info
        $user = getRegistration($server_id, $user_id);            
                
        //Daten ans Formular schicken und / bzw. es aufrufen
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _edit_comment_server_user_head.$user['name'],
								 "navi_on" => TRUE,
								 "navi_type" => "edit_comment_user",
								 );
        $index = show("$dir/edit_comment_user", array("server_id" => $server_id,
                									  "user_id" => $user_id,
		                                              "head" => _edit_comment_server_user_head,
		                                              "discription" => _edit_comment_server_user_discription,
		                                              "name_head" => _edit_comment_server_user_uname_head,
		                                              "server_head" => _edit_comment_server_user_server_head,
                    		                          "text_head" => _edit_comment_server_user_text_head,
                									  "text" => $user['comment'],
		                                              "name" => $user['name'],
		                                              "server" => $server['name']
		                                              )); 
                				
		//Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Ändern des Kommerntars eines Users
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  User eine Nachricht Abschluss
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'message_to_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_message_to_user_db;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("message_to_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Globale Daten
        $server_id = $_POST['server_id'];
        $session = $_POST['session'];
        
        //Definiere Text
        $text = $_MAPUSER['teamtag'] . " " . _message_to_server_user_message;
        $text .= $_POST['text'];
            	
        //Hole User Infos
        $UserState = getUserState($server_id, $session);
            	
        //Hole Server Info
        $server = server_info($server_id);
        
        //Sende Nachricht ab
        sendMessagetoUser($server_id, $session, $text);

        //Message + Weiterleitung
        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_send_message_sucsess_1.$UserState->name._server_user_send_message_sucsess_2."</div></div>";
        autoforward("../view/index.php?section=channelviewer&server_id=". $server_id,3);
                
        //Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_12";			    //Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $UserState->name; 			 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $text;	         				//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = $session;						//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  User eine Nachricht senden Abschluss
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  User eine Nachricht senden
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'message_to_user':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_message_to_user;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("message_to_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Hole Globale Daten
        $server_id = $_GET['server_id'];
        $session = $_GET['session'];
            	
        //Hole User Infos
        $UserState = getUserState($server_id, $session);
            	
        //Hole Server Info
        $server = server_info($server_id);
                
        //Daten ans Formular schicken und / bzw. es aufrufen
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
			                     "head_value" => _message_to_server_user_head.$UserState->name,
								 "navi_on" => TRUE,
								 "navi_type" => "message_to_user",
								 );
        $index = show("$dir/message_to_user", array("server_id" => $server_id,
               									    "session" => $session,
          											"name" => $UserState->name,
          											"server" => $server['name'],
		                                            "discription" => _message_to_server_user_discription,
		                                            "name_head" => _message_to_server_user_uname_head,
		                                            "server_head" => _message_to_server_user_server_head,
              		                                "text_head" => _message_to_server_user_text_head
		                                            )); 
                				
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  User eine Nachricht senden
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  User vom Server kicken
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'kick_user_default_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_kick_user_default;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("kick_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Globale Daten um User zu kicken
        $server_id = $_GET['server_id'];
        $session = $_GET['session'];
        $reason = "Kicked via Mumb1e Admin Plugin by the Server Administrator: " . $_MAPUSER['teamtag'];
        
        //Hole User Infos
        $UserState = getUserState($server_id, $session);

        //Kicke User
        kickUser($server_id, $session, $reason);

        //Message + Weiterleitung
        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_kick_sucsess_default_1.$UserState->name._server_user_kick_sucsess_default_2."</div></div>";
        autoforward($_SERVER['HTTP_REFERER'],2);
                
        //Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_11";			    //Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $UserState->name; 			 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";	         				//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
                				
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  User vom Server kicken
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  User vom Server Stumm stellen, EIN
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'mute_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_mute_user;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("mute_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Globale Daten um User stumm zu stellen
        $server_id = $_GET['server_id'];
        $session = $_GET['session'];
		
		//Stelle User stumm
		$UserState = getUserState($server_id, $session);
		$UserState->mute = TRUE;
		setUserState($server_id, $UserState);

        //Message + Weiterleitung
        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_mute_sucsess_1.$UserState->name._server_user_mute_sucsess_2."</div></div>";
        autoforward($_SERVER['HTTP_REFERER'],2);
                
        //Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_7";			    	//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $UserState->name;     		 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";	        				//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
                				
        //Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  User vom Server Stumm stellen, EIN
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  User vom Server Stumm stellen, AUS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'unmute_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_unmute_user;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("mute_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Globale Daten um User zu entmuten
        $server_id = $_GET['server_id'];
        $session = $_GET['session'];
            	       	
        //Entstumme User
		$UserState = getUserState($server_id, $session);
		$UserState->mute = FALSE;
		setUserState($server_id, $UserState);

        //Message + Weiterleitung
        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_unmute_sucsess_1.$UserState->name._server_user_unmute_sucsess_2."</div></div>";
        autoforward($_SERVER['HTTP_REFERER'],2);
                
        //Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_8";			    	//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $UserState->name;  		 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
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
// **ENDE**  User vom Server Stumm stellen, AUS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server User vom Server taub stellen, EIN
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'deaf_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_deaf_user;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("deaf_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Globale Daten um User taub zu stellen
        $server_id = $_GET['server_id'];
        $session = $_GET['session'];

        //Mache User taub
        $UserState = getUserState($server_id, $session);
		$UserState->deaf = TRUE;
		setUserState($server_id, $UserState);

        //Message + Weiterleitung
        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_deaf_sucsess_1.$UserState->name._server_user_deaf_sucsess_2."</div></div>";
        autoforward($_SERVER['HTTP_REFERER'],2);
                
        //Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_9";			    	//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $UserState->name;			 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
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
// **ENDE**  Server User vom Server taub stellen, EIN
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Server User vom Server taub stellen, AUS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'undeaf_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_server_user_undeaf_user;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("deaf_user", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:
                
        //Globale Daten um User zu entdeafen
        $server_id = $_GET['server_id'];
        $session = $_GET['session'];
            	       	
        //Mache User untaub
        $UserState = getUserState($server_id, $session);
		$UserState->deaf = FALSE;
		setUserState($server_id, $UserState);

        //Message + Weiterleitung
        $info = "<br><div align=\"center\"><div class='boxsucess'>"._server_user_undeaf_sucsess_1.$UserState->name._server_user_undeaf_sucsess_2."</div></div>";
        autoforward($_SERVER['HTTP_REFERER'],2);
                
        //Loggingfunktion, Übergabe der Werte: Server stoppen
        //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_10";		    	//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $UserState->name;    		 	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
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
// **ENDE**  Server User vom Server taub stellen, AUS
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** User erstellen > speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_create_user_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("create_user", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Hole Daten aus Formular
    	$form = array("name" => $_POST['name'],
    				  "email" => $_POST['email'],
    				  "server" => $_POST['server'],
    				  "pw_1" => $_POST['pw_1'],
    				  "pw_2" => $_POST['pw_2'],
    				  "comment" => $_POST['comment'],
    				 );
    				 
    	//Checke E-Mail Adresse
    	$email = check_email($form['email']);
    	
    	//Checke ob gesendete Daten richtig sind
    	if ($form['name'] != "" 
    	AND $email == TRUE 
    	AND is_numeric($form['server']) == TRUE 
    	AND $form['pw_1'] == $form['pw_2']
    	AND $form['pw_1'] != ""
    	AND substr_count($form['name'], "Ä") == 0 
		AND substr_count($form['name'], "ä") == 0 
		AND substr_count($form['name'], "Ö") == 0 
		AND substr_count($form['name'], "ö") == 0 
		AND substr_count($form['name'], "Ü") == 0 
		AND substr_count($form['name'], "ü") == 0 
		AND substr_count($form['name'], "+") == 0 
		AND substr_count($form['name'], "*") == 0 
		AND substr_count($form['name'], "#") == 0 
		AND substr_count($form['name'], "<") == 0 
		AND substr_count($form['name'], ">") == 0 
		AND substr_count($form['name'], "|") == 0 
		AND substr_count($form['name'], "!") == 0 
		AND substr_count($form['name'], "§") == 0 
		AND substr_count($form['name'], "$") == 0 
		AND substr_count($form['name'], "%") == 0 
		AND substr_count($form['name'], "&") == 0 
		AND substr_count($form['name'], "/") == 0 
		AND substr_count($form['name'], "(") == 0 
		AND substr_count($form['name'], ")") == 0 
		AND substr_count($form['name'], "[") == 0 
		AND substr_count($form['name'], "]") == 0 
		AND substr_count($form['name'], "{") == 0 
		AND substr_count($form['name'], "}") == 0 
		AND substr_count($form['name'], "\\") == 0 
		AND substr_count($form['name'], "ß") == 0 
		AND substr_count($form['name'], "?") == 0) {
    		$result = TRUE;
    		} else {
    			$result = FALSE;
    	}
    	
    	//Checke ob Username nicht bereits genutzt
    	if (checkIfUserExists($form['server'], $form['name']) != TRUE) {
	    	//wenn Daten OK, speichere User
	    	if ($result == TRUE) {
	    		
	    		//Erstelle $UserInfoMAP-Array
	    		$UserInfoMap = array("0" => $form['name'],
	    							 "1" => $form['email'],
	    							 "2" => $form['comment'],
	    							 "4" => $form['pw_1'],
	    							);
	    		
	    		//Speichere User ab
	    		$user_id = registerUser($form['server'], $UserInfoMap);
	    		
	    		//Gebe info aus und leite weiter
	    		$info = "<br><div align=\"center\"><div class='boxsucess'>"._create_user_sucsess_1.$form['name']._create_user_sucsess_2."</div></div>";
				$autoforward = TRUE;
					} else {
						$info = "<br><div align=\"center\"><div class='savearea'>"._create_user_false_1.$form['name']._create_user_false_2."</div></div>";
						$autoforward = FALSE;
			}
			} else {
				$info = "<br><div align=\"center\"><div class='savearea'>"._create_user_allready_exists_1.$form['name']._create_user_allready_exists_2."</div></div>";
				$autoforward = FALSE;
		}
    	
        //Loggingfunktion, Übergabe der Werte: erstellte Server User in DB schreiben
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_1";					//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $form['server'];				//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $form['name'];					//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $user_id;						//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        
        //Weiterleitung nach 3 Sekunden
        if ($autoforward == TRUE) {
            autoforward("../user/index.php?default",3);
        }  
        //Weiterleitung nach 5 Sekunden
        if ($autoforward == FALSE) {
            autoforward("../user/index.php?section=create_user",5);
        }

    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  User erstellen > speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  User erstellen > Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_user':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_create_user;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("create_user", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
            	
    	$servers = getServers();
    	foreach ($servers as $server_id) {
    		if (get_perms("create_user", $server_id)) {
    			//Hole Server Infos
    			$server = server_info($server_id);
    			$list .= "<option value=\"".$server_id."\">".$server['name']."</option>\n";
    		}
    	}

    	//Daten ans Formular schicken und / bzw. es aufrufen
    	$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
								 "head_value" => _create_user_head,
								 "navi_on" => TRUE,
								 "navi_type" => "create_user",
								 );
		$index = show("$dir/create_user", array("server" => $list,
												"create_name" => _create_user_name,
												"create_email" => _create_user_email,
												"create_server" => _create_user_server,
												"create_pw_1" => _create_user_pw_1,
												"create_pw_2" => _create_user_pw_2,
												"create_comment" => _create_user_comment,
												"create_do" => _create_user_do
												));  
               
		//Ende definition eigentliche Funktionen dieser Section^^
    }    
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  User erstellen > Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Edit User, eingetragene Daten speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_user_db':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_edit_user_db;

	//lösche vorhandene Sessions
	unset($_SESSION['id']);

	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("edit_user", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:

        //Hole Daten aus Formular
        $server_id = $_POST["server_id"];
        $user_id = $_POST["user_id"];
        //Hole Infos
		$user = getRegistration($server_id, $user_id);
		$server = server_info($server_id);
		//Hole weiter POSTs
		if ($user['name'] == "SuperUser") {
			$name = "SuperUser";
        	$email = "";
			} else {
				$name = $_POST["name"];
       			$email = $_POST["email"];
		}
		$pw_1 = $_POST["pw_1"];
        $pw_2 = $_POST["pw_2"];
        $comment = $_POST["comment"];
        
        //Checke ob SuperUser
        if ($user_id != "0") {
        	
        	//Editiere alle nicht "SuperUser"

		    //Check, was geändert wurde und erstelle UserInfoMap Array
		    //Name
        	//Checke ob Username nicht bereits genutzt
    		if (checkIfUserExists($server_id, $name) != TRUE) {
    			//Vergleiche
			    if ($user['name'] != $name) {
			    	//Überprüfe ob falsche Buchstaben nicht verwendet werden
			    	if (substr_count($name, "Ä") == 0 
			    	AND substr_count($name, "ä") == 0 
			    	AND substr_count($name, "Ö") == 0 
			    	AND substr_count($name, "ö") == 0 
			    	AND substr_count($name, "Ü") == 0 
			    	AND substr_count($name, "ü") == 0 
			    	AND substr_count($name, "+") == 0 
			    	AND substr_count($name, "*") == 0 
			    	AND substr_count($name, "#") == 0 
			    	AND substr_count($name, "<") == 0 
			    	AND substr_count($name, ">") == 0 
			    	AND substr_count($name, "|") == 0 
			    	AND substr_count($name, "!") == 0 
			    	AND substr_count($name, "§") == 0 
			    	AND substr_count($name, "$") == 0 
			    	AND substr_count($name, "%") == 0 
			    	AND substr_count($name, "&") == 0 
			    	AND substr_count($name, "/") == 0 
			    	AND substr_count($name, "(") == 0 
			    	AND substr_count($name, ")") == 0 
			    	AND substr_count($name, "[") == 0 
			    	AND substr_count($name, "]") == 0 
			    	AND substr_count($name, "{") == 0 
			    	AND substr_count($name, "}") == 0 
			    	AND substr_count($name, "\\") == 0 
			    	AND substr_count($name, "ß") == 0 
			    	AND substr_count($name, "?") == 0) {
				    	$UserInfoMap[0] = $name;
				    	$username = $name;
				    	$true_change = TRUE;
				    	} else {
			    		$UserInfoMap[0] = $user['name'];
			    		$username = $user['name'];
			    	}
			    	} else {
			    		$UserInfoMap[0] = $user['name'];
			    		$username = $user['name'];
		    	}
	    		} else {
					$UserInfoMap[0] = $user['name'];
					$username = $user['name'];
		    }
		    
		    //E-Mail
	    	if ($user['email'] != $email) {
	    		$check = check_email($email);
	    		if ($check) {
	    			$UserInfoMap[1] = $email;
	    			$true_change = TRUE;
	    			} else {
		    			$UserInfoMap[1] = $user['email'];
		 	   }
		    	} else {
		    		$UserInfoMap[1] = $user['email'];
		    }
		    
		    //Kommentar
		    if ($user['comment'] != $comment) {
				$UserInfoMap[2] = $comment;
				$true_change = TRUE;
		    	} else {
		    		$UserInfoMap[2] = $user['comment'];
		    }
		    
		    //Passwort
		    if (($pw_1 == $pw_2) && ($pw_1 != "")) {
	    		$UserInfoMap[4] = $pw_1;
	    		$true_change = TRUE;
		    }
		    
		    //Speichere geänderte Daten
		    if ($true_change == TRUE) {
		    	$do = setRegistration($server_id, $user_id, $UserInfoMap);
		    }
	
	        //Meldung, wenn nichts geändert wurde!
	        if ($true_change == TRUE) {
	            $info = "<br><div align=\"center\"><div class='boxsucess'>" . _edit_user_sucess . $username . " (" . $server['name'] . ")" . "</div></div>";
	            autoforward("../user/index.php?default", 3);
	        	} else {
	        		$info = "<br><div align=\"center\"><div class='savearea'>"._no_change_edit_funktion."</div></div>";
	            	autoforward("../user/index.php?default", 5);
	        }
	    
        	} else {
        		
        		//Editiere "SuperUser"
        		$username = $user['name'];
        		
	        	//Kommentar
			    if ($user['comment'] != $comment) {
					$UserInfoMap[2] = $comment;
					$true_change = TRUE;
			    	} else {
			    		$UserInfoMap[2] = $user['comment'];
			    }
        		
        		//Passwort
			    if (($pw_1 == $pw_2) && ($pw_1 != "")) {
		    		$UserInfoMap[4] = $pw_1;
		    		$true_change = TRUE;
			    }
			    
			    //Speichere geänderte Daten
	        	if ($true_change == TRUE) {
			    	$do = setRegistration($server_id, $user_id, $UserInfoMap);
			    }
			    
	        	//Meldung, wenn nichts geändert wurde!
		        if ($true_change == TRUE) {
		            $info = "<br><div align=\"center\"><div class='boxsucess'>" . _edit_user_sucess . $username . " (" . $server['name'] . ")" . "</div></div>";
		            autoforward("../user/index.php?default", 3);
		        	} else {
		        		$info = "<br><div align=\"center\"><div class='savearea'>"._no_change_edit_funktion."</div></div>";
		            	autoforward("../user/index.php?default", 5);
		        }

        }
        
        //Loggingfunktion, Übergabe der Werte: Edit Server User, eingetragene Daten in Datenbank schreiben
        //Definiert ob etwas geloggt werden soll
        if ($true_change == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_3";					//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $username;						//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $user_id;						//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)

    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Edit User, eingetragene Daten speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Edit User, Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_user':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_edit_user;
    
    //Hole user_id und server_id aus Session(Liste) oder aus URL
    if (isset($_GET['user_id'])) {
    	$ids[0] = $_GET['server_id'] . "-" . $_GET['user_id'];
	    } elseif (isset($_SESSION['id'])) {
	    	$ids = $_SESSION['id'];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("edit_user", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Check ob nur ein User ausgewählt wurde, ansonsten Fehlermldung und weiter leiten
    	if (count($ids) == "1") {
    		//Generiere $server_id und $user_id aus $id
    		$id = explode('-', $ids[0]);
    		$server_id = @$id[0];
    		$user_id = @$id[1];
    		//Wenn werte richtig übergeben, zeige Edit an, ansonsten Fehlermeldung
    		if (isset($server_id) AND isset($user_id)) {
				//Hole Infos
				$user = getRegistration($server_id, $user_id);
		    	$server = server_info($server_id);
	
		    	//Falls "SuperUser", verbiete das editieren des Namens
		    	if ($user_id == "0" && $user['name'] == "SuperUser") { 
		    		$disabled = 'disabled="disabled"';
		    		} else {
		    			$disabled = "";
		    	}
	    		
				//Daten ans Formular schicken und / bzw. es aufrufen
				$content_headers = array("head_on" => TRUE,
										 "head_type" => "default",
										 "head_value" => _edit_user_head.$user['name'],
										 "navi_on" => TRUE,
										 "navi_type" => "edit_user",
										 );        
				$index = show("$dir/edit_user", array("server_id" => $user['server_id'],
													  "user_id" => $user['user_id'],
													  "name" => $user['name'],
													  "disabled" => $disabled,
													  "email" => $user['email'],
													  "comment" => $user['comment'],
													  "edit_name" => _edit_user_name,
													  "edit_email" => _edit_user_email,
													  "edit_pw_1" => _edit_user_pw_1,
													  "edit_pw_2" => _edit_user_pw_2,
													  "edit_comment" => _edit_user_comment,
													  )); 
    			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._user_selected_wrong."</div></div>";
    			autoforward("../user/index.php?default",3);
    		}
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._user_selected_wrong."</div></div>";
    			autoforward("../user/index.php?default",3);
    	}
		//Ende definition eigentliche Funktionen dieser Section^^
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Edit User, Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Delete User, Löschfunktion > in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_user_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_delete_user_db;
    
	//Hole server_ids und user_ids aus Session(Liste) oder aus URL
	if (isset($_SESSION['delete_user'])) {
		$users = $_SESSION['delete_user'];
	}
	unset($_SESSION['id']);
	unset($_SESSION['delete_user']);
	
	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_user", FALSE)) {
    	
    	//Wenn Session nicht abgelaufen
    	if (isset($users)) {    	
	    	//Arbeite Array ab
	    	foreach ($users as $ids) {
	    		
	    		//Definiere ID´s
	    		$server_id = intval($ids[0]);
	    		$user_id = intval($ids[1]);
	    		
	    		//Hole Infos
				$user = getRegistration($server_id, $user_id);
	    		$server = server_info($server_id);
	
	    		//User löschen
	    		$do = deleteUser($server_id, $user_id);
	    		
	    		//Meldung und Weiterleitung
	         	$info .= "<br><div align=\"center\"><div class='boxsucess'>"._delete_user_sucsessful_1.$user['name']. " (" .  $server['name'] . ")" ._delete_user_sucsessful_2."</div></div>";
	         	$autoforward = TRUE;	 
	         	        	         	
	    	}
    		} else {
    			//Meldung und Weiterleitung
    			$info .= "<br><div align=\"center\"><div class='savearea'>"._delete_user_not_sucsessful."</div></div>";
	         	$autoforward = FALSE;
    	}
    	       
        //Loggingfunktion, Übergabe der Werte: Delete MAP User, Löschfunktion > in DB schreiben
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER["user_id"];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_6";					//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $user['name'];					//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $user_id;				     	//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
    
        //Weiterleitung nach 3 Sekunden
        if ($autoforward == TRUE) {
            autoforward("../user/index.php?default",3);
        }
        
        //Weiterleitung nach 5 Sekunden
        if ($autoforward == FALSE) {
            autoforward("../user/index.php?default",5);
        }
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Delete User, Löschfunktion > in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Delete User > Formular Ausgabe
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_user':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_delete_user;
    
    //Hole server_id und user_id aus Session(Liste) oder aus URL
    if (isset($_GET['user_id'])) {
    	$ids[0] = $_GET['server_id'] . "-" . $_GET['user_id'];
	    } elseif (isset($_SESSION['id'])) {
			$ids = $_SESSION['id'];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_user", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Wenn Server_id aus Formular / Liste übergeben wurde, ansonsten fehler!
    	if (isset($ids) AND (is_array($ids) OR @preg_match("-", $ids))) {
    		
    		$i = "0";
    		//Arbeite User ab
    		foreach($ids as $id) {
    			
    			//Generiere $server_id und $user_id aus $id
    			if (!is_array($id)) {
    				$id = explode('-', $id);
    			}
    			$server_id = intval($id[0]);
    			$user_id = intval($id[1]);
    			
    			//Hole Infos
				$user = getRegistration($server_id, $user_id);
	    		$status = getUserStatus($server_id, $user_id);	
	    		$server = server_info($server_id);	
	    		
	    		//Daten ans Formular schicken und / bzw. es aufrufen
					$content_headers = array("head_on" => TRUE,
											 "head_type" => "default",
											 "head_value" => _delete_user_head,
											 "navi_on" => TRUE,
											 "navi_type" => "delete_user",
											 ); 
											     			
    			//Wenn kein "SuperUser"
    			if ($user_id != 0) {
    			
	    			//Schreibe Session
					$_SESSION['delete_user'][$i] = $id;

					//Setze Trennbalken
		    		if($i > "0") {
						$line = '<hr />';
						} else {
							$line = '';
					}   
					$i++;     

    				//Platzhalter, falls keine E-Mail-Adresse oder Kommentar definiert
					if (!isset($user['email'])) {
			    		$user['email'] = _user_not_defined;
			    	}
					if (!isset($user['comment'])) {
			    		$user['comment'] = _user_not_defined;
			    	}
					        
					$index .= show("$dir/delete_user", array("name" => $user['name'],
															 "user_id" => $user_id,
															 "server" => $server['name'],
															 "status" => $status['status_icon'],
															 "email" => $user['email'],
															 "comment" => $user['comment'],
															 "lastactive" => $user['lastactive'],
															 "delete_question" => _delete_user_question,
															 "delete_name" => _delete_user_name,
															 "delete_user_id" => _delete_user_id,
															 "delete_status" => _delete_user_status,
															 "delete_server" => _delete_user_server,
															 "delete_email" => _delete_user_email,
															 "delete_comment" => _delete_user_comment,
															 "delete_lastactive" => _delete_user_lastactive,
															 "line" => $line,
															 ));
    				} elseif ($user_id == 0) {
						$info .= "<br><div align=\"center\"><div class='savearea'>"._user_delete_not_sucsessful_1.$user['name']. " - " . $server['name'] ._user_delete_not_sucsessful_2."</div></div>";
						$i++;
    			}
    		}
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._user_selected_wrong."</div></div>";
    			autoforward("../user/index.php?default",3);
		}
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Delete User > Formular Ausgabe
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Übergabescript an bearbeiten und Löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'action':

	//Hole Daten aus Listenanzeige: Userliste und leite weiter
	//Übergebe ID´s	
	$_SESSION['id'] = $_POST['id'];
	
	//Definiere Sections
	$avaliableActions = array("edit_x" => "edit_user",
							  "delete_x" => "delete_user",
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
// **START**  Listenanzeige: Anzeige der User
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_list_user;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("list_user", FALSE)) {       
		//Start definition eigentliche Funktionen dieser Section:  
    	
	    //Hole server_id und user_id aus Session(Liste) oder aus URL
	    if (isset($_GET['server_id'])) {
	    	$ServerID = $_GET['server_id'];
	    	$ServerID = array($ServerID);
	    	} else {
	    		$ServerID = FALSE;
	    }
    	
    	//Hole Server mit Perm und gebe aus
    	if ($ServerID == FALSE) {
    		$servers = getServers(); 
    		} else {
    			$servers = $ServerID;
    	}	
		foreach ($servers as $server_id) {
			//Checke ob Admin die User dieses Server sehen darf
			if (get_perms("list_user", $server_id)) {
				//Hole verantwortlichen des Servers
				$server = server_info($server_id);
				//Gebe Userliste aus, wenn Server Online ist
				if ($server['online']) {
					//Gebe die User des Servers aus
	    			foreach (array_keys($server['registered_users']) as $user_id) {
	    				//Hole UserInfo
	    				$user = getRegistration($server_id, $user_id);
	    				$status = getUserStatus($server_id, $user_id);
	    				
	    				//Platzhalter, falls keine E-Mail-Adresse oder Kommentar definiert
						if (!isset($user['email'])) {
				    		$user['email'] = _user_not_defined;
				    	}
	    				 				
	    				//Generiere user_id mit server_id zur weiteren Verwendung im Script
	    				$id = $server_id . "-" . $user_id;
	
		    			//Speichern der Userliste in Array
						$list .= show("$dir/user_list_elements", array("online" => $status['status_icon'],
																	   "name" => $user['name'],
																	   "server" => $server['name'],
																	   "email" => $user['email'],
																	   "lastactive" => $user['lastactive'],
																	   "action" => '<input type="checkbox" name="id[]" value="'.$id.'">',
																	   ));
		    		}
				}
			}
		}

		// Speichere in Ausgabe
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
								 "head_value" => _user_list_head,
								 "navi_on" => TRUE,
								 "navi_type" => "list_user",
								 );
		$index = show("$dir/user_list_head", array("list" => $list,
												   "status" => _userlist_status,
												   "name" => _userlist_name,
												   "server" => _userlist_server,
												   "email" => _userlist_email,
												   "lastactive" => _userlist_lastactive,
												   "action" => _userlist_action,
												   ));
													
		//Loggingfunktion, Übergabe der Werte: Anzeige der Server User
		//Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER["user_id"];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_show_12";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $ServerID;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = "";							//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
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
// **ENDE**  Listenanzeige: Anzeige der User
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