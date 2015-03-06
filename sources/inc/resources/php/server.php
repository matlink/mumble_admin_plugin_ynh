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

/*
 * Erstelle Server-URL, mit dem sich der User Verbinden kann
 * @ $ip = IP des Servers
 * @ $port = Port des Servers
 * @ $version = Version des Servers
 * @ $online = Status, ob de virtuelle Server online ist TRUE/1 oder FALSE/0
 * @ $channelpath = Channelstrucktu auf dem server, auf den connected werden soll
 * 
 */
function createServerURL($ip, $port, $version, $channelpath = FALSE, $online = TRUE, $user = FALSE, $password = FALSE) {
	
	//Erstelle das Protokoll
	$protokoll = 'mumble://'; 
	
	//Wenn Server Online, gebe URL aus, ansonsten Fehlermeldung
	if ($online == TRUE OR $online == "1") {
		
		//Berechne Loginwerte in URL
		if ($user != FALSE AND $password == FALSE) {
			$account = $user . '@';
			} elseif ($user != FALSE AND $password != FALSE) {
				$account = $user . ":" . $password . '@';
				} elseif ($user == FALSE AND $password == FALSE) {
					$account = "";
		}
		
		//Berechne Channelpath werte
		if ($channelpath != FALSE) {
			$channel = $channelpath . '/';
			} else {
				$channel = "";
		}

		//Ausgabe wenn Server Online
		$url = $protokoll . $account . $ip . ":" . $port . $channel . "/?version=" . $version;
		} else {
			//Ausgabe wenn Server Offline
			$url = "";
	}

	return $url;
	
}

//Gebe alle vorhandenen server_id als Array zurück
function getServers() {
	
	global $database_prefix;
	
	//Hole Gruppen
	$qry = mysql_query("SELECT * FROM `".$database_prefix."servers` ORDER BY server_id ASC");
    $i = "0";
	while ($get = mysql_fetch_array($qry)) {
		$servers[$i] = $get["server_id"];
		$i++;
	}
	
	return $servers;

}

//Generiere Auswahlboxen
function generiere_auswahlboxen($start, $ende, $aktuell) {
	$string = FALSE;
	while ($start <= $ende) {
		if ($start == "0") $start = "00";
		if ($start == "1") $start = "01";
		if ($start == "2") $start = "02";
		if ($start == "3") $start = "03";
		if ($start == "4") $start = "04";
		if ($start == "5") $start = "05";
		if ($start == "6") $start = "06";
		if ($start == "7") $start = "07";
		if ($start == "8") $start = "08";
		if ($start == "9") $start = "09";
		if ($start == $aktuell) {
			$string .= "<option value=\"".$start."\" selected=\"selected\">".$start."</option>\n";
			} else {
				$string .= "<option value=\"".$start."\">".$start."</option>\n";
		}
		$start++;
	}
	return $string;
}

//Hole Zahlstatus des Servers
function getServerPayTime($server_id) {
	
	global $database_prefix;
	
	//Hole Serverspezifische Daten aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."servers` WHERE server_id = '".$server_id."'");
	$get = mysql_fetch_array($qry);
	
	$pay["payed_until"] = $get["payed_until"];
	$TimeToGo =  strtotime($get["payed_until"]) - time();
	
	if ($TimeToGo > 0) {
		//Ausgabe der offenen Zeit
		$pay["TimeToGo"] = makeuserontime($TimeToGo);
		} else {
			//Ausgabe Abgelaufen
			$pay["TimeToGo"] = _server_expired;
	}
	
	if (strtotime($get["payed_until"]) > time()) {
		$pay["payed"] = TRUE;
		$pay["status"] = _server_payed;
		} else {
			$pay["payed"] = FALSE;
			$pay["status"] = _server_not_payed;
	}
	
	//Selectbox Ausgabe
	//y-String generieren
	$pay["selectbox"]["y"] = generiere_auswahlboxen("2010", "2025", substr($get["payed_until"], 0, 4));
	//m-String generieren
	$pay["selectbox"]["m"] = generiere_auswahlboxen("1", "12", substr($get["payed_until"], 5, 2));
	//d-String generieren
	$pay["selectbox"]["d"] = generiere_auswahlboxen("1", "31", substr($get["payed_until"], 8, 2));
	//h-String generieren
	$pay["selectbox"]["h"] = generiere_auswahlboxen("0", "23", substr($get["payed_until"], 11, 2));
	//i-String generieren
	$pay["selectbox"]["i"] = generiere_auswahlboxen("0", "59", substr($get["payed_until"], 14, 2));
	//s-String generieren
	$pay["selectbox"]["s"] = generiere_auswahlboxen("0", "59", substr($get["payed_until"], 17, 2));

	return $pay;
	
}

//Gibt die Daten eines beliebigen Servers zurück
//$server_id => bsp.: "1"
function server_info($server_id) {
	
	global $database_prefix;
	global $_SLICE;
	global $tpldir;
	
	//Hole Serverspezifische Daten aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."servers` WHERE server_id = '".$server_id."'");
	$get = mysql_fetch_array($qry);
	
	//Hole Anzahl der MAP Admins
	$admin = mysql_query("SELECT * FROM `".$database_prefix."user_servers` WHERE server_id = '".$server_id."'");
	$admins = mysql_num_rows($admin);

	//Hole Infos zum Serververantwortlichen
	$guarantor = user_info($get["guarantor"]);

	//Hole Slice Informationen vom Server
	$server = getServer($server_id);
	
	//Setze Staus für Server On/Off
	if ($server["online"] == "1") {
		$server["online"] = TRUE;
		$num_connected_users = count($server["connected_users"]);
		$num_registered_users = count($server["registered_users"]);
		$num_channels = count($server["channels"]);
		$version = $server["version"];
		$version_detailed = $server["version_detailed"];
		$server["status_icon"] = "<img src=\"../inc/tpl/".$tpldir."/images/server_status_online.png\" alt=\"\" border=\"0\">" . _server_show_status_server_on;
		$server["status_icon_small"]  = "<img src=\"../inc/tpl/".$tpldir."/images/server_status_online.png\" alt=\"\" border=\"0\">";
		} elseif ($server["online"] == "") {
			$server["online"] = FALSE;
			$num_connected_users = _slice_error_server_offline_small;
			$num_registered_users = _slice_error_server_offline_small;
			$num_channels = _slice_error_server_offline_small;
			$version = _slice_error_server_offline_small;
			$version_detailed = _slice_error_server_offline_small;
			$server["status_icon"] = "<img src=\"../inc/tpl/".$tpldir."/images/server_status_offline.png\" alt=\"\" border=\"0\">" . _server_show_status_server_off;
			$server["status_icon_small"] = "<img src=\"../inc/tpl/".$tpldir."/images/server_status_offline.png\" alt=\"\" border=\"0\">";
	}

	//Rechne aus, wie lange der server nun online ist
	if ($server["online"]) {
		$server["onlinetime"] = makeuserontime(time() - strtotime($get["started"]));
		$server["onlinedate"] = $get["started"];
		} else {
			$server["onlinetime"] = _server_is_offline;
			$server["onlinedate"] = _server_is_offline;
	}
	
	//Setze ip
	if (isset($server["ip"])) {
		$server["ip"] = $server["ip"];
		} else {
			$server["ip"] = "";
	}
	
	//Setze port
	if (isset($server["port"])) {
		$server["port"] = $server["port"];
		} else {
			$server["port"] = "";
	}
	
	//Setze users
	if (isset($server["users"])) {
		$server["users"] = $server["users"];
		} else {
			$server["users"] = "";
	}
	
	//Validiere Password
	if (!isset($server['serverpassword']) AND isset($server['password'])) {
		$server['serverpassword'] = $server['password'];
		} elseif (isset($server['serverpassword']) AND !isset($server['password'])) {
			$server['serverpassword'] = $server['serverpassword'];
			} elseif (isset($server['serverpassword']) AND isset($server['password'])) {
				$server['serverpassword'] = $server['serverpassword'];
				} else {
					$server['serverpassword'] = "";
	}	
	
	//Setze timeout
	if (isset($server["timeout"])) {
		$server["timeout"] = $server["timeout"];
		} else {
			$server["timeout"] = "";
	}
	
	//Setze channelname
	if (isset($server["channelname"])) {
		$server["channelname"] = $server["channelname"];
		} else {
			$server["channelname"] = "";
	}
	
	//Setze username
	if (isset($server["username"])) {
		$server["username"] = $server["username"];
		} else {
			$server["username"] = "";
	}
	
	//Setze defaultchannel
	if (isset($server["defaultchannel"])) {
		$server["defaultchannel"] = $server["defaultchannel"];
		} else {
			$server["defaultchannel"] = "";
	}
	
	//Setze registerHostname
	if (isset($server["registerHostname"])) {
		$server["registerHostname"] = $server["registerHostname"];
		} else {
			$server["registerHostname"] = "";
	}
	
	//Setze registerName
	if (isset($server["registerName"])) {
		$server["registerName"] = $server["registerName"];
		} else {
			$server["registerName"] = "";
	}
	
	//Setze registerPassword
	if (isset($server["registerPassword"])) {
		$server["registerPassword"] = $server["registerPassword"];
		} else {
			$server["registerPassword"] = "";
	}
	
	//Setze registerUrl
	if (isset($server["registerUrl"])) {
		$server["registerUrl"] = $server["registerUrl"];
		} else {
			$server["registerUrl"] = "";
	}
	
	//Setze registerLocation
	if (isset($server["registerLocation"])) {
		$server["registerLocation"] = $server["registerLocation"];
		} else {
			$server["registerLocation"] = "";
	}
	
	//Setze bandwidth
	if (isset($server["bandwidth"])) {
		$server["bandwidth"] = $server["bandwidth"];
		} else {
			$server["bandwidth"] = "";
	}
	
	//Setze imagemessagelength
	if (isset($server["imagemessagelength"])) {
		$server["imagemessagelength"] = $server["imagemessagelength"];
		} else {
			$server["imagemessagelength"] = "";
	}
	
	//Setze textmessagelength
	if (isset($server["textmessagelength"])) {
		$server["textmessagelength"] = $server["textmessagelength"];
		} else {
			$server["textmessagelength"] = "";
	}
	
	//Setze usersperchannel
	if (isset($server["usersperchannel"])) {
		$server["usersperchannel"] = $server["usersperchannel"];
		} else {
			$server["usersperchannel"] = "";
	}
	
	//Setze sslca
	if (isset($server["sslca"])) {
		$server["sslca"] = $server["sslca"];
		} else {
			$server["sslca"] = "";
	}
	
	//Setze sslcert
	if (isset($server["sslcert"])) {
		$server["sslcert"] = $server["sslcert"];
		} else {
			$server["sslcert"] = "";
	}
	
	//Setze sslkey
	if (isset($server["sslkey"])) {
		$server["sslkey"] = $server["sslkey"];
		} else {
			$server["sslkey"] = "";
	}
	
	//Setze sslpassphrase
	if (isset($server["sslpassphrase"])) {
		$server["sslpassphrase"] = $server["sslpassphrase"];
		} else {
			$server["sslpassphrase"] = "";
	}
	
	//Setze channelnestinglimit
	if (isset($server["channelnestinglimit"])) {
		$server["channelnestinglimit"] = $server["channelnestinglimit"];
		} else {
			$server["channelnestinglimit"] = "0";
	}
	
	//Setze opusthreshold
	if (isset($server["opusthreshold"])) {
		$server["opusthreshold"] = $server["opusthreshold"];
		} else {
			$server["opusthreshold"] = "0";
	}
	
	//Setze suggestpositional
	if (isset($server["suggestpositional"])) {
		$server["suggestpositional"] = $server["suggestpositional"];
		} else {
			$server["suggestpositional"] = "";
	}
	
	//Setze suggestpushtotalk
	if (isset($server["suggestpushtotalk"])) {
		$server["suggestpushtotalk"] = $server["suggestpushtotalk"];
		} else {
			$server["suggestpushtotalk"] = "";
	}
	
	//Setze suggestversion
	if (isset($server["suggestversion"])) {
		$server["suggestversion"] = $server["suggestversion"];
		} else {
			$server["suggestversion"] = "";
	}
	
	//Setzte Status TRUE/FALSE für "certrequired"
	if (isset($server["certrequired"])) {
		if ($server["certrequired"] == 'TRUE') {
			$server["certrequired"] = 'TRUE';
			} else {
				$server["certrequired"] = 'FALSE';
		}
		} else {
			$server["certrequired"] = 'FALSE';
	}
	
	//Setzte Status TRUE/FALSE für "obfuscate"
	if (isset($server["obfuscate"])) {
		if ($server["obfuscate"] == 'TRUE') {
			$server["obfuscate"] = 'TRUE';
			} else {
				$server["obfuscate"] = 'FALSE';
		}
		} else {
			$server["obfuscate"] = 'FALSE';
	}
	
	//Setzte Status TRUE/FALSE für "rememberchannel"
	if (isset($server["rememberchannel"])) {
		if ($server["rememberchannel"] == 'TRUE') {
			$server["rememberchannel"] = 'TRUE';
			} else {
				$server["rememberchannel"] = 'FALSE';
		}
		} else {
			$server["rememberchannel"] = 'FALSE';
	}
	
	//Setzte Status TRUE/FALSE für "allowhtml"
	if (isset($server["allowhtml"])) {
		if ($server["allowhtml"] == 'TRUE') {
			$server["allowhtml"] = 'TRUE';
			} else {
				$server["allowhtml"] = 'FALSE';
		}
		} else {
			$server["allowhtml"] = 'FALSE';
	}
	
	//Setzte Status TRUE/FALSE für "allowping"
	if (isset($server["allowping"])) {
		if ($server["allowping"] == 'TRUE') {
			$server["allowping"] = 'TRUE';
			} else {
				$server["allowping"] = 'FALSE';
		}
		} else {
			$server["allowping"] = 'FALSE';
	}
	
	//Setzte Status TRUE/FALSE für "sendversion"
	if (isset($server["sendversion"])) {
		if ($server["sendversion"] == 'TRUE') {
			$server["sendversion"] = 'TRUE';
			} else {
				$server["sendversion"] = 'FALSE';
		}
		} else {
			$server["sendversion"] = 'FALSE';
	}
	
	//Setzte Status TRUE/FALSE für "bonjour"
	if (isset($server["bonjour"])) {
		if ($server["bonjour"] == 'TRUE') {
			$server["bonjour"] = 'TRUE';
			} else {
				$server["bonjour"] = 'FALSE';
		}
		} else {
			$server["bonjour"] = 'FALSE';
	}
	
	//Setze welcometext
	if (isset($server["welcometext"])) {
		$server["welcometext"] = $server["welcometext"];
		} else {
			$server["welcometext"] = "";
	}
	
	//Setze connected_users
	if (isset($server["connected_users"])) {
		$server["connected_users"] = $server["connected_users"];
		} else {
			$server["connected_users"] = FALSE;
	}
	
	//Setze registered_users
	if (isset($server["registered_users"])) {
		$server["registered_users"] = $server["registered_users"];
		} else {
			$server["registered_users"] = FALSE;
	}
	
	//Setze channels
	if (isset($server["channels"])) {
		$server["channels"] = $server["channels"];
		} else {
			$server["channels"] = FALSE;
	}
	
	//Setze tree
	if (isset($server["tree"])) {
		$server["tree"] = $server["tree"];
		} else {
			$server["tree"] = FALSE;
	}
	
	//Setze compiled
	if (isset($server["compiled"])) {
		$server["compiled"] = $server["compiled"];
		} else {
			$server["compiled"] = "";
	}
	
	//Erstelle URL, um auf den Server zu verbinden
	$url = createServerURL($server["ip"], $server["port"], $version, FALSE, $server["online"], FALSE, FALSE);
	
	//Geneiere Zahlstatus
	$pay = getServerPayTime($server_id);
	
	//Schreibe Daten in globalen Array
	$_VSERVER = array("server_id" => $get["server_id"],          		 	  //Eindeutige ID des Servers
					  "name" => $get["name"],                     			  //Servername
	                  "discription" => $get["discription"],       			  //Serverbeschreibung
					  "guarantor" => $get["guarantor"],           			  //ID des Admins der Verantwortlicher des Servers ist
	                  "started" => $get["started"],               			  //Zeitstempel, wann der Server das letzte mal gestartet wurde
					  "starts" => $get["starts"],                 			  //Zähler, wie oft der Server bereits gestartet wurde.
					  "payed" => $pay["payed"],            	     			  //Zahlungsstatus, ob der Server gesperrt ist. Wenn gesperrt == FALSE
					  "payed_until" => $pay["payed_until"], 				  //Zeitstempel, wann der Server Offline gehen soll
					  "selectbox" => $pay["selectbox"], 	     			  //Selctcode für selectboxen 6fach, wann der Server Offline gehen soll
					  "zahlstatus" => $pay["status"], 			   			  //Zahlungsstatus, Ausgabe
					  "TimeToGo" => $pay["TimeToGo"],						  //Zeit die der Server noch bezahlt ist, bis er Offline geht					  
					  "linked_name" => $guarantor["linked_name"],			  //Ausgeschriebener Name des Admins, verlinkt
					  "email" => $guarantor["email"], 						  //E-Mail des Server-Verantwortlichen
	                  "admins" => $admins,									  //gebe Anzahl der MAP Admins auf diesem Server zurück
					  "online" => $server["online"],						  //TRUE/FALSE, ob Server Online
	                  "ip" => $server["ip"],								  //IP des Servers
					  "port" => $server["port"],							  //Port des Servers					  
					  "users" => $server["users"],             			  	  //max. User die auf den Server verbinden dürfen
					  "serverpassword" => $server["serverpassword"],		  //Serverpasswort dieses vServers
		              "timeout" => $server["timeout"],                        //Timeout des Servers in Sek.
					  "channelname" => $server["channelname"],                //Sonderzeichen in Channelname erlaubt...
					  "username" => $server["username"],                      //Sonderzeichen in Username erlaubt...
					  "defaultchannel" => $server["defaultchannel"],          //ID der Server-Defaultchannels
					  "registerHostname" => $server["registerHostname"],      //Hostname des Servers wie er in der Serverliste erscheint.
				      "registerName" => $server["registerName"],              //Name des Servers / Servername (erscheint auch so in der Serverliste)
					  "registerPassword" => $server["registerPassword"],      //Passwort für den Registrierungsvorgang
					  "registerUrl" => $server["registerUrl"],                //Diese URL wird aufgerufen, wenn der Benutzer in der Serverliste des Clients auf „Webseite öffnen“ klickt
					  "registerLocation" => $server["registerLocation"],	  //Definiert den Länderschlüssel, hier wird der Server in der Serverliste des Mumble-Client angezeigt. zB "de"
					  "bandwidth" => $server["bandwidth"],                    //Bandbeite des Servers je Client
					  "imagemessagelength" => $server["imagemessagelength"],  //Gesamtgröße der Nachrichten
					  "textmessagelength" => $server["textmessagelength"],    //Größe der Textnachrichten
					  "usersperchannel" => $server["usersperchannel"],        //max. User je Channel
					  "sslca" => $server['sslca'],							  //Pfad zum SSL Zertifikat
					  "sslcert" => $server['sslcert'],						  //SSL Zertifikat
					  "sslkey" => $server['sslkey'],						  //SSL Zertifikatsschlüssel
					  "sslpassphrase" => $server['sslpassphrase'],			  //SSL Passwort um das Zertifikat zu entschlüsseln
					  "channelnestinglimit" => $server['channelnestinglimit'],//Maximale Channeltiefe
					  "opusthreshold" => $server['opusthreshold'],			  //Wann wird OPUS erzwungen? in Prozent
					  "suggestpositional" => $server['suggestpositional'],    //Empfehle Positional Audio
					  "suggestpushtotalk" => $server['suggestpushtotalk'],	  //Empfehle Push to Talk
					  "suggestversion" => $server['suggestversion'],		  //Empfehle Update
					  "certrequired" => $server["certrequired"],              //zum connect Zert erfordert
					  "obfuscate" => $server["obfuscate"],                    //IPs verschleiern
					  "rememberchannel" => $server["rememberchannel"],        //Reconnect zum alten Channel
					  "allowhtml" => $server["allowhtml"],                    //HTML erlauben
					  "allowping" => $server["allowping"],                    //Ping anzeigen
					  "sendversion" => $server['sendversion'],                //Serverversion übermitteln
					  "bonjour" => $server['bonjour'],						  //Bonjour aktivieren/deaktivieren
					  "welcometext" => $server["welcometext"],                //Willkommennachricht auf dem Server
					  "conected_users" => $server["connected_users"],		  //gehe Array mit allen Usern zurück, die gerade Online sind
					  "registered_users" => $server["registered_users"],	  //Gebe Array mit allen Registrierten Usern zurück
					  "channels" => $server["channels"],					  //Liefert alle Channels des Servers als Array
					  "onlinetime" => $server["onlinetime"],				  //Gibt die Zeit zurück, seit wann der server läuft
					  "onlinedate" => $server["onlinedate"],				  //Gibt das Datum zurück, seit wann der server läuft
					  "status_icon" => $server["status_icon"],  			  //Serverstaus Icon, Ausgabe inkl. Text
					  "status_icon_small" => $server["status_icon_small"],	  //Serverstaus Icon, Ausgabe ohne Text
					  "num_conected_users" => $num_connected_users,			  //gehe Anzahl aller Usern zurück, die gerade Online sind
					  "num_registered_users" => $num_registered_users,		  //Gebe Anzahl aller Registrierten Usern zurück
					  "num_channels" => $num_channels,						  //Liefert Anzahl aller Channels des Servers
					  "tree" => $server["tree"],							  //Liefert den kompletten Channel Tree des Servers als Objekt
					  "version" => $version,								  //liefert die Version des Servers.
					  "version_detailed" => $version_detailed,		 	  	  //liefert die detailierte Version des Servers.
					  "compiled" => substr($server["compiled"], 9),			  //liefert das Datum an dem der Murmur Server Kompiliert wurde				  
					  "url" => $url,										  //Gebe die Server-Url zurück, zu der verbunden werden soll
					  );
					  
	return $_VSERVER;
	
}

//definiere Port Wert
function definePortValue($server_id = FALSE) {
	
	if ($server_id) {
		//Wenn serverID übergeben, gebe Index der ID mit Port aus
		$server_ids = getServers();
		$counter = array_search($server_id, $server_ids);
		$port_value = "64738" + $counter;
		} else {
			//Wenn keine ServerID übergeben wurde gebe nächsten freien Default Port aus (ohne Check ob real schon vergeben)
			$server_ids = getServers();
			$count = count($server_ids);
			foreach ($server_ids as $server_id) {
				$server = server_info($server_id);
				$port_value = $server['port'] + "1";
				if  ($port_value == "1") {
					$port_value = "64738" + $count;
				}
			}
	}
	
	return $port_value;
	
}

/*
 * Prüft ob der eigegebene Port gültig ist
 * 
 */
function checkPortValue($port, $origin_server_id = FALSE) {
	
	//Setze PortCheck
	$portCheck = FALSE;
	$ServerCheck = FALSE;
	
	//Checke on der eingetragene Wert numerisch ist
	if (is_numeric($port)) {
		//Checke ob im angegebenen Wertebereich
		if ($port >= '1024' AND $port <= '65535') {
			//Checke ob Port nicht bereits verwendet wird
			$server_ids = getServers();
			foreach ($server_ids as $compare_server_id) {
				$server = server_info($compare_server_id);
				if (($server['port'] == $port) AND ($origin_server_id != $compare_server_id)) {
					$ServerCheck = TRUE;
					break;
				}
			}
			if ($ServerCheck == FALSE) {
				$portCheck = TRUE;
			} 
		}
	}
	
	return $portCheck;
	
}

//Definiere Host des Servers
function defineHostValue() {
	
	global $database_prefix;
	
	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '34'");
	$host = mysql_fetch_array($qry);
	if (isset($host[2]) && $host[2] != "") {
		$host = $host[2];
		} else {
			$host = $_SERVER['SERVER_ADDR'];
	}
	
	return $host;
			
}

//Update Startzeit des virtuellen Servers
function updateServerStartUp($server_id) {
	
	global $database_prefix;
	
	//Hole Infos
	$aktdate = strtotime(date("Y-m-d H:i:s"));
	
	//Update das Datum
	mysql_query("UPDATE `".$database_prefix."servers` SET started = '$aktdate' WHERE server_id = '$server_id'");

}

//Checke ob noch andere virtuelle Server vorhanden sind, wenn ja erstelle in MAP DB
function defineUnsetVirtualServers() {
	
	global $database_prefix;
	global $aktdate;
	
	//Hole vServer aus Slice Inerface
	$servers = getAllServers();
	
	//Arbeite Server ab
	if ($servers != FALSE) {
		foreach ($servers as $server_id) {
					
			//Hole Server mit ID aus DB
			$qry = mysql_query("SELECT * FROM `".$database_prefix."servers` WHERE server_id = '".$server_id."'");
			$get = mysql_fetch_array($qry);
	
			if ($get == FALSE) {
				
				//Speichere MAP interne Daten zum Server
			   	$saveSRV = mysql_query("INSERT INTO `".$database_prefix."servers` (`server_id`, `name`, `discription`, `guarantor`, `started`, `payed_until`, `starts`) VALUES ('".$server_id."', 'Server ".$server_id."', 'This Server stands in Default settings. Please edit! This virtual server was not created by MAP, but still exists in Murmur Server.', '".$_COOKIE['user_id']."', '".$aktdate."', '2020-12-31 23:59:59', '0')");
			   	
		    	//Erstelle Code für externen Channelviewer in DB
		        $saftey_code = pw_gen($a = '5', $b = '5', $num = '5', $spec = '0');
		        $saveCV = mysql_query("INSERT INTO `".$database_prefix."channelviewer` (`server_id`, `colour`, `font_colour`, `width`, `height`, `external_on`, `safety_code`) VALUES ('$server_id', 'FFFFFF', '000000', '200', '500', '1', '".$saftey_code."')");
	
		        //Setze den Host des Servers, da dieser die einzige Conf ist, die später im MAp nicht mehr geändert wrden kann. Alle anderen confs können über server editieren geändert werden.
				setServer($server_id, $key = "host", defineHostValue());
			}
		}
	}
}
defineUnsetVirtualServers();

//Hole für jeden Server jeweils die Daten des letzten Server starts.
function validateLastServerStartUp() {

	global $database_prefix;
	
	//Hole Alle Server
	$servers = getServers();
	foreach ($servers as $server_id) {
		
		//Hole Server-Infos
		$server = server_info($server_id);
		$server["starts"]++;
		
		//Setze Zeitstempel
		$uptime = getVirtualServerUptime($server_id);
		$currentDate = strtotime(date("Y-m-d H:i:s"));
		$lastStartUp = strtotime($server["onlinedate"]);
		
		//Hole Uptime des Servers in Sekunden
		if ($uptime != FALSE) {
			$realUptime = $currentDate - $uptime;
			} else {
				$realUptime = $lastStartUp;
		}
		$uptimeMin = $lastStartUp - 1;
		$uptimeMax = $lastStartUp + 1;
		
		//Setze Timestring für RealUptime
		$realUptimeString = date("Y-m-d H:i:s", $realUptime);

		//Vergleiche Zeiten
		if (($realUptime <= $uptimeMin) OR ($realUptime >= $uptimeMax) ) {
			if ($realUptime < $uptimeMin) {
				mysql_query("UPDATE `".$database_prefix."servers` SET started = '$realUptimeString' WHERE server_id = '$server_id'");
			}
			if ($realUptime > $uptimeMax) {
				mysql_query("UPDATE `".$database_prefix."servers` SET started = '$realUptimeString' WHERE server_id = '$server_id'");
				mysql_query("UPDATE `".$database_prefix."servers` SET starts = '$server[starts]' WHERE server_id = '$server_id'");
			}
		}
	}
}
validateLastServerStartUp();

//Stoppe Server, wenn dieser nicht bezahlt ist
function setServerPayStop() {
	
	global $database_prefix;
	
	$qry = mysql_query("SELECT * FROM `".$database_prefix."servers` ORDER BY server_id ASC");
	while ($get = mysql_fetch_array($qry)) {
		
		//Hole Zahlungsinformationen
		$pay = getServerPayTime($get["server_id"]);
		$server = server_info($get["server_id"]);
		
		//Wenn Server nicht bezahlt, stoppen
		if ($pay["payed"] == FALSE AND $server["online"] == TRUE) {
			setServerStop($get["server_id"]);
			setServer($get["server_id"], $key = "boot", 'FALSE');
		}
		
	}
	
}
setServerPayStop();

//Gibt die Daten des Servers mit $port zurück
//$port => bsp.: "64738"
function get_server_by_port($port) {
	
}

//Gibt die Daten der Server zurück, dessen Admin $guarantor ist
//$guarantor => bsp.: "1" ($user_id | Admin)
function get_server_by_guarantor($guarantor) {
	
}

//Gibt Array mit $server_ids zurück, welche gerade Online sind
function get_booted_servers() {
	
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>