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

// Login Box
if (!isset($_MAPUSER['logged'])) {
	$_MAPUSER['logged'] = FALSE;
}
if ($_MAPUSER['logged'] == FALSE) { 
    $_Login = show("menu/login", array("login" => _menu_login_overview,
                                       "login_do" => _menu_login_do,
                                       "login_field_1" => _menu_login_field_1,
                                       "login_field_2" => _menu_login_field_2
                                       ));
    } else {
        $_Login = "";
}

//Damit es im Frontend anständig aussieht checke ob request und passwort an sind. ansonsten gebe jeweils einzeln aus.
$permRequest = getPermRequestFunctions();
$permOperate = getPermOperateFunctions();
//Gebe beide Links im Frontend aus, wenn EIN, ansonsten Einzelausgabe
if ($_MAPUSER['logged'] == FALSE && $permRequest['frontend'] == TRUE && $permOperate == TRUE) {
	$_RequestForgot = show("menu/request_forgot", array("request_link" => _menu_request_link,
											     		 "forgot_link" => _menu_forgot_link,
												 		 ));
	// Einzelausgabe: Request User Account - Link
	} elseif ($_MAPUSER['logged'] == FALSE && $permRequest['frontend'] == TRUE && $permOperate == FALSE) { 
	    $_RequestForgot = show("menu/request", array("request_link" => _menu_request_link));
		// Einzelausgabe: Passwort vergessen Funktionen - Link
		} elseif ($_MAPUSER['logged'] == FALSE && $permRequest['frontend'] == FALSE && $permOperate == TRUE) { 
		    $_RequestForgot = show("menu/forgot", array("forgot_link" => _menu_forgot_link));
		    //Oder nichts aus!
		    } elseif ($_MAPUSER['logged'] == FALSE && $permRequest['frontend'] == FALSE && $permOperate == FALSE) {
		        $_RequestForgot = FALSE;
				} elseif ($_MAPUSER['logged'] == TRUE) {
		        	$_RequestForgot = FALSE;
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>