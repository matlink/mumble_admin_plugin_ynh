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
$dir = "admin";
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
// **START**  Profil des Admins anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'profile':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_profile_admin;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['user_id'])) {
    	$user_id = $_GET['user_id'];
    	} elseif (count($_SESSION['user_ids']) == "1") {
    		$user_id = $_SESSION['user_ids'][0];
    		} else {
    			$user_id = FALSE;
    }
    	
    	//Checke übergebene user_ids und gebe ggf. Fehlermeldung aus, wenn zu viele User markiert wurden.
   		if (!isset($_SESSION['user_ids'])) {$_SESSION['user_ids'] = FALSE;}
		if (!isset($_GET['user_id'])) {$_GET['user_id'] = FALSE;}
    	if ((count($_SESSION['user_ids']) == "1" OR isset($_GET['user_id'])) AND $user_id != FALSE ) {
    		
	    	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	    	if (perm_handler("profile_admin", FALSE) AND getAdminViewPerms($_MAPUSER['user_id'], $user_id)) { 
	    	//Start definition eigentliche Funktionen dieser Section: 
	             
				//DB auslesen über User-Infos
				$admin = user_info($user_id);
				
				//Definiere Sprache
	            if ($admin['language'] == "german") {
	                $lang = _settings_language_de;
	            	} elseif ($admin['language'] == "english") {
	                	$lang = _settings_language_en;
	            }
	            			
				//Hole Servernamen
				$servers = getServernamesForUser($admin['servers']);
	       
				//Definiere Admin Typ
				if ($admin['type_id'] == "1") {
					$type = _admin_profile_reseller;
					} elseif ($admin['type_id'] == "0") {
						$type = _admin_profile_customer;
				}
				
				//Hole Gruppen -Name und -Beschreibung usw.
				$group = group_info($admin['group_id']);
				$gstat = get_all_percent_perms($user_id);
				
				//Ändere Strings, zu "n/a", wenn diese leer sind
	    		if (!isset($admin['servers'])) {
					$admin['servers'] = _admin_profile_na;
				}
				if ($admin['customer_id'] == "") {
					$admin['customer_id'] = _admin_profile_na;
				}
	    		if ($admin['icq'] == "") {
					$admin['icq'] = _admin_profile_na;
				}
	    		if ($admin['phone'] == "") {
					$admin['phone'] = _admin_profile_na;
				}	
				if ($admin['street'] == "") {
					$admin['street'] = _admin_profile_na;
				}
	    		if ($admin['postalcode'] == "" AND $admin['city'] == "") {
					$admin['postalcode'] = _admin_profile_na;
				}
	    		if ($admin['country'] == "") {
					$admin['country'] = _admin_profile_na;
				}
	    		if ($admin['discr'] == "") {
					$admin['discr'] = _admin_profile_na;
				}
				
				//Links generieren
				$edit = "<img src=\"../inc/tpl/".$tpldir."/images/server_data.png\" alt=\"\" border=\"0\"><a href=\"../admin/index.php?section=edit_admin&user_id=$admin[user_id]\" target=\"_self\">"._admin_profile_edit."</a>";
				$avatar = "<img src=\"../inc/tpl/".$tpldir."/images/avatar.png\" alt=\"\" border=\"0\"><a href=\"../admin/index.php?section=avatar&user_id=$admin[user_id]\" target=\"_self\">"._admin_profile_avatar."</a>";
				$delete = "<img src=\"../inc/tpl/".$tpldir."/images/delete.png\" alt=\"\" border=\"0\"><a href=\"../admin/index.php?section=delete_admin&user_id=$admin[user_id]\" target=\"_self\">"._admin_profile_delete."</a>";
				if ($admin['lock'] == "1") {
					$lock = _admin_list_unlock;
					$locking = "<img src=\"../inc/tpl/".$tpldir."/images/lock.png\" alt=\"\" border=\"0\"><a href=\"../admin/index.php?section=lock_admin&user_id=$admin[user_id]\" target=\"_self\">"._admin_profile_locking_s."</a>";
					} elseif ($admin['lock'] == "0") {
						$lock = _admin_list_nlock;
						$locking = "<img src=\"../inc/tpl/".$tpldir."/images/unlock.png\" alt=\"\" border=\"0\"><a href=\"../admin/index.php?section=lock_admin&user_id=$admin[user_id]\" target=\"_self\">"._admin_profile_locking_a."</a>";
				}
				$perissions = "<img src=\"../inc/tpl/".$tpldir."/images/permissions.png\" alt=\"\" border=\"0\"><a href=\"../permissions/index.php?section=edit_admin&user_id=$admin[user_id]\" target=\"_self\">"._admin_profile_permissions."</a>";
				if (isset($admin['servers'])) {
					foreach($admin['servers'] as $server_id) {
						$server = server_info($server_id);
						$server_url .= "<img src=\"../inc/tpl/".$tpldir."/images/servers.png\" alt=\"\" border=\"0\"><a href=\"../server/index.php?section=show_server&server_id=$server[server_id]\" target=\"_self\"> ".$server['name']."</a><br />";
					}
					} else {
						$server_url = FALSE;
				}
				
				//Hole Profilavatar
				$avatar_dir .= $admin['user_id'] . ".png";
				if (file_exists($avatar_dir)) {
					$image = '<img src="../inc/uploads/avatars/'.$admin['user_id'].'.png" width="170" height="210">';
					} else {
						$gravatar = get_gravatar( $admin['email'], $s = 170, $d = 'mm', $r = 'g', $img = false, $atts = array() );
						if ($gravatar == TRUE) {
							$image = '<img src="'.$gravatar.'" width="170" height="210">';
							} else {
								$image = '<img src="../inc/uploads/avatars/nopic.png" width="170" height="210">';
						}
				}
				
				// Speichere in Ausgabe
				$content_headers = array("head_on" => TRUE,
										 "head_type" => "default",
										 "head_value" => _admin_profile_head.$admin['teamtag'],
										 "navi_on" => TRUE,
										 "navi_type" => "default",
										 );
				$index = show("$dir/profile", array("teamtag" => $admin['teamtag'],
													"name" => $admin['name'],
													"user_id" => $admin['user_id'],
													"logins" => $admin['logins'],
													"language" => $lang,
													"template" => $admin['template'],
													"lock" => $lock,
													"last_active" => $admin['last_active'],
													"servers" => $servers,
													"customer_id" => $admin['customer_id'],
													"email" => $admin['email'],
													"icq" => $admin['icq'],
													"phone" => $admin['phone'],
													"street" => $admin['street'],
													"postalcode" => $admin['postalcode'],
													"city" => $admin['city'],
													"country" => $admin['country'],
													"type" => $type,
													"group" => $group['name'],
													"percent" => $gstat['percent'],
													"discr" => $admin['discr'],
													"edit" => $edit,
													"avatar" => $avatar,
													"delete" => $delete,
													"locking" => $locking,
													"permissions" => $perissions,
													"server_url" => $server_url,
													"image" => $image,
													"head_name" => _admin_profile_name,
													"head_user_id" => _admin_profile_user_id,
													"head_allg" => _admin_profile_allg,
													"head_logins" => _admin_profile_logins,
													"head_language" => _admin_profile_language,
													"head_template" => _admin_profile_template,
													"head_status" => _admin_profile_status,
													"head_last_active" => _admin_profile_last_active,
													"head_servers" => _admin_profile_servers,
													"head_contacts" => _admin_profile_contacts,
													"head_customer_id" => _admin_profile_customer_id,
													"head_email" => _admin_profile_email,
													"head_icq" => _admin_profile_icq,
													"head_phone" => _admin_profile_phone,
													"head_adress" => _admin_profile_adress,
													"head_street" => _admin_profile_street,
													"head_poci" => _admin_profile_poci,
													"head_country" => _admin_profile_country,
													"head_permissions" => _admin_profile_permissions_head,
													"head_type" => _admin_profile_type,
													"head_group" => _admin_profile_group,
													"head_percent" => _admin_profile_percent,
													"head_discr" => _admin_profile_discr,
													));					
			
			//Ende definition eigentliche Funktionen dieser Section^^
	    	} elseif (getAdminViewPerms($_MAPUSER['user_id'], $user_id) == FALSE) {
	    		$section_id = section_info(FALSE, "profile_admin");
	    		$perm_error .= "<br><div align=\"center\"><div class='savearea'>"._perm_error_group_user. " Error-ID: " . $section_id['section_id'] . "</div></div>";	
	    		autoforward("../admin/index.php?default",6);
	    } 
    	} else {
    		$info = "<br><div align=\"center\"><div class='savearea'>"._admin_show_selected_wrong."</div></div>";
    		autoforward("../admin/index.php?default",3);
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Profil des Admins anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Avatar eines Admins hochladen/ändern DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'avatar_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_avatar_admin;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("avatar_admin", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Hole Variablen
		$user_id = getGlobalPost('user_id');
		$delete = getGlobalPost('delete');
		$filename = $avatar_dir.$user_id.".png";
		$dirPerms = substr(decoct(fileperms($avatar_dir)), 1);

		//Checke ob CHMOD-Rechte stimmen
    	if ((octdec($dirPerms) & 0777) == 0777) {
	    	//Lösche altes Avatar
	    	if ($delete == "1") {
	    		//Wenn Datei vorhanden
	    		if (file_exists($filename)) {
	    			unlink($filename);
	    			$info = "<br><div align=\"center\"><div class='boxsucess'>"._avatar_admin_sucess_del."</div></div>";
	    			} else {
	    				$info = "<br><div align=\"center\"><div class='savearea'>"._avatar_admin_err_del."</div></div>";
	    		}    		
	    		} else {
	    		//Lade Avatar Hoch
		    	//Wenn .png-Format
		    	if ($_FILES['avatar']['tmp_name'] != FALSE) {
					if (exif_imagetype($_FILES['avatar']['tmp_name']) == IMAGETYPE_PNG) {
						//Wenn nicht Größer als 100 KiB
						if ($_FILES['avatar']['size'] <  102400) {
							if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
								$info = "<br><div align=\"center\"><div class='boxsucess'>"._avatar_admin_sucess."</div></div>";
			      			}
							move_uploaded_file($_FILES['avatar']['tmp_name'], $filename);
			      			} else {
			      				$info = "<br><div align=\"center\"><div class='savearea'>"._avatar_admin_err_size."</div></div>";
						}
						} else {
			    			$info = "<br><div align=\"center\"><div class='savearea'>"._avatar_admin_err_type."</div></div>";
					}
		    		} else {
		    			$info = "<br><div align=\"center\"><div class='savearea'>"._avatar_admin_err_type."</div></div>";
		    	}
	    	}
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._avatar_admin_err_chmod."</div></div>";
    	}
    	
    	//Weiterleiten
    	autoforward("../admin/index.php?section=profile&user_id=$user_id",5);
    	
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Avatar eines Admins hochladen/ändern DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Avatar eines Admins hochladen/ändern Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'avatar':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_avatar_admin;
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("avatar_admin", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 

		//DB auslesen über User-Infos
		$admin = user_info($_GET['user_id']);
				
		//Hole Profilavatar
		$avatar_dir .= $admin['user_id'] . ".png";
		if (file_exists($avatar_dir)) {
			$image = '<img src="../inc/uploads/avatars/'.$admin['user_id'].'.png" width="170" height="210">';
			} else {
				$image = '<img src="../inc/uploads/avatars/nopic.png" width="170" height="210">';
		}
		
		// Speichere in Ausgabe
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
								 "head_value" => _admin_avatar_head.$admin['teamtag'],
								 "navi_on" => TRUE,
								 "navi_type" => "avatar_admin",
								 );
		$index = show("$dir/avatar", array("head_image" => _admin_avatar_image,
										   "head_upload" => _admin_avatar_upload,
										   "head_info" => _admin_avatar_info,
										   "head_delete" => _admin_avatar_delete,
										   "image" => $image,
										   "info" => _admin_avatar_infos,
										   "delete" => _admin_avatar_deleted,
										   "user_id" => $admin['user_id'],
										   ));					
		
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Avatar eines Admins hochladen/ändern Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  erstellte MAP User in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_admin_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_create_admin_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("create_admin", FALSE)) { 
    	
		//Hole Werte aus Formular
    	$form = array("name" => $_POST['name'],
				      "pass1" => $_POST['pass1'],
				      "pass2" => $_POST['pass2'],
				      "email" => $_POST['email'],
				      "clantag" => $_POST['clantag'],
				      "username" => $_POST['username'],
				      "type_id" => $_POST['type_id'],
				      "group_id" => $_POST['group_id'],
				      "language" => $_POST['language'],
				      "template" => $_POST['template'],
				      "coockie" => $_POST['coockie'],
				      "customer_id" => $_POST['customer_id'],
				      "icq" => $_POST['icq'],
				      "phone" => $_POST['phone'],
				      "street" => $_POST['street'],
				      "postalcode" => $_POST['postalcode'],
				      "city" => $_POST['city'],
				      "country" => $_POST['country'],
				      "discr" => $_POST['discr'],
    				  );
    				  
            
        //Überpfüft ob E-Mail Adresse richtig eingegeben wurde bzw. ob diese existiert
        $check_email = check_email($form['email']);
    
        //Kontrolle ob Eingaben identisch sind und ob keine Eingabe fehlt
        if ($form['name'] == "" OR $form['email'] == "" OR $check_email == FALSE OR $form['pass1'] != $form['pass2'] OR $form['pass1'] == "") {
            $info .= "<br><div align=\"center\"><div class='savearea'>"._admin_create_wrong_entry_try_again."</div></div>";
            $check_error = TRUE;
        	} else {
        		$check_error = FALSE;
        }
        
        //DB nach vorhandenen Admins auslesen damit user nicht doppelt erstellt werden
        if (checkIfAdminExists($form['name'], FALSE)) {
        	$checkUN = TRUE;
        	} else {
        		$checkUN = FALSE;
        }
        
        if ($check_email == TRUE && $check_error == FALSE) {
            if ($checkUN == FALSE) {
            	//Generiere neue perm_id
    			$perm_id = pw_gen("3", "3", "3", "0");
    			//Speicher Userdaten
                $save_user = "INSERT INTO `".$database_prefix."user` (`type_id`, `group_id`, `perm_id`, `customer_id`, `name`, `username`, `clantag`, `email`, `pw`, `logins`, `last_active`, `lock`, `language`, `template`, `coockie`, `icq`, `discr`, `phone`, `street`, `postalcode`, `city`, `country`) VALUES ('$form[type_id]','$form[group_id]','$perm_id','$form[customer_id]','$form[name]','$form[username]','$form[clantag]','$form[email]','".md5($form['pass1'])."','0','$aktdate','1','$form[language]','$form[template]','$form[coockie]','$form[icq]','$form[discr]','$form[phone]','$form[street]','$form[postalcode]','$form[city]','$form[country]')";
                $qry = mysql_query($save_user);
                $user_id = mysql_insert_id();
                //zuständige Server adden
				$server = getServers();
				foreach ($server as $server_id) {
					if (isset($_POST[$server_id]) && $_POST[$server_id] != "0" && $_POST[$server_id] != FALSE) {
	        			mysql_query("INSERT INTO `".$database_prefix."user_servers` (`user_id`, `server_id`) VALUES ('".$user_id."', '".$server_id."');");
					}
				}
				//User-Perms in DB schreiben
				$sections = getSectionIDs();
            	foreach ($sections as $section) {
					mysql_query("INSERT INTO `".$database_prefix."user_perm` (`perm_id`, `section_id`, `value`) VALUES ('".$perm_id."', '".$section."', '0');");
				}
                if ($qry == true) {        
                    // Wenn User erfolgreich erstellt, Absenden seiner Daten an seine Mailadresse
                    //Definiere Platzhalter für E-Mail
			    	$Placeholder = array("ResellerName" => $_MAPUSER['teamtag'],
								    	 "URL" => $getHTTP  . $_SERVER['SERVER_NAME'],
								    	 "AdminLogin" =>$form['name'],
								    	 "AdminPassword" => $form['pass1'],			    	
			    						 );    	
			    	//Sende E-Mail
			    	$Connection = mapmail($_MAPUSER['email'], $_MAPUSER['teamtag'], $_MAPUSER['user_id'], 
			    						  $form['email'], $form['name'], $user_id,
			    						  $CcMail = FALSE, $BccMail = FALSE, $ReplyToMail = FALSE,
			    						  $WordWrap = "75", $IsHTML = TRUE, $template_id = '17001',
			    						  $Placeholder, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
			    						  $AltSubject = FALSE, $AltBody = FALSE);
                    
                    
                    $info .= "<br><div align=\"center\"><div class='boxsucess'>"._admin_create_sucsess_1.$form['name']._admin_create_sucsess_2."</div></div>";
                    $autoforward = TRUE;
                    } else {
                        $info .= "<br><div align=\"center\"><div class='savearea'>"._admin_create_false_1.$form['name']._admin_create_false_2."</div></div>";
                        $autoforward = FALSE;
                }
                } else {
                    $info .= "<br><div align=\"center\"><div class='savearea'>"._admin_allready_exists_1.$form['name']._admin_allready_exists_2.$form['name']._admin_allready_exists_3."</div></div>";
                    $autoforward = FALSE;
            }
            } else {
            	$info = "<br><div align=\"center\"><div class='savearea'>"._admin_create_false_1.$form['name']._admin_create_false_2."</div></div>";
            	$autoforward = FALSE;
        }
        
        //Loggingfunktion, Übergabe der Werte: erstellte MAP User in DB schreiben
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_2";					//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = "";							//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $form['name'];					//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
        
        //Direktweiterleitung
        if ($autoforward == TRUE) {
            autoforward("../admin/index.php?section=profile&user_id=".$user_id,3);
        }  
        //Weiterleitung nach 3 Sekunden
        if ($autoforward == FALSE) {
            autoforward("../admin/index.php?section=create_admin",5);
        }

	}
	
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  erstellte MAP User in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Admin erstellen > Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_admin':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_create_admin;
	
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("create_admin", FALSE)) {      
    	
		//Start definition eigentliche Funktionen dieser Section:

    	//Definiere den Typ des Admins
		if ($_MAPUSER['type_id'] == 1) {
			$type = "<option value=\"1\">"._edit_admin_reseller."</option>\n<option value=\"0\" selected=\"selected\">"._edit_admin_customer."</option>\n";
			} elseif ($_MAPUSER['type_id'] == 0) {
				$type = "<option value=\"0\" selected=\"selected\">"._edit_admin_customer."</option>\n";
		}
		
		
		//Definiere Berechtigungsgruppe des Admins
		$groups = getGroups();
		$group = FALSE;
		foreach ($groups as $group_id) {
			$group_info = group_info($group_id);
			//Checke ob Admin diese Gruppe benutzen darf
			if ($_MAPUSER['type_id'] == 1) {
				$group .= "<option value=\"".$group_info['group_id']."\">".$group_info['name']."</option>\n";
				} elseif ($_MAPUSER['type_id'] == 0 AND $_MAPUSER['group_id'] == $group_id) {
					$group .= "<option value=\"".$group_info['group_id']."\">".$group_info['name']."</option>\n";
			}
		}
		
		//Hole alle Server und gebe mit Checkboxen aus
		$allServers = getServers();
		$admin = user_info($_MAPUSER['user_id']);
		$server = FALSE;
		foreach ($allServers as $server_id) {
			$srv = server_info($server_id);
			//Checke ob User zu dem Server verbunden ist
			if ($_MAPUSER['type_id'] == 1) {
				$server .= '<div><input type="checkbox" id="'.$srv['server_id'].'" name="'.$srv['server_id'].'" value="TRUE" class="checkbox"><label for="'.$srv['server_id'].'"> '.$srv['name'].'</label></div>';
				} elseif ($_MAPUSER['type_id'] == 0) {
					foreach ($admin['servers'] as $AdminServerID) {
						if ($server_id == $AdminServerID) {
							$server .= '<div><input type="checkbox" id="'.$srv['server_id'].'" name="'.$srv['server_id'].'" value="TRUE" class="checkbox"><label for="'.$srv['server_id'].'"> '.$srv['name'].'</label></div>';
						}
					}
			}
		}
        
        //Definiere Coockies
        $coockie = FALSE;
        $coockie .= "<option value=\"300\">"._edit_admin_coockie_300."</option>\n";
		$coockie .= "<option value=\"1800\">"._edit_admin_coockie_1800."</option>\n";
		$coockie .= "<option value=\"3600\">"._edit_admin_coockie_3600."</option>\n";
		$coockie .= "<option value=\"10800\">"._edit_admin_coockie_10800."</option>\n";
		$coockie .= "<option value=\"86400\">"._edit_admin_coockie_86400."</option>\n";
		$coockie .= "<option value=\"604800\">"._edit_admin_coockie_604800."</option>\n";
		$coockie .= "<option value=\"999999999\">"._edit_admin_coockie_999999999."</option>\n";

		//Daten ans Formular schicken und / bzw. es aufrufen
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
								 "head_value" => _create_admin_head,
								 "navi_on" => TRUE,
								 "navi_type" => "create_admin",
								 );
		$index = show("$dir/create_admin", array("name" => "",
											    "email" => "",
											    "clantag" => "",
											    "username" => "",
											    "type" => $type,
											    "group" => $group,
											    "server" => $server,
											    "language" => getLanguageOptions($language_dir, $_MAPUSER['language']),
											    "template" => getTemplateOptions($template_dir, $_MAPUSER['template']),
											    "coockie" => $coockie,
											    "customer_id" => "",
											    "icq" => "",
											    "phone" => "",
											    "street" => "",
											    "postalcode" => "",
											    "city" => "",
											    "country" => "",
											    "discr" => "",
												"head_name" => _edit_admin_name,
												"head_pass1" => _edit_admin_pass1,
												"head_pass2" => _edit_admin_pass2,
												"head_email" => _edit_admin_email,
												"head_clantag" => _edit_admin_clantag,
												"head_username" => _edit_admin_username,
												"head_type" => _edit_admin_type,
												"head_group" => _edit_admin_group,  
												"head_server" => _edit_admin_server,
												"head_language" => _edit_admin_language,
												"head_template" => _edit_admin_template,
												"head_coockie" => _edit_admin_coockie,
												"head_customer_id" => _edit_admin_customer_id,
												"head_icq" => _edit_admin_icq,
												"head_phone" => _edit_admin_phone,  
												"head_street" => _edit_admin_street,
												"head_postalcode" => _edit_admin_postalcode,
												"head_city" => _edit_admin_city,
												"head_country" => _edit_admin_country,
												"head_discr" => _edit_admin_discr,
												"discription_name" => _edit_admin_discr_name,
												"discription_pass1" => _edit_admin_discr_pass1,
												"discription_pass2" => _edit_admin_discr_pass2,
												"discription_email" => _edit_admin_discr_email,
												"discription_clantag" => _edit_admin_discr_clantag,
												"discription_username" => _edit_admin_discr_username,
												"discription_type" => _edit_admin_discr_type,
												"discription_group" => _edit_admin_discr_group,
												"discription_server" => _edit_admin_discr_server,
												"discription_language" => _edit_admin_discr_language,
												"discription_template" => _edit_admin_discr_template,
												"discription_coockie" => _edit_admin_discr_coockie,
												"discription_customer_id" => _edit_admin_discr_customer_id,
												"discription_icq" => _edit_admin_discr_icq,
												"discription_phone" => _edit_admin_discr_phone,
												"discription_street" => _edit_admin_discr_street,
												"discription_postalcode" => _edit_admin_discr_postalcode,
												"discription_city" => _edit_admin_discr_city,
												"discription_country" => _edit_admin_discr_country,
												"discription_discr" => _edit_admin_discr_discr,
											   )); 
                                                               
		//Ende definition eigentliche Funktionen dieser Section^^
    }    
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Admin erstellen > Formular
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Edit Admin, eingetragene Daten in Datenbank schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_admin_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_edit_admin_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("edit_admin", FALSE)) {
    	
    	//Hole Werte aus Formular
    	$form = array("user_id" => getGlobalPost('user_id'),
					  "name" => getGlobalPost('name'),
				      "pass1" => getGlobalPost('pass1'),
				      "pass2" => getGlobalPost('pass2'),
				      "email" => getGlobalPost('email'),
				      "clantag" => getGlobalPost('clantag'),
				      "username" => getGlobalPost('username'),
				      "language" => getGlobalPost('language'),
				      "template" => getGlobalPost('template'),
				      "coockie" => getGlobalPost('coockie'),
				      "customer_id" => getGlobalPost('customer_id'),
				      "icq" => getGlobalPost('icq'),
				      "phone" => getGlobalPost('phone'),
				      "street" => getGlobalPost('street'),
				      "postalcode" => getGlobalPost('postalcode'),
				      "city" => getGlobalPost('city'),
				      "country" => getGlobalPost('country'),
				      "discr" => getGlobalPost('discr'),
    				  );
    				  
    	$true_change = FALSE;
    	$val_error = FALSE;
    				  
    	//Hole Daten des Admins
    	$admin = user_info($form['user_id']);

		//Loginname "name" ändern
		if (perm_handler("edit_admin_name", FALSE) && $admin['name'] != $form['name'] && $form['name'] != "") {
			$qryCntUsers = mysql_query("SELECT user_id FROM `".$database_prefix."user` WHERE name = '$form[name]'");
        	$getCntUsers = mysql_num_rows($qryCntUsers);
        	if ($getCntUsers == 0) {
        		mysql_query("UPDATE `".$database_prefix."user` SET name = '$form[name]' WHERE user_id = '$form[user_id]'");
        		$true_change = TRUE;
        	}
		}
    	
    	//Passwort "pass" ändern
    	if (perm_handler("edit_admin_pass", FALSE) && $admin['pw'] != md5($form['pass1']) && $form['pass1'] == $form['pass2'] && $form['pass1'] != "") {
			mysql_query("UPDATE `".$database_prefix."user` SET pw = '".md5($form[pass1])."' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//E-Mail "email" ändern
    	if (perm_handler("edit_admin_email", FALSE) && check_email($form['email']) && $admin['email'] != $form['email'] && $form['email'] != "") {
			mysql_query("UPDATE `".$database_prefix."user` SET email = '$form[email]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Clantag "clantag" ändern
    	if (perm_handler("edit_admin_clantag", FALSE) && $admin['clantag'] != $form['clantag']) {
			mysql_query("UPDATE `".$database_prefix."user` SET clantag = '$form[clantag]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Benutzername "username" ändern
    	if (perm_handler("edit_admin_username", FALSE) && $admin['username'] != $form['username']) {
			mysql_query("UPDATE `".$database_prefix."user` SET username = '$form[username]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	    	
    	//Sprache "language" ändern
    	if (perm_handler("edit_admin_language", FALSE) && $admin['language'] != $form['language']) {
			mysql_query("UPDATE `".$database_prefix."user` SET language = '$form[language]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Template "template" ändern
    	if (perm_handler("edit_admin_template", FALSE) && $admin['template'] != $form['template']) {
			mysql_query("UPDATE `".$database_prefix."user` SET template = '$form[template]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Coockies "coockie" ändern
    	if (perm_handler("edit_admin_coockie", FALSE) && $admin['coockie'] != $form['coockie']) {
			mysql_query("UPDATE `".$database_prefix."user` SET coockie = '$form[coockie]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Kundennummer "customer_id" ändern
    	if (perm_handler("edit_admin_customer_id", FALSE) && $admin['customer_id'] != $form['customer_id']) {
			mysql_query("UPDATE `".$database_prefix."user` SET customer_id = '$form[customer_id]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//ICQ Nummer "icq" ändern
    	if (perm_handler("edit_admin_icq", FALSE) && $admin['icq'] != $form['icq']) {
			mysql_query("UPDATE `".$database_prefix."user` SET icq = '$form[icq]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Telefonnummer "phone" ändern
    	if (perm_handler("edit_admin_phone", FALSE) && $admin['phone'] != $form['phone']) {
			mysql_query("UPDATE `".$database_prefix."user` SET phone = '$form[phone]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Straße "street" ändern
    	if (perm_handler("edit_admin_street", FALSE) && $admin['street'] != $form['street']) {
			mysql_query("UPDATE `".$database_prefix."user` SET street = '$form[street]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Postleizahl "postalcode" ändern
    	if (perm_handler("edit_admin_postalcode", FALSE) && $admin['postalcode'] != $form['postalcode']) {
			mysql_query("UPDATE `".$database_prefix."user` SET postalcode = '$form[postalcode]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Stadt "city" ändern
    	if (perm_handler("edit_admin_city", FALSE) && $admin['city'] != $form['city']) {
			mysql_query("UPDATE `".$database_prefix."user` SET city = '$form[city]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Land "country" ändern
    	if (perm_handler("edit_admin_country", FALSE) && $admin['country'] != $form['country']) {
			mysql_query("UPDATE `".$database_prefix."user` SET country = '$form[country]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Beschreibung "discr" ändern
    	if (perm_handler("edit_admin_discr", FALSE) && $admin['discr'] != $form['discr']) {
			mysql_query("UPDATE `".$database_prefix."user` SET discr = '$form[discr]' WHERE user_id = '$form[user_id]'");
			$true_change = TRUE;
    	}

        //Loggingfunktion, Übergabe der Werte: Edit MAP User, eingetragene Daten in Datenbank schreiben
        //Definiert ob etwas geloggt werden soll
        if ($true_change == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_4";					//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = "";							//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $admin['teamtag'];				//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $admin['user_id'];				//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
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
            $info = "<br><div align=\"center\"><div class='boxsucess'>"._edit_admin_edit_success."</div></div>";
            $autoforward = TRUE;
        }
        
        //Direktweiterleitung
        if ($autoforward == TRUE) {
            autoforward("../admin/index.php?section=profile&user_id=$form[user_id]",2);
        }    
        //Weiterleitung nach 3 Sekunden
        if ($autoforward == FALSE) {
            autoforward("../admin/index.php?section=edit_admin&user_id=$form[user_id]",5);
        }
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Edit Admin, eingetragene Daten in Datenbank schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Edit Admin, Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_admin':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_edit_admin;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['user_id'])) {
    	$user_id = $_GET['user_id'];
    	} elseif (count($_SESSION['user_ids']) == "1") {
    		$user_id = $_SESSION['user_ids'][0];
    		} else {
    			$user_id = FALSE;
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("edit_admin", FALSE)) { 
    	
    	//Wenn Server_id aus Formular / Liste übergeben wurde, ansonsten fehler!
    	if(count($user_id) != "0" AND $user_id != FALSE) {
	    	//Start definition eigentliche Funktionen dieser Section:
	    	
	    	//Hole Admin-Werte
	    	$admin = user_info($user_id);
	        
	        //Definiere Coockies
	        $coockie = FALSE;
	        if ($admin['coockie'] == 300) {  //5 Minuten
	        	$coockie .= "<option value=\"300\" selected=\"selected\">"._edit_admin_coockie_300."</option>\n";
	        	} else {
	        		$coockie .= "<option value=\"300\">"._edit_admin_coockie_300."</option>\n";
	        }
	        if ($admin['coockie'] == 1800) {  //30 Minuten
	        	$coockie .= "<option value=\"1800\" selected=\"selected\">"._edit_admin_coockie_1800."</option>\n";
	        	} else {
	        		$coockie .= "<option value=\"1800\">"._edit_admin_coockie_1800."</option>\n";
	        }
	        if ($admin['coockie'] == 3600) {  //1 Stunde
	        	$coockie .= "<option value=\"3600\" selected=\"selected\">"._edit_admin_coockie_3600."</option>\n";
	        	} else {
	        		$coockie .= "<option value=\"3600\">"._edit_admin_coockie_3600."</option>\n";
	        }
	        if ($admin['coockie'] == 10800) {  //3 Stunden
	        	$coockie .= "<option value=\"10800\" selected=\"selected\">"._edit_admin_coockie_10800."</option>\n";
	        	} else {
	        		$coockie .= "<option value=\"10800\">"._edit_admin_coockie_10800."</option>\n";
	        }
	        if ($admin['coockie'] == 86400) {  //1 Tag
	        	$coockie .= "<option value=\"86400\" selected=\"selected\">"._edit_admin_coockie_86400."</option>\n";
	        	} else {
	        		$coockie .= "<option value=\"86400\">"._edit_admin_coockie_86400."</option>\n";
	        }
	        if ($admin['coockie'] == 604800) {  //1 Woche
	        	$coockie .= "<option value=\"604800\" selected=\"selected\">"._edit_admin_coockie_604800."</option>\n";
	        	} else {
	        		$coockie .= "<option value=\"604800\">"._edit_admin_coockie_604800."</option>\n";
	        }
	    	if ($admin['coockie'] == 999999999) {  //immer
	        	$coockie .= "<option value=\"999999999\" selected=\"selected\">"._edit_admin_coockie_999999999."</option>\n";
	        	} else {
	        		$coockie .= "<option value=\"999999999\">"._edit_admin_coockie_999999999."</option>\n";
	        }
	        
	    	//Darstellungsberechtigungen überprüfen
	    	if (perm_handler("edit_admin_name", FALSE)) {
				$setAname = ''; $setBname = '';
				} else {
					$setAname = '<!--'; $setBname = '-->';
			}
	        if (perm_handler("edit_admin_pass", FALSE)) {
				$setApass = ''; $setBpass = '';
				} else {
					$setApass = '<!--'; $setBpass = '-->';
			}
	        if (perm_handler("edit_admin_email", FALSE)) {
				$setAemail = ''; $setBemail = '';
				} else {
					$setAemail = '<!--'; $setBemail = '-->';
			}
	        if (perm_handler("edit_admin_clantag", FALSE)) {
				$setAclantag = ''; $setBclantag = '';
				} else {
					$setAclantag = '<!--'; $setBclantag = '-->';
			}
	        if (perm_handler("edit_admin_username", FALSE)) {
				$setAusername = ''; $setBusername = '';
				} else {
					$setAusername = '<!--'; $setBusername = '-->';
			}
	        if (perm_handler("edit_admin_language", FALSE)) {
				$setAlanguage = ''; $setBlanguage = '';
				} else {
					$setAlanguage = '<!--'; $setBlanguage = '-->';
			}
	        if (perm_handler("edit_admin_template", FALSE)) {
				$setAtemplate = ''; $setBtemplate = '';
				} else {
					$setAtemplate = '<!--'; $setBtemplate = '-->';
			}
	        if (perm_handler("edit_admin_coockie", FALSE)) {
				$setAcoockie = ''; $setBcoockie = '';
				} else {
					$setAcoockie = '<!--'; $setBcoockie = '-->';
			}
	        if (perm_handler("edit_admin_customer_id", FALSE)) {
				$setAcustomer_id = ''; $setBcustomer_id = '';
				} else {
					$setAcustomer_id = '<!--'; $setBcustomer_id = '-->';
			}
	        if (perm_handler("edit_admin_icq", FALSE)) {
				$setAicq = ''; $setBicq = '';
				} else {
					$setAicq = '<!--'; $setBicq = '-->';
			}
	        if (perm_handler("edit_admin_phone", FALSE)) {
				$setAphone = ''; $setBphone = '';
				} else {
					$setAphone = '<!--'; $setBphone = '-->';
			}
	        if (perm_handler("edit_admin_street", FALSE)) {
				$setAstreet = ''; $setBstreet = '';
				} else {
					$setAstreet = '<!--'; $setBstreet = '-->';
			}
	        if (perm_handler("edit_admin_postalcode", FALSE)) {
				$setApostalcode = ''; $setBpostalcode = '';
				} else {
					$setApostalcode = '<!--'; $setBpostalcode = '-->';
			}
	        if (perm_handler("edit_admin_city", FALSE)) {
				$setAcity = ''; $setBcity = '';
				} else {
					$setAcity = '<!--'; $setBcity = '-->';
			}
	        if (perm_handler("edit_admin_country", FALSE)) {
				$setAcountry = ''; $setBcountry = '';
				} else {
					$setAcountry = '<!--'; $setBcountry = '-->';
			}
	        if (perm_handler("edit_admin_discr", FALSE)) {
				$setAdiscr = ''; $setBdiscr = '';
				} else {
					$setAdiscr = '<!--'; $setBdiscr = '-->';
			}
			
			//Daten ans Formular schicken und / bzw. es aufrufen
			$content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
									 "head_value" => _edit_admin_head . $admin['teamtag'],
									 "navi_on" => TRUE,
									 "navi_type" => "edit_admin",
									 );
			$index = show("$dir/edit_admin", array("user_id" => $admin['user_id'],
												    "name" => $admin['name'],
												    "email" => $admin['email'],
												    "clantag" => $admin['clantag'],
												    "username" => $admin['username'],
												    "language" => getLanguageOptions($language_dir, $admin['language']),
												    "template" => getTemplateOptions($template_dir, $admin['template']),
												    "coockie" => $coockie,
												    "customer_id" => $admin['customer_id'],
												    "icq" => $admin['icq'],
												    "phone" => $admin['phone'],
												    "street" => $admin['street'],
												    "postalcode" => $admin['postalcode'],
												    "city" => $admin['city'],
												    "country" => $admin['country'],
												    "discr" => $admin['discr'],
													"head_name" => _edit_admin_name,
													"head_pass1" => _edit_admin_pass1,
													"head_pass2" => _edit_admin_pass2,
													"head_email" => _edit_admin_email,
													"head_clantag" => _edit_admin_clantag,
													"head_username" => _edit_admin_username,
													"head_language" => _edit_admin_language,
													"head_template" => _edit_admin_template,
													"head_coockie" => _edit_admin_coockie,
													"head_customer_id" => _edit_admin_customer_id,
													"head_icq" => _edit_admin_icq,
													"head_phone" => _edit_admin_phone,  
													"head_street" => _edit_admin_street,
													"head_postalcode" => _edit_admin_postalcode,
													"head_city" => _edit_admin_city,
													"head_country" => _edit_admin_country,
													"head_discr" => _edit_admin_discr,
													"discription_name" => _edit_admin_discr_name,
													"discription_pass1" => _edit_admin_discr_pass1,
													"discription_pass2" => _edit_admin_discr_pass2,
													"discription_email" => _edit_admin_discr_email,
													"discription_clantag" => _edit_admin_discr_clantag,
													"discription_username" => _edit_admin_discr_username,
													"discription_language" => _edit_admin_discr_language,
													"discription_template" => _edit_admin_discr_template,
													"discription_coockie" => _edit_admin_discr_coockie,
													"discription_customer_id" => _edit_admin_discr_customer_id,
													"discription_icq" => _edit_admin_discr_icq,
													"discription_phone" => _edit_admin_discr_phone,
													"discription_street" => _edit_admin_discr_street,
													"discription_postalcode" => _edit_admin_discr_postalcode,
													"discription_city" => _edit_admin_discr_city,
													"discription_country" => _edit_admin_discr_country,
													"discription_discr" => _edit_admin_discr_discr,
													"setAname" => $setAname,
													"setBname" => $setBname,
													"setApass" => $setApass,
													"setBpass" => $setBpass,
													"setAemail" => $setAemail,
													"setBemail" => $setBemail,
													"setAclantag" => $setAclantag,
													"setBclantag" => $setBclantag,
													"setAusername" => $setAusername,
													"setBusername" => $setBusername,
													"setAlanguage" => $setAlanguage,
													"setBlanguage" => $setBlanguage,
													"setAtemplate" => $setAtemplate,
													"setBtemplate" => $setBtemplate,
													"setAcoockie" => $setAcoockie,
													"setBcoockie" => $setBcoockie,
													"setAcustomer_id" => $setAcustomer_id,
													"setBcustomer_id" => $setBcustomer_id,
													"setAicq" => $setAicq,
													"setBicq" => $setBicq,
													"setAphone" => $setAphone,
													"setBphone" => $setBphone,
													"setAstreet" => $setAstreet,
													"setBstreet" => $setBstreet,
													"setApostalcode" => $setApostalcode,
													"setBpostalcode" => $setBpostalcode,
													"setAcity" => $setAcity,
													"setBcity" => $setBcity,
													"setAcountry" => $setAcountry,
													"setBcountry" => $setBcountry,
													"setAdiscr" => $setAdiscr,
													"setBdiscr" => $setBdiscr,
												   )); 
	                                                               
			//Ende definition eigentliche Funktionen dieser Section^^
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._admin_show_selected_wrong."</div></div>";
    			autoforward("../admin/index.php?default",3);
		}
    }        
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Edit Admin, Formular ausgeben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Delete Admin, Löschfunktion > in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_admin_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_delete_admin_db;

    //Hole user_id aus Session(Liste) oder aus URL
	if ($_SESSION['users'][0] != "0") {
		$users = $_SESSION['users'];
		} else {
			$users[0] = "1";  //Wenn Session abgelaufen, mache ungültig und gebe fehler aus!
	}
	unset($_SESSION['users']);
	
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_admin", FALSE)) {
    	
    	//Arbeite Array ab
    	foreach ($users as $user_id) {

	        //Userdaten auslesen
	        $user = user_info($user_id);

	        //Sämtliche MAP Struckturen zu diesem User löschen
	        if ($user['user_id'] != "1" OR $user['user_id'] != $_MAPUSER['user_id']) {
	        	//Lösche Avatar, falls vorhanden
				$filename = $avatar_dir.$user_id.".png";
				$dirPerms = substr(decoct(fileperms($avatar_dir)), 1);
				//Checke ob CHMOD-Rechte stimmen
		    	if ((octdec($dirPerms) & 0777) == 0777) {
		    		//Wenn Datei vorhanden
		    		if (file_exists($filename)) {
		    			unlink($filename);
		    		}
		    	}
	        	//Leere Datenbank
				mysql_query("DELETE FROM `".$database_prefix."user` WHERE user_id = '".$user['user_id']."' LIMIT 1");
				mysql_query("DELETE FROM `".$database_prefix."user_perm` WHERE perm_id = '".$user['perm_id']."'");
				mysql_query("DELETE FROM `".$database_prefix."user_servers` WHERE user_id = '".$user['user_id']."'");
	         	$info .= "<br><div align=\"center\"><div class='boxsucess'>"._delete_admin_sucsessful_1.$user['name']._delete_admin_sucsessful_2."</div></div>";
	         	$autoforward = TRUE;
	         	} else {
	         		$info .= "<br><div align=\"center\"><div class='savearea'>"._delete_admin_not_sucsessful_1.$user['name']._delete_admin_not_sucsessful_2."</div></div>";     
	         		$autoforward = FALSE;
	        }
         	
    	}
    	
        //Loggingfunktion, Übergabe der Werte: Delete MAP User, Löschfunktion > in DB schreiben
        //Definiert ob etwas geloggt werden soll
        if ($autoforward == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "user_db_5";					//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = "";							//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $user['name'];					//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = $user['user_id'];				//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
    
        //Weiterleitung nach 3 Sekunden
        if ($autoforward == 'TRUE') {
            autoforward("../admin/index.php?default",5);
        }

    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Delete Admin, Löschfunktion > in DB schreiben
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Delete Admin > Formular Ausgabe
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_admin':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_delete_admin;
    
    //Hole user_id aus Session(Liste) oder aus URL
    if (isset($_GET['user_id'])) {
    	$_SESSION['user_ids'][0] = $_GET['user_id'];
    	} elseif (isset($_SESSION['user_ids'])) {
			$_SESSION['user_ids'] = $_SESSION['user_ids'];
    		} else {
    			$_SESSION['user_ids'] = FALSE;
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_admin", FALSE)) {
		//Start definition eigentliche Funktionen dieser Section:
		
    	//Wenn Server_id aus Formular / Liste übergeben wurde, ansonsten fehler!
    	if(count($_SESSION['user_ids']) != "0" AND $_SESSION['user_ids'] != FALSE) {
			
    		$i = "0";
	    	//Arbeite user_ids ab
	    	foreach($_SESSION['user_ids'] as $user_id) {
	    	
				//Hole Server Infos
				$user = user_info($user_id);
				
				//Nicht löschen, wenn user_id = 1 und nicht sich selbst
				if ($user['user_id'] != "1" OR $user['user_id'] != $_MAPUSER['user_id']) {
					
					//Schreibe Session
					$_SESSION['users'][$i] = $user_id;
					$i++;
				
					//Setze Trennbalken
		    		if(count($_SESSION['user_ids']) > "1") {
						$line = '<hr />';
						} else {
							$line = '';
					}
					
					//Definiere Status
					if ($user['lock'] == "1") {
						$lock = "<img src=\"../inc/tpl/".$tpldir."/images/unlock.png\" alt=\"\" border=\"0\">";
						} elseif ($user['lock'] == "0") {
							$lock = "<img src=\"../inc/tpl/".$tpldir."/images/lock.png\" alt=\"\" border=\"0\">";
					}	
					
					//Definiere Beschreibung
					if (isset($user['discr'])) {
						$discr = $user['discr'];
						} else {
							$discr = _delete_admin_no_discr;
					}
			                           
					//Daten ans Formular schicken und / bzw. es aufrufen
					$content_headers = array("head_on" => TRUE,
											 "head_type" => "default",
											 "head_value" => _delete_admin_head,
											 "navi_on" => TRUE,
											 "navi_type" => "delete_admin",
											 );         
					$index .= show("$dir/delete_admin", array("user_id" => $user['user_id'],
															  "name" => $user['name'],
															  "linked_name" => $user['linked_name'],
															  "email" => $user['email'],
															  "logins" => $user['logins'],
															  "last_active" => $user['last_active'],
															  "lock" => $lock,
															  "discr" => $discr,
															  "delete_question" => _delete_admin_question,
															  "delete_id" => _delete_admin_id,
															  "delete_name" => _delete_admin_name,
															  "delete_linked_name" => _delete_admin_linked_name,
															  "delete_email" => _delete_admin_email,
															  "delete_logins" => _delete_admin_logins,
															  "delete_last_active" => _delete_admin_last_active,
															  "delete_lock" => _delete_admin_lock,
															  "delete_discr" => _delete_discr,
															  "line" => $line,
															  ));
					} else {
						$info .= "<br><div align=\"center\"><div class='savearea'>"._admin_delete_not_sucsessful_1.$user['linked_name']._admin_delete_not_sucsessful_2."</div></div>";
						if(count($_SESSION['user_ids']) == "1") {
							autoforward("../admin/index.php?section=profile&user_id=".$user['user_id'],3);
						}
				}
	    	}
    		} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._admin_show_selected_wrong."</div></div>";
    			autoforward("../admin/index.php?default",3);
		}
    	
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Delete Admin > Formular Ausgabe
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Lock Admin > Admin sperren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'lock_admin':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_lock_map_user;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['user_id'])) {
    	$user_id = $_GET['user_id'];
    	} elseif (count($_SESSION['user_ids']) == "1") {
    		$user_id = $_SESSION['user_ids'][0];
    }
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("lock_admin", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene server_ids und gebe ggf. Fehlermeldung aus, wenn zu viele Server markiert wurden.
    	if (count($_SESSION['user_ids']) == "1" OR isset($_GET['user_id'])) {
             
			//DB auslesen über User-Infos
			$admin = user_info($user_id);
			
			//Checke ob gesperrt oder nicht, ggf. leite weiter
			if($admin['lock'] == "1") {
	            
				if ($admin['user_id'] != $_MAPUSER['user_id']) {
					mysql_query("UPDATE `".$database_prefix."user` SET `last_active` = NOW( ), `lock` = '0' WHERE `user_id` = '".$admin['user_id']."' LIMIT 1");
					$info = "<br><div align=\"center\"><div class='boxsucess'>"._lock_map_user_sucess."</div></div>";
					$autoforward = TRUE;               	
					} elseif ($admin['user_id'] == $_MAPUSER['user_id']) {
						$info = "<br><div align=\"center\"><div class='savearea'>"._lock_map_user_error_1.$admin['name']._lock_map_user_error_2."</div></div>";
						$autoforward = FALSE;
				}
		                
				//Weiterleitung nach 3 Sekunden
				if ($autoforward == TRUE) {
					autoforward($_SERVER['HTTP_REFERER'],3);
				}
				        
				//Weiterleitung nach 5 Sekunden
				if ($autoforward == FALSE) {
					autoforward($_SERVER['HTTP_REFERER'],5);
				}
			
				} elseif ($admin['lock'] == "0") {
					header("Location: index.php?section=unlock_admin&user_id=$admin[user_id]");
			}
			
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._admin_show_selected_wrong."</div></div>";
    			autoforward("../admin/index.php?default",3);
    	}
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Lock Admin > Admin sperren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  UnLock Admin > Admin entsperren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'unlock_admin':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_unlock_map_user;
    
	//Hole server_id aus Session(Liste) oder aus URL
    if (isset($_GET['user_id'])) {
    	$user_id = $_GET['user_id'];
    	} elseif (count($_SESSION['user_ids']) == "1") {
    		$user_id = $_SESSION['user_ids'][0];
    }

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("lock_admin", FALSE)) { 
    	//Start definition eigentliche Funktionen dieser Section: 
    	
    	//Checke übergebene server_ids und gebe ggf. Fehlermeldung aus, wenn zu viele Server markiert wurden.
    	if (count($_SESSION['user_ids']) == "1" OR isset($_GET['user_id'])) {
             
			//DB auslesen über User-Infos
			$admin = user_info($user_id);
	        
			if ($admin['user_id'] != $_MAPUSER['user_id']) {
				mysql_query("UPDATE `".$database_prefix."user` SET `last_active` = NOW( ), `lock` = '1' WHERE `user_id` = '".$admin['user_id']."' LIMIT 1");
				$info = "<br><div align=\"center\"><div class='boxsucess'>"._unlock_map_user_sucess."</div></div>";
				$autoforward = TRUE;               	
				} elseif ($admin['user_id'] == $_MAPUSER['user_id']) {
					$info = "<br><div align=\"center\"><div class='savearea'>"._unlock_map_user_error_1.$admin['name']._unlock_map_user_error_2."</div></div>";
					$autoforward = FALSE;
			}
	                
			//Weiterleitung nach 3 Sekunden
			if ($autoforward == TRUE) {
				autoforward($_SERVER['HTTP_REFERER'],3);
			}
			        
			//Weiterleitung nach 5 Sekunden
			if ($autoforward == FALSE) {
				autoforward($_SERVER['HTTP_REFERER'],5);
			}
			
			} else {
    			$info = "<br><div align=\"center\"><div class='savearea'>"._admin_show_selected_wrong."</div></div>";
    			autoforward("../admin/index.php?default",3);
    	}
		//Ende definition eigentliche Funktionen dieser Section^^
    } 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  UnLock Admin > Admin entsperren
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Übergabescript an Admin anzeigen , bearbeiten, löschen, entsperren und sperren Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'action':
	
	//Hole Daten aus Listenanzeige: Adminliste und leite weiter
	//Übergebe ID´s	
	$_SESSION['user_ids'] = $_POST['user_id'];
	
	//Definiere Sections
	$avaliableActions = array("profile_x" => "profile",
							  "edit_x" => "edit_admin",
							  "delete_x" => "delete_admin",
							  "lock_x" => "lock_admin",
							  );
							  
	//Leite weiter
	foreach ($avaliableActions as $key => $value) {
		if (isset($_POST[$key])) {
			header("Location: index.php?section=".$value);
			
		}
	}

break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Übergabescript an Admin anzeigen , bearbeiten, löschen, entsperren und sperren Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Listenanzeige: Admins
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_show_admin_list;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("list_admin", FALSE)) {       
		//Start definition eigentliche Funktionen dieser Section:

	    //Hole server_id aus URL
	    if (isset($_GET['server_id'])) {
	    	$server_id = $_GET['server_id'];
	    	} else {
	    		$server_id = FALSE;
	    }
    	
    	//Checke Ob Liste mit Admins zu speziellem Server ausgegeben werden soll, wenn ja => array nur mit user dieses server erstellen
		$admin = getAdmins();
		if ($server_id != FALSE) {
			foreach ($admin as $user) {
				$user = user_info($user);
				foreach ($user['servers'] as $server) {
					if ($server == $server_id) {
						$ListAdmin[] = $user['user_id'];
					}
				}
			}
			$admin = $ListAdmin;
		}
		//Anzeige der Admins
		foreach ($admin as $user_id) {
			//Hole User-Infos
			$user = user_info($user_id);

			//Hole Gruppen -Name und -Beschreibung usw.
			$group = group_info($user['group_id']);
			
			//Hole Servernamen
			$name = getServernamesForUser($user['servers']);
				
			//Definiere Lock-Status
			if ($user['lock'] == "1") {
				$lock = "<a href=\"../admin/index.php?section=lock_admin&user_id=$user[user_id]\"><div align=\"center\"><img src=\"../inc/tpl/".$tpldir."/images/unlock.png\" alt=\"\" title=\""._admin_list_unlock."\" border=\"0\"></div></a>";
				} elseif ($user['lock'] == "0") {
					$lock = "<a href=\"../admin/index.php?section=unlock_admin&user_id=$user[user_id]\"><div align=\"center\"><img src=\"../inc/tpl/".$tpldir."/images/lock.png\" alt=\"\" title=\""._admin_list_nlock."\" border=\"0\"></div></a>";
			}
			
			//Definiere Typ des Admins
			if($user['type_id'] == "1") {
				$user['type'] = _admin_list_type_reseller;
				} else {
					$user['type'] = _admin_list_type_customer;
			}

			//Checke ob Berechtigungen vorhanden sind
			if (getAdminViewPerms($_MAPUSER['user_id'], $user_id)) {
				
				//Speichern der Userliste in Array
				$list .= show("$dir/admin_list_elements", array("name" => $user['linked_name'],
																"login" => $user['name'],
																"type" => $user['type'],
																"group" => $group['name'],
																"server" => $name,
																"email" => $user['email'],
																"last_active" => $user['last_active'],
																"lock" => $lock,
																"action" => '<input type="checkbox" name="user_id[]" value="'.$user['user_id'].'">',
																));
																
			}
		}	

		// Speichere in Ausgabe
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
								 "head_value" => _admin_list_head,
								 "navi_on" => TRUE,
								 "navi_type" => "list_admin",
								 );
		$index = show("$dir/admin_list_head", array("list" => $list,
												    "name" => _admin_list_name,
													"login" => _admin_list_login,
													"type" => _admin_list_type,
													"group" => _admin_list_group,
													"server" => _admin_list_server,
													"email" => _admin_list_email,
													"last_active" => _admin_list_last_active,
													"lock" => _admin_list_lock,
													"action" => _admin_list_action,
													));

		//Ende definition eigentliche Funktionen dieser Section^^
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Listenanzeige: Admins
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