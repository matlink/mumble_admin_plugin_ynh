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
include('../inc/_permissions/globals.php');
include('../inc/_permissions/sections.php');
include('../inc/_permissions/groups.php');
include('../inc/_permissions/users.php');
include('../inc/_permissions/public.php');
//************************************************************************************************//
// **ENDE**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
//************************************************************************************************//
// Start des Ausgabeinhalts
//************************************************************************************************//

//Prüfe ob Datenbankeinträge nicht doppelt oder gar nicht vorhanden sind, ansonsten lösche oder adde diese
function checkValidPermsInDB() {
	
	global $database_prefix;
	global $info;
	
	//Hole Sections
	$SectionIDs = getSectionIDs();
	//Hole Daten
	$GroupIDs = getGroups();
	//Hole ALLE Admins
	$qry = mysql_query("SELECT * FROM `".$database_prefix."user` ORDER BY user_id ASC");
	$i = "0";
	while ($get = mysql_fetch_array($qry)) {         //Schreibe Index
		$AdminIDs[$i] = $get['user_id'];             //...
		$i++;                                        //...
	}
	
	//Checke DB-group-perm
	foreach ($GroupIDs as $GroupID) {
		foreach ($SectionIDs as $SectionID) {
			$qryGrpPerms = mysql_query("SELECT * FROM `".$database_prefix."group_perm` WHERE group_id = '".$GroupID."' AND section_id = '".$SectionID."'");
			$getGrpPerms = mysql_num_rows($qryGrpPerms);
			if ($getGrpPerms > '1') {
				mysql_query("DELETE FROM `".$database_prefix."group_perm` WHERE group_id = '".$GroupID."' AND section_id = '".$SectionID."' LIMIT 1");
				$info .= "<br><div align=\"center\"><div class='savearea'>Database struckture was corrupt (to much entries) now fixed!</div></div>";
			}
			if ($getGrpPerms == '0') {
				mysql_query("INSERT INTO `".$database_prefix."group_perm` (`group_id`, `section_id`, `value`) VALUES ('".$GroupID."', '".$SectionID."', '0');");
				$info .= "<br><div align=\"center\"><div class='savearea'>Database struckture was corrupt (entry was missing) now fixed!</div></div>";
			}
		}
	}
	
	//Checke DB-user-perm
	foreach ($AdminIDs as $AdminID) {
		$Admin = user_info($AdminID);
		foreach ($SectionIDs as $SectionID) {
			$qryGrpPerms = mysql_query("SELECT * FROM `".$database_prefix."user_perm` WHERE perm_id = '".$Admin['perm_id']."' AND section_id = '".$SectionID."'");
			$getGrpPerms = mysql_num_rows($qryGrpPerms);
			if ($getGrpPerms > '1') {
				mysql_query("DELETE FROM `".$database_prefix."user_perm` WHERE perm_id = '".$Admin['perm_id']."' AND section_id = '".$SectionID."' LIMIT 1");
				$info .= "<br><div align=\"center\"><div class='savearea'>Database struckture was corrupt (to much entries) now fixed!</div></div>";
			}
			if ($getGrpPerms == '0') {
				mysql_query("INSERT INTO `".$database_prefix."user_perm` (`perm_id`, `section_id`, `value`) VALUES ('".$Admin['perm_id']."', '".$SectionID."', '0');");
				$info .= "<br><div align=\"center\"><div class='savearea'>Database struckture was corrupt (entry was missing) now fixed!</div></div>";
			}
		}
	}
}
if ($dir == "permissions") {
	checkValidPermsInDB();
}

//Logging der Perm-Data
function perm_logging($section_name) {
	
	global $_MAPUSER;
	global $database_prefix;
	
	//Hole Array mit Infos zu der Section für die die Berechtigungen ausgegeben werden soll 
	$section_info = section_info(FALSE, $section_name);
	
	//Führe Logging aus, wenn es laut Section info erlaubt ist.
	if ($section_info['data_count'] == "1") {
		//Hole Daten aus DB
		$qry = mysql_query("SELECT * FROM `".$database_prefix."section_data` WHERE section_id = '".$section_info['section_id']."' AND user_id = '".$_MAPUSER['user_id']."' LIMIT 1");
		$get = mysql_fetch_array($qry);
		
		if ($get['id'] != "") {
			$counter = $get['access_count'] + 1;
			mysql_query("UPDATE `".$database_prefix."section_data` SET access_count = '$counter' WHERE id = '$get[id]'");
			mysql_query("UPDATE `".$database_prefix."section_data` SET ip = '$counter' WHERE id = '".ip2long($_SERVER['REMOTE_ADDR'])."'");
			} else {
				mysql_query("INSERT INTO `".$database_prefix."section_data` (`section_id`, `user_id`, `access_count`, `ip`) VALUES (".$section_info['section_id'].", '".$_MAPUSER['user_id']."', '1', '".ip2long($_SERVER['REMOTE_ADDR'])."');");
		}
	}
}

//Funktion perm_server um Berechtigungen des angemeldeten Admins zu bestimmen, welchen Server er ansehen darf
// Copy-Paste: $perm_server = perm_server($server_id);
function perm_server($server_id, $user_id = FALSE) {
	
	//Definiere User, dessen Berechtigungen abgefragt werden
	if ($user_id == FALSE) {
		global $_MAPUSER;
		} elseif (isset($user_id)) {
			$_MAPUSER = user_info($user_id);			
	}
	$perm_result = FALSE;

	//Prüfe ob Kunde zum Server gehört, wenn Serverspezifische Seite aufgerufen wurde
	if ($server_id != FALSE) {
		$i_max = count($_MAPUSER['servers']);
        $i = "1";
		while ($i <= $i_max) {
			if ($_MAPUSER['servers'][$i] == $server_id) {
				$perm_result = TRUE;
				break;
				} else {
					$perm_result = FALSE;
			}
    		$i++;  
		}
		} else {
			$perm_result = TRUE;
	}

	return $perm_result;             //TRUE oder FALSE
	
}

//Funktion perm_handler um Berechtigungen des angemeldeten Admins zu bestimmen, wenn er eine bestimmte Section aufruft
// Copy-Paste: $perm_handler = perm_handler($section_name, $server_id);
function perm_handler($section_name, $server_id) {
	
	global $_MAPUSER;
	global $perm_error;
	$get_group_perms = FALSE;
	
	//Führe Perm-Logging aus
	perm_logging($section_name);
    
	//Hole Array mit Infos zu der Section für die die Berechtigungen ausgegeben werden soll 
	$section_info = section_info(FALSE, $section_name);
	
	//Hole Berectigungen für die Section aus Gruppe
	if ($_MAPUSER['perm_id'] != "") {
		$get_group_perms = perm_group($section_name);
	}
	
	//Hole Berechtigung für die Section aus Userspezifischen Berechtigungen
	$get_user_perms = perm_user($section_name);
	
	//Prüfe ob Reseller oder Kunde
	$get_user_type = type_of_user();
	
	//Prüfe ob Kunde zum Server gehört, wenn Serverspezifische Seite aufgerufen wurde
	$get_server_perms = perm_server($server_id);
	
	//Rechne Type und Server Perms verhältnis aus
	if ($get_user_type == TRUE && $get_server_perms == TRUE) {
		$type_srv_perms = TRUE;
		} elseif ($get_user_type == FALSE && $get_server_perms == TRUE)	{
			$type_srv_perms = TRUE;
			} elseif ($get_user_type == TRUE && $get_server_perms == FALSE)	{
				$type_srv_perms = TRUE;
				} elseif ($get_user_type == FALSE && $get_server_perms == FALSE) {
					$type_srv_perms = FALSE;
	}
	
	//Rechne Groups und Userspez. Perms verhältnis aus
	if ($get_group_perms == TRUE && $get_user_perms == TRUE) {
		$group_user_perms = TRUE;
		} elseif ($get_group_perms == FALSE && $get_user_perms == TRUE)	{
			$group_user_perms = TRUE;
			} elseif ($get_group_perms == TRUE && $get_user_perms == FALSE)	{
				$group_user_perms = TRUE;
				} elseif ($get_group_perms == FALSE && $get_user_perms == FALSE) {
					$group_user_perms = FALSE;
	}
	
	//Rechne Ergebnis aus
	if ($group_user_perms == TRUE && $type_srv_perms == TRUE) {
		$perm_result = TRUE;
		$perm_error_message = FALSE;
		} elseif ($group_user_perms == FALSE && $type_srv_perms == TRUE)	{
			$perm_result = FALSE;
			$perm_error_message = "1";
			} elseif ($group_user_perms == TRUE && $type_srv_perms == FALSE)	{
				$perm_result = FALSE;
				$perm_error_message = "2";
				} elseif ($group_user_perms == FALSE && $type_srv_perms == FALSE) {
					$perm_result = FALSE;
					$perm_error_message = "3";
	}
	
	//Gebe Perm Error Message aus.
	if ($section_info['error_message'] == "1" && $perm_error_message == "1") {
		$perm_error .= "<br><div align=\"center\"><div class='savearea'>"._perm_error_group_user. " Error-ID: " . $section_info['section_id']. "</div></div>";
		} elseif ($section_info['error_message'] == "1" && $perm_error_message == "2") {
			$perm_error .= "<br><div align=\"center\"><div class='savearea'>"._perm_error_type_server. " Error-ID: " . $section_info['section_id']. "</div></div>";
			} elseif ($section_info['error_message'] == "1" && $perm_error_message == "3") {
				$perm_error .= "<br><div align=\"center\"><div class='savearea'>"._perm_error_all. " Error-ID: " . $section_info['section_id']. "</div></div>";	
	}
	
	return $perm_result;             //TRUE oder FALSE
	
}

// Hole ausschließlich die Berechtigungen, nicht für Ausgaben geeignet
// Copy-Paste: $perm_handler = perm_handler($section_name, $server_id);
function get_perms($section_name, $server_id, $user_id = FALSE) {
	
	//Definiere User, dessen Berechtigungen abgefragt werden
	if ($user_id == FALSE) {
		global $_MAPUSER;
		} elseif (isset($user_id)) {
			$_MAPUSER = user_info($user_id);
	}
	
	//Hole Array mit Infos zu der Section für die die Berechtigungen ausgegeben werden soll 
	$section_info = section_info(FALSE, $section_name);
	
	//Hole Berectigungen für die Section aus Gruppe
	if ($_MAPUSER['perm_id'] != "") {
		$get_group_perms = perm_group($section_name, $user_id);
	}
	
	//Hole Berechtigung für die Section aus Userspezifischen Berechtigungen
	$get_user_perms = perm_user($section_name, $user_id);
	
	//Prüfe ob Reseller oder Kunde
	$get_user_type = type_of_user($user_id);
	
	//Prüfe ob Kunde zum Server gehört, wenn Serverspezifische Seite aufgerufen wurde
	$get_server_perms = perm_server($server_id, $user_id);
	
	//Rechne Type und Server Perms verhältnis aus
	if ($get_user_type == TRUE && $get_server_perms == TRUE) {
		$type_srv_perms = TRUE;
		} elseif ($get_user_type == FALSE && $get_server_perms == TRUE)	{
			$type_srv_perms = TRUE;
			} elseif ($get_user_type == TRUE && $get_server_perms == FALSE)	{
				$type_srv_perms = TRUE;
				} elseif ($get_user_type == FALSE && $get_server_perms == FALSE) {
					$type_srv_perms = FALSE;
	}
	
	//Rechne Groups und Userspez. Perms verhältnis aus
	if ($get_group_perms == TRUE && $get_user_perms == TRUE) {
		$group_user_perms = TRUE;
		} elseif ($get_group_perms == FALSE && $get_user_perms == TRUE)	{
			$group_user_perms = TRUE;
			} elseif ($get_group_perms == TRUE && $get_user_perms == FALSE)	{
				$group_user_perms = TRUE;
				} elseif ($get_group_perms == FALSE && $get_user_perms == FALSE) {
					$group_user_perms = FALSE;
	}
	
	//Rechne Ergebnis aus
	if ($group_user_perms == TRUE && $type_srv_perms == TRUE) {
		$perm_result = TRUE;
		} elseif ($group_user_perms == FALSE && $type_srv_perms == TRUE)	{
			$perm_result = FALSE;
			} elseif ($group_user_perms == TRUE && $type_srv_perms == FALSE)	{
				$perm_result = FALSE;
				} elseif ($group_user_perms == FALSE && $type_srv_perms == FALSE) {
					$perm_result = FALSE;
	}
	
	return $perm_result;             //TRUE oder FALSE
	
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>