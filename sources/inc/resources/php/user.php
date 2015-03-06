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

//Hole den Online-Status eines registrierten Users
function getUserStatus($server_id, $user_id) {
	
	global $tpldir;
	$status = FALSE;
	
	$server = getServer($server_id);

	$user = $server['connected_users'];
	foreach ($user as $userID) {
		if ($userID->userid == $user_id) {
			$status = TRUE;
			break;
			} else {
				$status = FALSE;
		}
	}

	if ($status) {
		$bool = TRUE;
		$string = "Online";
		$status_icon = "<img src=\"../inc/tpl/".$tpldir."/images/server_status_online.png\" alt=\"\" border=\"0\">" . _server_show_status_server_on;
		$status_icon_small = "<img src=\"../inc/tpl/".$tpldir."/images/server_status_online.png\" alt=\"\" border=\"0\">";
		} else {
			$bool = FALSE;
			$string = "Offline";
			$status_icon = "<img src=\"../inc/tpl/".$tpldir."/images/server_status_offline.png\" alt=\"\" border=\"0\">" . _server_show_status_server_off;
			$status_icon_small = "<img src=\"../inc/tpl/".$tpldir."/images/server_status_offline.png\" alt=\"\" border=\"0\">";
	}
	
	$return = array("bool" => $bool,								//Gibt je nach Online Status true oder false zurück
					"string" => $string,							//Gibt einen Online oder Offline String zurück
					"status_icon" => $status_icon,					//Gibt ein Icon mit Text zurück
					"status_icon_small" => $status_icon_small,		//Gibt nur ein Icon (grün oder rot) zurück
					);
	
	return $return;
	
}

//Checke ob Admin existiert
function checkIfUserExists($server_id, $name) {
	
	$user_id = getUserIds($server_id, array("0" => $name));
	
    if ($user_id[$name] == "-2") {
    	$return = FALSE;
    	} else {
    		$return = TRUE;
    }
    
    return $return;
    	
}

//Chekce ob MAPUSER berechtigung hat andere mapuser in liste zu sehen
function getAdminViewPerms($mapuser_id, $admin_id) {
	
	//Hole Userinfos
	$MAPUSER = user_info($mapuser_id);
	$ADMIN = user_info($admin_id);
	$perm = FALSE;
	
	if ($MAPUSER['type_id'] == 1) {
		$perm = TRUE;
		} elseif ($MAPUSER['type_id'] == 0) {
			if ($ADMIN['type_id'] == 1) {
				$perm = FALSE;
				} elseif ($ADMIN['type_id'] == 0) {
					foreach ($MAPUSER['servers'] as $M_server_id) {
						foreach ($ADMIN['servers'] as $A_server_id) { 
							if ($M_server_id == $A_server_id) {
								$perm = TRUE;
								break;
								} else {
									$perm = FALSE;
							}
						}
					}
					if ($perm == FALSE AND $MAPUSER['group_id'] == $ADMIN['group_id']) {
						$perm = TRUE;
					}
			}
	}
	
	return $perm;
	
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>