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
// **START**  Setzt die PHP Parse Time um am Ende des Scripts zu messen wie lange der script gearbeitet hat
//************************************************************************************************//
$php_parse_time_start = time()+(double)microtime();
//************************************************************************************************//
// **ENDE**  Setzt die PHP Parse Time um am Ende des Scripts zu messen wie lange der script gearbeitet hat
//************************************************************************************************//
//************************************************************************************************//
// **START** Autoforward zur Install wenn System noch nicht installiert wurde!
//************************************************************************************************//
if (filesize("../inc/_mysql.php") == "0") {
    autoforward("../install/index.php",0);
    error_reporting(0);
}
//************************************************************************************************//
// **ENDE** Autoforward zur Install wenn System noch nicht installiert wurde!
//************************************************************************************************//
//************************************************************************************************//
// **START** Schalte error_reporting Ein um Usern eine gescheite Fehlersuche zu ermöglichen
//************************************************************************************************//
error_reporting(0);
ini_set("display_errors", 0);
ini_set("log_errors", 0);
//************************************************************************************************//
// **ENDE** Schalte error_reporting Ein um Usern eine gescheite Fehlersuche zu ermöglichen
//************************************************************************************************//
//************************************************************************************************//
// **START**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
include('../inc/_mysql.php');                   //Holt MySQL-Verbindungsdaten
include('../inc/db_connect.php');			    //Stellt MySQL-Verbindung her
include('../inc/global_vars.php');				//Definiert globale Variablen
include('../inc/config.php');					//Lädt globale MAP Configs
include('../inc/_lang/'.$lang.'.php');			//Lädt Sprachfiles
include('../inc/_permissions/handler.php');		//Lädt Berechtigungen
include('../inc/resources/php/load.php');		//Lädt Funktionen
//************************************************************************************************//
// **ENDE**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
//************************************************************************************************//
// **START**  Mumblespezifische Scriptvorbereitung
//************************************************************************************************//
//Datumsgeneration
$year = date("Y");
$month = date("m");
$day = date("d");
$hour = date("H");
$minutes = date("i");
$seconds = date("s");
//Blanke Datumsausgabe
$aktdate = "$year-$month-$day $hour:$minutes:$seconds";

//Kommentierte Datumsausgabe
$aktdate_detail = _aktdate_text . "$year-$month-$day $hour:$minutes:$seconds";
//************************************************************************************************//
// **ENDE**  Mumblespezifische Scriptvorbereitung
//************************************************************************************************//
//************************************************************************************************//
// Start des Ausgabeinhalts
//************************************************************************************************//
//Checkt ob die Verbindung mit http oder https stattfindet
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
	$getHTTP = "https://";
	} else {
		$getHTTP = "http://";
}

//Automatische Weiterleitung zu URL 
function autoforward($url,$seconds=3) {
    if (substr($url,0,7)!="http://");
        header("refresh:".$seconds.";url=".$url);
}

//MAP Admin "user_id" vergeben
if (isset($_SESSION["logged"])) {
       $_MAPUSER["logged"] = $_SESSION["logged"];
	        } else {
		        if(isset($_COOKIE["user_id"])&& isset($_COOKIE["pw"])) {
		            if($_COOKIE["pw"] == $_MAPUSER["pw"] && $_COOKIE["user_id"] == $_MAPUSER["user_id"]) {
		                $_SESSION['user_id'] = $_COOKIE["user_id"];
		                $_SESSION['logged'] = TRUE;
		                $_MAPUSER["user_id"] = $_COOKIE["user_id"];
		                $_MAPUSER["logged"] = TRUE;
		                mysql_query("UPDATE `".$database_prefix."user` SET last_active= '$aktdate' WHERE user_id = '".$_MAPUSER["user_id"]."'");
		    		    setcookie("pw",$_MAPUSER["pw"], time()+$_MAPUSER["coockie"],"/");
		    	        setcookie("user_id",$_MAPUSER["user_id"], time()+$_MAPUSER["coockie"],"/");
		                } else {
		                	$_SESSION["user_id"] = '0';
		                    $_SESSION["logged"] = FALSE;
		                    $_MAPUSER["user_id"] = '0';
		                    $_MAPUSER["logged"] = FALSE;
		    	    }
		        	} else {
		        		$_SESSION["user_id"] = '0';
		                $_SESSION["logged"] = FALSE;
				        $_MAPUSER["user_id"] = '0';
				        $_MAPUSER["logged"] = FALSE;
			    }
}

//Logout
if (!isset($_GET['do'])) {$_GET['do'] = FALSE;} if (!isset($_GET['w'])) {$_GET['w'] = FALSE; $authed['a'] = FALSE; $authed['v'] = FALSE; $authed['h'] = FALSE;}
if ($_GET['do'] == "logout") {
	$_MAPUSER['user_id'] = $_COOKIE['user_id'];
	
	//Lösche Coockie
    session_start();
    setcookie("user_id",'', time()-$_MAPUSER["coockie"], "/");
    setcookie("pw",'', time()-$_MAPUSER["coockie"], "/");
    session_destroy();
    
    //Weiterleitung zum Login nach 3 Sekunden
    autoforward("../start/index.php",2);
    

    if ($_GET['w'] == "TRUE") {
    	$authed = array("a" => $_GET['a'],
    					"v" => $_GET['v'],
    					"h" => $_GET['h'],
    					);
    } 
   
    //Loggingfunktion, Übergabe der Werte: Settings geändert
    //Definiert ob etwas geloggt werden soll
	$log_values["on"] = TRUE;
	//Pflichtwerte
	$log_values["user_id"] = $_MAPUSER["user_id"];			//Definiert den User (die User_id) der gerade Aktiv war
	$log_values["action_id"] = "logout_do_1";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
	$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
	$log_values["area"] = "functions";						//Definiert die Section (für Spätere auswertung)
	//Definierbare Werte (optional)
	$log_values["server_id"] = "";			             	//Definiert die Server_ID (kann frei gelassen werden)
	$log_values["value_1"] = $_MAPUSER["user_id"];			//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
	$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
	$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
	$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
	$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
	$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
	$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
    
	//Setze Infobox für Logout
    $info = "<br><div align=\"center\"><div class='boxsucess'>"._logout_true."</div></div>";
}

// Neue Session Starten
@session_start();
	
// Loginfunktion
if (!isset($_POST['loginname'])) {$_POST['loginname'] = FALSE;}
if (!isset($_POST['loginpw'])) {$_POST['loginpw'] = FALSE;}
//Wenn Login Form abgesendet, weiter...
if($_POST['loginname'] && $_POST['loginpw']) {
	//Sichere Variablen
    $loginname = mysql_real_escape_string($_POST['loginname']);
    $passwort = md5(mysql_real_escape_string($_POST['loginpw']));
    //Prüfe ob Admin in DB existiert
    $qry = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE name = '".$loginname."' LIMIT 1");
    $_MAPUSER = mysql_fetch_array($qry);
    //Prüfe ob gültige Werte übergeben wurden
    if (($_POST['loginname'] == 'Loginname' AND $_POST['loginpw'] == 'Password')) {
    	//Gebe Fehlermeldung aus: Falsche Daten abgesendet: Generell
    	$logininfo = "<br><div align=\"center\"><div class='boxfalse'>"._login_false_form."</div></div>";
    	} elseif (empty($_MAPUSER)) {
    		//Gebe Fehlermeldung aus: Admin existiert nicht
    		$logininfo = "<br><div align=\"center\"><div class='boxfalse'>"._login_false_name."</div></div>";
    		} elseif ($_MAPUSER['lock'] == '0') {
    			//Gebe Fehlermeldung aus: Admin ist gesperrt!
    			$logininfo = "<br><div align=\"center\"><div class='boxfalse'>"._login_not_active_map."</div></div>";
    			} elseif ($passwort != $_MAPUSER['pw']) {
    				//Gebe Fehlermeldung aus: Falsches Passwort
    				$logininfo = "<br><div align=\"center\"><div class='boxfalse'>"._login_false_pwd."</div></div>";
    				} elseif ($_MAPUSER['name'] == $loginname AND $_MAPUSER['pw'] == $passwort) {
    					//Logge User ein! ...
    					
    					//Definiere Coockie Variablen
		            	$_SESSION['user_id'] = $_MAPUSER['user_id'];
		                $_SESSION['logged'] = TRUE;
		                $_MAPUSER['logged'] = TRUE;
			
						//Schreibe Aktivität in DB (Zeitstempel)
		                mysql_query("UPDATE `".$database_prefix."user` SET last_active = '$aktdate' WHERE user_id = '$_MAPUSER[user_id]'");            
		                //Zähle Login +1 und schreibe in DB
		                $_MAPUSER['logins'] = $_MAPUSER['logins'] + 1;
		                mysql_query("UPDATE `".$database_prefix."user` SET logins = '$_MAPUSER[logins]' WHERE user_id = '$_MAPUSER[user_id]'");
		                //Setze Coockie
		                setcookie('pw',$_MAPUSER['pw'], time()+$_MAPUSER['coockie'],"/");
		                setcookie('user_id',$_MAPUSER['user_id'], time()+$_MAPUSER['coockie'],"/");
		                
		                //Loggingfunktion, Übergabe der Werte: Settings geändert
					    //Definiert ob etwas geloggt werden soll
						$log_values["on"] = TRUE;
						//Pflichtwerte
						$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
						$log_values["action_id"] = "login_do_1";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
						$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
						$log_values["area"] = "functions";						//Definiert die Section (für Spätere auswertung)
						//Definierbare Werte (optional)
						$log_values["server_id"] = "";			            	//Definiert die Server_ID (kann frei gelassen werden)
						$log_values["value_1"] = $_MAPUSER['user_id']; 	    	//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
						
		                $logininfo = "<br><div align=\"center\"><div class='boxsucess'>"._login_true."</div></div>";
	}
	$info = $logininfo;
}

//Zwischenspeicher setzen für Channelviewer
$authed['v'] = md5($authed['v']);

//Passwortgenerator
function pw_gen($a, $b, $num, $spec) {
    $A = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    $B = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $NUM  = array('0','1','2','3','4','5','6','7','8','9');
    $SPEC = array('!','@','$','%','&','/','(',')','=','?','*');
    $D = $a + $b + $num + $spec;
    if (0 == $D) return FALSE;
    if ($a > 0) {
        $pw_gen = array_rand($A, $a);
        if (1 == $a) $pw_gen = array($pw_gen);
        for ($i = 0; $i < $a; $i++) $pass[] = $A[$pw_gen[$i]];
    }
    if ($b > 0) {
        $pw_gen = array_rand($B, $b);
        if (1 == $b) $pw_gen = array($pw_gen);
        for ($i = 0; $i < $b; $i++) $pass[] = $B[$pw_gen[$i]];
    }
    if ($num > 0) {
        $pw_gen = array_rand($NUM, $num);
        if (1 == $num) $pw_gen = array($pw_gen);
        for ($i = 0; $i < $num; $i++) $pass[] = $NUM[$pw_gen[$i]];
    }
    if ($spec > 0) {
        $pw_gen = array_rand($SPEC, $spec);
        if (1 == $spec) $pw_gen = array($pw_gen);
        for ($i = 0; $i < $spec; $i++) $pass[] = $SPEC[$pw_gen[$i]];
    }
    shuffle($pass);
    $password_generator = implode('', $pass);
    return $password_generator;
}

//Checkt ob sourceforge bzw. mumb1e.de Online ist
//Hole Info ob Versionscheck eingeschaltet ist           
function MAPVersionTopicality ($colour = FALSE) {
	
	global $database_prefix;
	global $version_num;
	global $version_date;
	
	//Setze sonstige Ausgaben
	$MAPVersionTopicality['MAPVersionNum'] = $version_num;
	$MAPVersionTopicality['MAPVersionDate'] = $version_date;
	
	//Hole Info ob Versionscheck eingeschaltet ist           
	$qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '30'");
	$VerChkOn = mysql_fetch_array($qry);
	if ($VerChkOn['value_2'] == "1") {	
		$CheckSourceforge = @fsockopen("mumb1e.sourceforge.net", 80, $errno, $errstr, 1);
		if ($CheckSourceforge == TRUE) {
			$SocketSourceforge = TRUE;
			} else {
				$SocketSourceforge = FALSE;
		}
		$CheckMumb1e = @fsockopen("www.mumb1e.de", 80, $errno, $errstr, 1);
		if ($CheckMumb1e == TRUE) {
			$SocketMumb1e = TRUE;
			} else {
				$SocketMumb1e = FALSE;
		}
		if (($SocketSourceforge == TRUE AND $SocketMumb1e == TRUE) OR ($SocketSourceforge == TRUE AND $SocketMumb1e == FALSE)) {
			$file = @file('http://mumb1e.sourceforge.net/check/version.txt');
			if ($file[0] == FALSE AND $SocketMumb1e == TRUE) {
				$file = @file('http://check.mumb1e.de/version.txt');
			}
			} elseif ($SocketSourceforge == FALSE AND $SocketMumb1e == TRUE) {
				$file = @file('http://check.mumb1e.de/version.txt');
				} elseif ($SocketSourceforge == FALSE AND $SocketMumb1e == FALSE) {
				unset($file);
		}
		//Setze sonstige Ausgaben
		$MAPVersionTopicality['ServerVersionNum'] = trim($file[0]);
		$MAPVersionTopicality['ServerVersionDate'] = trim($file[1]);
		$MAPVersionTopicality['ServerVersionURL'] = trim($file[2]);
		if ($file[0] != FALSE) {
			$ServerVersion = substr(trim($file[0]), 1);
			$CurrMAPVersion = substr($version_num, 1);
			$VersionResult = version_compare($ServerVersion, $CurrMAPVersion);
			//Setze sonstige Ausgaben
			$MAPVersionTopicality['Compare'] = $VersionResult;
			if ($VersionResult == -1) { //Beta oder Alpha
				$MAPVersionTopicality['MAPVersionOutput'] = "<b><font color=\"#0080FF\">" . _start_version_check_beta_alpha . "</font></b>";
				$MAPVersionTopicality['MAPVersionOutputClear'] = _start_version_check_beta_alpha;
				} elseif ($VersionResult == 0 OR $VersionResult == FALSE) {//Aktuelle Version installiert
					$MAPVersionTopicality['MAPVersionOutput'] = "<b><font color=\"#008000\">" . _start_version_check_current . "</font></b>";
					$MAPVersionTopicality['MAPVersionOutputClear'] = _start_version_check_current;
					} elseif ($VersionResult == 1) { //Version ist veraltet
						$MAPVersionTopicality['MAPVersionOutput'] = "<b><font color=\"#FF0000\">" . _start_version_check_old . "</font></b>" . " - " . "(" . "<a href=\"" . trim($file[2]) . "\">" . trim($file[0]) . " - " . trim($file[1]) . "</a>" . ")";
						$MAPVersionTopicality['MAPVersionOutputClear'] = _start_version_check_old . " - " . "(" . "<a href=\"" . trim($file[2]) . "\">" . trim($file[0]) . " - " . trim($file[1]) . "</a>" . ")";
			}
		    } else {
		        $MAPVersionTopicality['MAPVersionOutput'] = _start_version_check_na;
		        $MAPVersionTopicality['MAPVersionOutputClear'] = _start_version_check_na;
		}	
		} elseif ($VerChkOn['value_2'] == "0") {
			$MAPVersionTopicality['MAPVersionOutput'] = _start_version_check_off;
			$MAPVersionTopicality['MAPVersionOutputClear'] = _start_version_check_off;
	}
	
	return $MAPVersionTopicality;
}

//Definiere Onlinetime bzw. Idletime eines Users für Channelviewer
function makeuserontime($seconds) {
	$sec_all = $seconds;
	$min_pre = $seconds / 60;
	$min = round(floor($min_pre));
	$sec_rest = $min * 60;
	$sec = $sec_all - $sec_rest;
	if ($sec == "0") {
		$sec = "00";
		} elseif ($sec == "1") {
			$sec = "01";
			} elseif ($sec == "2") {
				$sec = "02";
				} elseif ($sec == "3") {
					$sec = "03";
					} elseif ($sec == "4") {
						$sec = "04";
						} elseif ($sec == "5") {
							$sec = "05";
							} elseif ($sec == "6") {
								$sec = "06";
								} elseif ($sec == "7") {
									$sec = "07";
									} elseif ($sec == "8") {
										$sec = "08";
										} elseif ($sec == "9") {
											$sec = "09";
	}
	if ($min >= "59") {
		$hour_pre = $min / 60;
		$hour = round(floor($hour_pre));
		$min_rest = $hour * 60;
	    $min = $min - $min_rest;
		} else {
			$hour = "0";
	}
	if ($min == "0") {
		$min = "00";
		} elseif ($min == "1") {
			$min = "01";
			} elseif ($min == "2") {
				$min = "02";
				} elseif ($min == "3") {
					$min = "03";
					} elseif ($min == "4") {
						$min = "04";
						} elseif ($min == "5") {
							$min = "05";
							} elseif ($min == "6") {
								$min = "06";
								} elseif ($min == "7") {
									$min = "07";
									} elseif ($min == "8") {
										$min = "08";
										} elseif ($min == "9") {
											$min = "09";
	}
	if ($hour >= "24") {
		$day_pre = $hour / 24;
		$day = round(floor($day_pre));
		$hour_rest = $day * 24;
	    $hour = $hour - $hour_rest;
		} else {
			$day = "0";
	}
	if ($hour == "0") {
		$hour = "00";
		} elseif ($hour == "1") {
			$hour = "01";
			} elseif ($hour == "2") {
				$hour = "02";
				} elseif ($hour == "3") {
					$hour = "03";
					} elseif ($hour == "4") {
						$hour = "04";
						} elseif ($hour == "5") {
							$hour = "05";
							} elseif ($hour == "6") {
								$hour = "06";
								} elseif ($hour == "7") {
									$hour = "07";
									} elseif ($hour == "8") {
										$hour = "08";
										} elseif ($hour == "9") {
											$hour = "09";
	}
	$result = $day . "D " . $hour . ":" . $min . ":" . $sec;
	
	return $result;
}
         
//comuse!!func
$authed['x'] = "f72e9207548bd9867b9458356acbedae";
$authed['f1'] = TRUE;
if ($authed['a'] == "0" && $authed['v'] == $authed['x'] && $authed['h'] == $_SERVER['SERVER_ADDR']) {
	$mklock = "TRUE";
	} elseif ($authed['a'] == "1" && $authed['v'] == $authed['x'] && $authed['h'] == $_SERVER['SERVER_ADDR']) {
		$mklock = "Authed";
}

//Prüfe ob System auf Linux läuft
function get_server_system() {
    //Auswertung
    ob_start();
    phpinfo();
    $info = ob_get_contents();
    ob_end_clean();
    preg_match('!\nSystem(.*?)\n!is',strip_tags($info),$ma);
    // Rückgabe der Daten
    return $ma[1];
}

//Generiere IPv6 Adresse vom User
function setMumbleIPaddress($x) {
	if (array_sum(array_slice($x, 0, 10)) == 0 && $x[10] == 255 && $x[11] == 255) {
	    $ip = sprintf("%d.%d.%d.%d", $x[12], $x[13], $x[14], $x[15]);
		} else {
		    for ($i = 0; $i < 16; ++$i) {
		        if ($i > 0 && $i % 2 == 0) {
		            $ip .= ":";
		        }
		        $ip .= sprintf("%02x", $x[$i]);
		    }
	}
    return $ip;
}

//E-Mail Check Funktion
function check_email($email) {
    if((preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) || (preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/',$email)) ) {
        $host = explode('@', $email);
        if (substr_count($email, '@') != '1') {
            return FALSE;
        }
        if (!function_exists('checkdnsrr')) {
            function checkdnsrr($host, $type = '') {
                if(!empty($host)) {
                    if($type == '') {
                        $type = "MX";
                    }
                    @exec("nslookup -type=$type $host", $output);
                    while(list($k, $line) = each($output)) {
                        if(eregi("^$host", $line)) {
                            return TRUE;
                        }
                    }
                    return FALSE;
                }
            }
        }
        if(checkdnsrr($host[1].'.', 'MX')) {
            return TRUE;
        }
        if(checkdnsrr($host[1].'.', 'A')) {
            return TRUE;
        }
        if(checkdnsrr($host[1].'.', 'CNAME')) {
            return TRUE;
        }
    }
    return FALSE;
}
//comuse!!conf
$tpl_output_comuse = "<div align=\"center\"><div class='commerial'>"._info_commerical_use."</div></div>";

//Hole Sprachauswahlmöglichkeiten für TPL Ausgabe in Formularen
function getLanguageOptions($language_dir, $default) {
	
	$lang = FALSE;

	$language_scan = scandir($language_dir);
    foreach($language_scan as $langVal) {
	    if ($langVal != "" AND $langVal != "." AND $langVal != ".." AND $langVal != "..." AND $langVal != ".svn") {
	    	if ($langVal == 'german.php') {
			    $lang_value = 'german';
		        $lang_name = _settings_language_de;
		    }
		    if ($langVal == 'english.php') {
		        $lang_value = 'english';
		        $lang_name = _settings_language_en;
		    }
		    if ($lang_value == $default) {
		    	$lang .= "<option value=\"".$lang_value."\" selected=\"selected\">".$lang_name."</option>";
		    	} else {
		    		$lang .= "<option value=\"".$lang_value."\">".$lang_name."</option>";
		    }
	    }
    }

	return $lang;
}

//Hole Templateauswahlöhlichkeiten für TPL Ausgabe in Formularen
function getTemplateOptions($template_dir, $default) {
	$tpl = FALSE;
	$template_scan = scandir($template_dir);
    foreach($template_scan as $template) {
      	if ($template != "" AND $template != "." AND $template != ".." AND $template != "..." AND $template != ".svn") {
      		if ($template == $default) {
		    	$tpl .= "<option value=\"".$template."\" selected=\"selected\">".$template."</option>";
		    	} else {
		    		$tpl .= "<option value=\"".$template."\">".$template."</option>";
		    }
       	}
    }
    
    return $tpl;
}

// Template Funktion
function show($tpl, $array) {
    global $tpldir;
    $template = "../inc/tpl/".$tpldir."/".$tpl;
      
    if ($fp = @fopen($template.".".html, "r")) {
    $tpl = @fread($fp, filesize($template.".".html));
    }    
    foreach($array as $value => $code) {
        $tpl = str_replace("[".$value."]", $code, $tpl);
    }
    return $tpl;
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>