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

// Main Navigation (Menu)
function getMenu() {

	global $_MAPUSER;
	global $tpldir;
	global $database_prefix;
	
	//Wenn User eingeloggt, gebe Menu aus
	if ($_MAPUSER['logged']) {
	
		//"Startseite"
		if (perm_handler("menu_link_start", FALSE)) {
			$linkArray[] = array("name" => _menu_link_home,
				    		     "pic" => 'home.png',
				    		     "goTo" => '../start/index.php',
				    		     );
		}
		
		//"Mein Profil"
		if (perm_handler("menu_link_myprofile", FALSE)) {
			$linkArray[] = array("name" => _menu_link_myprofile,
				    		     "pic" => 'avatar.png',
				    		     "goTo" => '../admin/index.php?section=profile&user_id='.$_MAPUSER['user_id'],
				    		     );
		}
		
		//"Server"
		if (perm_handler("menu_link_servers", FALSE)) {
			$linkArray[] = array("name" => _menu_link_servers,
				    		     "pic" => 'servers.png',
				    		     "goTo" => '../server/index.php?default',
				    		     );
		}
		
		//"Channelviewer"
		if (perm_handler("menu_link_channelviewer", FALSE)) {
			$linkArray[] = array("name" => _menu_link_view,
				    		     "pic" => 'channelviewer.png',
				    		     "goTo" => '../view/index.php?default',
				    		     );
		}
			
		//"User"
		if (perm_handler("menu_link_user", FALSE)) {
			$linkArray[] = array("name" => _menu_link_server_users,
				    		     "pic" => 'user.png',
				    		     "goTo" => '../user/index.php?default',
				    		     );
		}
		
		//"Admin"
		if (perm_handler("menu_link_admin", FALSE)) {
			$linkArray[] = array("name" => _menu_link_map_users,
				    		     "pic" => 'admin.png',
				    		     "goTo" => '../admin/index.php?default',
				    		     );
		}
		
		//"Erstellen"
		if (perm_handler("menu_link_create", FALSE)) {
			$linkArray[] = array("name" => _menu_link_create,
				    		     "pic" => 'create.png',
				    		     "goTo" => '../server/index.php?section=create',
				    		     );
		}
		
		//"beantr. Konten"
		if (perm_handler("menu_link_request", FALSE)) {
			$linkArray[] = array("name" => _menu_link_requested_accounts,
				    		     "pic" => 'request.png',
				    		     "goTo" => '../request/index.php?section=list_accounts',
				    		     );
		}
		
		//"System-Log"
		if (perm_handler("menu_link_log", FALSE)) {
			$linkArray[] = array("name" => _menu_link_log,
				    		     "pic" => 'logging.png',
				    		     "goTo" => '../log/index.php?default',
				    		     );
		}
		
		//"E-Mail"
		if (perm_handler("menu_link_email", FALSE)) {
			$linkArray[] = array("name" => _menu_link_email,
				    		     "pic" => 'email.png',
				    		     "goTo" => '../email/index.php?default',
				    		     );
		}
		
		//"Berechtigungen"
		if (perm_handler("menu_link_permissions", FALSE)) {
			$linkArray[] = array("name" => _menu_link_permissions,
				    		     "pic" => 'permissions.png',
				    		     "goTo" => '../permissions/index.php?default',
				    		     );
		}

		//"Einstellungen"
		if (perm_handler("menu_link_settings", FALSE)) {
			$linkArray[] = array("name" => _menu_link_settings,
				    		     "pic" => 'settings.png',
				    		     "goTo" => '../settings/index.php?default',
				    		     );
		}
				
		//"Logout"
		if (perm_handler("menu_link_logout", FALSE)) {
			$linkArray[] = array("name" => _menu_link_logout,
				    		     "pic" => 'logout.png',
				    		     "goTo" => '../start?do=logout',
				    		     );
		}
			    		     
	    //Gebe die gesammelten Arrays in das Template aus
	    $linkContent = FALSE;
	    foreach($linkArray as $links) {
	    	$linkContent .= show("menu/menu_link",array("name" => $links['name'],
	    									            "pic" => $links['pic'],
	    												"tpldir" => $tpldir,
	    										        "goTo" => $links['goTo'],
	    									            ));
	    }
	    
	    //Generiere fertiges Menu
	    $_MainNavi = FALSE;
	    $_MainNavi .= show("menu/main_navi",array("overview" => _menu_link_overview,
	    									      "linkContent" => $linkContent,
												  ));
		} else {
			$_MainNavi = "";
	}
    return $_MainNavi;
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>