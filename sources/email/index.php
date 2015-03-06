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
$dir = "email";
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
switch ($section){
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** E-Mail Header bearbeiten - Änderungen speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'settings_header_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_settings_header_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_settings_header", FALSE)) {
        //Start definition eigentliche Funktionen dieser Section:
        
    	// Eingaben speichern -- Aus Formular Übernehmen
        $header = $_POST["header"];
    	
		//Hole E-Mail-Einstellungen
		$MailSettings = getMailSettings();
		
		//Vergleich, ggf. speichern
		if ($header != $MailSettings['email_header']) {
			$result = mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$header' WHERE id = 36");
		}	
		
		//Meldung ausgeben
        if ($result != FALSE) {            
            $info = "<br><div align=\"center\"><div class='boxsucess'>"._email_settings_header_sucessful."</div></div>";
        	autoforward("../settings/index.php?default",3);    
        	} else {
            	$info = "<br><div align=\"center\"><div class='savearea'>"._email_settings_header_error."</div></div>";
        		autoforward("../settings/index.php?default",5);
        }
        
		//Ende definition eigentliche Funktionen dieser Section^^
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE** E-Mail Header bearbeiten - Änderungen speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** E-Mail Header bearbeiten - Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'settings_header':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_settings_header;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_settings_header", FALSE)) {
        //Start definition eigentliche Funktionen dieser Section:
        
		//Hole E-Mail-Einstellungen
		$MailSettings = getMailSettings();
		
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
				                 "head_value" => _email_settings_header_head,
								 "navi_on" => TRUE,
								 "navi_type" => "settings_header",
								 );   
		$index = show("$dir/settings_header", array("discription" => _email_settings_header_discription,
													"content" => $MailSettings['email_header']
                                        			));
		
		//Ende definition eigentliche Funktionen dieser Section^^
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE** E-Mail Header bearbeiten - Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** E-Mail Footer bearbeiten - Änderungen speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'settings_footer_db':
    //Definition Seitentitel
    $seitentitel .= _pagetitel_email_settings_footer_db;
	
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_settings_footer", FALSE)) {
        //Start definition eigentliche Funktionen dieser Section:
        
    	// Eingaben speichern -- Aus Formular Übernehmen
        $footer = $_POST["footer"];
    	
		//Hole E-Mail-Einstellungen
		$MailSettings = getMailSettings();
		
		//Vergleich, ggf. speichern
		if ($footer != $MailSettings['email_footer']) {
			$result = mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$footer' WHERE id = 37");
		}	
		
		//Meldung ausgeben
        if ($result != FALSE) {            
            $info = "<br><div align=\"center\"><div class='boxsucess'>"._email_settings_footer_sucessful."</div></div>";
        	autoforward("../settings/index.php?default",3);    
        	} else {
            	$info = "<br><div align=\"center\"><div class='savearea'>"._email_settings_footer_error."</div></div>";
        		autoforward("../settings/index.php?default",5);
        }
        
		//Ende definition eigentliche Funktionen dieser Section^^
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE** E-Mail Footer bearbeiten - Änderungen speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** E-Mail Footer bearbeiten - Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'settings_footer':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_settings_footer;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_settings_footer", FALSE)) {
        //Start definition eigentliche Funktionen dieser Section:
        
		//Hole E-Mail-Einstellungen
		$MailSettings = getMailSettings();
		
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
				                 "head_value" => _email_settings_footer_head,
								 "navi_on" => TRUE,
								 "navi_type" => "settings_footer",
								 );   
		$index = show("$dir/settings_footer", array("discription" => _email_settings_footer_discription,
													"content" => $MailSettings['email_footer']
                                        			));
		
		//Ende definition eigentliche Funktionen dieser Section^^
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE** E-Mail Footer bearbeiten - Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Testmail versenden
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'check_email_settings_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_check_email_settings_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("settings_db_set2", FALSE)) {
        //Start definition eigentliche Funktionen dieser Section:
        
    	//Definiere Platzhalter für E-Mail
    	$Placeholder = array("Date" => $aktdate,
    						 "ResellerName" => $_MAPUSER['teamtag'],
    						 );    	
    	//Sende E-Mail
    	$Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
    						  $_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'],
    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '93001',
    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
    						  $AltSubject = FALSE, $AltBody = FALSE);
		
    	//Meldung ausgeben
        if ($Connection['result'] != FALSE) {            
            $info = "<br><div align=\"center\"><div class='boxsucess'>"._email_check_sucessful."</div></div>";
            } else {
            	$info = "<br><div align=\"center\"><div class='savearea'>"._email_check_error.$Connection['errors']."</div></div>";
        }
        
        //Weiterleitung nach 8 Sekunden
        autoforward("../settings/index.php?default",8);
		
		//Ende definition eigentliche Funktionen dieser Section^^
	}
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE** Testmail versenden
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  UnLock Template > Template entsperren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'unlock_template':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_unlock_template;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['template_id'])) {
    	$template_id = $_GET['template_id'];
    	} elseif (count($_SESSION['template_ids']) == "1") {
    		$template_id = $_SESSION['template_ids'][0];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_lock_templates", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene server_ids und gebe ggf. Fehlermeldung aus, wenn zu viele Server markiert wurden.
    	if (count($_SESSION['template_ids']) == "1" OR isset($_GET['template_id'])) {

			mysql_query("UPDATE `".$database_prefix."email_templates` SET `lock` = '1' WHERE template_id = '$template_id'");
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `user_id` = '$_MAPUSER[user_id]' WHERE template_id = '$template_id'");
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `date` = '$aktdate' WHERE template_id = '$template_id'");
			$info = "<br><div align=\"center\"><div class='boxsucess'>"._unlock_template_sucess."</div></div>";
			autoforward("../email/index.php?section=list_templates",3);
			
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._email_show_selected_wrong."</div></div>";
    			autoforward("../email/index.php?section=list_templates",3);
    	}
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  UnLock Template > Template entsperren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Lock Template > Template sperren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'lock_template':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_lock_template;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['template_id'])) {
    	$template_id = $_GET['template_id'];
    	} elseif (count($_SESSION['template_ids']) == "1") {
    		$template_id = $_SESSION['template_ids'][0];
    }
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_lock_templates", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene server_ids und gebe ggf. Fehlermeldung aus, wenn zu viele Server markiert wurden.
    	if (count($_SESSION['template_ids']) == "1" OR isset($_GET['template_id'])) {
             
			//DB auslesen über Template-Infos
			$template = getMailTemplate($template_id);
			
			//Checke ob gesperrt oder nicht, ggf. leite weiter
			if($template['lock'] == "1") {
	            
				mysql_query("UPDATE `".$database_prefix."email_templates` SET `lock` = '0' WHERE template_id = '$template_id'");
				mysql_query("UPDATE `".$database_prefix."email_templates` SET `user_id` = '$_MAPUSER[user_id]' WHERE template_id = '$template_id'");
				mysql_query("UPDATE `".$database_prefix."email_templates` SET `date` = '$aktdate' WHERE template_id = '$template_id'");
				$info = "<br><div align=\"center\"><div class='boxsucess'>"._lock_template_sucess."</div></div>";
				autoforward("../email/index.php?section=list_templates",3);
			
				} elseif ($template['lock'] == "0") {
					header("Location: index.php?section=unlock_template&template_id=$template_id");
			}
			
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._email_show_selected_wrong."</div></div>";
    			autoforward("../email/index.php?section=list_templates",3);
    	}
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Lock Template > Template sperren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Template bearbeiten DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_template_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_edit_template_db;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_edit_templates", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	    	
    	//Hole Variablen
		$template_id = $_POST['template_id'];
		$lock = $_POST['lock'];
		$subject =  $_POST['subject'];
		$body = $_POST['body'];
		
		//Hole Infos vom Template
		$template = getMailTemplate($template_id);
		//Hole Mail Sprache
		$template['MailLanguage'] = getMailLanguage($_MAPUSER['user_id'], $template_id);  
		//Hole Betreff
		$templateSubject = getMailContent($template_id, $template['MailLanguage'], $type = "subject");
		//Hole Content
		$templateBody = getMailContent($template_id, $template['MailLanguage'], $type = "body");
		
		//Checke ob Status geändert wurde
		if ($template['lock'] != $lock AND perm_handler("email_lock_templates", FALSE)) {
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `lock` = '$lock' WHERE template_id = '$template_id'");
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `user_id` = '$_MAPUSER[user_id]' WHERE template_id = '$template_id'");
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `date` = '$aktdate' WHERE template_id = '$template_id'");
			$Changes['lock'] = TRUE;
			} else {
				$Changes['lock'] = FALSE;
		}
		//Checke ob Subject geändert wurde
    	if ($templateSubject['text'] != $subject) {
    		mysql_query("UPDATE `".$database_prefix."email_content` SET `text` = '$subject' WHERE content_id = '$templateSubject[content_id]'");
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `user_id` = '$_MAPUSER[user_id]' WHERE template_id = '$template_id'");
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `date` = '$aktdate' WHERE template_id = '$template_id'");
			$Changes['subject'] = TRUE;
			} else {
				$Changes['subject'] = FALSE;
		}
    	//Checke ob Body geändert wurde
    	if ($templateBody['text'] != $body) {
    		mysql_query("UPDATE `".$database_prefix."email_content` SET `text` = '$body' WHERE content_id = '$templateBody[content_id]'");
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `user_id` = '$_MAPUSER[user_id]' WHERE template_id = '$template_id'");
			mysql_query("UPDATE `".$database_prefix."email_templates` SET `date` = '$aktdate' WHERE template_id = '$template_id'");
			$Changes['body'] = TRUE;
			} else {
				$Changes['body'] = FALSE;
		}

	    //Meldung ausgeben
	    if ($Changes['lock'] != FALSE OR $Changes['subject'] != FALSE OR $Changes['body'] != FALSE) {            
	        $info = "<br><div align=\"center\"><div class='boxsucess'>"._email_edit_template_sucessful."</div></div>";
	        } else {
	          	$info = "<br><div align=\"center\"><div class='savearea'>"._email_edit_template_no_change."</div></div>";
	    }	        
	        
	    //Weiterleitung nach 3 Sekunden
        autoforward("../email/index.php?section=list_templates",3);

		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Template bearbeiten DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//   	
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Template bearbeiten
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_template':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_edit_template;
    
	//Hole email_id aus Session(Liste) oder aus URL
    if (isset($_GET['template_id'])) {
    	$template_id = $_GET['template_id'];
    	} elseif (isset($_POST['template_id'])) {
    		$template_id = $_POST['template_id'];
    			} elseif (isset($_SESSION['template_ids'])) {
    				$template_id = $_SESSION['template_ids'][0];
    }
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_edit_templates", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene email_ids und gebe ggf. Fehlermeldung aus, wenn zu viele E-Mails markiert wurden.
    	if (count($template_id) == "1") {
    		
    		//Hole Infos vom Template
			$template = getMailTemplate($template_id);	
			//Hole Mail Sprache
			$template['MailLanguage'] = getMailLanguage($_MAPUSER['user_id'], $template_id);  
			//Hole Betreff
			$template['subject'] = getMailContent($template_id, $template['MailLanguage'], $type = "subject");
			$template['subject'] = $template['subject']['text'];
			//Hole Content
			$template['body'] = getMailContent($template_id, $template['MailLanguage'], $type = "body");
			$template['body'] = $template['body']['text'];
			//Hole Platzhalter
			$template['placeholder'] = getMailPlaceholder($template_id); 
			foreach ($template['placeholder'] as $key => $value) {
				$placeholder .= show("$dir/placeholder", array("name" => $value['name'],
															  "translation_name" => constant($value['translation_name']),
															  "translation_discription" => constant($value['translation_discription']),
													          ));
			}
			//Erhalte Infos zum Admin
			$admin = user_info($template['user_id']);
			//Setze Status des Templates
			if ($template['lock'] == "1") {
				$template['lock'] .= "<option value=\"1\" selected=\"selected\">"._email_edit_template_unlock."</option>\n";
            	$template['lock'] .= "<option value=\"0\">"._email_edit_template_lock."</option>\n";
				} else {
					$template['lock'] .= "<option value=\"1\">"._email_edit_template_unlock."</option>\n";
            		$template['lock'] .= "<option value=\"0\" selected=\"selected\">"._email_edit_template_lock."</option>\n";
			}
	
			// Speichere in Ausgabe
			$content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
									 "head_value" => _email_edit_template_head . $template['name'],
									 "navi_on" => TRUE,
									 "navi_type" => "email_edit_template",
									 );
			$index = show("$dir/edit_template", array("template_id" => $template_id,
													  "name" => $template['name'],
													  "user" => $admin['linked_name'],			
													  "date" => $template['date'],
													  "lock" => $template['lock'],
													  "subject" => $template['subject'],
												  	  "body" => $template['body'],
													  "placeholder" => $placeholder,
													  "head_template_id" => _email_edit_template_template_id,
													  "head_name" => _email_edit_template_name,
													  "head_user" => _email_edit_template_user,
													  "head_date" => _email_edit_template_date,
													  "head_lock" => _email_edit_template_lock_head,
													  "head_explanation" => _email_edit_template_lock_explanation,
													  "head_subject" => _email_edit_template_subject,
													  "head_body" => _email_edit_template_body,
													  "head_placeholder" => _email_edit_template_placeholder,
													  "head_placeholder_info" => _email_edit_template_placeholder_info,
													  ));					
			
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._email_show_selected_wrong."</div></div>";
    			autoforward("../email/index.php?section=list_templates",3);
    	}
	//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Template bearbeiten
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Liste der E-Mail Templates anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'list_templates':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_list_templates;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_list_templates", FALSE)) {
        //Start definition eigentliche Funktionen dieser Section:

	    //Hole E-Mails
		$qry = mysql_query("SELECT * FROM `".$database_prefix."email_templates` ORDER BY template_id ASC");
	    $i = "0";
		while ($get = mysql_fetch_array($qry)) {
			$template_ids[$i] = $get['template_id'];
			$i++;
		}
		
		//Schreibe E-Mails in Liste
		foreach ($template_ids as $template_id) {
			
				//Hole Infos vom Template
				$template = getMailTemplate($template_id);	
				//Hole Mail Sprache
				$template['MailLanguage'] = getMailLanguage($_MAPUSER['user_id'], $template_id);  
				//Hole Betreff
				$template['subject'] = getMailContent($template_id, $template['MailLanguage'], $type = "subject");
				$template['subject'] = $template['subject']['text'];
				//Hole Content
				$template['body'] = getMailContent($template_id, $template['MailLanguage'], $type = "body");
				$template['body'] = $template['body']['text'];
				//Hole Platzhalter
				unset($temporary);
				$template['placeholder'] = getMailPlaceholder($template_id); 
				foreach($template['placeholder'] as $value => $code) {
					if (isset($temporary)) {
						$temporary .= ", " . $value;
						} else {
							$temporary = $value;
					}
		    	}
				//Erhalte Infos zum Admin
				$admin = user_info($template['user_id']);
				//Setze Lock-Status
				if ($template['lock'] == "1") {
						$template['lock'] = "<a href=\"../email/index.php?section=lock_template&template_id=$template[template_id]\"><div align=\"center\"><img src=\"../inc/tpl/".$tpldir."/images/unlock.png\" alt=\"\" title=\""._email_list_template_lock_status_unlock."\" border=\"0\"></div></a>";
						} elseif ($template['lock'] == "0") {
							$template['lock'] = "<a href=\"../email/index.php?section=unlock_template&template_id=$template[template_id]\"><div align=\"center\"><img src=\"../inc/tpl/".$tpldir."/images/lock.png\" alt=\"\" title=\""._email_list_template_lock_status_lock."\" border=\"0\"></div></a>";
				}
		                  
				$list .= show("$dir/template_list", array("template_id" => $template['template_id'],
													      "name" => $template['name'],
													      "subject" => '<a href="../email/index.php?section=edit_template&template_id=' . $template['template_id'] . '">' . $template['subject'] . '</a>',
		               								      "body" => $template['body'],
													      "placeholder" => $temporary,
													      "last_edit_from" => $admin['linked_name'],
														  "last_edit_date" => $template['date'],
														  "lock" => $template['lock'],
													      "action" => '<input type="checkbox" name="template_id[]" value="'.$template['template_id'].'">',
													      ));		            
		}

    	// Speichere in Ausgabe
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
								 "head_value" => _email_list_template_head,
								 "navi_on" => TRUE,
								 "navi_type" => "email_list_templates",
								 );
		$index = show("$dir/list_template_head", array("list" => $list,
													   "template_id" => _email_list_template_template_id,
													   "name" => _email_list_template_name,		
												       "subject" => _email_list_template_subject,
													   "body" => _email_list_template_body,
													   "placeholder" => _email_list_template_placeholder,
													   "last_edit_from" => _email_list_template_last_edit_from,
													   "last_edit_date" => _email_list_template_last_edit_date,
													   "lock" => _email_list_template_lock,
													   "action" => _email_list_template_action,
													   ));
    	
    	//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;  
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Liste der E-Mail Templates anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** E-Mail(s) löschen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_delete_db;
    
	//Hole email_id aus Session(Liste) oder aus URL
    if (isset($_GET['email_id'])) {
    	$email_id = array($_GET['email_id']);
    	} elseif (isset($_POST['email_id'])) {
    		$email_id = array($_POST['email_id']);
    			} elseif (count($_SESSION['email_ids']) >= "1") {
    				$email_id = $_SESSION['email_ids'];
    }
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_delete_mails", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene email_ids und gebe ggf. Fehlermeldung aus, wenn zu viele E-Mails markiert wurden.
    	if (count($_SESSION['email_ids']) >= "1" OR isset($_POST['email_id']) OR isset($_GET['email_id'])) {				
			
    		foreach ($email_id as $id) {
	    		
    			//Lösche E-Mail
    			$email = mysql_query("DELETE FROM `".$database_prefix."email_history` WHERE email_id = '".$id."' LIMIT 1");
				
		    	//Meldung ausgeben
		        if ($email == TRUE) {            
		            $info .= "<br><div align=\"center\"><div class='boxsucess'>"._email_delete_sucessful."</div></div>";
		            } else {
		            	$info .= "<br><div align=\"center\"><div class='savearea'>"._email_delete_error."</div></div>";
		        }	        
    		}
	        
	        //Weiterleitung nach 8 Sekunden
        	autoforward("../email/index.php?default",5);
    		
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._email_show_selected_wrong."</div></div>";
    			autoforward("../email/index.php?default",3);
    	}
	//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** E-Mail(s) löschen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** gesendete E-Mail bearbeiten und erneut versenden (Absenden)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_edit_db_mail;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_send_mails", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Hole Variablen
		$email_id = $_POST['email_id'];
		$to_name = $_POST['to_name'];
		$to_mail = $_POST['to_mail'];
		$subject = $_POST['subject'];
		$body = $_POST['body'];
		
		//Hole E-Mail Infos
	    $qry = mysql_query("SELECT * FROM `".$database_prefix."email_history` WHERE email_id = '$email_id'");
	    $email = mysql_fetch_array($qry);

    	//Sende E-Mail erneut
	    $Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
	    					  $to_mail, $to_name, $email['to_user_id'],
	    					  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
	    					  $WordWrap = "75", $IsHTML = TRUE, $email['template_id'],
	    					  $Placeholder = FALSE, $subject, $body,
    					  	  $AltSubject = FALSE, $AltBody = FALSE);
			
	    //Meldung ausgeben
	    if ($Connection['result'] != FALSE) {            
	        $info .= "<br><div align=\"center\"><div class='boxsucess'>"._email_send_again_sucessful."</div></div>";
	        } else {
	          	$info .= "<br><div align=\"center\"><div class='savearea'>"._email_send_again_error.$Connection['errors']."</div></div>";
	    }	        
	        
	    //Weiterleitung nach 8 Sekunden
        autoforward("../email/index.php?default",5);

	//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** gesendete E-Mail bearbeiten und erneut versenden (Absenden)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** gesendete E-Mail bearbeiten und erneut versenden
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_edit_mail;
    
	//Hole email_id aus Session(Liste) oder aus URL
    if (isset($_GET['email_id'])) {
    	$email_id = $_GET['email_id'];
    	} elseif (isset($_POST['email_id'])) {
    		$email_id = $_POST['email_id'];
    			} elseif (isset($_SESSION['email_ids'])) {
    				$email_id = $_SESSION['email_ids'][0];
    }
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_edit_mails", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene email_ids und gebe ggf. Fehlermeldung aus, wenn zu viele E-Mails markiert wurden.
    	if (isset($_SESSION['email_ids']) OR isset($_POST['email_id']) OR isset($_GET['email_id'])) {
    		
    		//Hole E-Mail Infos
    		$qry = mysql_query("SELECT * FROM `".$database_prefix."email_history` WHERE email_id = '$email_id'");
    		$email = mysql_fetch_array($qry);

			//Setzte From-Adresse
			$from = '<a href="../admin/index.php?section=profile&user_id=' . $_MAPUSER['user_id'] . '">' . $_MAPUSER['teamtag'] . ' &lt;' . $_MAPUSER['email'] . '&gt;' . '</a>';
				
			// Speichere in Ausgabe
			$content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
									 "head_value" => _email_edit_mail_head . $email['subject'],
									 "navi_on" => TRUE,
									 "navi_type" => "email_edit_mail",
									 );
			$index = show("$dir/edit_mail", array("from" => $from,
												  "to_name" => $email['to_name'],
												  "to_mail" => $email['to_mail'],
												  "last_sent" => $email['last_sent'],
												  "counter" => $email['counter'],
												  "subject" => $email['subject'],
												  "body" => $email['text'],
												  "email_id" => $email_id,
												  "head_from" => _email_edit_head_from,
												  "head_to_name" => _email_edit_head_to_name,
												  "head_to_mail" => _email_edit_head_to_mail,
												  "head_last_sent" => _email_edit_head_last_sent,
												  "head_counter" => _email_edit_head_counter,
												  "head_subject" => _email_edit_head_subject,
												  "head_body" => _email_edit_head_body,
												  ));					
			
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._email_show_selected_wrong."</div></div>";
    			autoforward("../email/index.php?default",3);
    	}
	//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** gesendete E-Mail bearbeiten und erneut versenden
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** E-Mail(s) erneut versenden
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'send_again_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_send_again_db;
    
	//Hole email_id aus Session(Liste) oder aus URL
    if (isset($_GET['email_id'])) {
    	$email_id = array($_GET['email_id']);
    	} elseif (isset($_POST['email_id'])) {
    		$email_id = array($_POST['email_id']);
    			} elseif (count($_SESSION['email_ids']) >= "1") {
    				$email_id = $_SESSION['email_ids'];
    }
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_send_mails", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene email_ids und gebe ggf. Fehlermeldung aus, wenn zu viele E-Mails markiert wurden.
    	if (count($_SESSION['email_ids']) >= "1" OR isset($_POST['email_id']) OR isset($_GET['email_id'])) {				
			
    		foreach ($email_id as $id) {
	    		//Hole E-Mail Infos
	    		$qry = mysql_query("SELECT * FROM `".$database_prefix."email_history` WHERE email_id = '$id'");
	    		$email = mysql_fetch_array($qry);
	    		
	    		//Sende E-Mail erneut
		    	$Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
		    						  $email['to_mail'], $email['to_name'], $email['to_user_id'],
		    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
		    						  $WordWrap = "75", $IsHTML = TRUE, $email['template_id'],
		    						  $Placeholder = FALSE, $email['subject'], $email['text'],
	    						  	  $AltSubject = FALSE, $AltBody = FALSE);
				
		    	//Meldung ausgeben
		        if ($Connection['result'] != FALSE) {            
		            $info .= "<br><div align=\"center\"><div class='boxsucess'>"._email_send_again_sucessful."</div></div>";
		            } else {
		            	$info .= "<br><div align=\"center\"><div class='savearea'>"._email_send_again_error.$Connection['errors']."</div></div>";
		        }	        
    		}
	        
	        //Weiterleitung nach 8 Sekunden
        	autoforward("../email/index.php?default",5);
    		
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._email_show_selected_wrong."</div></div>";
    			autoforward("../email/index.php?default",3);
    	}
	//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** E-Mail(s) erneut versenden
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** gesendete E-Mail anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'show':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_show_mail;
    
	//Hole email_id aus Session(Liste) oder aus URL
    if (isset($_GET['email_id'])) {
    	$email_id = $_GET['email_id'];
    	} elseif (isset($_POST['email_id'])) {
    		$email_id = $_POST['email_id'];
    			} elseif (isset($_SESSION['email_ids'])) {
    				$email_id = $_SESSION['email_ids'][0];
    }
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_list_mails", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene email_ids und gebe ggf. Fehlermeldung aus, wenn zu viele E-Mails markiert wurden.
    	if (isset($_SESSION['email_ids']) OR isset($_POST['email_id']) OR isset($_GET['email_id'])) {
    		
    		//Hole E-Mail Infos
    		$qry = mysql_query("SELECT * FROM `".$database_prefix."email_history` WHERE email_id = '$email_id'");
    		$email = mysql_fetch_array($qry);

			//Setzte To-Adresse
			if ($email['to_user_id'] != FALSE) {
				$to = '<a href="../admin/index.php?section=profile&user_id=' . $email['to_user_id'] . '">' . $email['to_name'] . ' &lt;' . $email['to_mail'] . '&gt;' . '</a>';
				} else {
					$to = '<a href="mailto:' . $email['to_mail'] . '">' . $email['to_name'] . ' &lt;' . $email['to_mail'] . '&gt;' . '</a>';
			}
			//Setzte From-Adresse
			if ($email['from_user_id'] != FALSE) {
				$from = '<a href="../admin/index.php?section=profile&user_id=' . $email['from_user_id'] . '">' . $email['from_name'] . ' &lt;' . $email['from_mail'] . '&gt;' . '</a>';
				} else {
					$from = '<a href="mailto:' . $email['from_mail'] . '">' . $email['from_name'] . ' &lt;' . $email['from_mail'] . '&gt;' . '</a>';
			}
				
			// Speichere in Ausgabe
			$content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
									 "head_value" => _email_show_mail_head . $email['subject'],
									 "navi_on" => TRUE,
									 "navi_type" => "email_show_mail",
									 );
			$index = show("$dir/show_mail", array("from" => $from,
												  "to" => $to,
												  "last_sent" => $email['last_sent'],
												  "counter" => $email['counter'],
												  "subject" => $email['subject'],
												  "body" => $email['text'],
												  "email_id" => $email_id,
												  "head_from" => _email_show_head_from,
												  "head_to" => _email_show_head_to,
												  "head_last_sent" => _email_show_head_last_sent,
												  "head_counter" => _email_show_head_counter,
												  "head_subject" => _email_show_head_subject,
												  "head_body" => _email_show_head_body,
												  ));					
			
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._email_show_selected_wrong."</div></div>";
    			autoforward("../email/index.php?default",3);
    	}
	//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** gesendete E-Mail anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Übergabescript an E-Mail anzeigen , bearbeiten, erneut senden und löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'action':
	
	//Hole Daten aus Listenanzeige: E-Mail-Liste und leite weiter
	//Übergebe ID´s	
	if (isset($_POST['email_id'])) {
		$_SESSION['email_ids'] = $_POST['email_id'];
		} else {
			$_POST['email_id'] = FALSE;
	}
	$_SESSION['template_ids'] = $_POST['template_id'];
	
	//Definiere Sections
	$avaliableActions = array("show_x" => "show",
							  "send_again_db_x" => "send_again_db",
							  "edit_x" => "edit",
							  "delete_db_x" => "delete_db",
							  "edit_template_x" => "edit_template",
							  "lock_x" => "lock_template",
							  );
							  
	//Leite weiter
	foreach ($avaliableActions as $key => $value) {
		if (isset($_POST[$key])) {
			header("Location: index.php?section=".$value);
			
		}
	}

break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Übergabescript an E-Mail anzeigen , bearbeiten, erneut senden und löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Liste der gesendeten E-Mails anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_email_list_mails;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("email_list_mails", FALSE)) {
        //Start definition eigentliche Funktionen dieser Section:

	    //Hole E-Mails
		$qry = mysql_query("SELECT * FROM `".$database_prefix."email_history` ORDER BY last_sent DESC");
	    $i = "0";
		while ($get = mysql_fetch_array($qry)) {
			$mails[$i] = $get;
			$i++;
		}
		
		//Schreibe E-Mails in Liste
		if (isset($mails)) {
			foreach ($mails as $email) {
		            
					//Kürze Body
					$email['body'] = substr($email['text'], 0, 80) . "...";
					//Setzte To-Adresse
					if ($email['to_user_id'] != FALSE AND checkIfAdminExists(FALSE, $email['to_user_id']) == TRUE) {
						$to = user_info($email['to_user_id']);
						} else {
							$to['linked_name'] = '<a href="mailto:' . $email['to_mail'] . '">' . $email['to_name'] . '</a>';
					}
					//Setzte From-Adresse
					if ($email['from_user_id'] != FALSE AND checkIfAdminExists(FALSE, $email['to_user_id']) == TRUE) {
						$from = user_info($email['from_user_id']);
						} else {
							$from['linked_name'] = '<a href="mailto:' . $email['from_mail'] . '">' . $email['from_name'] . '</a>';
					}
			                  
					$list .= show("$dir/email_list", array("to" => $to['linked_name'],
														   "from" => $from['linked_name'],
														   "subject" => '<a href="../email/index.php?section=show&email_id=' . $email['email_id'] . '">' . $email['subject'] . '</a>',
			               								   "body" => $email['body'],
														   "counter" => $email['counter'],
														   "last_sent" => $email['last_sent'],
														   "action" => '<input type="checkbox" name="email_id[]" value="'.$email['email_id'].'">',
														   ));		            
			}
		}
    	// Speichere in Ausgabe
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
								 "head_value" => _email_list_mails_head,
								 "navi_on" => TRUE,
								 "navi_type" => "email_list_mails",
								 );
		$index = show("$dir/list_emails_head", array("list" => $list,
													 "to" => _email_list_mail_to,
													 "from" => _email_list_mail_from,		
												     "subject" => _email_list_mail_subject,
													 "body" => _email_list_mail_body,
													 "counter" => _email_list_mail_counter,
													 "last_sent" => _email_list_mail_last_sent,
													 "action" => _email_list_mail_action,
													 ));
    	
    	//Ende definition eigentliche Funktionen dieser Section^^
	}    
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE** Liste der gesendeten E-Mails anzeigen
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