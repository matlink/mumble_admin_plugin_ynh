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
$dir = "permissions";
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
// **START** Berechtigungsgruppe löschen - DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_group_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_delete_group_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_delete_group", FALSE)) {
    	
    	$group_ids = $_POST['group_ids'];
    	
    	//Query Gruppe(n) löschen
    	$qry1 = FALSE;
    	$qry2 = FALSE;
    	foreach ($group_ids as $group_id) {    	
	    	//Lösche Gruppen-Berechtigungen aus DB
			$qry1[] .= mysql_query("DELETE FROM `".$database_prefix."group_perm` WHERE group_id = '".$group_id."'");
			
			//Lösche Gruppen-Stam aus DB
			$qry2[] .= mysql_query("DELETE FROM `".$database_prefix."group` WHERE group_id = '".$group_id."'");
			
			//Entfene bei Usern diese Gruppe, sollten diese zu der Gruppe, die gerade gelöscht wird, zugeordnet sein
			$qry3 = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE group_id = '".$group_id."'");
			while ($get3 = mysql_fetch_array($qry3)) {
				mysql_query("UPDATE `".$database_prefix."user` SET group_id = NULL WHERE user_id = '$get3[user_id]'");
			}		
    	}
    	
    	//Auswertung Querys
    	foreach ($qry1 as $qry) {
    		if ($qry == "1") {
    			$resultQry1 = TRUE;
    			} else {
    				$resultQry1 = FALSE;
    				break;
    		}
    	}
    	foreach ($qry2 as $qry) {
    		if ($qry == "1") {
    			$resultQry2 = TRUE;
    			} else {
    				$resultQry2 = FALSE;
    				break;
    		}
    	}
    	
		//Abschlussmeldung
		if ($resultQry1 && $resultQry2) {
			$info = "<br><div align=\"center\"><div class='boxsucess'>"._perm_delete_group_do_true."</div></div>";
			$autoforward1 = 'TRUE';
			} else {
				$info = "<br><div align=\"center\"><div class='savearea'>"._perm_delete_group_do_false."</div></div>";
				$autoforward1 = 'FALSE';
		}
		
        
        //Weiterleitung nach 3 Sekunden
        if ($autoforward1 == TRUE) {
            autoforward("../permissions/index.php?section=list_group",3);
        }

    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Berechtigungsgruppe löschen - DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Formular Berechtigungsgruppe löschen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_group':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_delete_group;
    $hiddenGroupIDs = FALSE;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_delete_group", FALSE)) {
    	
    	//Hole array mit Gruppen_ID´s
    	$group_ids = $_SESSION['ids'];
    	
    	//Werte Array $group_ids aus
    	if (count($group_ids) == "0") {
    		
			//Gebe Fehlermeldung aus, dass keine Gruppe selektiert wurde
    		$info = "<br><div align=\"center\"><div class='savearea'>"._perm_delete_group_not_selected."</div></div>";
	        autoforward("../permissions/index.php?section=list_group",5);
	        
	        //Gebe Formular für eine Gruppe aus
    		} elseif (count($group_ids) == "1") {
    			
    			//Hole GruppenID
    			$group_id = $group_ids[0];
    			
    			//Wenn die eigene Gruppe gelöscht werden soll, gebe Fehlermeldung aus
    			if ($_MAPUSER['group_id'] != $group_id) {
    				
    				//Gebe Formular für eine Gruppe aus
    				
			    	//Hole Daten zur Gruppe, die gelöscht werden soll
			    	$qry = mysql_query("SELECT * FROM `".$database_prefix."group` WHERE group_id = '".$group_id."'");
			        $get = mysql_fetch_array($qry);
			        
			        //Wenn keine Beschreibung, gebe meldung aus
			        if ($get['discription'] == "") {
			        	$get['discription'] = _perm_delete_group_no_discription;
			        }
			    	
			        //Hole Gruppen Statistiken
			    	$group_stats = get_group_statistics($group_id);
			    	        	
				    $content_headers = array("head_on" => TRUE,
								        	 "head_type" => "default",
						        	         "head_value" => _perm_delete_group_head,
											 "navi_on" => TRUE,
											 "navi_type" => "delete_group",
											 ); 
			        $index = show("$dir/delete_group", array("ask" => _perm_delete_group_ask,
			        										 "name_head" => _perm_delete_group_name,
			        										 "discription_head" => _perm_delete_group_discription,
			        										 "group_id_head" => _perm_delete_group_groupid,
			        										 "percent_head" => _perm_delete_group_percent,
			        										 "ratio_head" => _perm_delete_group_ratio,
			        										 "users_head" => _perm_delete_group_users,
			        										 "name" => $get['name'],
			        										 "discription" => $get['discription'],
			        										 "group_id" => $get['group_id'],
			        										 "percent" => $group_stats['percent'],
			        										 "ratio" => $group_stats['ratio'],
			        										 "users" => $group_stats['users'],
			        										 ));	
			        										 
				    
	    			} elseif ($_MAPUSER['group_id'] == $group_id) {
    					$info = "<br><div align=\"center\"><div class='savearea'>"._perm_delete_group_own_group."</div></div>";
					    autoforward("../permissions/index.php?section=list_group",5);
    			}
	    		} elseif (count($group_ids) >= "2") {
	    			
	    			//Zähle wieviele Berechtigungsgruppen vorhanden sind
	    			$qry = mysql_query("SELECT * FROM `".$database_prefix."group`");
		        	$cntGroups = mysql_num_rows($qry);
					
		        	//Wenn nicht alle Gruppen gelöscht werden sollen, gebe Formular aus, ansonsten Fehlermeldungen
		        	if ($cntGroups > count($group_ids)) {
		        		
		        		//Gebe Gruppeninfos aus
				        foreach ($group_ids as $group_id) {
				        	
				        	//Wenn die eigene Gruppe gelöscht werden soll, gebe Fehlermeldung aus
			    			if ($_MAPUSER['group_id'] != $group_id) {
					        	
					        	//Hole Daten zur Gruppe, die gelöscht werden soll
						    	$qry = mysql_query("SELECT * FROM `".$database_prefix."group` WHERE group_id = '".$group_id."'");
						        $get = mysql_fetch_array($qry);
						        
						        //Wenn keine Beschreibung, gebe meldung aus
						        if ($get['discription'] == "") {
						        	$get['discription'] = _perm_delete_group_no_discription;
						        }
						    	
						        //Hole Gruppen Statistiken
						    	$group_stats = get_group_statistics($group_id);
					
					        	//Schreibe Zeile der Tabelle
					            $list .= show("$dir/delete_group_list_element", array("name_head" => _perm_delete_group_name,
									        									 	  "discription_head" => _perm_delete_group_discription,
									        										  "group_id_head" => _perm_delete_group_groupid,
									        										  "percent_head" => _perm_delete_group_percent,
									        										  "ratio_head" => _perm_delete_group_ratio,
									        										  "users_head" => _perm_delete_group_users,
									        										  "name" => $get['name'],
									        										  "discription" => $get['discription'],
									        										  "group_id" => $get['group_id'],
									        										  "percent" => $group_stats['percent'],
									        										  "ratio" => $group_stats['ratio'],
									        										  "users" => $group_stats['users'],
							                                                          )); 
							
								$hiddenGroupIDs .= show("$dir/delete_group_hidden_elements", array("group_id" => $group_id,
							                                                          			   ));
						        } elseif ($_MAPUSER['group_id'] == $group_id) {
		    						$list .= "<hr/><b>" . _perm_delete_group_own_group . "</b>";
		    			}
				        }
				        
				        $content_headers = array("head_on" => TRUE,
									        	 "head_type" => "default",
							        	         "head_value" => _perm_delete_group_head,
												 "navi_on" => TRUE,
												 "navi_type" => "delete_group",
												 ); 
				        
				        $index = show("$dir/delete_group_list", array("ask" => _perm_delete_groups_ask,
					        										  "content" => $list,
				        											  "hidden" => $hiddenGroupIDs,
					        										  ));
    		
		        		} else {
		        			//Fehlermeldung, dass zu viele Gruppen markiert wurden
					        $info = "<br><div align=\"center\"><div class='savearea'>"._perm_delete_group_too_much."</div></div>";
					        autoforward("../permissions/index.php?section=list_group",5);
		        	}
		}
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Formular Berechtigungsgruppe löschen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Gruppe editieren (in DB speichern)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_group_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_edit_group_db;
    $name_error = FALSE;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_edit_group", FALSE)) {

    	//Hole Werte aus Formular
    	$group_id = $_POST["group_id"];
    	$name = $_POST["name"];
    	$discription = $_POST["discription"];
    	
		//Hole alten Datenbestand der Gruppe aus DB
    	$qry = mysql_query("SELECT * FROM `".$database_prefix."group` WHERE group_id = '".$group_id."'");
    	$group = mysql_fetch_array($qry);
    	
    	//Checke ob Name geändert wurde und ändere Wert in DB, falls geändert
    	if ($name != $group['name'] && $name != "") {
    		mysql_query("UPDATE `".$database_prefix."group` SET name = '".$name."' WHERE group_id = '".$group_id."'");
    		$true_change = TRUE;
    		} elseif ($name == "") {
    			$name_error = TRUE;
    	}
    	
    	//Checke ob Beschreibung geändert wurde und ändere Wert in DB, falls geändert
    	if ($discription != $group['discription']) {
    		mysql_query("UPDATE `".$database_prefix."group` SET discription = '".$discription."' WHERE group_id = '".$group_id."'");
    		$true_change = TRUE;
    	}
    	
    	//Ändere Berechtigungs-Werte in DB, wenn diese geändert wurden
    	//Schreibe Gruppenberechtigungen in DB
    	$qry = mysql_query("SELECT * FROM `".$database_prefix."section` ORDER BY section_id ASC");
    	while ($get = mysql_fetch_array($qry)) {
    		//Setze Werte für Auswertung
    		if (perm_group_detailed($group_id, FALSE, $get['section_id']) == TRUE) {
    			$curPerm = "1";
    			} else {
    				$curPerm = "0";
    		}
    		if (isset($_POST[$get['section_id']])) {
    			$_POST[$get['section_id']] = "1";
    			} else {
    				$_POST[$get['section_id']] = "0";
    		}
    		
    		//Checke ob Section in Tabelle map_group_perm bereits vorhanden ist, wenn nicht, erstelle
    		$qryChkAvailable = mysql_query("SELECT * FROM `".$database_prefix."group_perm` WHERE group_id = '".$group_id."' AND section_id = '".$get['section_id']."'");
    		$getChkAvailable = mysql_num_rows($qryChkAvailable);
    		if ($getChkAvailable != "1") {
    			mysql_query("INSERT INTO `".$database_prefix."group_perm` (`group_id`, `section_id`, `value`, `date`) VALUES ('".$group_id."', '".$get['section_id']."', '".$_POST[$get['section_id']]."','".$aktdate."');");
    		}

    		//Werte aus und ändere ggf. Wert in DB
    		if ($curPerm != $_POST[$get['section_id']]) {
	    		mysql_query("UPDATE `".$database_prefix."group_perm` SET `value` = '".$_POST[$get['section_id']]."' WHERE group_id = '".$group_id."' AND section_id = '".$get['section_id']."'");
	    		$true_change = TRUE;
    		}
    	}
    	
        //Abschlussmeldung
        if ($true_change != "TRUE" && $name_error != TRUE) {
            $info = "<br><div align=\"center\"><div class='savearea'>"._perm_edit_group_do_false."</div></div>";
            $autoforward1 = 'FALSE';
        	} elseif ($true_change != "TRUE" && $name_error == TRUE) {
				$info = "<br><div align=\"center\"><div class='savearea'>"._perm_edit_group_name_error."</div></div>";
	            $autoforward1 = 'FALSE';
	            } elseif ($true_change == "TRUE") {
	                mysql_query("UPDATE `".$database_prefix."group` SET `user_id` = '".$_MAPUSER['user_id']."' WHERE group_id = '".$group_id."'");
    		      	$info = "<br><div align=\"center\"><div class='boxsucess'>"._perm_edit_group_do_true."</div></div>";
	                $autoforward1 = 'TRUE';
        }
        
        //Weiterleitung nach x Sekunden
        if ($autoforward1 == 'TRUE') {
            autoforward("../permissions/index.php?section=list_group",2);
        	} else {
        		autoforward("../permissions/index.php?section=edit_group",3);
        }
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Gruppe editieren (in DB speichern)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Gruppe editieren (Formular)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_group':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_edit_group;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_edit_group", FALSE)) {
    	
    	//Hole Variablen
	    if (isset($_GET['group_id'])) {
	    	$group_ids[0] = $_GET['group_id'];
	    	} elseif (isset($_SESSION['ids'])) {
				$group_ids = $_SESSION['ids'];
	    		} else {
	    			$group_ids[0] = TRUE; $group_ids[1] = TRUE;
	    }
	    
    	//Werte Array $group_ids aus
    	if (count($group_ids) == "0" OR count($group_ids) > "1") {
    		
            //Fehlermeldung, dass zu wenige Gruppen markiert wurden
	        $info = "<br><div align=\"center\"><div class='savearea'>"._perm_edit_group_do_mark."</div></div>";
	        autoforward("../permissions/index.php?section=list_group",5);
    		
    		} elseif (count($group_ids) == "1") {
    			
    			//Übergebe GruppenID zur Formularausgabe
    			$group_id = $group_ids[0];
    	
		    	//Hole Gruppen -Name und -Beschreibung    	
		    	$qry = mysql_query("SELECT * FROM `".$database_prefix."group` WHERE group_id = '".$group_id."'");
		    	$get = mysql_fetch_array($qry);
		    	
		    	//Hole Sections mit checkboxen als array
		    	$output = get_group_sections("edit", $group_id);
		    	
			    $content_headers = array("head_on" => TRUE,
							        	 "head_type" => "default",
					        	         "head_value" => _perm_edit_group_head,
										 "navi_on" => TRUE,
										 "navi_type" => "edit_group",
										 ); 
		        $index = show("$dir/edit_group", array("discr_head" => _perm_edit_group_discr_head,
		        									   "name" => _perm_edit_group_name,
		        									   "name_value" => $get['name'],
		        									   "discription" => _perm_edit_group_discription,
		        									   "discription_value" => $get['discription'],
		        									   "perm_head" => _perm_edit_group_perm_head,
		                                               "sections" => $output,
		        									   "group_id" => $group_id,
		        									   "edit_do" => _perm_edit_group_do,
		                                               ));	
    		}
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Gruppe editieren (Formular)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Admin editieren (in DB speichern)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_admin_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_edit_admin_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_edit_admin", FALSE)) {
		
    	//Setze ggf. nicht definierte Werte
    	if (!isset($_POST['type_id'])) $_POST['type_id'] = 'FALSE'; 
    	if (!isset($_POST['group_id'])) $_POST['group_id'] = 'FALSE'; 
    	
    	//Hole Werte aus Formular
    	$form = array("perm_id" => $_POST['perm_id'],
					  "type_id" => $_POST['type_id'],
				      "group_id" => $_POST['group_id'],
    				  );

    	
    	//Hole Daten des Admins
    	$admin = user_info_ByPermID($form['perm_id']);

    	//Ändere Berechtigungs-Werte in DB, wenn diese geändert wurden
    	//Schreibe Gruppenberechtigungen in DB
    	$qry = mysql_query("SELECT * FROM `".$database_prefix."section` ORDER BY section_id ASC");
    	while ($get = mysql_fetch_array($qry)) {
    		//Setze Werte für Auswertung
    		if (perm_admin_detailed($form['perm_id'], FALSE, $get['section_id']) == TRUE) {
    			$curPerm = "1";
    			} else {
    				$curPerm = "0";
    		}
    		if (isset($_POST[$get['section_id']])) {
    			$_POST[$get['section_id']] = "1";
    			} else {
    				$_POST[$get['section_id']] = "0";
    		}
    		
    		//Checke ob Section in Tabelle map_user_perm bereits vorhanden ist, wenn nicht, erstelle
    		$qryChkAvailable = mysql_query("SELECT * FROM `".$database_prefix."user_perm` WHERE perm_id = '".$form['perm_id']."' AND section_id = '".$get['section_id']."'");
    		$getChkAvailable = mysql_num_rows($qryChkAvailable);
    		if ($getChkAvailable != "1") {
    			mysql_query("INSERT INTO `".$database_prefix."user_perm` (`perm_id`, `section_id`, `value`, `date`) VALUES ('".$form['perm_id']."', '".$get['section_id']."', '".$_POST[$get['section_id']]."','".$aktdate."');");
    		}

    		//Werte aus und ändere ggf. Wert in DB
    		if ($curPerm != $_POST[$get['section_id']]) {
	    		mysql_query("UPDATE `".$database_prefix."user_perm` SET `value` = '".$_POST[$get['section_id']]."' WHERE perm_id = '".$form['perm_id']."' AND section_id = '".$get['section_id']."'");
	    		$true_change = TRUE;
    		}
    	}

   		//Kontoart "type_id" ändern
    	if (perm_handler("edit_admin_type_id", FALSE) && $admin['type_id'] != $form['type_id']) {
			mysql_query("UPDATE `".$database_prefix."user` SET type_id = '$form[type_id]' WHERE user_id = '$admin[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//Berechtigungsgruppe "group_id" ändern
    	if (perm_handler("edit_admin_group_id", FALSE) && $admin['group_id'] != $form['group_id']) {
			mysql_query("UPDATE `".$database_prefix."user` SET group_id = '$form[group_id]' WHERE user_id = '$admin[user_id]'");
			$true_change = TRUE;
    	}
    	
    	//zuständige Server ändern
    	if (perm_handler("edit_admin_server", FALSE)) {
			$server = getServers();
			foreach ($server as $server_id) {
				$qry = mysql_query("SELECT * FROM `".$database_prefix."user_servers` WHERE user_id = '$admin[user_id]' AND server_id = '$server_id'");
        		$get = mysql_num_rows($qry);
        		if (!isset($_POST[$server_id])) {
        			$_POST[$server_id] = FALSE;
        		}
				if ($_POST[$server_id] == TRUE) {
					if ($get == 0) {
        				mysql_query("INSERT INTO `".$database_prefix."user_servers` (`user_id`, `server_id`) VALUES ('".$admin['user_id']."', '".$server_id."');");
        				$true_change = TRUE;
					}
					} else {
						if ($get != 0) {
							mysql_query("DELETE FROM `".$database_prefix."user_servers` WHERE user_id = '".$admin['user_id']."' AND server_id = '".$server_id."' LIMIT 1");
							$true_change = TRUE;
						}
				}
			}
    	}

        //Abschlussmeldung
        if ($true_change != TRUE) {
            $info = "<br><div align=\"center\"><div class='savearea'>"._perm_edit_admin_do_false."</div></div>";
	            } elseif ($true_change == TRUE) {
	                $info = "<br><div align=\"center\"><div class='boxsucess'>"._perm_edit_admin_do_true."</div></div>";
        }
        
        //Weiterleitung nach 5 Sekunden
        autoforward("../permissions/index.php?section=list_admin",5);
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Admin editieren (in DB speichern)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Admin Berechtigungen editieren (Formular)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'edit_admin':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_edit_admin;
    $group = FALSE;
    $server = FALSE;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_edit_admin", FALSE)) {

	    //Hole server_id und user_id aus Session(Liste) oder aus URL
	    if (isset($_GET['user_id'])) {
	    	$qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE user_id = '".$_GET['user_id']."'");
		    $get = mysql_fetch_array($qry);
	    	$perm_id = $get['perm_id'];
	    	} elseif (isset($_SESSION['ids'])) {
				$perm_id = $_SESSION['ids'][0];
	    }
    	
    	//Werte Array $group_ids aus
    	if (!isset($perm_id)) {
    		
            //Fehlermeldung, dass zu wenige Gruppen markiert wurden
	        $info = "<br><div align=\"center\"><div class='savearea'>"._perm_edit_admin_do_mark."</div></div>";
	        autoforward("../permissions/index.php?section=list_admin",5);
    		
    		} else {
    	
		    	//Hole Userinfo    
		    	$qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE perm_id = '".$perm_id."'");
		    	$get = mysql_fetch_array($qry);
		    	$admin = user_info($get['user_id']);
		    	
    			//Definiere den Typ des Admins
				if ($_MAPUSER['type_id'] == 1) {
					if ($admin['type_id'] == 1) {
						$type = "<option value=\"1\" selected=\"selected\">"._edit_admin_reseller."</option>\n<option value=\"0\">"._edit_admin_customer."</option>\n";
						} elseif ($admin['type_id'] == 0) {
							$type = "<option value=\"1\">"._edit_admin_reseller."</option>\n<option value=\"0\" selected=\"selected\">"._edit_admin_customer."</option>\n";
					}
					$typeFound = TRUE;
					} elseif ($_MAPUSER['type_id'] == 0 AND $admin['type_id'] == 0) {
						$type = "<option value=\"0\" selected=\"selected\">"._edit_admin_customer."</option>\n";
						$typeFound = TRUE;
				}
				
				
				//Definiere Berechtigungsgruppe des Admins
				$groups = getGroups();
				foreach ($groups as $group_id) {
					$group_info = group_info($group_id);
					//Checke ob Admin diese Gruppe benutzen darf
					if ($_MAPUSER['type_id'] == 1) {
						if ($admin['group_id'] == $group_info['group_id']) {
							$group .= "<option value=\"".$group_info['group_id']."\" selected=\"selected\">".$group_info['name']."</option>\n";
							} else {
								$group .= "<option value=\"".$group_info['group_id']."\">".$group_info['name']."</option>\n";
						}
						$groupFound = TRUE;
						} elseif ($_MAPUSER['type_id'] == 0 AND $_MAPUSER['group_id'] == $group_id) {
							$group .= "<option value=\"".$group_info['group_id']."\">".$group_info['name']."</option>\n";
							$groupFound = TRUE;
					}
				}
				
				//Hole alle Server und gebe mit Checkboxen aus
				$allServers = getServers();
				foreach ($allServers as $server_id) {
					$srv = server_info($server_id);
					//Checke ob User zu dem Server verbunden ist
					if ($_MAPUSER['type_id'] == 1) {
						if (array_search($srv['server_id'], $admin['servers'])) {
							$server .= '<div><input type="checkbox" id="'.$srv['server_id'].'" name="'.$srv['server_id'].'" value="TRUE" class="checkbox" checked><label for="'.$srv['server_id'].'"> '.$srv['name'].'</label></div>';
							} else {
								$server .= '<div><input type="checkbox" id="'.$srv['server_id'].'" name="'.$srv['server_id'].'" value="TRUE" class="checkbox"><label for="'.$srv['server_id'].'"> '.$srv['name'].'</label></div>';
						}
						$serverFound = TRUE;
						} elseif ($_MAPUSER['type_id'] == 0) {
							if (get_perms("edit_admin_server", $server_id)) { 
								if (array_search($srv['server_id'], $admin['servers'])) {
									$server .= '<div><input type="checkbox" id="'.$srv['server_id'].'" name="'.$srv['server_id'].'" value="TRUE" class="checkbox" checked><label for="'.$srv['server_id'].'"> '.$srv['name'].'</label></div>';
									} else {
										$server .= '<div><input type="checkbox" id="'.$srv['server_id'].'" name="'.$srv['server_id'].'" value="TRUE" class="checkbox"><label for="'.$srv['server_id'].'"> '.$srv['name'].'</label></div>';
								}
							}
					}
				}
				
				//Erstelle Berechtigungen
    			if (perm_handler("edit_admin_type_id", FALSE) AND $typeFound == TRUE) {
					$setAtype = ''; $setBtype = '';
					} else {
						$setAtype = '<!--'; $setBtype = '-->';
				}
		        if (perm_handler("edit_admin_group_id", FALSE) AND $groupFound == TRUE) {
					$setAgroup = ''; $setBgroup = '';
					} else {
						$setAgroup = '<!--'; $setBgroup = '-->';
				}
		        if (perm_handler("edit_admin_server", FALSE)) {
					$setAserver = ''; $setBserver = '';
					} else {
						$setAserver = '<!--'; $setBserver = '-->';
				}
		    	
		    	//Hole Sections mit checkboxen als array
		    	$output = get_admin_sections($perm_id);
		    	
			    $content_headers = array("head_on" => TRUE,
							        	 "head_type" => "default",
					        	         "head_value" => _perm_edit_admin_head,
										 "navi_on" => TRUE,
										 "navi_type" => "edit_admin",
										 ); 
		        $index = show("$dir/edit_admin", array("discr_head" => _perm_edit_admin_discr_head,
		        									   "name" => _perm_edit_admin_name,
		        									   "name_value" => $admin['linked_name'],
		        									   "email" => _perm_edit_admin_email,
		        									   "email_value" => $admin['email'],
		        									   "perm_head" => _perm_edit_admin_perm_head,
		        									   "head_type" => _edit_admin_type,
													   "head_group" => _edit_admin_group,  
													   "head_server" => _edit_admin_server,
		        									   "discription_type" => _edit_admin_discr_type,
												       "discription_group" => _edit_admin_discr_group,
													   "discription_server" => _edit_admin_discr_server,
		        									   "type" => $type,
											    	   "group" => $group,
											    	   "server" => $server,
			        								   "setAtype" => $setAtype,
													   "setBtype" => $setBtype,
													   "setAgroup" => $setAgroup,
													   "setBgroup" => $setBgroup,
													   "setAserver" => $setAserver,
													   "setBserver" => $setBserver,
		                                               "sections" => $output,
		        									   "perm_id" => $perm_id,
		        									   "edit_do" => _perm_edit_admin_do,
		                                               ));	
    		}
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Admin Bererchtigungen editieren (Formular)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Gruppe erstellen (in DB speichern)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_group_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_create_group_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_create_group", FALSE)) {

    	//Generiere neue group_id
    	$group_id = pw_gen("3", "3", "3", "0");

    	//Hole Werte aus Formular
    	$group_name = $_POST['name'];
    	$group_discription = $_POST['discription'];

    	//Checke, ob Name eingegeben wurde
    	if ($group_name != "") {
    		//Schreibe Gruppe in DB
	    	mysql_query("INSERT INTO `".$database_prefix."group` (`group_id`, `name`, `discription`, `user_id`, `date`) VALUES ('".$group_id."', '".$group_name."', '".$group_discription."', '".$_MAPUSER['user_id']."', '".$aktdate."');");
	    	
	    	//Schreibe Gruppenberechtigungen in DB
	    	$qry = mysql_query("SELECT * FROM `".$database_prefix."section` ORDER BY section_id ASC");
	    	while ($get = mysql_fetch_array($qry)) {
	    		if (!isset($_POST[$get['section_id']])) {
	    			$value = '0';
	    			} else {
	    				$value = $_POST[$get['section_id']];
	    		}
	    		mysql_query("INSERT INTO `".$database_prefix."group_perm` (`group_id`, `section_id`, `value`, `date`) VALUES ('".$group_id."', '".$get['section_id']."', '".$value."','".$aktdate."');");
	    		$true_change = TRUE;
	    	}
    		} else {
    			$true_change = FALSE;
    	}
    	
        //Abschlussmeldung
        if ($true_change != "TRUE") {
            $info = "<br><div align=\"center\"><div class='savearea'>"._perm_create_group_do_false."</div></div>";
            $autoforward1 = 'FALSE';
            } elseif ($true_change == "TRUE") {
                $info = "<br><div align=\"center\"><div class='boxsucess'>"._perm_create_group_do_true."</div></div>";
                $autoforward1 = 'TRUE';
        }
        
        //Weiterleitung nach 4 Sekunden
        if ($autoforward1 == 'TRUE') {
            autoforward("../permissions/index.php?section=list_group",4);
        	} else {
        		autoforward("../permissions/index.php?section=create_group",4);
        }
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Gruppe erstellen (in DB speichern)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Gruppe erstellen (Formular)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'create_group':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_create_group;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_create_group", FALSE)) {
    	
    	//Hole Sections mit checkboxen als array
    	$output = get_group_sections("create", FALSE);
    	
	    $content_headers = array("head_on" => TRUE,
					        	 "head_type" => "default",
			        	         "head_value" => _perm_create_group_head,
								 "navi_on" => TRUE,
								 "navi_type" => "create_group",
								 ); 
        $index = show("$dir/create_group", array("discr_head" => _perm_create_group_discr_head,
        										 "name" => _perm_create_group_name,
        										 "discription" => _perm_create_group_discription,
        										 "perm_head" => _perm_create_group_perm_head,
                                                 "sections" => $output,
                                                 ));	
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Gruppe erstellen (Formular)
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Übergabescript an Editieren und Löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'action':
	
	//Hole Daten aus Listenanzeige: Adminliste und leite weiter
	//Übergebe ID´s	
	$_SESSION['ids'] = $_POST['id'];
	
	//Definiere Sections
	$avaliableActions = array("edit_group_x" => "edit_group",
							  "edit_admin_x" => "edit_admin",
							  "delete_group_x" => "delete_group",
							  );
							  
	//Leite weiter
	foreach ($avaliableActions as $key => $value) {
		if (isset($_POST[$key])) {
			header("Location: index.php?section=".$value);
			
		}
	}

break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Übergabescript an Editieren und Löschen Funktionen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Admin Berechtigungen auflisten
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'list_admin':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_list_admin;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_list_admin", FALSE)) {
            
    	//Generiere Tabelle
		$qry = mysql_query("SELECT * FROM `".$database_prefix."user` ORDER BY user_id ASC");
        while ($get = mysql_fetch_array($qry)) {
        	
        	//Hole Userinfos
        	$admin = user_info($get['user_id']);
        	
        	//Hole Gruppenstatistiken
        	$group_stats = get_group_statistics($get['group_id']);
        	$group = group_info($get['group_id']);
        	
        	//Hole Adminstatistiken
        	$admin_stats = get_admin_statistics($get['perm_id']);
        	$all_stats = get_all_percent_perms($admin['user_id']);

        	//Checke ob Berechtigungen vorhanden sind
			if (getAdminViewPerms($_MAPUSER['user_id'], $admin['user_id'])) {
				
	        	//Schreibe Zeile der Tabelle
	            $list .= show("$dir/list_admin_list", array("name" => $admin['linked_name'],
	                                                        "email" => $admin['email'],
	            											"group" => $group['linked_name'],
	            											"admin_percent" => $admin_stats['percent'],
	                                                        "group_percent" => $group_stats['percent'],
	                                                        "all_percent" => $all_stats['percent'],
	                                                        "mark" => '<input type="radio" name="id[]" value="'.$admin['perm_id'].'">',
	                                                        ));
                                                        
			}
        }
            
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
					             "head_value" => _perm_list_admin_head,
								 "navi_on" => TRUE,
								 "navi_type" => "perm_list_admin",
								 ); 
        $index = show("$dir/list_admin", array("list" => $list,
        									   "name" => _perm_list_admin_name,
                                               "email" => _perm_list_admin_email,
        									   "group" => _perm_list_admin_group,
        									   "admin_percent" => _perm_list_admin_perms,
                                               "group_percent" => _perm_list_admin_group_percent,
                                               "all_percent" => _perm_list_admin_all,
                                               "mark" => _perm_list_admin_mark,
                                               ));	
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Admin Berechtigungen auflisten
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Gruppen auflisten
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'list_group':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_list_group;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_list_group", FALSE)) {
            
    	//Generiere Tabelle
		$qry = mysql_query("SELECT * FROM `".$database_prefix."group` ORDER BY date DESC");
        while ($get = mysql_fetch_array($qry)) {
        	
        	//Hole Gruppenstatistiken
        	$group_stats = get_group_statistics($get['group_id']);
        	
        	//Hole Userinfos von dem User, der zuletzt die Gruppe editiert hatte
        	$admin = user_info($get['user_id']);

        	//Schreibe Zeile der Tabelle
            $list .= show("$dir/list_group_list", array("name" => $get['name'],
                                                        "discription" => $get['discription'],
            											"users" => $group_stats['users'],
                                                        "percent" => $group_stats['percent'],
                                                        "edited_by" => $admin['linked_name'],
                                                        "last_edit" => $get['date'],
                                                        "action" => '<input type="checkbox" name="id[]" value="'.$get['group_id'].'">',
                                                        ));
        }
            
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
					             "head_value" => _perm_list_group_head,
								 "navi_on" => TRUE,
								 "navi_type" => "list_groups",
								 ); 
        $index = show("$dir/list_group", array("list" => $list,
        									   "name" => _perm_list_group_name,
                                               "discription" => _perm_list_group_discr,
        									   "users" => _perm_list_group_user,
                                               "percent" => _perm_list_group_percent,
                                               "edited_by" => _perm_list_group_edited_by,
                                               "last_edit" => _perm_list_group_last_edit,
                                               "action" => _perm_list_group_action,
                                               ));	
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Gruppen auflisten
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Startseite Berechtigungen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_perm_start;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("perm_start_page", FALSE)) {
    	
    	// Lädt HTML-Datei
    	$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
					             "head_value" => _perm_head_start,
								 "navi_on" => TRUE,
								 "navi_type" => "default",
								 );
		$index = show("$dir/start", array("group" => _perm_group_name,
		                                  "group_discr" => _perm_group_discr,
		                                  "group_create" => _perm_group_create,
		                                  "group_list" => _perm_group_list,
		                                  "user" => _perm_user_name,
		                                  "user_discr" => _perm_user_discr,
		                                  "user_list" => _perm_user_list,
		                                  ));    	
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE** Startseite Berechtigungen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
}
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
//************************************************************************************************//
// **START** Ladt das Template
//************************************************************************************************//
include("../inc/layout.php");
//************************************************************************************************//
// **ENDE**  Lädt das Template
//************************************************************************************************//
?>