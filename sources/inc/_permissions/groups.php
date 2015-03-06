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
// Start des Ausgabeinhalts
//************************************************************************************************//

//Hole definierte Section-Berechtigung aus der Gruppe des angemeldeten Admins
function perm_group($section_name, $user_id = FALSE) {
	
	//Definiere User, dessen Berechtigungen abgefragt werden
	if ($user_id == FALSE) {
		global $_MAPUSER;
		} elseif (isset($user_id)) {
			$_MAPUSER = user_info($user_id);
	}
	global $database_prefix;
	
	//Hole Array mit Infos zu den Sections für die die Berechtigungen ausgegeben werden sollen
	$section_info = section_info(FALSE, $section_name);
	
	//Hole Berechtigung aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."group_perm` WHERE group_id = '".$_MAPUSER['group_id']."' AND section_id = '".$section_info['section_id']."' LIMIT 1");
	$get = mysql_fetch_array($qry);
	
	//Vergleiche Ergebnis und Werte aus
	if ($get['value'] == "1") {
		$perms = TRUE;
		} elseif ($get['value'] == "0") {
			$perms = FALSE;
	}
	
	return $perms;
}

//Hole definierte Section-Berechtigung aus einer beliebigen Gruppe
function perm_group_detailed($group_id, $section_name, $section_id) {
	
	global $_MAPUSER;
	global $database_prefix;
	
	//Hole Array mit Infos zu den Sections für die die Berechtigungen ausgegeben werden sollen
	if ($section_name != FALSE) {
		$section_info = section_info(FALSE, $section_name);
		$section_id = $section_info['section_id'];
	}
	
	//Hole Berechtigung aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."group_perm` WHERE group_id = '".$group_id."' AND section_id = '".$section_id."' LIMIT 1");
	$get = mysql_fetch_array($qry);
	
	//Vergleiche Ergebnis und Werte aus
	if ($get['value'] == "1") {
		$perms = TRUE;
		} elseif ($get['value'] == "0") {
			$perms = FALSE;
	}
	
	return $perms;
}

//Gebe Infos zur Gruppe aus
function group_info($group_id) {
	
	global $database_prefix;
		
	//Hole Gruppen -Name und -Beschreibung    	
	$group_qry = mysql_query("SELECT * FROM `".$database_prefix."group` WHERE group_id = '".$group_id."'");
	$get = mysql_fetch_array($group_qry);
	
	//Generiere Teamtag mit Url zur Proilseite
	$linked_name = "<a href=\"../permissions/index.php?section=edit_group&group_id=".$get['group_id']."\">".$get['name']."</a>";
	
	
	$group = array("group_id" => $get['group_id'],
				   "name" => $get['name'],
				   "linked_name" => $linked_name,
				   "discription" => $get['discription'],
				   "user_id" => $get['user_id'],
				   "date" => $get['date'],
				   );
				   
	return $group;
	
}

//Hole Array mit vorhanden group_id
function getGroups() {
	
	global $database_prefix;
	
	//Hole Gruppen
	$qry = mysql_query("SELECT * FROM `".$database_prefix."group` ORDER BY date ASC");
    $i = "0";
	while ($get = mysql_fetch_array($qry)) {
		$group[$i] = $get['group_id'];
		$i++;
	}
	
	return $group;
	
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>