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
// Definiere, bzw. lade Section
if (!isset($_GET['section'])) {
    $section = "";
    } else {
        $section = $_GET['section'];
}

// Gehe zu aufgerufenem Bereich
switch ($section) {
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  settings Channelviewer in DB speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'settings_db':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_view_settings_db;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("settings_channelviewer", $_POST['server_id'])) {      
		//Start definition eigentliche Funktionen dieser Section:
            
        //Übernehme Daten
        $server_id = $_POST["server_id"];
        $background = $_POST["background"];
        $font_colour = $_POST["font_colour"];
        $width = $_POST["width"];
        $height = $_POST["height"];
        $external_on = $_POST["external_on"];
        $safety_code = $_POST["safety_code"];
        $true_change = FALSE;
        	
        //Hole Server Daten
        $ServerData = server_info($server_id);
			
        // Hole Channelviewer Settings des Servers
        $qry = mysql_query("SELECT * FROM `".$database_prefix."channelviewer` WHERE server_id = '".$server_id."'");
        $ServerViewSettings = mysql_fetch_array($qry);
            
        //Background, falls geändert in DB schreiben            
        if ($background != $ServerViewSettings['colour']) {
            mysql_query("UPDATE `".$database_prefix."channelviewer` SET colour = '".$background."' WHERE server_id = '".$server_id."'");
            $true_change = TRUE;
        }
            
        //front_colour, falls geändert in DB schreiben            
        if ($font_colour != $ServerViewSettings['font_colour']) {
            mysql_query("UPDATE `".$database_prefix."channelviewer` SET font_colour = '".$font_colour."' WHERE server_id = '".$server_id."'");
            $true_change = TRUE;
        }
            
        //width, falls geändert in DB schreiben            
        if ($width != $ServerViewSettings['width']) {
            mysql_query("UPDATE `".$database_prefix."channelviewer` SET width = '".$width."' WHERE server_id = '".$server_id."'");
            $true_change = TRUE;
        }
            
        //height, falls geändert in DB schreiben            
        if ($height != $ServerViewSettings['height']) {
            mysql_query("UPDATE `".$database_prefix."channelviewer` SET height = '".$height."' WHERE server_id = '".$server_id."'");
            $true_change = TRUE;
        }
            
        //external_on, falls geändert in DB schreiben            
        if ($external_on == "") {
        	$external_on = "0";
        }
        if ($external_on != $ServerViewSettings['external_on']) {
            mysql_query("UPDATE `".$database_prefix."channelviewer` SET external_on = '".$external_on."' WHERE server_id = '".$server_id."'");
            $true_change = TRUE;
        }
            
        //safety_code, falls geändert in DB schreiben            
        if ($safety_code != $ServerViewSettings['safety_code']) {
            mysql_query("UPDATE `".$database_prefix."channelviewer` SET safety_code = '".$safety_code."' WHERE server_id = '".$server_id."'");
            $true_change = TRUE;
        }
            
        //Abschlussmeldung
        if ($true_change == FALSE) {
            $info = "<br><div align=\"center\"><div class='savearea'>"._view_settings_do_false1.$ServerData['name']._view_settings_do_false2."</div></div>";
            autoforward("../view/index.php?section=settings&server_id=".$server_id."",5);
            } elseif ($true_change == TRUE) {
                $info = "<br><div align=\"center\"><div class='boxsucess'>"._view_settings_do_true1.$ServerData['name']._view_settings_do_true2."</div></div>";
                autoforward("../view/index.php?section=channelviewer&server_id=".$server_id."",3);
        }
           
        //Loggingfunktion, Übergabe der Werte: settings Channelviewer in DB speichern
	    //Definiert ob etwas geloggt werden soll
	    if ($true_change == TRUE) {
			$log_values["on"] = TRUE;
		}
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "view_db_1";					//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "2";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $ServerData['name'];			//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		                				 
        //Ende definition eigentliche Funktionen dieser Section^^
    }       				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  settings Channelviewer in DB speichern
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  settings Channelviewer anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'settings':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_view_settings;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("settings_channelviewer", $_GET['server_id'])) {      
		//Start definition eigentliche Funktionen dieser Section:

        //Übernehme Daten
		$server_id = $_GET['server_id'];
			
		//Hole Server Daten
        $ServerData = server_info($server_id);
			
        // Gebe Hintergrundfarbe, front-colour, width und heigth aus
        $qry = mysql_query("SELECT * FROM `".$database_prefix."channelviewer` WHERE server_id = '".$server_id."'");
        $get_view_settings = mysql_fetch_array($qry);   

        // Gebe aus, ob Channelviewer auch extern Ein ist.
        if ($get_view_settings['external_on'] == '1') {
            $get_external_value = ' checked';
            } elseif ($get_view_settings['external_on'] == '0') {
                $get_external_value = '';
        }
                  
        // Lade Template mit errechnetem Inhalt
        $content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
		                         "head_value" => _view_settings_head . $ServerData['name'],
								 "navi_on" => TRUE,
								 "navi_type" => "settings_channelviewer",
								 );
        $index = show("$dir/settings", array("background_head" => _view_settings_background_head,
          									 "font_colour_head" => _view_settings_font_colour_head,
                                             "width_head" => _view_settings_width_head,
                                             "height_head" => _view_settings_height_head,
                                             "external_on_head" => _view_settings_external_on_head,
                                             "safety_code_head" => _view_settings_safety_code_head,
                                             "background_discribtion" => _view_settings_background_discribtion,
          									 "font_colour_discribtion" => _view_settings_font_colour_discribtion,
                                             "width_discribtion" => _view_settings_width_discribtion,
                                             "height_discribtion" => _view_settings_height_discribtion,
                                             "external_on_discribtion" => _view_settings_external_on_discribtion,
                                             "safety_code_discribtion" => _view_settings_safety_code_discribtion,
                                             "background_value" => $get_view_settings['colour'],
         									 "font_colour_value" => $get_view_settings['font_colour'],
                                             "width_value" => $get_view_settings['width'],
                                             "height_value" => $get_view_settings['height'],
                                             "external_on_value" => $get_external_value,
                                             "safety_code_value" => $get_view_settings['safety_code'],
          									 "server_id" => $server_id,
                                             ));
                                                 
		//Loggingfunktion, Übergabe der Werte: settings Channelviewer anzeigen
	    //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "view_show_2";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $ServerData['name'];			//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)                                    
		                				 
        //Ende definition eigentliche Funktionen dieser Section^^
    }   
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  settings Channelviewer anzeigen
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Include Channelviewer
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'include':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_view_include;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("include_channelviewer", $_GET['server_id'])) {      
		//Start definition eigentliche Funktionen dieser Section:

		$server_id = $_GET['server_id'];
			
		//Hole Server Daten
		$ServerData = server_info($server_id);

        // Hole Channelviewer Settings des Servers
        $qry = mysql_query("SELECT * FROM `".$database_prefix."channelviewer` WHERE server_id = '".$server_id."'");
        $ServerViewSettings = mysql_fetch_array($qry);

        $map_path = substr($_SERVER['SCRIPT_NAME'], 0, -15);           
        $view_url = $getHTTP . $_SERVER['SERVER_NAME'] . $map_path . "/view/view.php";
	
		//Daten ans Formular schicken und / bzw. es aufrufen
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
		                         "head_value" => _view_include_head.$ServerData['name'],
								 "navi_on" => TRUE,
								 "navi_type" => "default",
								 );      
		$index = show("$dir/include", array("server_id" => $server_id,
											"discription" => _view_include_discription,
											"width" => $ServerViewSettings['width'],
											"height" => $ServerViewSettings['height'],
											"safety_code" => $ServerViewSettings['safety_code'],
										    "view_path" => $view_url,
											"note" => _view_include_note,
											"example_head" => _view_include_example_head,
											"example_text1" => _view_include_example_text1,
											"example_text2" => _view_include_example_text2,
											"example_text3" => _view_include_example_text3,
		                				    ));

		//Loggingfunktion, Übergabe der Werte: Include Channelviewer
	    //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "view_show_3";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $ServerData['name'];			//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)                 				    
		                				    
        //Ende definition eigentliche Funktionen dieser Section^^
    }   
                				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Include Channelviewer
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Channelviewer
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
case 'channelviewer':
	// Definition Seitentitel
	$seitentitel .= _pagetitel_view_channelviewer;

    //perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("view_channelviewer", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:

		$server_id = $_GET['server_id'];
		
		//Hole Server Daten
		$ServerData = server_info($server_id);

		//Hole HTML CV Ausgabe
		$tree = generateChannelviewer($server_id);
		
		//Setze Links nach Berechtigungen
		if (get_perms("include_channelviewer", FALSE) OR get_perms("settings_channelviewer", FALSE)) {
			$head = _view_channelviewer_action_head;
		}
		if (get_perms("include_channelviewer", FALSE)) {
			$include = "<img src=\"../inc/tpl/".$tpldir."/images/activate.png\" alt=\"\" border=\"0\"><a href=\"../view/index.php?section=include&server_id=".$server_id."\" target=\"_self\">"._view_channelviewer_include."</a>";
		}
		if (get_perms("settings_channelviewer", FALSE)) {
			$config = "<img src=\"../inc/tpl/".$tpldir."/images/settings.png\" alt=\"\" border=\"0\"><a href=\"../view/index.php?section=settings&server_id=".$server_id."\" target=\"_self\">"._view_channelviewer_settings."</a>";	
		}
		
		//Daten ans Formular schicken und / bzw. es aufrufen  
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
		                         "head_value" => _view_channelviewer_head.$ServerData['name'],
								 "navi_on" => TRUE,
								 "navi_type" => "default",
								 );      
		$index = show("$dir/tree", array("channel_tree" => $tree,
										 "action_head" => $head,
										 "include" => $include,
										 "view_config" => $config,
		                				 ));

		//Loggingfunktion, Übergabe der Werte: Channelviewer
	    //Definiert ob etwas geloggt werden soll
		$log_values["on"] = TRUE;
		//Pflichtwerte
		$log_values["user_id"] = $_MAPUSER['user_id'];			//Definiert den User (die User_id) der gerade Aktiv war
		$log_values["action_id"] = "view_show_4";				//Definiert was gerade von User gemacht wurde: (section)_(show, db)_anzahl
		$log_values["priority"] = "1";							//Definiert die Wichtigkeit dieser Section: 2=wichtig; 1=normal
		$log_values["area"] = $dir;								//Definiert die Section (für Spätere auswertung)
		//Definierbare Werte (optional)
		$log_values["server_id"] = $server_id;					//Definiert die Server_ID (kann frei gelassen werden)
		$log_values["value_1"] = $ServerData['name'];			//Definiert value_1, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_2"] = "";							//Definiert value_2, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_3"] = "";							//Definiert value_3, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_4"] = "";							//Definiert value_4, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_5"] = "";							//Definiert value_5, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_6"] = "";							//Definiert value_6, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)
		$log_values["value_7"] = "";							//Definiert value_7, welche später durch action_id individuell aufgerufen wird! (kann frei gelassen werden)               				 
		                				 
        //Ende definition eigentliche Funktionen dieser Section^^
    }             				 
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Channelviewer
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **START**  Default Page
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
default:
	// Definition Seitentitel
	$seitentitel .= _pagetitel_view_overview;
	
	//perm_handler: Definiert ob User Berechtigung hat diesen Bereich zu sehen
	if (perm_handler("view_overview", FALSE)) {      
		//Start definition eigentliche Funktionen dieser Section:

		$qry = mysql_query("SELECT * FROM `".$database_prefix."servers` ORDER BY server_id ASC");
		while ($get = mysql_fetch_array($qry)) {
			if (get_perms("view_channelviewer", $get['server_id'])) {			
		        $list .= show("$dir/view_overview_list", array("image" => "<a href=\"../view/index.php?section=channelviewer&server_id=$get[server_id]\" target=\"_self\"><img src=\"../inc/tpl/".$tpldir."/images/server.png\" alt=\"\" border=\"0\"></a>",
		                                                       "name" => "<a href=\"../view/index.php?section=channelviewer&server_id=$get[server_id]\" target=\"_self\">".$get['name']."</a>",
		                                                       ));
			}
		}
		$content_headers = array("head_on" => TRUE,
								 "head_type" => "default",
		                         "head_value" => _view_overview_head,
								 "navi_on" => TRUE,
								 "navi_type" => "default",
								 ); 
	    $index = show("$dir/view_overview", array("list" => $list,
	                                              "discription" => _view_discription,
	      										  ));  
	     
		//Ende definition eigentliche Funktionen dieser Section^^
		
    }   
break;
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
// **ENDE**  Default Page
//*<>*~~**<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~*<>*~~//
}
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