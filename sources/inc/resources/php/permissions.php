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

//Rechne aus, wie viel Prozent der Berechtigungen in Gruppe gewährt sind
function get_group_statistics($group_id) {
	
	global $database_prefix;
	
	//Zähle Anzahl der vorhanden Sections
	$qry_sections = mysql_query("SELECT * FROM `".$database_prefix."section`");
	$i_sections = mysql_num_rows($qry_sections);  
	
	//Hole Berechtigungen der Gruppe, mit value 1
	$qry_group_perms = mysql_query("SELECT * FROM `".$database_prefix."group_perm` WHERE group_id = '".$group_id."' AND value = '1'");
	$i_group_perms = mysql_num_rows($qry_group_perms);
	
	//Hole Anzahl der User, die zu der Gruppe gehören
	$qry_users = mysql_query("SELECT * FROM `".$database_prefix."user` WHERE group_id = '".$group_id."'");
	$stats['users'] = mysql_num_rows($qry_users);
    
	//Gebe Berechtigungen als (xxx.xx%)-Wert aus
    $stats['percent'] = round(100 / $i_sections * $i_group_perms, 2) . '%';
	
    //Gebe Berechtigungen als (xx / xx)-Wert aus (erhaltene Berechtigungen von Sections Gesamt)
    $stats['ratio'] = $i_group_perms . ' / ' . $i_sections;
    
	return $stats;
}

//Rechne aus, wie viel Prozent der Berechtigungen dem einzelnen Admin gewährt sind
function get_admin_statistics($perm_id) {
	
	global $database_prefix;
	
	//Zähle Anzahl der vorhanden Sections
	$qry_sections = mysql_query("SELECT * FROM `".$database_prefix."section`");
	$i_sections = mysql_num_rows($qry_sections);  
	
	//Hole Berechtigungen der Gruppe, mit value 1
	$qry_admin_perms = mysql_query("SELECT * FROM `".$database_prefix."user_perm` WHERE perm_id = '".$perm_id."' AND value = '1'");
	$i_admin_perms = mysql_num_rows($qry_admin_perms);

	//Gebe Berechtigungen als (xxx.xx%)-Wert aus
    $stats['percent'] = round(100 / $i_sections * $i_admin_perms, 2) . '%';
	
    //Gebe Berechtigungen als (xx / xx)-Wert aus (erhaltene Berechtigungen von Sections Gesamt)
    $stats['ratio'] = $i_admin_perms . ' / ' . $i_sections;
    
	return $stats;
}

//Berechne wieviel Prozent der Admin komplett Berechtigungen hat
function get_all_percent_perms($user_id) {
	
	global $database_prefix;
	
	//Setze Zähler
	$goodPerms = 0;
	$badPerms = 0;
	
	$qry = mysql_query("SELECT * FROM `".$database_prefix."section` ORDER BY section_id ASC");
    while ($get = mysql_fetch_array($qry)) {
    	
    	//Checke ob Perms verhanden
    	if (get_perms($get['name'], FALSE, $user_id) == TRUE) {
    		$goodPerms++;
    		} elseif (get_perms($get['name'], FALSE, $user_id) == FALSE) {
    			$badPerms++;
    	}
    }
    
    //Zähle Anzahl der vorhanden Sections
	$qry_sections = mysql_query("SELECT * FROM `".$database_prefix."section`");
	$i_sections = mysql_num_rows($qry_sections); 
	
	//Gebe Berechtigungen als (xxx.xx%)-Wert aus
    $stats['percent'] = round(100 / $i_sections * $goodPerms, 2) . '%';
	
    //Gebe Berechtigungen als (xx / xx)-Wert aus (erhaltene Berechtigungen von Sections Gesamt)
    $stats['ratio'] = $goodPerms . ' / ' . $i_sections;
    
	return $stats;

}

//Hole Übersetzungen für die Sections
function trans_sec($toTrans) {

	//Wenn Konstannte vergeben, führe den constant()-Befehl aus, um Error meldungen zu verhindern
	if (defined($toTrans)) {
		$translated = constant($toTrans);
	}
	
	return $translated;
}

//Gebe letzte Section in der Datenbank aus; Result ist section_id
function get_last_section() {
	
	global $database_prefix;
	
	$qry = mysql_query("SELECT * FROM `".$database_prefix."section` ORDER BY `section_id` DESC LIMIT 1");
	$get = mysql_fetch_array($qry);

	return $get['section_id'];
}

//Generiere Checkbox für Gruppe erstellen (beachte rekursive Rechtevergabe und default Perms)
function get_perm_checkbox_create_group($defPerm, $secName) {
     
	//Check der Default Perms
	if ($defPerm == '0') {
		$defPerm = FALSE;
		} elseif ($defPerm == '1') {
			$defPerm = TRUE;
	}
	
	//Check ob Berechtigungen für diese section vorhanden
	if (get_perms($secName, FALSE) == FALSE) {
		$curPerm = FALSE;
		} elseif (get_perms($secName, FALSE) == TRUE) {
			$curPerm = TRUE;
	}
	
	//Check ob Section == "perm_recursive_rigths" gerade abgefragt wird
	if ($secName == "perm_recursive_rigths") {
		$secLock = TRUE;
		} else {
			$secLock = FALSE;
	}
	
	//Check der "perm_recursive_rigths" dieses Users
	if (get_perms("perm_recursive_rigths", FALSE) == FALSE) {
		$recPerm = FALSE;
		} else {
			$recPerm = TRUE;
	}
	
	//Ausgabe entsprechend der Berechtigungen
	//Wenn rekursive Rechtevergabe aktiv
	if ($recPerm == TRUE) {
		//Wenn Berechtigungen vohanden und Default Perms auf True
		if ($curPerm == TRUE && $defPerm == TRUE) {
			$checkbox = ' checked="checked"';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf False
		if ($curPerm == TRUE && $defPerm == FALSE) {
			$checkbox = '';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf True
		if ($curPerm == FALSE && $defPerm == TRUE) {
			$checkbox = ' disabled="disabled"';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf False
		if ($curPerm == FALSE && $defPerm == FALSE) {
			$checkbox = ' disabled="disabled"';
		}
		//Wenn rekursiv check
		if ($secLock == TRUE) {
			$checkbox = ' disabled="disabled" checked="checked"';
		}
		
		//Wenn rekursive Rechtevergabe nicht aktiv
		} elseif ($recPerm == FALSE) {
			//Wenn Default Perms auf True
			if ($defPerm == TRUE) {
				$checkbox = ' checked="checked"';
			}
			//Wenn Default Perms auf False
			if ($defPerm == FALSE) {
				$checkbox = '';
			}
	}

	return $checkbox;
}

//Generiere Checkbox für Gruppe editieren (beachte rekursive Rechtevergabe und default Perms)
function get_perm_checkbox_edit_group($group_id, $secName) {
     
	//Check der Perms der abgefragten Gruppe
	if (perm_group_detailed($group_id, $secName, FALSE) == FALSE) {
		$groupPerm = FALSE;
		} elseif (perm_group_detailed($group_id, $secName, FALSE) == TRUE) {
			$groupPerm = TRUE;
	}
	
	//Check ob Berechtigungen für diese section vorhanden
	if (get_perms($secName, FALSE) == FALSE) {
		$curPerm = FALSE;
		} elseif (get_perms($secName, FALSE) == TRUE) {
			$curPerm = TRUE;
	}
	
	//Check ob Section == "perm_recursive_rigths" gerade abgefragt wird
	if ($secName == "perm_recursive_rigths") {
		$secLock = TRUE;
		} else {
			$secLock = FALSE;
	}
	
	//Check der "perm_recursive_rigths" dieses Users
	if (get_perms("perm_recursive_rigths", FALSE) == FALSE) {
		$recPerm = FALSE;
		} else {
			$recPerm = TRUE;
	}
	
	//Ausgabe entsprechend der Berechtigungen
	//Wenn rekursive Rechtevergabe aktiv
	if ($recPerm == TRUE) {
		//Wenn Berechtigungen vohanden und Default Perms auf True
		if ($curPerm == TRUE && $groupPerm == TRUE) {
			$checkbox = ' checked="checked"';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf False
		if ($curPerm == TRUE && $groupPerm == FALSE) {
			$checkbox = '';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf True
		if ($curPerm == FALSE && $groupPerm == TRUE) {
			$checkbox = ' disabled="disabled" checked="checked"';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf False
		if ($curPerm == FALSE && $groupPerm == FALSE) {
			$checkbox = ' disabled="disabled"';
		}
		//Wenn rekursiv check
		if ($secLock == TRUE) {
			$checkbox = ' checked="checked" disabled="disabled" readonly';
		}
		
		//Wenn rekursive Rechtevergabe nicht aktiv
		} elseif ($recPerm == FALSE) {
			//Wenn Default Perms auf True
			if ($groupPerm == TRUE) {
				$checkbox = ' checked="checked"';
			}
			//Wenn Default Perms auf False
			if ($groupPerm == FALSE) {
				$checkbox = '';
			}
	}

	return $checkbox;
}

//Gebe Sections mit checkboxen in array aus, für gruppe erstellen und editieren seite
function get_group_sections($area, $group_id) {
	
	global $database_prefix;
	global $dir;
	$cat = FALSE;
	$catHTML = FALSE;
	$sectHTML = FALSE;
	
	$last_section = get_last_section();
	
    //Generiere Ausgabe der Sections mit Haken
	$qry = mysql_query("SELECT * FROM `".$database_prefix."section` ORDER BY section_id ASC");
    while ($get = mysql_fetch_array($qry)) {
    	
    	//Definiere vorherige Kategorie
    	$cat_old = $cat;
    	
    	//Generiere die aktuelle (neue) Kategorie
    	$cat = substr($get['section_id'], 0, 2);
    	
    	//Generiere Sections Part1
        if (($cat == $cat_old OR $cat_old == FALSE)) {
        	//Hole Check-Wert für diese Section
        	if ($area == "create") {
        		$checkbox = get_perm_checkbox_create_group($get['default_perms'], $get['name']);
        		} elseif ($area == "edit") { 
        			$checkbox = get_perm_checkbox_edit_group($group_id, $get['name']);
        	}
        	
		    //Gebe Section in Array aus
	    	$sectionArray[] = array("name" => ' ' . trans_sec($get['name_translation']),
	    							"discription" => trans_sec($get['discr_translation']),
	            			        "section_id" => $get['section_id'],
	            				    "checkbox" => $checkbox,
	            		            );
    		
    	}
        
    	//Schreibe fertige Kategorie mit Sections
        if (($cat > $cat_old && $cat_old != FALSE) || $last_section == $get['section_id']) {
        	$catArray[] = array("name" => trans_sec('_perm_cat_' . $cat_old),
	    						"id" => $cat_old,
	    						"sections" => $sectionArray
	    						);
	 		//Lösche Array
	    	$sectionArray = array();
        }
        
        //Generiere Sections Part2
        if ($cat > $cat_old && $cat_old != FALSE) {
            //Hole Check-Wert für diese Section
        	if ($area == "create") {
        		$checkbox = get_perm_checkbox_create_group($get['default_perms'], $get['name']);
        		} elseif ($area == "edit") { 
        			$checkbox = get_perm_checkbox_edit_group($group_id, $get['name']);
        	}
        	
		    //Gebe Section in Array aus
	    	$sectionArray[] = array("name" => ' ' . trans_sec($get['name_translation']),
	    							"discription" => trans_sec($get['discr_translation']),
	            			        "section_id" => $get['section_id'],
	            				    "checkbox" => $checkbox,
	            		            );
    	}
    }

    //Gebe die gesammelten Arrays in das Template aus
    foreach($catArray as $cat) {
    	$catHTML .= show("$dir/tab/tabs",array("id" => $cat['id'],
    									       "name" => $cat['name'],
    									       ));
    									  
    	$sectHTML .= '<div style="clear:left;">';
        $sectHTML .= '<div id="'.$cat['id'].'" class="ui-tabs-panel">';
    	
	    foreach($cat['sections'] as $sect) {
	    	$sectHTML .= show("$dir/tab/sections",array("section_id" => $sect['section_id'],
	    	                                            "discription" => $sect['discription'],
											            "name" => $sect['name'],
											            "checkbox" => $sect['checkbox']
											            ));
	    }
	    
    	$sectHTML .= '</div>';	
    }
   
    $output = show("$dir/tab/output", array("tabs" => $catHTML,
    									    "sections"=>$sectHTML
                                            ));

	return $output;
}

//Generiere Checkbox für Admin editieren
function get_perm_checkbox_edit_admin($perm_id, $secName) {

	//Check der Perms des abgefragten Admins
	if (perm_admin_detailed($perm_id, $secName, FALSE) == FALSE) {
		$adminPerm = FALSE;
		} elseif (perm_admin_detailed($perm_id, $secName, FALSE) == TRUE) {
			$adminPerm = TRUE;
	}
	
	//Check ob Berechtigungen für diese section vorhanden
	if (get_perms($secName, FALSE) == FALSE) {
		$curPerm = FALSE;
		} elseif (get_perms($secName, FALSE) == TRUE) {
			$curPerm = TRUE;
	}
	
	//Check ob Section == "perm_recursive_rigths" gerade abgefragt wird
	if ($secName == "perm_recursive_rigths") {
		$secLock = TRUE;
		} else {
			$secLock = FALSE;
	}
	
	//Check der "perm_recursive_rigths" dieses Users
	if (get_perms("perm_recursive_rigths", FALSE) == FALSE) {
		$recPerm = FALSE;
		} else {
			$recPerm = TRUE;
	}
	
	//Ausgabe entsprechend der Berechtigungen
	//Wenn rekursive Rechtevergabe aktiv
	if ($recPerm == TRUE) {
		//Wenn Berechtigungen vohanden und Default Perms auf True
		if ($curPerm == TRUE && $adminPerm == TRUE) {
			$checkbox = ' checked="checked"';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf False
		if ($curPerm == TRUE && $adminPerm == FALSE) {
			$checkbox = '';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf True
		if ($curPerm == FALSE && $adminPerm == TRUE) {
			$checkbox = ' disabled="disabled" checked="checked"';
		}
		//Wenn Berechtigungen nicht vorhanden und Default Perms auf False
		if ($curPerm == FALSE && $adminPerm == FALSE) {
			$checkbox = ' disabled="disabled"';
		}
		//Wenn rekursiv check
		if ($secLock == TRUE) {
			$checkbox = ' checked="checked" disabled="disabled" readonly';
		}
		
		//Wenn rekursive Rechtevergabe nicht aktiv
		} elseif ($recPerm == FALSE) {
			//Wenn Default Perms auf True
			if ($adminPerm == TRUE) {
				$checkbox = ' checked="checked"';
			}
			//Wenn Default Perms auf False
			if ($adminPerm == FALSE) {
				$checkbox = '';
			}
	}

	return $checkbox;
}

//Gebe Sections mit checkboxen in array aus, für admin und editieren seite
function get_admin_sections($perm_id) {
	
	global $database_prefix;
	global $dir;
	$cat = FALSE;
	$area = FALSE;
	$catHTML = FALSE;
	$sectHTML = FALSE;
	
	$last_section = get_last_section();
	
    //Generiere Ausgabe der Sections mit Haken
	$qry = mysql_query("SELECT * FROM `".$database_prefix."section` ORDER BY section_id ASC");
    while ($get = mysql_fetch_array($qry)) {
    	
    	//Definiere vorherige Kategorie
    	$cat_old = $cat;
    	
    	//Generiere die aktuelle (neue) Kategorie
    	$cat = substr($get['section_id'], 0, 2);
    	
    	//Generiere Sections Part1
        if (($cat == $cat_old OR $cat_old == FALSE)) {
        	//Hole Check-Wert für diese Section
        	$checkbox = get_perm_checkbox_edit_admin($perm_id, $get['name']);
        	
		    //Gebe Section in Array aus
	    	$sectionArray[] = array("name" => ' ' . trans_sec($get['name_translation']),
	    							"discription" => trans_sec($get['discr_translation']),
	            			        "section_id" => $get['section_id'],
	            				    "checkbox" => $checkbox,
	            		            );
    		
    	}
        
    	//Schreibe fertige Kategorie mit Sections
        if (($cat > $cat_old && $cat_old != FALSE) || $last_section == $get['section_id']) {
        	$catArray[] = array("name" => trans_sec('_perm_cat_' . $cat_old),
	    						"id" => $cat_old,
	    						"sections" => $sectionArray
	    						);
	 		//Lösche Array
	    	$sectionArray = array();
        }
        
        //Generiere Sections Part2
        if ($cat > $cat_old && $cat_old != FALSE) {
            //Hole Check-Wert für diese Section
        	if ($area == "create") {
        		$checkbox = get_perm_checkbox_create_group($get['default_perms'], $get['name']);
        		} elseif ($area == "edit") { 
        			$checkbox = get_perm_checkbox_edit_group($group_id, $get['name']);
        	}
        	
		    //Gebe Section in Array aus
	    	$sectionArray[] = array("name" => ' ' . trans_sec($get['name_translation']),
	    							"discription" => trans_sec($get['discr_translation']),
	            			        "section_id" => $get['section_id'],
	            				    "checkbox" => $checkbox,
	            		            );
    	}
    }

    //Gebe die gesammelten Arrays in das Template aus
    foreach($catArray as $cat) {
    	$catHTML .= show("$dir/tab/tabs",array("id" => $cat['id'],
    									       "name" => $cat['name'],
    									       ));
    									  
    	$sectHTML .= '<div style="clear:left;">';
        $sectHTML .= '<div id="'.$cat['id'].'" class="ui-tabs-panel">';
    	
	    foreach($cat['sections'] as $sect) {
	    	$sectHTML .= show("$dir/tab/sections",array("section_id" => $sect['section_id'],
	    	                                            "discription" => $sect['discription'],
											            "name" => $sect['name'],
											            "checkbox" => $sect['checkbox']
											            ));
	    }
	    
    	$sectHTML .= '</div>';	
    }
   
    $output = show("$dir/tab/output", array("tabs" => $catHTML,
    									    "sections"=>$sectHTML
                                            ));

	return $output;
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>