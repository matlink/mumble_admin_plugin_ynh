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
$dir = "start";
// Lädt Globale Funktionen
include("../inc/functions.php");
//************************************************************************************************//
// **ENDE**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
//************************************************************************************************//
// Start des Ausgabeinhalts
//************************************************************************************************//
// Definition Seitentitel
$seitentitel .= _pagetitel_start;

//Hole Versionsinformationen
$Version = MAPVersionTopicality();

// Lädt HTML-Datei
$index = show("$dir/start", array("version_num" => $Version['MAPVersionNum'],
                                  "version_date" => $Version['MAPVersionDate'],
								  "tpldir" => $tpldir,
								  "firefox" => _welcome_firefox_optimized,
								  "donate" => _welcome_donate,
                                  "text_1" => _welcome_area_1,
                                  "text_2" => _welcome_area_2,
                                  "text_3" => _welcome_area_3,
                                  "text_4" => _welcome_area_4,
                                  "text_5" => _welcome_area_5,
                                  "text_6" => _welcome_area_6,
                                  "text_7" => _welcome_area_7,
                                  "text_8" => _welcome_area_8,
                                  "text_8_1" => _welcome_area_8_1,
                                  "text_8_2" => $Version['MAPVersionOutput'],
                                  "text_9" => _welcome_area_9,
                                  "text_10" => _welcome_area_10,
                                  "text_11" => '<script type="text/javascript" src="http://livezilla.mumb1e.de/image.php?tl=PGEgaHJlZj1cImphdmFzY3JpcHQ6dm9pZCh3aW5kb3cub3BlbignaHR0cDovL2xpdmV6aWxsYS5tdW1iMWUuZGUvY2hhdC5waHAnLCcnLCd3aWR0aD01OTAsaGVpZ2h0PTYxMCxsZWZ0PTAsdG9wPTAscmVzaXphYmxlPXllcyxtZW51YmFyPW5vLGxvY2F0aW9uPXllcyxzdGF0dXM9eWVzLHNjcm9sbGJhcnM9eWVzJykpXCIgPCEtLWNsYXNzLS0-PjwhLS10ZXh0LS0-PC9hPg__&amp;tlont=TGl2ZSBTdXBwb3J0IChPbmxpbmUp&amp;tloft=TGl2ZSBTdXBwb3J0IChPZmZsaW5lKQ__"></script>',
                                  ));
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
//************************************************************************************************//
// **START** Lädt das Template
//************************************************************************************************//
include("../inc/layout.php");
//************************************************************************************************//
// **ENDE**  Lädt das Template
//************************************************************************************************//
//************************************************************************************************//
// **START** Lädt die Loggingfunktionen
//************************************************************************************************//
include("../inc/logging.php");
//************************************************************************************************//
// **ENDE**  Lädt die Loggingfunktionen
//************************************************************************************************//
?>