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
$dir = "settings";
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
// **START**  Veränderte Einstellungen aus dem Formular in CB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'settings_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_admin_settings_db;
	$autoforward1 = FALSE;
	$autoforward2 = FALSE;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("settings_db_set1", FALSE) OR perm_handler("settings_db_set2", FALSE) OR perm_handler("settings_db_set3", FALSE) OR perm_handler("settings_db_set4", FALSE) OR perm_handler("settings_db_set5", FALSE) OR perm_handler("settings_db_set6", FALSE)) {
        //Start definition eigentliche Funktionen dieser Section:
        // Eingaben speichern -- Aus Formular Ã¼bernehmen
        $pagetitle = $_POST["pagetitle"];
        if (isset($_POST["version_show"])) {
	        $pagetitle_version_show = $_POST["version_show"];
	        } else {
        		$pagetitle_version_show = FALSE;
        }
        $language = $_POST["language"];
        $template = $_POST["template"];
    	if (isset($_POST["ver_chk_on"])) {
	        $ver_chk_on = $_POST["ver_chk_on"];
	        } else {
        		$ver_chk_on = FALSE;
        }
        $host = $_POST["host"];
        $reCAPTCHA_Public = $_POST["reCAPTCHA_Public"];
        $reCAPTCHA_Private = $_POST["reCAPTCHA_Private"];
        $email_subject_prefix = $_POST["email_subject_prefix"];
        $email_delete_history = $_POST["email_delete_history"];
        $email_sender = $_POST["email_sender"];
        $email_type = $_POST["email_type"];
        $email_host = $_POST["email_host"];
        $email_port = $_POST["email_port"];
        $email_username = $_POST["email_username"];
        $email_password = $_POST["email_password"];
    	if (isset($_POST["request_account"])) {
	        $request_account = $_POST["request_account"];
	        } else {
        		$request_account = FALSE;
        }
    	if (isset($_POST["request_account_pw_on"])) {
	        $request_account_pw_on = $_POST["request_account_pw_on"];
	        } else {
        		$request_account_pw_on = FALSE;
        }
        $request_account_password = $_POST["request_account_password"];
        $request_access_page =  $_POST["request_access_page"];
        $ice_host = $_POST["ice_host"];
        $ice_port = $_POST["ice_port"];
        $ice_secure = $_POST["ice_secure"];
    	if (isset($_POST["logging_functions_on"])) {
	        $logging_functions_on = $_POST["logging_functions_on"];
	        } else {
        		$logging_functions_on = FALSE;
        }
        $logging_priority = $_POST["logging_priority"];
        $logging_show_priority = $_POST["logging_show_priority"];
        $logging_days = $_POST["logging_days"];
        $logging_show_permissions = $_POST["logging_show_permissons"];
    	if (isset($_POST["forgot_area_on"])) {
	        $forgot_area_on = $_POST["forgot_area_on"];
	        } else {
        		$forgot_area_on = FALSE;
        }
		
        //Checke Berechtigungen für dieses Set 1
        if (perm_handler("settings_db_set1", FALSE)) {
	        //Pagetitle, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '3'");
	        $get_pagetitle = mysql_fetch_array($qry);
	        if ($pagetitle != $get_pagetitle[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$pagetitle' WHERE id = '3'");
	            $TCSet1_1 = TRUE;
	        	} else {
	        		$TCSet1_1 = FALSE;
	        }
	          
	        //Anzeige der Version im Seitentitel, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '8'");
	        $get_pagetitle_version_show = mysql_fetch_array($qry);
	        if ($pagetitle_version_show == '') {
	            $pagetitle_version_show = '0';
	        }
	        if ($pagetitle_version_show != $get_pagetitle_version_show[2] && $pagetitle_version_show == '1') {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '1' WHERE id = '8'");
	            $TCSet1_2 = TRUE;
	            } elseif ($pagetitle_version_show != $get_pagetitle_version_show[2] && $pagetitle_version_show == '0'){
	                mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '0' WHERE id = '8'");
	                $TCSet1_2 = TRUE;
	        	} else {
	        		$TCSet1_2 = FALSE;
	        }
	          
	        //Language, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '6'");
	        $get_language = mysql_fetch_array($qry);
	        if ($language != $get_language[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$language' WHERE id = '6'");
	            $TCSet1_3 = TRUE;
	        	} else {
	        		$TCSet1_3 = FALSE;
	        }
	            
	        //Template, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '5'");
	        $get_template = mysql_fetch_array($qry);
	        if ($template != $get_template[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$template' WHERE id = '5'");
	            $TCSet1_4 = TRUE;
	        	} else {
	        		$TCSet1_4 = FALSE;
	        }
	            
	        //Versionscheck An, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '30'");
	        $get_ver_chk_on = mysql_fetch_array($qry);
	        if ($ver_chk_on == '') {
	            $ver_chk_on = '0';
	        }
	        if ($ver_chk_on != $get_ver_chk_on[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$ver_chk_on' WHERE id = '30'");
	            $TCSet1_5 = TRUE;
	        	} else {
	        		$TCSet1_5 = FALSE;
	        }
	        
        	//Host, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '34'");
	        $get_host = mysql_fetch_array($qry);
	        if ($host != $get_host[2]) {
	            //Schreibe neuen Wert in DB
	        	mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$host' WHERE id = '34'");
	            //Aktualisiere Server Host via Slice
	        	$servers = getServers();
				foreach ($servers as $server_id) { 
					//Hole Infos zu diesem Server
					$server = server_info($server_id);
					//Ändere Host, wenn nicht gleich dem neuen Wert
					if ($server['ip'] != $host) {
						setServer($server_id, $key = "host", $host);
					}
				}           	            
	            $TCSet1_6 = TRUE;
	        	} else {
	        		$TCSet1_6 = FALSE;
	        }
	        
	        //reCAPTCHA-Keys, falls geändert in DB schreiben      
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '45'");
	        $get_reCAPTCHA_Public = mysql_fetch_array($qry);
        	if ($reCAPTCHA_Public != $get_reCAPTCHA_Public[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$reCAPTCHA_Public' WHERE id = '45'");
	            $true_change = 'TRUE';
	        }
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '46'");
	        $get_reCAPTCHA_Private = mysql_fetch_array($qry);
        	if ($reCAPTCHA_Private != $get_reCAPTCHA_Private[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$reCAPTCHA_Private' WHERE id = '46'");
	            $TCSet1_7 = TRUE;
	        	} else {
	        		$TCSet1_7 = FALSE;
	        }
        }
        
        //Checke Berechtigungen für dieses Set 2
        if (perm_handler("settings_db_set2", FALSE)) {                 
	        //E-Mail --> Betreff Präfix, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '38'");
	        $get_email_subject_prefix = mysql_fetch_array($qry);
	        if ($email_subject_prefix != $get_email_subject_prefix[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$email_subject_prefix."' WHERE id = '38'");
	            $TCSet2_1 = TRUE;
	        	} else {
	        		$TCSet2_1 = FALSE;
	        }
	        
        	//E-Mail --> Historie löschen, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '39'");
	        $get_email_delete_history = mysql_fetch_array($qry);
	        if ($email_delete_history != $get_email_delete_history[2] && is_numeric($email_delete_history)) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$email_delete_history."' WHERE id = '39'");
	            $TCSet2_2 = TRUE;
	        	} else {
	        		$TCSet2_2 = FALSE;
	        }
	        
        	//E-Mail --> Absenderadresse, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '47'");
	        $get_email_sender = mysql_fetch_array($qry);
	        if ($email_sender != $get_email_sender[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$email_sender."' WHERE id = '47'");
	            $TCSet2_3 = TRUE;
	        	} else {
	        		$TCSet2_3 = FALSE;
	        }
	        
        	//E-Mail --> Mailer (Type), falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '40'");
	        $get_email_type = mysql_fetch_array($qry);
	        if ($email_type != $get_email_type[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$email_type."' WHERE id = '40'");
	            $TCSet2_4 = TRUE;
	        	} else {
	        		$TCSet2_4 = FALSE;
	        }
	        
        	//E-Mail --> Host, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '41'");
	        $get_email_host = mysql_fetch_array($qry);
	        if ($email_host != $get_email_host[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$email_host."' WHERE id = '41'");
	            $TCSet2_5 = TRUE;
	        	} else {
	        		$TCSet2_5 = FALSE;
	        }
	        
        	//E-Mail --> Port, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '42'");
	        $get_email_port = mysql_fetch_array($qry);
	        if (($email_port != $get_email_port[2] && is_numeric($email_port)) OR $email_port == "") {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$email_port."' WHERE id = '42'");
	            $TCSet2_6 = TRUE;
	        	} else {
	        		$TCSet2_6 = FALSE;
	        }
	        
        	//E-Mail --> Benutzernamen, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '43'");
	        $get_email_username = mysql_fetch_array($qry);
	        if ($email_username != $get_email_username[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$email_username."' WHERE id = '43'");
	            $TCSet2_7 = TRUE;
	        	} else {
	        		$TCSet2_7 = FALSE;
	        }
	        
        	//E-Mail --> Passwort , falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '44'");
	        $get_email_password = mysql_fetch_array($qry);
	        if ($email_password != $get_email_password[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$email_password."' WHERE id = '44'");
	            $TCSet2_8 = TRUE;
	        	} else {
	        		$TCSet2_8 = FALSE;
	        }
	        
        }

        //Checke Berechtigungen für dieses Set 3
        if (perm_handler("settings_db_set3", FALSE)) {
	        //Account request, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '16'");
	        $get_request_account = mysql_fetch_array($qry);
	        if ($request_account == '') {
	            $request_account = '0';
	        }
	        if ($request_account != $get_request_account[2] && $request_account == '1') {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '1' WHERE id = '16'");
	            $true_change = 'TRUE';
	            } elseif ($request_account != $get_request_account[2] && $request_account == '0') {
	                mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '0' WHERE id = '16'");
	                $TCSet3_1 = TRUE;
	        	} else {
	        		$TCSet3_1 = FALSE;
	        }
	            
	        //Account request (Passwort funktion an aus), falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '19'");
	        $get_request_account_pw_on = mysql_fetch_array($qry);
	        if ($request_account_pw_on == '') {
	            $request_account_pw_on = '0';
	        }
	        if ($request_account_pw_on != $get_request_account_pw_on[2] && $request_account_pw_on == '1') {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '1' WHERE id = '19'");
	            $true_change = 'TRUE';
	            } elseif ($request_account_pw_on != $get_request_account_pw_on[2] && $request_account_pw_on == '0') {
	                mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '0' WHERE id = '19'");
	                $TCSet3_2 = TRUE;
	        	} else {
	        		$TCSet3_2 = FALSE;
	        }  
	                        
	        //Account request (Passwort value), falls geändert in DB schreiben 
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '18'");
	        $get_request_account_password = mysql_fetch_array($qry);
	        if ($request_account_password != $get_request_account_password[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$request_account_password' WHERE id = '18'");
	            $TCSet3_3 = TRUE;
	        	} else {
	        		$TCSet3_3 = FALSE;
	        }  
	            
	        //request_access_page, falls geändert in DB schreiben     
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '31'");
	        $get_request_access_page = mysql_fetch_array($qry);
	        if ($request_access_page != $get_request_access_page[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$request_access_page."' WHERE id = '31'");
	            $TCSet3_4 = TRUE;
	        	} else {
	        		$TCSet3_4 = FALSE;
	        }  
        }

        //Checke Berechtigungen für dieses Set 4
        if (perm_handler("settings_db_set4", FALSE)) {
        	
        	//Slice Host, holen          
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '28'");
	        $get_slice_host = mysql_fetch_array($qry);
        	
	        //Slice Port, holen       
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '29'");
	        $get_slice_port = mysql_fetch_array($qry);
	        
	        //Slice Secure, holen           
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '33'");
	        $get_slice_secure = mysql_fetch_array($qry);
	        
        	//Checke neuen Host-Settings
        	$IceResult = FALSE;
        	if (($ice_host != $get_slice_host[2]) OR ($ice_port != $get_slice_port[2]) OR ($ice_secure != $get_slice_secure[2])) {
        		try { 
	        		$secure = array('secret' => $ice_secure);
					$_BASE = $ICE->stringToProxy("Meta:tcp -h ".$ice_host." -p ".$ice_port.""); 
					$_SLICE = $_BASE->ice_checkedCast("::Murmur::Meta")->ice_context($secure); 
					if ($_SLICE) {
						$IceResult = TRUE;
						} else {
							$IceResult = FALSE;
					}
					} catch (Ice_Exception $error) { 
						$IceResult = FALSE;
				}
        	}
            
        	//Wenn Host Okay, kann in DB geschrieben werden
	        if ($IceResult) {
		        //Slice Host, falls geändert in DB schreiben   
		        if ($ice_host != $get_slice_host[2]) {
					mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$ice_host' WHERE id = '28'");
		            $TCSet4_1 = TRUE;
		        	} else {
	        			$TCSet4_1 = FALSE;
	        	}      
		                   
		        //Slice Port, falls geändert in DB schreiben     
		        if ($ice_port != $get_slice_port[2]) {
		            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$ice_port' WHERE id = '29'");
		            $TCSet4_2 = TRUE;
		        	} else {
	        			$TCSet4_2 = FALSE;
	        	}  
	        	                   
		        //Slice Secure, falls geändert in DB schreiben 
		        if ($ice_secure != $get_slice_secure[2]) {
		            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$ice_secure' WHERE id = '33'");
		            $TCSet4_3 = TRUE;
		        	} else {
	        			$TCSet4_3 = FALSE;
	        	}
	        }
        }
        
        //Checke Berechtigungen für dieses Set 5
        if (perm_handler("settings_db_set5", FALSE)) {   
	        //Logging funktionen ein (Logging funktion an aus), falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '23'");
	        $get_logging_functions_on = mysql_fetch_array($qry);
	        if ($logging_functions_on == '') {
	            $logging_functions_on = '0';
	        }
	        if ($logging_functions_on != $get_logging_functions_on[2] && $logging_functions_on == '1') {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '1' WHERE id = '23'");
	            $TCSet5_1 = TRUE;
	            } elseif ($logging_functions_on != $get_logging_functions_on[2] && $logging_functions_on == '0') {
	                mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '0' WHERE id = '23'");
	                $TCSet5_1 = TRUE;
	        		} else {
	        			$TCSet5_1 = FALSE;
	        }
	           
	        //Logging Priorität, falls geändert in DB schreiben     
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '24'");
	        $get_logging_priority = mysql_fetch_array($qry);
	        if ($logging_priority != $get_logging_priority[2] && $logging_priority == '1') {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '1' WHERE id = '24'");
	            $TCSet5_2 = TRUE;
	            } elseif ($logging_priority != $get_logging_priority[2] && $logging_priority == '2') {
	                mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '2' WHERE id = '24'");
	                $TCSet5_2 = TRUE;
	        		} else {
	        			$TCSet5_2 = FALSE;
	        }
	            
	        //Logging "Show" Priorität, falls geändert in DB schreiben     
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '25'");
	        $get_logging_show_priority = mysql_fetch_array($qry);
	        if ($logging_show_priority != $get_logging_show_priority[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '".$logging_show_priority."' WHERE id = '25'");
	            $TCSet5_3 = TRUE;
	        	} else {
	        		$TCSet5_3 = FALSE;
	        }
	            
	        //Logging Days, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '26'");
	        $get_logging_days = mysql_fetch_array($qry);
	        if ($logging_days != $get_logging_days[2] && is_numeric($logging_days) == "TRUE") {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$logging_days' WHERE id = '26'");
	            $TCSet5_4 = TRUE;
	        	} else {
	        		$TCSet5_4 = FALSE;
	        }
	            
	        //Logging Berechtigungen, falls geändert in DB schreiben     
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '27'");
	        $get_logging_permissions = mysql_fetch_array($qry);
	        if ($logging_show_permissions != $get_logging_permissions[2] && $logging_show_permissions == '1') {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '1' WHERE id = '27'");
	            $TCSet5_5 = TRUE;
	            } elseif ($logging_show_permissions != $get_logging_permissions[2] && $logging_show_permissions == '2') {
	                mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '2' WHERE id = '27'");
	                $TCSet5_5 = TRUE;
	        		} else {
	        			$TCSet5_5 = FALSE;
	        }
        }
            
        //Checke Berechtigungen für dieses Set 6
        if (perm_handler("settings_db_set6", FALSE)) {
	        //Paswswort vergessen Funktionen, falls geändert in DB schreiben            
	        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '32'");
	        $get_forgot_area_on = mysql_fetch_array($qry);
	        if ($forgot_area_on == '') {
	            $forgot_area_on = '0';
	        }
	        if ($forgot_area_on != $get_forgot_area_on[2]) {
	            mysql_query("UPDATE `".$database_prefix."settings` SET value_2 = '$forgot_area_on' WHERE id = '32'");
	            $TCSet6_1 = TRUE;
	        	} else {
	        		$TCSet6_1 = FALSE;
	        }
        }
                   
        //Abschlussmeldung
        if ($TCSet1_1 OR $TCSet1_2 OR $TCSet1_3 OR $TCSet1_4 OR $TCSet1_5 OR $TCSet1_6 OR $TCSet1_7 OR $TCSet2_1 OR $TCSet2_2 OR $TCSet2_3 OR $TCSet2_4 OR $TCSet2_5 OR $TCSet2_6 OR $TCSet2_7 OR $TCSet2_8 OR $TCSet3_1 OR $TCSet3_2 OR $TCSet3_3 OR $TCSet3_4 OR $TCSet4_1 OR $TCSet4_2 OR $TCSet4_3 OR $TCSet5_1 OR $TCSet5_2 OR $TCSet5_3 OR $TCSet5_4 OR $TCSet5_5 OR $TCSet6_1) {
        	$info = "<br><div align=\"center\"><div class='boxsucess'>"._settings_do_true."</div></div>";
            $autoforward = TRUE;
        	} else {
        		$info = "<br><div align=\"center\"><div class='savearea'>"._settings_do_false."</div></div>";
        		$autoforward = FALSE;
        }

        //Loggingfunktion, Übergabe der Werte: Settings geändert
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
		    $log_values["on"] = TRUE;
	    }
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "settings_db_1";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
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
	            
        //Weiterleitung nach 4 Sekunden
        if ($autoforward == TRUE) {
            autoforward("../settings/index.php?default",4);
	    	} else {
        		//Weiterleitung nach 2 Sekunden
            	autoforward("../settings/index.php?default",2);
	    }

        //Ende definition eigentliche Funktionen dieser Section^^
	    } else {
	    		$perm_error = "<br><div align=\"center\"><div class='savearea'>"._perm_error_group_user."</div></div>";
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Veränderte Einstellungen aus dem Formular in CB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Settings, Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_admin_settings;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("settings_show_set1", FALSE) OR perm_handler("settings_show_set2", FALSE) OR perm_handler("settings_show_set3", FALSE) OR perm_handler("settings_show_set4", FALSE) OR perm_handler("settings_show_set5", FALSE) OR perm_handler("settings_show_set6", FALSE) OR perm_handler("settings_show_set7", FALSE)) { 
        //Start definition eigentliche Funktionen dieser Section:
        
    	//Definiere Permissions
    	if (perm_handler("settings_show_set1", FALSE)) {
    		$setA1 = ''; $setB1 = '';
    		} else {
    			$setA1 = '<!--'; $setB1 = '-->';
    	}
    	if (perm_handler("settings_show_set2", FALSE)) {
    		$setA2 = ''; $setB2 = '';
    		} else {
    			$setA2 = '<!--'; $setB2 = '-->';
    	}
    	if (perm_handler("settings_show_set3", FALSE)) {
    		$setA3 = ''; $setB3 = '';
    		} else {
    			$setA3 = '<!--'; $setB3 = '-->';
    	}
    	if (perm_handler("settings_show_set4", FALSE)) {
    		$setA4 = ''; $setB4 = '';
    		} else {
    			$setA4 = '<!--'; $setB4 = '-->';
    	}
    	if (perm_handler("settings_show_set5", FALSE)) {
    		$setA5 = ''; $setB5 = '';
    		} else {
    			$setA5 = '<!--'; $setB5 = '-->';
    	}
    	if (perm_handler("settings_show_set6", FALSE)) {
    		$setA6 = ''; $setB6 = '';
    		} else {
    			$setA6 = '<!--'; $setB6 = '-->';
    	}
    	if (perm_handler("settings_show_set7", FALSE)) {
    		$setA7 = ''; $setB7 = '';
    		} else {
    			$setA7 = '<!--'; $setB7 = '-->';
    	}
        if (perm_handler("settings_db_set1", FALSE)) {
    		$dis1 = '';
    		} else {
    			$dis1 = 'disabled="disabled"';
    	}
    	if (perm_handler("settings_db_set2", FALSE)) {
    		$dis2 = '';
    		} else {
    			$dis2 = 'disabled="disabled"';
    	}
    	if (perm_handler("settings_db_set3", FALSE)) {
    		$dis3 = '';
    		} else {
    			$dis3 = 'disabled="disabled"';
    	}
    	if (perm_handler("settings_db_set4", FALSE)) {
    		$dis4 = '';
    		} else {
    			$dis4 = 'disabled="disabled"';
    	}  	
    	if (perm_handler("settings_db_set5", FALSE)) {
    		$dis5 = '';
    		} else {
    			$dis5 = 'disabled="disabled"';
    	}
    	if (perm_handler("settings_db_set6", FALSE)) {
    		$dis6 = '';
    		} else {
    			$dis6 = 'disabled="disabled"';
    	}
            
        //Gebe Seitentitel aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '3'");
        $get_pagetitle = mysql_fetch_array($qry);
            
        // Zeige Versionsnummer im Seitentitel (Checkbox) - Ausgabe
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '8'");
        $get_pt_version_show = mysql_fetch_array($qry);
        if ($get_pt_version_show[2] == '1') {
            $get_pagetitle_version_value = ' checked';
            } elseif ($get_pt_version_show[2] == '0') {
                $get_pagetitle_version_value = '';
        }
            
        //Versionscheck an/aus (Checkbox) - Ausgabe
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '30'");
        $get_ver_chk_on = mysql_fetch_array($qry);
        if ($get_ver_chk_on[2] == '1') {
            $get_ver_chk_on_value = ' checked';
            } elseif ($get_ver_chk_on[2] == '0') {
                $get_ver_chk_on_value = '';
        }
        
    	// Definiere und gebe vorhandene Languages aus!
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '6'");
        $get_language = mysql_fetch_array($qry);
            
        // Definiere und gebe vorhandene TPL-Verzeichnisse aus!
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '5'");
        $get_tpl = mysql_fetch_array($qry);
        
        //Server Host - Ausgabe
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '34'");
        $get_host = mysql_fetch_array($qry);
        if (isset($get_host[2]) && $get_host[2] != "") {
        	$get_host_value = $get_host[2];
        	} else {
        		$get_host_value = $_SERVER[SERVER_ADDR];
        }
        
        //Google reCAPTCHA - Keys definieren
        //Public-Key
    	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '45'");
        $get_reCAPTCHA_Public = mysql_fetch_array($qry);
        //Private-Key
    	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '46'");
        $get_reCAPTCHA_Private = mysql_fetch_array($qry);

        //E-Mail -> Betreff--Prefix
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '38'");
        $get_email_subject_prefix = mysql_fetch_array($qry);
        
        //E-Mail -> Historie löschen
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '39'");
        $get_email_delete_history = mysql_fetch_array($qry);
        
        //E-Mail -> Absender
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '47'");
        $get_email_sender = mysql_fetch_array($qry);
        
    	//E-Mail -> Type ausgeben (php-mail, SMTP, sendmail oder qmail)
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '40'");
        $get_email_type = mysql_fetch_array($qry);
        $get_email_type_value = FALSE;
        if ($get_email_type[2] == "mail") {
            $get_email_type_value .= "<option value=\"mail\" selected=\"selected\">"._settings_show_email_type_mail."</option>\n";
            $get_email_type_value .= "<option value=\"smtp\">"._settings_show_email_type_smtp."</option>\n";
            $get_email_type_value .= "<option value=\"sendmail\">"._settings_show_email_type_sendmail."</option>\n";
            $get_email_type_value .= "<option value=\"qmail\">"._settings_show_email_type_qmail."</option>\n";
        } 
    	if ($get_email_type[2] == "smtp") { 
            $get_email_type_value .= "<option value=\"smtp\" selected=\"selected\">"._settings_show_email_type_smtp."</option>\n";
            $get_email_type_value .= "<option value=\"mail\">"._settings_show_email_type_mail."</option>\n";
            $get_email_type_value .= "<option value=\"sendmail\">"._settings_show_email_type_sendmail."</option>\n";
            $get_email_type_value .= "<option value=\"qmail\">"._settings_show_email_type_qmail."</option>\n";
        }
    	if ($get_email_type[2] == "sendmail") { 
            $get_email_type_value .= "<option value=\"sendmail\" selected=\"selected\">"._settings_show_email_type_sendmail."</option>\n";
            $get_email_type_value .= "<option value=\"mail\">"._settings_show_email_type_mail."</option>\n";
            $get_email_type_value .= "<option value=\"smtp\">"._settings_show_email_type_smtp."</option>\n";
            $get_email_type_value .= "<option value=\"qmail\">"._settings_show_email_type_qmail."</option>\n";
        }
        if ($get_email_type[2] == "qmail") { 
            $get_email_type_value .= "<option value=\"qmail\" selected=\"selected\">"._settings_show_email_type_qmail."</option>\n";
            $get_email_type_value .= "<option value=\"mail\">"._settings_show_email_type_mail."</option>\n";
            $get_email_type_value .= "<option value=\"smtp\">"._settings_show_email_type_smtp."</option>\n";
            $get_email_type_value .= "<option value=\"sendmail\">"._settings_show_email_type_sendmail."</option>\n";
        }
        
        //E-Mail -> Server Host
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '41'");
        $get_email_host = mysql_fetch_array($qry);
        
        //E-Mail -> Server Port
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '42'");
        $get_email_port = mysql_fetch_array($qry);
        
        //E-Mail -> Server Benutzername
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '43'");
        $get_email_username = mysql_fetch_array($qry);
        
        //E-Mail -> Server Passwort
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '44'");
        $get_email_password = mysql_fetch_array($qry);
               
        // erlaube Usern das beantragen von Accounts (Checkbox) - Ausgabe
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '16'");
        $get_allow_request_account = mysql_fetch_array($qry);
        if ($get_allow_request_account[2] == '1') {
            $get_allow_request_account_value = ' checked';
            } elseif ($get_allow_request_account[2] == '0') {
                $get_allow_request_account_value = '';
        }
                       
        //Selektion ob Secure_Password eingeschaltet oder secure_code (default) ein ist (Checkbox) - Ausgabe
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '19'");
        $get_secure = mysql_fetch_array($qry);
        if ($get_secure[2] == '1') {
            $get_secure_pw_on_value = ' checked';
            } elseif ($get_secure[2] == '0') {
                $get_secure_pw_on_value = '';
        }
                          
        // Gebe Request_Secure_Password aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '18'");
        $get_secure_password = mysql_fetch_array($qry);
            
        // Gebe request_access_page aus, sprich welches Request Formualr aktiv ist.
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '31'");
        $get_request_access_page = mysql_fetch_array($qry);
        $get_request_access_page_value = FALSE;
        if ($get_request_access_page[2] == "1") {
            $get_request_access_page_value .= "<option value=\"1\" selected=\"selected\">"._settings_request_access_page_value_1."</option>\n";
            $get_request_access_page_value .= "<option value=\"2\">"._settings_request_access_page_value_2."</option>\n";
            $get_request_access_page_value .= "<option value=\"3\">"._settings_request_access_page_value_3."</option>\n";
        } 
        if ($get_request_access_page[2] == "2") { 
            $get_request_access_page_value .= "<option value=\"1\">"._settings_request_access_page_value_1."</option>\n";
            $get_request_access_page_value .= "<option value=\"2\" selected=\"selected\">"._settings_request_access_page_value_2."</option>\n";
            $get_request_access_page_value .= "<option value=\"3\">"._settings_request_access_page_value_3."</option>\n";
        }
        if ($get_request_access_page[2] == "3") { 
            $get_request_access_page_value .= "<option value=\"1\">"._settings_request_access_page_value_1."</option>\n";
            $get_request_access_page_value .= "<option value=\"2\">"._settings_request_access_page_value_2."</option>\n";
            $get_request_access_page_value .= "<option value=\"3\" selected=\"selected\">"._settings_request_access_page_value_3."</option>\n";
        }

        // Gebe Slice Host aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '28'");
        $get_ice_host_value = mysql_fetch_array($qry);
          
        // Gebe Slice Port aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '29'");
        $get_ice_port_value = mysql_fetch_array($qry);
          
        // Gebe Slice Secure Pwd aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '33'");
        $get_ice_secure_value = mysql_fetch_array($qry);
        
        //Selektion ob Logging Funktionen eingeschaltet sind (Checkbox) - Ausgabe
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '23'");
        $get_logging_functions = mysql_fetch_array($qry);
        if ($get_logging_functions[2] == '1') {
            $get_logging_functions_on_value = ' checked';
            } elseif ($get_logging_functions[2] == '0') {
                $get_logging_functions_on_value = '';
        }
            
        // Definiere die Logging Priorität und gebe liste aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '24'");
        $get_logging_priority = mysql_fetch_array($qry);
        $get_logging_priority_value = FALSE;
        if ($get_logging_priority[2] == "1") {
            $get_logging_priority_value .= "<option value=\"1\" selected=\"selected\">"._settings_logging_priority_value_1."</option>\n";
            $get_logging_priority_value .= "<option value=\"2\">"._settings_logging_priority_value_2."</option>\n";
        } 
        if ($get_logging_priority[2] == "2") { 
            $get_logging_priority_value .= "<option value=\"1\">"._settings_logging_priority_value_1."</option>\n";
            $get_logging_priority_value .= "<option value=\"2\" selected=\"selected\">"._settings_logging_priority_value_2."</option>\n";
        }
            
        // Zeige an, welche Loggings angezeigt werden sollen.
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '25'");
        $get_logging_show_priority = mysql_fetch_array($qry);
        $get_logging_show_priority_value = FALSE;
        if ($get_logging_show_priority[2] == "1") {
            $get_logging_show_priority_value .= "<option value=\"1\" selected=\"selected\">"._settings_logging_show_priority_value_1."</option>\n";
            $get_logging_show_priority_value .= "<option value=\"2\">"._settings_logging_show_priority_value_2."</option>\n";
            $get_logging_show_priority_value .= "<option value=\"3\">"._settings_logging_show_priority_value_3."</option>\n";
        } 
        if ($get_logging_show_priority[2] == "2") { 
            $get_logging_show_priority_value .= "<option value=\"1\">"._settings_logging_show_priority_value_1."</option>\n";
            $get_logging_show_priority_value .= "<option value=\"2\" selected=\"selected\">"._settings_logging_show_priority_value_2."</option>\n";
            $get_logging_show_priority_value .= "<option value=\"3\">"._settings_logging_show_priority_value_3."</option>\n";
        }
        if ($get_logging_show_priority[2] == "3") { 
            $get_logging_show_priority_value .= "<option value=\"1\">"._settings_logging_show_priority_value_1."</option>\n";
            $get_logging_show_priority_value .= "<option value=\"2\">"._settings_logging_show_priority_value_2."</option>\n";
            $get_logging_show_priority_value .= "<option value=\"3\" selected=\"selected\">"._settings_logging_show_priority_value_3."</option>\n";
        }
            
        // Zeige Log-Days an
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '26'");
        $get_logging_days = mysql_fetch_array($qry);
            
        // Definiere die Logging "Show" Berechtigungen
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '27'");
        $get_logging_permissions = mysql_fetch_array($qry);
        $get_logging_permissions_value = FALSE;
        if ($get_logging_permissions[2] == "1") {
            $get_logging_permissions_value .= "<option value=\"1\" selected=\"selected\">"._settings_logging_permissions_value_1."</option>\n";
            $get_logging_permissions_value .= "<option value=\"2\">"._settings_logging_permissions_value_2."</option>\n";
        } 
        if ($get_logging_permissions[2] == "2") { 
            $get_logging_permissions_value .= "<option value=\"1\">"._settings_logging_permissions_value_1."</option>\n";
            $get_logging_permissions_value .= "<option value=\"2\" selected=\"selected\">"._settings_logging_permissions_value_2."</option>\n";
        }
            
        // Gebe aus: Passwort vergessen Funktionen AN/AUS
        $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '32'");
        $get_forgot_area_on = mysql_fetch_array($qry);
        if ($get_forgot_area_on[2] == '1') {
            $get_forgot_area_on_value = ' checked';
            } elseif ($get_forgot_area_on[2] == '0') {
                $get_forgot_area_on_value = '';
        }
        
        //Definiere Versionscheck AUSGABE
        $Version = MAPVersionTopicality();
        $map_version_check_value = $Version['MAPVersionOutputClear'];
        
        //Definiere Murmur-Server Version
		$server = server_info(1);
        $murmur_version_value = $server['version_detailed'];

        //Definiere PHP Version
        $php_version_value = phpversion();

		//Definiere Slice Version
        if (extension_loaded('ice')) {
			if (function_exists('Ice_stringVersion')) {
       			$slice_version_value = Ice_stringVersion();
				} else {
					$slice_version_value = "not installed or version not available!";
			}
        	} else {
        		$slice_version_value = "not installed or version not available!";
        }

		//Definiere MySQL Version
        $mysql_version_value = mysql_get_server_info();

		//Definiere Apache Version
		$apache_version_value = @$_SERVER["SERVER_SOFTWARE"];
        if ($apache_version_value == "Apache" ) {
        	$apache_version_value .= ' <i>(for more Apache-Version informations see <a href="http://httpd.apache.org/docs/current/mod/core.html#servertokens">HERE</a>)</i>';
        }
        
        //Gebe Konfigurationsbericht aus
        //Gebe System aus
        $system = get_server_system();
        //Gebe ice.slice Parameter aus
        $ice_slice_path = ini_get('ice.slice');
        //Gebe das definierte extension_dir aus
        $php_extension_dir = ini_get('extension_dir');
        //Gebe Pfad zur IcePHP.ini aus
        //exec('updatedb');
        unset($path_to_IcePHP_ini);
        $path_to_IcePHP_ini = array();
        $path_to_IcePHP_ini = exec('locate IcePHP.ini');
        //Gebe Inhalt der IcePHP.ini aus
        $content_IcePHP_ini = FALSE;
        exec("cat -n $path_to_IcePHP_ini", $IcePHP_ini_array);
        foreach ($IcePHP_ini_array as $line) {
        	$content_IcePHP_ini .= $line;
        }
        if ($lang == "german") {
        	$report_url = "http://de.wiki.mumb1e.de/wiki/Fehlerreport";
        	$report_code = "
[table]
 [tr]
   [td]Type[/td]
   [td]Beispiel[/td]
   [td]dein Report[/td]
 [/tr]
 [tr]
   [td]MAP Version[/td]
   [td]V2.8.0 - 8678 - 2015-01-01[/td]
   [td]".$version_num." - ".$version_build." - ".$version_date."[/td]
 [/tr]
 [tr]
   [td]System[/td]
   [td]Ubuntu 12.04 LTS[/td]
   [td]".$system."[/td]
 [/tr]
 [tr]
   [td]PHP Version[/td]
   [td]5.3.6[/td]
   [td]".$php_version_value."[/td]
 [/tr]
 [tr]
   [td]Slice Version[/td]
   [td]3.4.2[/td]
   [td]".$slice_version_value."[/td]
 [/tr]
 [tr]
   [td]Murmur Version[/td]
   [td]1.2.3 [/td]
   [td]".$murmur_version_value."[/td]
 [/tr]
 [tr]
   [td]PHP Extension_dir[/td]
   [td]/usr/lib/php5/20060613+lfs/[/td]
   [td]".$php_extension_dir."[/td]
 [/tr]
 [tr]
   [td]Verzeichnis zu Murmur.ice[/td]
   [td]/home/murmur/static/Murmur.ice[/td]
   [td]".$ice_slice_path."[/td]
 [/tr]
 [tr]
   [td]Verzeichnis zu IcePHP.ini[/td]
   [td]/etc/php5/conf.d/IcePHP.ini[/td]
   [td]".$path_to_IcePHP_ini."[/td]
 [/tr]
 [tr]
   [td]Inhalt deiner IcePHP.ini[/td]
   [td]extension = IcePHP.so; ice.slice = /home/murmur/static/Murmur.ice[/td]
   [td]".$content_IcePHP_ini."[/td]
 [/tr]
 [tr]
   [td]detailierte Fehlerbeschreibung[/td]
   [td]Wenn ich MAP aufrufe, dann ....[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]eindeutige Fehlermeldung (falls vorhanden)[/td]
   [td]Error 500, Unable to...[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Murmur Installationsart[/td]
   [td]Paket oder Static[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]MAP Erstinstallation[/td]
   [td]Ja / Nein[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Was hast du bisher unternommen?[/td]
   [td]Slice neu installiert, ...[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Tritt der Fehler sporadisch oder ständig auf?[/td]
   [td]ständig[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Hast du bereits die MurmurFixed.ice aus dem MAP-Projekt getestet?[/td]
   [td]Ja / Nein[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Hat dir die Seite Troubleshooting geholfen?[/td]
   [td]Ja / Nein[/td]
   [td][/td]
 [/tr]
[/table]";
        	} else {
        		$report_url = "http://en.wiki.mumb1e.de/wiki/Errorreport";
        		$report_code = "
[table]
 [tr]
   [td]Type[/td]
   [td]Sample[/td]
   [td]your Report[/td]
 [/tr]
 [tr]
   [td]MAP Version[/td]
   [td]V2.8.0 - 8678 - 2015-01-01[/td]
   [td]".$version_num." - ".$version_build." - ".$version_date."[/td]
 [/tr]
 [tr]
   [td]System[/td]
   [td]Ubuntu 12.04 LTS[/td]
   [td]".$system."[/td]
 [/tr]
 [tr]
   [td]PHP Version[/td]
   [td]5.3.6[/td]
   [td]".$php_version_value."[/td]
 [/tr]
 [tr]
   [td]Slice Version[/td]
   [td]3.4.2[/td]
   [td]".$slice_version_value."[/td]
 [/tr]
 [tr]
   [td]Murmur Version[/td]
   [td]1.2.4[/td]
   [td]".$murmur_version_value."[/td]
 [/tr]
 [tr]
   [td]PHP Extension_dir[/td]
   [td]/usr/lib/php5/20060613+lfs/[/td]
   [td]".$php_extension_dir."[/td]
 [/tr]
 [tr]
   [td]Path to Murmur.ice[/td]
   [td]/home/murmur/static/Murmur.ice[/td]
   [td]".$ice_slice_path."[/td]
 [/tr]
 [tr]
   [td]Path to IcePHP.ini[/td]
   [td]/etc/php5/conf.d/IcePHP.ini[/td]
   [td]".$path_to_IcePHP_ini."[/td]
 [/tr]
 [tr]
   [td]Content of IcePHP.ini[/td]
   [td]extension = IcePHP.so; ice.slice = /home/murmur/static/Murmur.ice[/td]
   [td]".$content_IcePHP_ini."[/td]
 [/tr]
 [tr]
   [td]exact Error discription[/td]
   [td]When i call MAP, i get ....[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Error (if present)[/td]
   [td]Error 500, Unable to...[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Murmur installation[/td]
   [td]package or static[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]new MAP install[/td]
   [td]yes/ no[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]What have you done to fix your error?[/td]
   [td]installed Slice ...[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]The error occurs sporadically or continuously?[/td]
   [td]continuously[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Do you have tried MurmurFixed.ice-File from MAP-Project?[/td]
   [td]yes / no[/td]
   [td][/td]
 [/tr]
 [tr]
   [td]Was the page Troubleshooting helpfully? [/td]
   [td]yes / no[/td]
   [td][/td]
 [/tr]
[/table]";
        }
     
        // Lade Template mit errechnetem Inhalt
        $content_headers = array("head_on" => TRUE,
		    					 "head_type" => "default",
		                     	 "head_value" => $get_pagetitle[2] . _settings_show_head,
								 "navi_on" => TRUE,
								 "navi_type" => "settings",
								 );
        $index = show("$dir/settings", array("settings_head_1" => _settings_show_head_overview_1,
                                             "pagetitle_head" => _settings_show_pagetitle_head,
                                             "version_cb_head" => _settings_show_version_pagetitle_1,
                                             "version_cb_discribtion" => _settings_show_version_pagetitle_2,
                                             "language_head" => _settings_show_language_head,
                                             "template_head" => _settings_show_template_head,
                 							 "ver_chk_on_head" => _settings_show_ver_chk_on_head,
            	        					 "ver_chk_on_discribtion" => _settings_show_ver_chk_on_discribtion,
                                             "host_head" => _settings_show_host_head,
        									 "host_discribtion" => _settings_show_host_discribtion,
        									 "reCAPTCHA_Public_head" => _settings_show_reCAPTCHA_Public_head,
        									 "reCAPTCHA_Public_discribtion" => _settings_show_reCAPTCHA_Public_discribtion,
        									 "reCAPTCHA_Private_head" => _settings_show_reCAPTCHA_Private_head,
        									 "reCAPTCHA_Private_discribtion" => _settings_show_reCAPTCHA_Private_discribtion,
            	        					 "settings_head_2" => _settings_show_head_overview_2,
                                             "email_subject_prefix_head" => _settings_show_email_subject_prefix_head,
                                             "email_subject_prefix_discribtion" => _settings_show_email_subject_prefix_discribtion,
                                             "email_delete_history_head" => _settings_show_email_delete_history_head,
        									 "email_delete_history_discribtion" => _settings_show_email_delete_history_discribtion,
        									 "email_sender_head" => _settings_show_email_sender_head,
        									 "email_sender_discribtion" => _settings_show_email_sender_discribtion,
        									 "email_type_head" => _settings_show_email_type_head,
        									 "email_type_discribtion" => _settings_show_email_type_discribtion,
        									 "email_host_head" => _settings_show_email_host_head,
        									 "email_host_discribtion" => _settings_show_email_host_discribtion,
        									 "email_port_head" => _settings_show_email_port_head,
        									 "email_port_discribtion" => _settings_show_email_port_discribtion,
        									 "email_username_head" => _settings_show_email_username_head,
        									 "email_username_discribtion" => _settings_show_email_username_discribtion,
        									 "email_password_head" => _settings_show_email_password_head,
        									 "email_password_discribtion" => _settings_show_email_password_discribtion,
                                             "settings_head_3" => _settings_show_head_overview_3,
                                             "request_account_head" => _settings_show_request_account_head,
                                             "request_account_discribtion" => _settings_show_request_account_discribtion,                                           
                                             "request_account_pw_on_head" => _settings_show_request_account_pw_on_head,
                                             "request_account_pw_on_discribtion" => _settings_show_request_account_pw_on_discribtion,
                                             "request_account_password_head" => _settings_show_request_account_password_head,
                                             "request_account_password_discribtion" => _settings_show_request_account_password_discribtion,
           				             		 "request_access_page_head" => _settings_show_request_access_page_head,
                                             "request_access_page_discribtion" => _settings_show_request_access_page_discribtion,
                                             "settings_head_4" => _settings_show_head_overview_4,
                                             "ice_host_head" => _settings_ice_host_head,
                                             "ice_host_discribtion" => _settings_ice_host_discribtion,
                							 "ice_port_head" => _settings_ice_port_head,
                                             "ice_port_discribtion" => _settings_ice_port_discribtion,
            	         					 "ice_secure_head" => _settings_ice_secure_head,
                                             "ice_secure_discribtion" => _settings_ice_secure_discribtion,
            						         "settings_head_5" => _settings_show_head_overview_5,
                                             "logging_functions_on_head" => _settings_logging_functions_on_head,
                                             "logging_functions_on_discribtion" => _settings_logging_functions_on_discribtion,
            			    				 "logging_priority_head" => _settings_logging_priority_head,
            				    		     "logging_priority_discribtion" => _settings_logging_priority_discribtion,
            					    		 "logging_show_priority_head" => _settings_logging_show_priority_head,
                                             "logging_show_priority_discribtion" => _settings_logging_show_priority_discribtion,
            						     	 "logging_days_head" => _settings_logging_days_head,
            						         "logging_days_discribtion" => _settings_logging_days_discribtion,
            							     "logging_show_permissons_head" => _settings_logging_show_permissons_head,
                						     "logging_show_permissons_discribtion" => _settings_logging_show_permissons_discribtion,
            	    						 "settings_head_6" => _settings_show_head_overview_6,
            		    					 "forgot_area_on_head" => _settings_forgot_area_on_head,
                                             "forgot_area_on_discribtion" => _settings_forgot_area_on_discribtion,
        									 "settings_head_7" => _settings_show_head_overview_7,
        									 "map_version_head" => _settings_map_version_head,
        									 "map_build_head" => _settings_map_build_head,
        									 "map_date_head" => _settings_map_date_head,
        									 "map_version_check_head" => _settings_map_version_check_head,
        									 "murmur_version_head" => _settings_murmur_version_head,	
        									 "php_version_head" => _settings_php_version_head,
        									 "slice_version_head" => _settings_slice_version_head,
        									 "mysql_version_head" => _settings_mysql_version_head,
       										 "apache_version_head" => _settings_apache_version_head,
        									 "reports_head" => _settings_reports_head,
        									 "phpinfo" => _settings_phpinfo,
        									 "report" => _settings_report,
        									 "settings_do" => _settings_show_do,
                                             "pagetitle_value" => $get_pagetitle[2],
                                             "pagetitle_version_checkbox_value" => $get_pagetitle_version_value,
                                             "language_value" => getLanguageOptions($language_dir, $get_language[2]),
                                             "template_value" => getTemplateOptions($template_dir, $get_tpl[2]),
            			    				 "ver_chk_on_value" => $get_ver_chk_on_value,
                                             "host_value" => $get_host_value,
        									 "reCAPTCHA_Public_value" => $get_reCAPTCHA_Public[2],        
        									 "reCAPTCHA_Private_value" => $get_reCAPTCHA_Private[2],
            			    				 "email_subject_prefix_value" => $get_email_subject_prefix[2],
        									 "email_delete_history_value" => $get_email_delete_history[2],
        									 "email_sender_value" => $get_email_sender[2],
        									 "email_type_value" => $get_email_type_value,
        									 "email_host_value" => $get_email_host[2],
        									 "email_port_value" => $get_email_port[2],
        									 "email_username_value" => $get_email_username[2],
        									 "email_password_value" => $get_email_password[2],        
                                             "request_account_value" => $get_allow_request_account_value,
                                             "request_account_pw_on_value" => $get_secure_pw_on_value,
                                             "request_account_password_value" => $get_secure_password[2],
            				    			 "request_access_page_value" => $get_request_access_page_value,
                                             "ice_host_value" => $get_ice_host_value[2],
							                 "ice_port_value" => $get_ice_port_value[2],
											 "ice_secure_value" => $get_ice_secure_value[2],
                 							 "logging_functions_on_value" => $get_logging_functions_on_value,
                                             "logging_priority_value" => $get_logging_priority_value,
                                             "logging_show_priority_value" => $get_logging_show_priority_value,
                                             "logging_days_value" => $get_logging_days[2],
                                             "logging_show_permissons_value" => $get_logging_permissions_value,
                							 "forgot_area_on_value" => $get_forgot_area_on_value,
        									 "map_version_value" => $version_num,
									         "map_build_value" => $version_build,
									         "map_date_value" => $version_date,
       										 "map_version_check_value" => $map_version_check_value,
        									 "murmur_version_value" => $murmur_version_value,
        									 "php_version_value" => $php_version_value,
        									 "slice_version_value" => $slice_version_value,
        									 "mysql_version_value" => $mysql_version_value,
        									 "apache_version_value" => $apache_version_value,
        									 "report_url" => $report_url,
        									 "report_code" => $report_code,
        									 "setA1" => $setA1,
        									 "setB1" => $setB1,
        									 "dis1" => $dis1,
        									 "setA2" => $setA2,
        									 "setB2" => $setB2,
        									 "dis2" => $dis2,
        									 "setA3" => $setA3,
        									 "setB3" => $setB3,
        									 "dis3" => $dis3,
        									 "setA4" => $setA4,
        									 "setB4" => $setB4,
        									 "dis4" => $dis4,
        									 "setA5" => $setA5,
        									 "setB5" => $setB5,
        									 "dis5" => $dis5,
        									 "setA6" => $setA6,
        									 "setB6" => $setB6,
        									 "dis6" => $dis6,
        									 "setA7" => $setA7,
        									 "setB7" => $setB7,
        									 ));
            
        //Loggingfunktion, Übergabe der Werte: Settings angesehen
        //Definiert ob etwas geloggt werden soll
        if (isset($_SERVER['HTTP_REFERER'])) {
	        if (strpos($_SERVER['HTTP_REFERER'], "settings_db") != TRUE) {
			    $log_values["on"] = TRUE;
		    }
       		} else {
        		 $log_values["on"] = FALSE;
        }
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "settings_show_2";			//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
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
    		$perm_error = "<br><div align=\"center\"><div class='savearea'>"._perm_error_group_user."</div></div>";
    }
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Settings, Formular ausgeben
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