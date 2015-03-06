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

//Speichere Logs in Datenbank  
//Checke ob Logging eingeschaltet ist
if (!isset($log_values["on"])) {$log_values["on"] = FALSE;}
if (!isset($log_values["priority"])) {$log_values["priority"] = FALSE;}
$qry_logging = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '23'");
$get_logging = mysql_fetch_array($qry_logging);
if ($get_logging['2'] == "1") {
	//Checke ob Priority stimmt
	$qry_log_priority = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '24'");
	$get_log_priority = mysql_fetch_array($qry_log_priority);
	if ($log_values['priority'] >= $get_log_priority['2']) {
		//Checke ob im aktuellen Parse ein Logging durchgeführt werden muss
		if ($log_values["on"] == TRUE) {
			//Definiere User_ID, falls Login durchgeführt wurde.
	 	    //Speichern
			$save = mysql_query("INSERT INTO `".$database_prefix."log` (`user_id`, `priority`, `action_id`, `ip`, `date`, `area`, `server_id`, `value_1`, `value_2`, `value_3`, `value_4`, `value_5`, `value_6`, `value_7`) VALUES ('".$log_values['user_id']."', '".$log_values['priority']."', '".$log_values['action_id']."', '".ip2long($_SERVER['REMOTE_ADDR'])."', '".$aktdate."', '".$log_values['area']."', '".$log_values['server_id']."', '".$log_values['value_1']."', '".$log_values['value_2']."', '".$log_values['value_3']."', '".$log_values['value_4']."', '".$log_values['value_5']."', '".$log_values['value_6']."', '".$log_values['value_7']."');");
		}
	}
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>