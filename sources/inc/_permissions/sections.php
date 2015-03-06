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

//Hole alle vorhandene Section ID´s
function getSectionIDs() {
	
	global $database_prefix;
	
	//Hole Section aus DB und speichere diese in globalem Array
	$qry = mysql_query("SELECT * FROM `".$database_prefix."section` ORDER BY section_id ASC");
    $i = "0";
	while ($get = mysql_fetch_array($qry)) {
		$Sections[$i] = $get["section_id"];
		$i++;
	}
	
	return $Sections;
	
}

//Hole Informationen zu einer bestimmten Section entweder nach:
// - section_id
// - oder name
// Copy-Paste: $section_info = section_info($section_id, $section_name);

function section_info($section_id, $section_name) {

	global $database_prefix;
	
	if ($section_id) {
		//Hole Section aus DB und speichere diese in globalem Array
		$qry = mysql_query("SELECT * FROM `".$database_prefix."section` WHERE section_id = '".$section_id."' LIMIT 1");
		$get = mysql_fetch_array($qry);
		
		//Schreibe Daten in globalen Array
		$_SECTION = array("section_id" => $get['section_id'],               //Eindeutige ID der Section
					      "name" => $get['name'],                           //Name der Berechtigungs-Section
						  "name_translation" => $get['name_translation'],   //PHP-Tag für Name => zur Übersetzung
						  "discription" => $get['discription'],             //Beschreibung der Section auf Deutsch
						  "discr_translation" => $get['discr_translation'], //PHP-Tag für Bescheibung => zur Übersetzung (Kann gehovert werden, sodass User auf seiner Sprache eine genaue Beschreibung der Berechtigungsebene bekommt
						  "default_perms" => $get['default_perms'],         //Definieren beim anlegen einer neuen berechtigungsebene, die Standartberechtigungen für diese Section, TRUE = 1 oder FALSE = 0.
						  "section_name" => $get['section_name'],           //Definiert den Bereich im Script(PHP-Section) in der sich diese Berechtigungssection befindet.
						  "section_path" => $get['section_path'],           //Definiert die PHP-Datei, in der sich diese Berechtigungssection befindet.
						  "error_message" => $get['error_message'],         //Definiert, ob MAP bei Fehlender Berechtigung einen ERROR-Text ausgeben soll, dass der User z.B. keine Berechtigung hat.
						  "data_count" => $get['data_count'],               //Definiert, ob die Tabelle map_section_data mit Logging daten dieser Section gefüllt werden soll.
						  );		 
	}

	if ($section_name) {
		//Hole Section aus DB und speichere diese in globalem Array
		$qry = mysql_query("SELECT * FROM `".$database_prefix."section` WHERE name = '".$section_name."' LIMIT 1");
		$get = mysql_fetch_array($qry);
		
		//Schreibe Daten in globalen Array
		$_SECTION = array("section_id" => $get['section_id'],               //Eindeutige ID der Section
					      "name" => $get['name'],                           //Name der Berechtigungs-Section
						  "name_translation" => $get['name_translation'],   //PHP-Tag für Name => zur Übersetzung
						  "discription" => $get['discription'],             //Beschreibung der Section auf Deutsch
						  "discr_translation" => $get['discr_translation'], //PHP-Tag für Bescheibung => zur Übersetzung (Kann gehovert werden, sodass User auf seiner Sprache eine genaue Beschreibung der Berechtigungsebene bekommt
						  "default_perms" => $get['default_perms'],         //Definieren beim anlegen einer neuen berechtigungsebene, die Standartberechtigungen für diese Section, TRUE = 1 oder FALSE = 0.
						  "section_name" => $get['section_name'],           //Definiert den Bereich im Script(PHP-Section) in der sich diese Berechtigungssection befindet.
						  "section_path" => $get['section_path'],           //Definiert die PHP-Datei, in der sich diese Berechtigungssection befindet.
		                  "error_message" => $get['error_message'],         //Definiert, ob MAP bei Fehlender Berechtigung einen ERROR-Text ausgeben soll, dass der User z.B. keine Berechtigung hat.
						  "data_count" => $get['data_count'],               //Definiert, ob die Tabelle map_section_data mit Logging daten dieser Section gefüllt werden soll.
						  );
	}

	return $_SECTION;

}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>