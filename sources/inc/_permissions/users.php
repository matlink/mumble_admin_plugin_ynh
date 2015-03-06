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

//Hole definierte Section-Berechtigung aus Userspezifischen Berechtigungen
function perm_user($section_name, $user_id = FALSE) {
	
	//Definiere User, dessen Berechtigungen abgefragt werden
	if ($user_id == FALSE) {
		global $_MAPUSER;
		} elseif (isset($user_id)) {
			$_MAPUSER = user_info($user_id);
	}
	global $database_prefix;
	$perms = FALSE;
	
	//Hole Array mit Infos zu den Sections für die die Berechtigungen ausgegeben werden sollen
	$section_info = section_info(FALSE, $section_name);
	
	//Hole Berechtigung aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."user_perm` WHERE perm_id = '".$_MAPUSER['perm_id']."' AND section_id = '".$section_info['section_id']."' LIMIT 1");
	$get = mysql_fetch_array($qry);
	
	//Vergleiche Ergebnis und Werte aus
	if ($get['value'] == "1") {
		$perms = TRUE;
		} elseif ($get['value'] == "0") {
			$perms = FALSE;
	}
	
	return $perms;
}

//Prüfe was User für eine Kontoart hat
function type_of_user($user_id = FALSE) {
	
	//Definiere User, dessen Berechtigungen abgefragt werden
	if ($user_id == FALSE) {
		global $_MAPUSER;
		} elseif (isset($user_id)) {
			$_MAPUSER = user_info($user_id);
	}
	
	//Vergleiche Ergebnis und Werte aus
	if ($_MAPUSER['type_id'] == "1") {
		$perms = TRUE;
		} elseif ($_MAPUSER['type_id'] == "0") {
			$perms = FALSE;
	}
	
	//Ausgabe: TRUE=Reseller, FALSE=Kunde
	return $perms;
}

//Gibt die Daten eines beliebigen Users zurück
function user_info($user_id) {
	
	global $database_prefix;
	
	//Hole Userspezifische Daten aus DB und speichere diese in globalem Array
	$qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE user_id = '".$user_id."' LIMIT 1");
	$get = mysql_fetch_array($qry);
	
	//Erstelle zusammengestzte Namensfelder
	if ($get['username'] == '' && $get['clantag'] == '') {
		$get['username'] = $get['name'];
		$get['teamtag'] = $get['name'];
		} elseif ($get['username'] != '' && $get['clantag'] == '') {
		    $get['teamtag'] = $get['username'];
			} elseif ($get['username'] != '' && $get['clantag'] != '') {
				$get['teamtag'] = $get['clantag']. " | " . $get['username'];
				} elseif ($get['username'] == '' && $get['clantag'] != '') {
					$get['teamtag'] = $get['clantag']. " | " . $get['name'];
	}
	
	//Hole server_id´s, die dem User zugeordnet sind
	$qry_servers = mysql_query("SELECT * FROM `".$database_prefix."user_servers` WHERE user_id = '".$get["user_id"]."' ORDER BY server_id ASC");
	$cnt_servers = mysql_num_rows($qry_servers);
	if ($cnt_servers != "0") {
		$i = "1"; //Setze Index für $_MAPUSER[servers] feld1, feld2 usw.
		while ($get_servers = mysql_fetch_array($qry_servers)) {         //Schreibe Index
			$servers[$i] = $get_servers["server_id"];                      //...
			$i++;                                                        //...
		}
		} else {
			$servers = array();
	}
	
	//Generiere Teamtag mit Url zur Proilseite
	$linked_name = "<a href=\"../admin/index.php?section=profile&user_id=".$get["user_id"]."\">".$get["teamtag"]."</a>";
	
	//Schreibe Daten in globalen Array
	$_MAPUSER = array("user_id" => $get["user_id"],               //Eindeutige ID des Admins
					  "type_id" => $get["type_id"],               //Kontotyp des Admins: Kunde oder Reseller
	                  "group_id" => $get["group_id"],             //Gruppe, in der sich der Admin befindet
					  "perm_id" => $get["perm_id"],               //Berechtigungs ID des Admins
					  "customer_id" => $get["customer_id"],       //Kundennummer des Admins, falls er Kunde ist
	                  "name" => $get["name"],                     //Loginname des Admins, mit dem er sich einloggt.
					  "username" => $get["username"],             //Öffentlicher Name des Admins
	                  "clantag" => $get["clantag"],               //Clantag, der vor den öffentlichen Namen des Admins gestellt wird
					  "teamtag" => $get["teamtag"],               //Öffentlicher Name mit Clantag
	                  "email" => $get["email"],                   //E-Mail-Adresse des Admins
	                  "pw" => $get["pw"],                         //Passwort des Admins in MD5() gespeichert
					  "logins" => $get["logins"],                 //Anzahl der bisherigen Logins
					  "last_active" => $get["last_active"],       //Letzte Aktivität des Admins
					  "lock" => $get["lock"],                     //Status, ob Admin gesperrt ist 0=gesperrt,1=aktiv
	                  "language" => $get["language"],             //Sprache des Admins
					  "template" => $get["template"],             //Template des Admins
					  "coockie" => $get["coockie"],               //Zeit in sek. bi das aktuelle Coockie abläuft
					  "icq" => $get["icq"],                       //ICQ-Nummer des Admins
	                  "discr" => $get["discr"],                   //Beschreibung des Admins
					  "phone" => $get["phone"],                   //Telefonnummer des Admins
					  "street" => $get["street"],                 //Straße des Admins
					  "postalcode" => $get["postalcode"],         //Postleitzahl des Admins
	                  "city" => $get["city"],                     //Wohnort des Admins
					  "country" => $get["country"],               //Land des Admins
					  "servers" => $servers,                    //Alle Server, zu dem dieser User zugeordnet ist.
					  "linked_name" => $linked_name,            //Gibt $_MAPUSER[teamtag] aus, jedoch mit link zur Profilseite
					  );
	return $_MAPUSER;
}

//Gibt die Daten eines beliebigen Users anhand seiner perm_id zurück
function user_info_ByPermID($perm_id) {
	
	global $database_prefix;
	
	//Hole Userspezifische Daten aus DB und speichere diese in globalem Array
	$qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE perm_id = '".$perm_id."' LIMIT 1");
	$get = mysql_fetch_array($qry);
	
	$UserArrray = user_info($get["user_id"]);

	return $UserArrray;
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>