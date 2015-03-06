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
// **START**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
include('../install/inc/config.php');                   //Holt wichtige Basisdaten
include('../inc/_lang/'.$lang.'.php');
//************************************************************************************************//
// **ENDE**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
//************************************************************************************************//
// Start des Ausgabeinhalts
//************************************************************************************************//
//Definiere Globale Variablen
$seitentitel = FALSE;

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

//Automatische Weiterleitung zu URL 
function autoforward($url,$seconds=3) {
    if (substr($url,0,7)!="http://");
        header("refresh:".$seconds.";url=".$url);
}

//Checkt ob die Verbindung mit http oder https stattfindet
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
	$getHTTP = "https://";
	} else {
		$getHTTP = "http://";
}
  
// Template Funktion
function show($tpl, $array) {
    global $tpldir;
    $template = "../inc/tpl/".$tpldir."/".$tpl;
      
    if ($fp = @fopen($template.".".'html', "r")) {
    	$tpl = @fread($fp, filesize($template.".".'html'));
    }    
    foreach($array as $value => $code) {
        $tpl = str_replace("[".$value."]", $code, $tpl);
    }
    return $tpl;
}

// Generate Database SQL Dump
function generateDatabaseDump($array, $action) {
    $SQLFile = "../install/sql/".$action.".sql";
      
    if ($fp = @fopen($SQLFile, "r")) {
    	$SQL = @fread($fp, filesize($SQLFile));
    }    
    foreach($array as $value => $code) {
        $SQL = str_replace("[".$value."]", $code, $SQL);
    }
    return $SQL;
}

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

//Definere System
function get_server_system() {
	
	//Hole Daten
	ob_start();
	phpinfo();
	$info = ob_get_contents();
	ob_end_clean();
	preg_match('!\nSystem(.*?)\n!is',strip_tags($info),$ma);
	
	//Auswerten der Daten
	$os = strtolower($ma[1]);
	if (strstr($os, 'linux')) {
		$return = "linux";
		} elseif (strstr($os, 'windows')) {
			$return = "windows";
	}
	
	return $return;
}
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>