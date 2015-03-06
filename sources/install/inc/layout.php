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
// Start des Ausgabeinhaltss
//************************************************************************************************//
if (!isset($content_headers)) {
	$content_headers['head_on'] = FALSE;	
	$content_headers['head_type'] = FALSE;
	$content_headers['navi_on'] = FALSE;	
	$content_headers['navi_type'] = FALSE;
}
if (!isset($logged)) {
	$logged = FALSE;
}
if (!isset($_Login)) {
	$_Login = FALSE;
}
if (!isset($_RequestForgot)) {
	$_RequestForgot = FALSE;
}
if (!isset($_ContentHead)) {
	$_ContentHead = FALSE;
}
if (!isset($_ContentNavi)) {
	$_ContentNavi = FALSE;
}
if (!isset($info)) {
	$info = FALSE;
}
if (!isset($perm_error)) {
	$perm_error = FALSE;
}

// Content - Head
if ($content_headers['head_on'] == TRUE) {
	// Lädt HTML-Datei
	if ($content_headers['head_type'] == "default") {
		$content_head = show("content/head", array("head" => $content_headers['head_value'],
			                                     ));
	}
}

// Content - Navi
if ($content_headers['navi_on'] == TRUE) {
	// Lädt HTML-Datei
	if ($content_headers['navi_type'] == "default") {
		$content_navi = show("content/navi_default", array("navi" => "",
		                                                  ));
	}
}

//Berechnet die Parse Zeit des Scripts und gibt in Footer aus.
$php_parse_time_finish = time()+(double)microtime();
$php_parse_time = round($php_parse_time_finish-$php_parse_time_start,5);

// Footer
// Lädt HTML-Datei
$footer = show("footer/footer", array("copyrigth" => $copyrigth,
                                      "logged" => $logged,
                                      "akt_date" => $aktdate_detail,
                                      "version_num" => $version_num,
                                      "parse_time" => $php_parse_time,
                                      "version_date" => $version_date
                                      ));

// Header
// Lädt HTML-Datei
$header = show("header/header", array());

// Anzeige des gesammten Templates
echo show("/index", array("seitentitel" => $seitentitel,
    					  "javascript" => _error_javascript_not_available,
                          "header" => $header,
                          "main_navi" => "",
                          "login" => $_Login,
                          "request_forgot" => $_RequestForgot,
    					  "content_head" => $_ContentHead,
    					  "content_navi" => $_ContentNavi,
                          "info" => $info,
    					  "perm_error" => $perm_error,
    					  "slice" => "",
                          "index" => $index,
                          "footer" => $footer
                          ));
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>