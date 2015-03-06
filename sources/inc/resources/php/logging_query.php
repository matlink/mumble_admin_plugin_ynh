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

//Hole Settings (log_days)
$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '26'");
$log_days = mysql_fetch_array($qry);

//Lösche Einträge aus der Datenbank, wenn Log_Days nicht ausgeschaltet ist (AUS =0)
if ($log_days[2] != "0") {
	//Definiere Datum, wie alt die Logs sein düfen
	$max_days = time()-60*60*24*$log_days[2];
	$unix_time = date("Y-m-d H:i:s",$max_days);

	//Hole alle Logs und Werte diese in einer While Schleife aus.
	$qry = mysql_query("SELECT id, date FROM `".$database_prefix."log` WHERE date < '".$unix_time."'");
	while ($get = mysql_fetch_array($qry)) {
		//Wenn älter als Settings es wollen, löschen!
		mysql_query("DELETE FROM `".$database_prefix."log` WHERE id = '".$get['id']."' LIMIT 1");	
	}	
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>