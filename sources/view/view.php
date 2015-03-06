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
// **START**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
// Definiert Ort des Scripts
$dir = "view";
// Lädt Globale Funktionen
include("../inc/functions.php");
//************************************************************************************************//
// **ENDE**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
//************************************************************************************************//
// Start des Ausgabeinhalts
//************************************************************************************************//

//Übernehme Daten
if (isset($_GET['id'])) {
	$server_id = $_GET['id'];
	} else {
		$server_id = FALSE;
}
if (isset($_GET['sc'])) {
	$safety_code = $_GET['sc'];
	} else {
		$safety_code = FALSE;
}
if (isset($_GET['bgcolour'])) {
	$background = $_GET['bgcolour'];
	} else {
		$background = FALSE;
}
if (isset($_GET['fontcolour'])) {
	$fontcolour = $_GET['fontcolour'];
	} else {
		$fontcolour = FALSE;
}
if (isset($_GET['width'])) {
	$width = $_GET['width'];
	} else {
		$width = FALSE;
}
if (isset($_GET['height'])) {
	$height = $_GET['height'];
	} else {
		$height = FALSE;
}

//Hole Server Daten
$ServerData = server_info($server_id);

// Hole Channelviewer Settings des Servers
$qry = mysql_query("SELECT * FROM `".$database_prefix."channelviewer` WHERE server_id = '".$server_id."'");
$ServerViewSettings = mysql_fetch_array($qry);

// Definition Seitentitel
$seitentitel = _pagetitel_view_channelviewer_external.$ServerData['name'];

//Definiere Background falls nich übergeben
if ($background == "") {
	//Setze hintergrundfarbe aus DB, ansonsten mache Hintergrund transparent
	if ($ServerViewSettings['colour'] == "") {
		$background = "transparent";
		} else {
			$background = "#" . $ServerViewSettings['colour'];
	}
	} else {
		$background = "#" . $background;
}
//Definiere frontcolour falls nich übergeben
if ($fontcolour == "") {	
	$fontcolour = $ServerViewSettings['font_colour'];
}
//Definiere width falls nich übergeben
if ($width == "") {	
	$width = $ServerViewSettings['width'] - "10";
	} else {
		$width = $width - "10";
}
//Definiere height falls nich übergeben
if ($height == "") {	
	$height = $ServerViewSettings['height'];
}

//Definiere Footer Text
$footer = "<a href=\"http://www.mumb1e.de/\" target=\"_top\">powered by MAP</a>";

//Check on Channelviewer extern Eingeschaltet ist
if ($ServerViewSettings['external_on'] == "1") {
	//Check ob Sicherheitscode stimmt, ansonsten wird CV nicht dargestellt
	if ($ServerViewSettings['safety_code'] == $safety_code) {
		$Output = generateChannelviewer($server_id);
		} else {
			$Output = _view_sc_wrong;
	}
	} else {
		$Output = _view_cv_switched_off_short;
}
		
//Daten ausgeben  
echo show("$dir/tree_external", array("head" => _view_channelviewer_head.$ServerData['name'],
									  "background" => $background,
									  "fontcolour" => $fontcolour,
									  "width" => $width,
									  "height" => $height,
									  "channel_tree" => $Output,
	                		          "seitentitel" => $seitentitel,
		                              "map_version" => $version_num,
		                              "map_date" => $version_date,
		                              "footer" => $footer
		                              ));	                				 
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>