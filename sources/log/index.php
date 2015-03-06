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
$dir = "log";
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
// **START**  Delete Logs > lösche aus DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'delete_log_db':
    // Definition Seitentitel
    $seitentitel .= _pagetitel_log_delete_log;
    
	//Hole log_id aus Session(Liste) oder aus URL
    if (isset($_GET['id'])) {
    	$ids[0] = $_GET['id'];
    	} elseif (isset($_SESSION['log'])) {
			$ids = $_SESSION['log'];
    }
    
    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("delete_log_db", FALSE)) {

        //Arbeite Lod_if Array ab
        if (isset($ids[0])) {
	        foreach ($ids as $log_id) {
	
		        $db = mysql_query("DELETE FROM `".$database_prefix."log` WHERE id = '".$log_id."' LIMIT 1");
		        if ($db == TRUE) {
		            $info .= "<br><div align=\"center\"><div class='boxsucess'>" . _delete_log_sucsessful . $log_id . "</div></div>";
		            } else {
		            	$info .= "<br><div align=\"center\"><div class='savearea'>" . _delete_log_not_sucsessful . $log_id . "</div></div>";
		        }
	        }
        	} else {
        	$info .= "<br><div align=\"center\"><div class='savearea'>" . _delete_log_not_sucsessful_no_entry  . "</div></div>";
        }
    
        //Weiterleitung nach 2 Sekunden
        autoforward($_SERVER['HTTP_REFERER'],2);
    
    }
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Delete Logs > lösche aus DB
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Logs in einer Liste anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'show_log':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_log_show_log;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("show_log", FALSE)) {
        	$qry_logging_on = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '23'");
            $get_logging_on = mysql_fetch_array($qry_logging_on);
            if ($get_logging_on[2]== "1") {
            	
            	//definiere Einstellungen wie Log dargestellt werden soll > log_show_priority
            	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '25'");
            	$get_log_set_s_prio = mysql_fetch_array($qry);
            	if ($get_log_set_s_prio[2] == "1") {
            		$get_log_set_s_prio_print = "WHERE priority = '1'";
            		$get_log_set_s_prio_print_srv = "AND priority = '1'";
            		} elseif ($get_log_set_s_prio[2] == "2") {
            			$get_log_set_s_prio_print = "WHERE priority = '2'";
            			$get_log_set_s_prio_print_srv = "AND priority = '2'";
            			} elseif ($get_log_set_s_prio[2] == "3") {
            				$get_log_set_s_prio_print = '';
            				$get_log_set_s_prio_print_srv = '';
            	}        	
            	
	            if ($_GET['view'] == "all" && perm_handler("show_log_all", FALSE)) {   
	                 
	                //DB auslesen über Log-Infos
	                $qry_empty = mysql_query("SELECT * FROM `".$database_prefix."log` ".$get_log_set_s_prio_print." ORDER BY date DESC");
	                $get_empty = mysql_fetch_array($qry_empty);
	                
	                //Listenausgabe
	                if ($get_empty['id'] != "") {
	                    $qry = mysql_query("SELECT * FROM `".$database_prefix."log` ".$get_log_set_s_prio_print." ORDER BY date DESC");
	                    while ($get = mysql_fetch_array($qry)) {
	                    	if (checkIfAdminExists(FALSE, $get['user_id'])) {
	                    		$user = user_info($get['user_id']);
	                    		} else {
	                    			$user = FALSE;
	                    	}

	            			//Definiere Name
	            			if ($get['user_id'] == "0") {
	            				$user['teamtag'] = _log_guest;
	            			}
	                    		                    	
	            			//globale Section Definition der $action_id für Listenanzeige.
	            			//Login und Logout
	            			$defActionID["logout_do_1"] = _log_action_def_logout_do_1; 
            				$defActionID["login_do_1"] = _log_action_def_login_do_1; 
            				
            				//Request Funktionen
            				$defActionID["request_db_1"] = _log_action_def_request_db_1.$get['value_1'];
            				$defActionID["request_show_2"] = _log_action_def_request_show_2.$get['value_1']; 
            				$defActionID["request_db_3"] = _log_action_def_request_db_3.$get['value_1']._log_action_def_request_db_4.$get['value_2'];
            				$defActionID["request_show_5"] = _log_action_def_request_show_5.$get['value_1'];
            				$defActionID["request_db_6"] = _log_action_def_request_db_6.$get['value_1'];
            				$defActionID["request_show_7"] = _log_action_def_request_show_7.$get['value_1']; 
            				$defActionID["request_show_8"] = _log_action_def_request_show_8; 
            				$defActionID["request_db_9"] = _log_action_def_request_db_9.$get['value_1']; 
            				$defActionID["request_db_10"] = _log_action_def_request_db_10.$get['value_1'];
            				
            				//Server Funktionen
            				$defActionID["server_db_1"] = _log_action_def_server_db_1_1.$get['value_1']._log_action_def_server_db_1_2.$get['value_2'];
            				$defActionID["server_db_2"] = _log_action_def_server_db_2_1.$get['value_1']._log_action_def_server_db_2_2.$get['value_2'];
            				$defActionID["server_db_3"] = _log_action_def_server_db_3_1.$get['value_1']._log_action_def_server_db_3_2.$get['value_2'];
            				$defActionID["server_db_4"] = _log_action_def_server_db_4."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
	            			$defActionID["server_db_5"] = _log_action_def_server_db_5."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_db_6"] = _log_action_def_server_db_6_1.$get['value_1']._log_action_def_server_db_6_2.$get['server_id'];
            				$defActionID["server_show_7"] = _log_action_def_server_show_7."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_db_8"] = _log_action_def_server_db_8."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_show_9"] = _log_action_def_server_show_9."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
	            			$defActionID["server_db_10"] = _log_action_def_server_db_10."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
	            			$defActionID["server_db_11"] = _log_action_def_server_db_11."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
	            			$defActionID["server_db_12"] = _log_action_def_server_db_12."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_show_13"] = _log_action_def_server_show_13."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_show_14"] = _log_action_def_server_show_14;
            				
            				//Channelviewer Funktionen
            				$defActionID["view_db_1"] = _log_action_def_view_db_1."<a href=\"../view/index.php?section=settings&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["view_show_2"] = _log_action_def_view_show_2."<a href=\"../view/index.php?section=settings&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["view_show_3"] = _log_action_def_view_show_3."<a href=\"../view/index.php?section=include&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["view_show_4"] = _log_action_def_view_show_4."<a href=\"../view/index.php?section=channelviewer&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				
            				//User Funktionen
            				$defActionID["user_db_1"] = _log_action_def_user_db_1."<a href=\"../user/index.php?section=edit_server_user&server_id=".$get['server_id']."&user_id=".$get['value_2']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["user_db_2"] = _log_action_def_user_db_2."<a href=\"../user/index.php?section=show_map_users_list&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
							$defActionID["user_db_3"] = _log_action_def_user_db_3."<a href=\"../user/index.php?section=edit_server_user&server_id=".$get['server_id']."&user_id=".$get['value_2']."\" target=\"_self\">".$get['value_1']."</a>";
							$defActionID["user_db_4"] = _log_action_def_user_db_4."<a href=\"../user/index.php?section=edit_map_user&id=".$get['value_2']."\" target=\"_self\">".$get['value_1']."</a>";
							$defActionID["user_db_5"] = _log_action_def_user_db_5_1.$get['value_1']._log_action_def_user_db_5_2.$get['value_2'];
							$defActionID["user_db_6"] = _log_action_def_user_db_6_1.$get['value_1']._log_action_def_user_db_6_2.$get['value_2'];
							$defActionID["user_db_7"] = _log_action_def_user_db_7_1.$get['value_1']._log_action_def_user_db_7_2;
							$defActionID["user_db_8"] = _log_action_def_user_db_8_1.$get['value_1']._log_action_def_user_db_8_2;
							$defActionID["user_db_9"] = _log_action_def_user_db_9_1.$get['value_1']._log_action_def_user_db_9_2;
							$defActionID["user_db_10"] = _log_action_def_user_db_10_1.$get['value_1']._log_action_def_user_db_10_2;
							$defActionID["user_db_11"] = _log_action_def_user_db_11_1.$get['value_1']._log_action_def_user_db_11_2;							
							$defActionID["user_show_12"] = _log_action_def_user_show_12;
							$defActionID["user_db_12"] = _log_action_def_user_db_12_1.$get['value_1']._log_action_def_user_db_12_2.$get['value_2'];
							$defActionID["user_db_13"] = _log_action_def_user_db_13_1 . $get['value_1'] . _log_action_def_user_db_13_2 . $get['server_id'] . _log_action_def_user_db_13_3;
							
							//Operate Funktionen (Passwort vergessen)
							$defActionID["operate_db_1"] = _log_action_def_operate_db_1;
							$defActionID["operate_show_2"] = _log_action_def_operate_show_2;
							$defActionID["operate_db_3"] = _log_action_def_operate_db_3;
							$defActionID["operate_show_4"] = _log_action_def_operate_show_4;
							$defActionID["operate_db_5"] = _log_action_def_operate_db_5;
							$defActionID["operate_show_6"] = _log_action_def_operate_show_6;
							$defActionID["operate_db_7"] = _log_action_def_operate_db_7;
							$defActionID["operate_show_8"] = _log_action_def_operate_show_8;
							$defActionID["operate_db_9"] = _log_action_def_operate_db_9;
							$defActionID["operate_show_10"] = _log_action_def_operate_show_10;
														
	            			//Settings Funktionen
            				$defActionID["settings_db_1"] = _log_action_def_settings_db_1;
            				$defActionID["settings_show_2"] = _log_action_def_settings_show_2; 

	                        //Speichern der Logliste in Array
	                        $list .= show("$dir/log_list", array("id" => $get['id'],
	                                                             "user" => "<a href=\"../admin/index.php?section=profile&user_id=".$get['user_id']."\" target=\"_self\">".$user['teamtag']."</a>",
	                                                             "content" => $defActionID["$get[action_id]"],
	                                                             "ip" => long2ip($get['ip']),
	                                                             "date" => $get['date'],
	                                                             "action" => '<input type="checkbox" name="id[]" value="'.$get['id'].'">',
	                                                             ));
	                    }
	                    //Ausgabe wenn keine beantragten Accounts vorhanden   
	                    } else {
	                        $list = show("$dir/no_log", array("empty" => _log_list_empty,
	                                                                  ));
	                }
	                
	              	// Lade Template mit errechnetem Inhalt
            		$content_headers = array("head_on" => TRUE,
											 "head_type" => "default",
					                         "head_value" => _log_show_head_1,
											 "navi_on" => TRUE,
											 "navi_type" => "list_log",
											 );
	                    
	                // Speichere in Ausgabe
	                $index = show("$dir/log_show", array("list" => $list,
	                                                     "id" => _log_show_id,
	                                                     "user" => _log_show_user,
	                                                     "content" => _log_show_action,
	                                                     "ip" => _log_show_ip,
	                                                     "date" => _log_show_date,
	                                                     "action" => _log_show_delete,
	                                                     ));
	            }
	            
	            if ($_GET['view'] == "server" && perm_handler("show_log", $_GET['server_id'])) {   
	            	                   
	                //DB auslesen über User-Infos
	                $qry_empty = mysql_query("SELECT * FROM `".$database_prefix."log` WHERE server_id = '".$_GET['server_id']."' ".$get_log_set_s_prio_print_srv." ORDER BY date DESC");
	                $get_empty = mysql_fetch_array($qry_empty);
	                
	                //Listenausgabe
	                if ($get_empty['id'] != "") {
	                    $qry = mysql_query("SELECT * FROM `".$database_prefix."log` WHERE server_id = '".$_GET['server_id']."' ".$get_log_set_s_prio_print_srv." ORDER BY date DESC");
	                    while ($get = mysql_fetch_array($qry)) {	 
	                    	if (checkIfAdminExists(FALSE, $get['user_id'])) {
	                    		$user = user_info($get['user_id']);
	                    		} else {
	                    			$user = FALSE;
	                    	}                   	

	            			//Definiere Name
	            			if ($get['user_id'] == "0") {
	            				$user['teamtag'] = _log_guest;
	            			}   

	            			//globale Section Definition der $action_id für Listenanzeige.
	            			//Login und Logout
	            			$defActionID["logout_do_1"] = _log_action_def_logout_do_1; 
            				$defActionID["login_do_1"] = _log_action_def_login_do_1; 
            				
            				//Request Funktionen
            				$defActionID["request_db_1"] = _log_action_def_request_db_1.$get['value_1'];
            				$defActionID["request_show_2"] = _log_action_def_request_show_2.$get['value_1']; 
            				$defActionID["request_db_3"] = _log_action_def_request_db_3.$get['value_1']._log_action_def_request_db_4.$get['value_2'];
            				$defActionID["request_show_5"] = _log_action_def_request_show_5.$get['value_1'];
            				$defActionID["request_db_6"] = _log_action_def_request_db_6.$get['value_1'];
            				$defActionID["request_show_7"] = _log_action_def_request_show_7.$get['value_1']; 
            				$defActionID["request_show_8"] = _log_action_def_request_show_8; 
            				$defActionID["request_db_9"] = _log_action_def_request_db_9.$get['value_1']; 
            				$defActionID["request_db_10"] = _log_action_def_request_db_10.$get['value_1'];
            				
            				//Server Funktionen
            				$defActionID["server_db_1"] = _log_action_def_server_db_1_1.$get['value_1']._log_action_def_server_db_1_2.$get['value_2'];
            				$defActionID["server_db_2"] = _log_action_def_server_db_2_1.$get['value_1']._log_action_def_server_db_2_2.$get['value_2'];
            				$defActionID["server_db_3"] = _log_action_def_server_db_3_1.$get['value_1']._log_action_def_server_db_3_2.$get['value_2'];
            				$defActionID["server_db_4"] = _log_action_def_server_db_4."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
	            			$defActionID["server_db_5"] = _log_action_def_server_db_5."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_db_6"] = _log_action_def_server_db_6_1.$get['value_1']._log_action_def_server_db_6_2.$get['server_id'];
            				$defActionID["server_show_7"] = _log_action_def_server_show_7."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_db_8"] = _log_action_def_server_db_8."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_show_9"] = _log_action_def_server_show_9."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
	            			$defActionID["server_db_10"] = _log_action_def_server_db_10."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
	            			$defActionID["server_db_11"] = _log_action_def_server_db_11."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
	            			$defActionID["server_db_12"] = _log_action_def_server_db_12."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_show_13"] = _log_action_def_server_show_13."<a href=\"../server/index.php?section=show_server&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["server_show_14"] = _log_action_def_server_show_14;

            				//Channelviewer Funktionen
            				$defActionID["view_db_1"] = _log_action_def_view_db_1."<a href=\"../view/index.php?section=settings&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["view_show_2"] = _log_action_def_view_show_2."<a href=\"../view/index.php?section=settings&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["view_show_3"] = _log_action_def_view_show_3."<a href=\"../view/index.php?section=include&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["view_show_4"] = _log_action_def_view_show_4."<a href=\"../view/index.php?section=channelviewer&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
            				
            				//User Funktionen
            				$defActionID["user_db_1"] = _log_action_def_user_db_1."<a href=\"../user/index.php?section=edit_server_user&server_id=".$get['server_id']."&user_id=".$get['value_2']."\" target=\"_self\">".$get['value_1']."</a>";
            				$defActionID["user_db_2"] = _log_action_def_user_db_2."<a href=\"../user/index.php?section=show_map_users_list&server_id=".$get['server_id']."\" target=\"_self\">".$get['value_1']."</a>";
							$defActionID["user_db_3"] = _log_action_def_user_db_3."<a href=\"../user/index.php?section=edit_server_user&server_id=".$get['server_id']."&user_id=".$get['value_2']."\" target=\"_self\">".$get['value_1']."</a>";
							$defActionID["user_db_4"] = _log_action_def_user_db_4."<a href=\"../user/index.php?section=edit_map_user&id=".$get['value_2']."\" target=\"_self\">".$get['value_1']."</a>";
							$defActionID["user_db_5"] = _log_action_def_user_db_5_1.$get['value_1']._log_action_def_user_db_5_2.$get['value_2'];
							$defActionID["user_db_6"] = _log_action_def_user_db_6_1.$get['value_1']._log_action_def_user_db_6_2.$get['value_2'];
							$defActionID["user_db_7"] = _log_action_def_user_db_7_1.$get['value_1']._log_action_def_user_db_7_2;
							$defActionID["user_db_8"] = _log_action_def_user_db_8_1.$get['value_1']._log_action_def_user_db_8_2;
							$defActionID["user_db_9"] = _log_action_def_user_db_9_1.$get['value_1']._log_action_def_user_db_9_2;
							$defActionID["user_db_10"] = _log_action_def_user_db_10_1.$get['value_1']._log_action_def_user_db_10_2;
							$defActionID["user_db_11"] = _log_action_def_user_db_11_1.$get['value_1']._log_action_def_user_db_11_2;
							$defActionID["user_show_12"] = _log_action_def_user_show_12;							
							$defActionID["user_db_12"] = _log_action_def_user_db_12_1.$get['value_1']._log_action_def_user_db_12_2.$get['value_2'];
							$defActionID["user_db_13"] = _log_action_def_user_db_13_1 . $get['value_1'] . _log_action_def_user_db_13_2 . $get['server_id'] . _log_action_def_user_db_13_3;
							
							//Operate Funktionen (Passwort vergessen)
							$defActionID["operate_db_1"] = _log_action_def_operate_db_1;
							$defActionID["operate_show_2"] = _log_action_def_operate_show_2;
							$defActionID["operate_db_3"] = _log_action_def_operate_db_3;
							$defActionID["operate_show_4"] = _log_action_def_operate_show_4;
							$defActionID["operate_db_5"] = _log_action_def_operate_db_5;
							$defActionID["operate_show_6"] = _log_action_def_operate_show_6;
							$defActionID["operate_db_7"] = _log_action_def_operate_db_7;
							$defActionID["operate_show_8"] = _log_action_def_operate_show_8;
							$defActionID["operate_db_9"] = _log_action_def_operate_db_9;
							$defActionID["operate_show_10"] = _log_action_def_operate_show_10;
														
	            			//Settings Funktionen
            				$defActionID["settings_db_1"] = _log_action_def_settings_db_1;
            				$defActionID["settings_show_2"] = _log_action_def_settings_show_2;
	                    	
	                        //Speichern der Userliste in Array
	                        $list .= show("$dir/log_list", array("id" => $get['id'],
	                                                             "user" => "<a href=\"../admin/index.php?section=profile&user_id=".$get['user_id']."\" target=\"_self\">".$user['teamtag']."</a>",
	                                                             "content" => $defActionID["$get[action_id]"],
	                                                             "ip" => long2ip($get['ip']),
	                                                             "date" => $get['date'],
	                                                             "action" => '<input type="checkbox" name="id[]" value="'.$get['id'].'">',
	                                                             ));
	                    }
	                    //Ausgabe wenn keine beantragten Accounts vorhanden   
	                    } else {
	                        $list = show("$dir/no_log", array("empty" => _log_list_empty,
	                                                                  ));
	                }
	                
	                //Hole Server Name für Head
	                $qry_srv_data = mysql_query("SELECT * FROM `".$database_prefix."servers` WHERE server_id = '".$_GET['server_id']."' LIMIT 1");
	            	$get_srv_data = mysql_fetch_array($qry_srv_data); 
	            	
	            	// Lade Template mit errechnetem Inhalt
            		$content_headers = array("head_on" => TRUE,
											 "head_type" => "default",
					                         "head_value" => _log_show_head_2 . $get_srv_data['name'],
											 "navi_on" => TRUE,
											 "navi_type" => "list_log",
											 );
	            	
	                // Speichere in Ausgabe
	                $index = show("$dir/log_show", array("list" => $list,
	                                                     "id" => _log_show_id,
	                                                     "user" => _log_show_user,
	                                                     "content" => _log_show_action,
	                                                     "ip" => _log_show_ip,
	                                                     "date" => _log_show_date,
	                                                     "action" => _log_show_delete,
	                                                     ));
	            }
                } else {
                //Meldung an MAP User, dass die Logging Funktionen derzeit ausgeschaltet sin
                $info = "<br><div align=\"center\"><div class='savearea'>"._log_functions_currently_disabled."</div></div>";
       		}
    }  
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Logs in einer Liste anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START** Übergabescript
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'action':
	
	//Hole Daten aus Listenanzeige: Adminliste und leite weiter
	//Übergebe ID´s	
	$_SESSION['log'] = $_POST['id'];
	
	//Definiere Sections
	$avaliableActions = array("delete_x" => "delete_log_db",
							  );
							  
	//Leite weiter
	foreach ($avaliableActions as $key => $value) {
		if (isset($_POST[$key])) {
			header("Location: index.php?section=".$value);
			
		}
	}

break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Übergabescript
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Anzeige Server Auswahl um Logs anzuzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
    // Definition Seitentitel
    $seitentitel .= _pagetitel_log_overview;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
    if (perm_handler("log_overview", FALSE)) {
     	$qry_logging_on = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '23'");
        $get_logging_on = mysql_fetch_array($qry_logging_on);
        if ($get_logging_on[2]== "1") {       
	        //Start definition eigentliche Funktionen dieser Section:
            $qry = mysql_query("SELECT * FROM `".$database_prefix."servers` ORDER BY server_id ASC");
            while ($get = mysql_fetch_array($qry)) {
              	if (perm_server($get['server_id']) OR perm_handler("show_log_all", FALSE)) {
               		$list .= show("$dir/log_overview_list", array("image" => "<a href=\"../log/index.php?section=show_log&view=server&server_id=$get[server_id]\" target=\"_self\"><img src=\"../inc/tpl/".$tpldir."/images/server.png\" alt=\"\" border=\"0\"></a>",
                                                           		  "name" => "<a href=\"../log/index.php?section=show_log&view=server&server_id=$get[server_id]\" target=\"_self\">".$get['name']."</a>",
                                                           	      ));
               	}
            }
               
            // Lade Template mit errechnetem Inhalt
           	$content_headers = array("head_on" => TRUE,
									 "head_type" => "default",
			                         "head_value" => _log_overview_head,
									 "navi_on" => TRUE,
									 "navi_type" => "default",
									 );
								 
            $index = show("$dir/log_overview", array("list" => $list,
                                                     "discription" => _log_discription,
                                                     "list_image" => "<a href=\"../log/index.php?section=show_log&view=all\" target=\"_self\"><img src=\"../inc/tpl/".$tpldir."/images/all_server.png\" alt=\"\" border=\"0\"></a>",
                                                     "list_name" => "<a href=\"../log/index.php?section=show_log&view=all\" target=\"_self\">"._log_overview_show_server_list."</a>",
                                                     ));       
	        //Ende definition eigentliche Funktionen dieser Section^^
          		} else {
           		//Meldung an MAP User, dass die Logging Funktionen derzeit ausgeschaltet sin
              	 	$info = "<br><div align=\"center\"><div class='savearea'>"._log_functions_currently_disabled."</div></div>";
       	}
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Anzeige Server Auswahl um Logs anzuzeigen
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