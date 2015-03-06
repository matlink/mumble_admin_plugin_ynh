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


//Definiere Zugangs Berechtigungen für Request Functions
function getAccessPermRequestFunctions() {
	
	global $database_prefix;

	//Hole Berechtigungen
	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '16'");
	$perm = mysql_fetch_array($qry);
	
	//Definiere Berechtigungen für Passwort vergessen Funktionen (forgot_area)
	if ($perm['value_2'] == "0") {
	   	$return = FALSE;
	   	} elseif ($perm['value_2'] == "1") {
	   		$return = TRUE;
	}
	
	return $return;

}

// Definiere Anzeige Berechtigung für Request Functions, ob
// a) (1) Nur öffentliches Konto beanragen Formular erlaubt, oder
// b) (2) Nur erweitertes Konto beanragen Formular erlaubt, oder
// c) (3) Beide Konto beanragen Formulare erlaubt
// $return[frontend] => Öffentliches Konto beantragen Formular erlaubt
// $return[backend] => erweitertes Konto beantragen Formular erlaubt
function getViewPermRequestFunctions() {
	
	global $database_prefix;

	//Hole Berechtigungen
	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '31'");
	$perm = mysql_fetch_array($qry);
	
	//Definiere Berechtigungen für Request Formulare (request_access_page).
	if ($perm['value_2'] == "1") {
	 	$return['frontend'] = TRUE;					// Nur öffentliches Konto beanragen Formular erlaubt
	   	$return['backend'] = FALSE;
	   	} elseif ($perm['value_2'] == "2") {
	   		$return['frontend'] = FALSE;				// Nur erweitertes Konto beanragen Formular erlaubt
	   		$return['backend'] = TRUE;
	   		} elseif ($perm['value_2'] == "3") {
	   			$return['frontend'] = TRUE;			// Beide Konto beanragen Formulare erlaubt
	   			$return['backend'] = TRUE;
	}
	
	return $return;

}

//Definiere komplette Berechtigungen für Request Functions (Access und View zusammen)
function getPermRequestFunctions() {
	
	//Hole Access Berechtigungen
	$access = getAccessPermRequestFunctions();
	//Hole View Berechtigungen
	$view = getViewPermRequestFunctions();

	//Berechne Berechtigungen für Frontend Request Bereich
	if ($access == TRUE && $view['frontend'] == TRUE) {
		$return['frontend'] = TRUE;
		} else {
			$return['frontend'] = FALSE;
	}
	
	//Berechne Berechtigungen für Backend Request Bereich
	if ($access == TRUE && $view['backend'] == TRUE) {
		$return['backend'] = TRUE;
		} else {
			$return['backend'] = FALSE;
	}
	
	return $return;
	
}

//Definiere Anzeige Berechtigungen für Passwort vergessen Bereiche
function getPermOperateFunctions() {
	
	global $database_prefix;

	//Hole Berechtigungen
	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '32'");
	$perm = mysql_fetch_array($qry);
	
	//Definiere Berechtigungen für Passwort vergessen Funktionen (forgot_area)
	if ($perm['value_2'] == "0") {
	   	$return = FALSE;
	   	} elseif ($perm['value_2'] == "1") {
	   		$return = TRUE;
	}
	
	return $return;

}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>