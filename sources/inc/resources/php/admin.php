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

//Hole alle Admins => gibt Array mit user_id´s der Admins zurück
function getAdmins() {
	
	global $_MAPUSER;
	global $database_prefix;
	
	if($_MAPUSER['type_id'] = "1") { //Reseller!
		//Hole ALLE Admins
		$qry = mysql_query("SELECT * FROM `".$database_prefix."user` ORDER BY user_id ASC");
		$i = "0";
		while ($get = mysql_fetch_array($qry)) {         //Schreibe Index
			$admins[$i] = $get['user_id'];               //...
			$i++;                                        //...
		}
		} elseif($_MAPUSER['type_id'] = "0") { //Kunde!
			//Hole alle Admins zu den Servern, die der Admin editieren darf
			foreach($_MAPUSER['servers'] as $server_id) {
				$qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE server_id = ".$server_id." ORDER BY user_id ASC");
				while ($get = mysql_fetch_array($qry)) {         //Schreibe Index
					$admins[$i] .= $get['user_id'];              //...
					$i++;                                        //...
				}
				$admins = array_unique($admins);
			}
	}
	return $admins;
}

//Hole Servernamen die einem User zugeordnet sind, als ganzer String
function getServernamesForUser($serverArray) {
	
	if (empty($serverArray) == FALSE) {
		foreach($serverArray as $server_id) {
			$server = server_info($server_id);
			if(isset($servers)) {
				$servers .= ', '.$server['name']; 
				} else {
					$servers = $server['name']; 
			}
		}
		} else {
			$servers = _admin_list_server_not_set;
	}
	
	return $servers;
}

//Checke ob Admin existiert
//bitte entweder oder name/user_id übergeben!
function checkIfAdminExists($name = FALSE, $user_id = FALSE) {
	
	global $database_prefix;
	
	if (isset($name)) {
		$qry = mysql_query("SELECT user_id FROM `".$database_prefix."user` WHERE name = '$name'");
	    $admin = mysql_num_rows($qry);
	    
	    if ($admin != 0) {
	    	$return = TRUE;
	    	} else {
	    		$return = FALSE;
		}
	}
	
	if (isset($user_id)) {
		$qry = mysql_query("SELECT user_id FROM `".$database_prefix."user` WHERE user_id = '$user_id'");
	    $admin = mysql_num_rows($qry);
	    
	    if ($admin != 0) {
	    	$return = TRUE;
	    	} else {
	    		$return = FALSE;
		}
	}
    
    return $return;
    	
}

//Hole definierte Section-Berechtigung zu beliebigem Admin
function perm_admin_detailed($perm_id, $section_name, $section_id) {
	
	global $_MAPUSER;
	global $database_prefix;
	
	//Hole Array mit Infos zu den Sections für die die Berechtigungen ausgegeben werden sollen
	if ($section_name != FALSE) {
		$section_info = section_info(FALSE, $section_name);
		$section_id = $section_info['section_id'];
	}
	
	//Hole Berechtigung aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."user_perm` WHERE perm_id = '".$perm_id."' AND section_id = '".$section_id."' LIMIT 1");
	$get = mysql_fetch_array($qry);
	
	//Vergleiche Ergebnis und Werte aus
	if ($get['value'] == "1") {
		$perms = TRUE;
		} elseif ($get['value'] == "0") {
			$perms = FALSE;
	}
	
	return $perms;
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>