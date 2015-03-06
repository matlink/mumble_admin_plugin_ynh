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

function viewtree($channelobject,$url,$servername,$server = FALSE) {
						
	global $Output;
	global $tpldir;
	global $server_id;
	
	$channeldepth = 0;
	$menustatus = array("1","1");
	$channelobject->c->name = $servername;
	if ($server['online'] == TRUE) {
		$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/mumble.png>";
		$Output .= " " . "<a href=\"" . $url . "\">" . $servername . "</a>";
		} else {
			$Output .= "Server offline!";
	}
	if (isset($channelobject->children) AND isset($channelobject->users)) {
		if (count($channelobject->children) + count($channelobject->users) > 0) {
			$Output .= "<div class=\"channel\">\n";
			foreach ($channelobject->users as $users) {
				showusers($users,$channelobject->users[count($channelobject->users)-1]->userid,$channeldepth+1,$menustatus,$url,$tpldir);
			}
			foreach ($channelobject->children as $children) {
				showchannels($children,$channelobject->children[count($channelobject->children)-1]->c->id,$channeldepth+1,$menustatus,$url,$tpldir); 
			}
			$Output .= "</div>\n";
		}
	}
}
					
function showchannels($channelobject,$lastid,$channeldepth,$menustatus,$url) {
	
	global $Output;
	global $tpldir;
	global $server_id;
	
	$menustatus[$channeldepth] = 1;
	if ($channelobject->c->id == $lastid) {
		$menustatus[$channeldepth] = 0;
	}
	
	$count = 1;
	while($count < $channeldepth) {
		if ($menustatus[$count] == 0) {
			$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_space.gif>";
		} else {
			$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_line.png>";
		}
	    $count++;
    }

	if (count($channelobject->children) + count($channelobject->users) > 0) {
		if ($channelobject->c->id != $lastid) {
			$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_mid.png>";
			} else {
				$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_end.png>";
		}
		} else {
			if ($channelobject->c->id != $lastid) {
				$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_mid.png>";
				} else {
					$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_end.png>";
			}
	}
	
	$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/channel.png>";
	$Output .= " ".htmlentities($channelobject->c->name, ENT_QUOTES, "UTF-8")."<br>\n";

	if (count($channelobject->children) + count($channelobject->users) > 0) {
		$Output .= "<div class=\"channel\">\n";
		foreach ($channelobject->users as $users) {
			showusers($users,$channelobject->users[count($channelobject->users)-1]->userid,$channeldepth+1,$menustatus,$url);
		}
		foreach ($channelobject->children as $children) {
			showchannels($children,$channelobject->children[count($channelobject->children)-1]->c->id,$channeldepth+1,$menustatus,$url);
		}
		$Output .= "</div>\n";
	}
	return $menustatus;
}
					
function showusers($userobject,$lastid,$channeldepth,$menustatus) {
	
	global $Output;
	global $tpldir;
	global $server_id;

	$Output .= "<div class=\"user\">";
	/*
	 * Diese If-Schleife produziert ne Fehlermeldung. erst mal auskommentiert. KA wofür die da ist!
	 * 
	$menustatus[$channeldepth] = 1;
	if ($channelobject->c->id == $lastid) {
		$menustatus[$channeldepth] = 0;
	}
	*/
	
	$count = 1;
	while($count < $channeldepth) {
		if ($menustatus[$count] == 0) {
			$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_space.gif>";
			} else {
				$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_line.png>";
		}
	    $count++;
    }
    
	if ($userobject->userid == $lastid) {
		$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_end.png>";
		} else {
			$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/list_tree_mid.png>";
	}
	
	if ($userobject->bytespersec >= "1") {
		$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/player.png>";
		} else {
			$Output .= "<img border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/player_null.png>";
	}
	if ($userobject->userid != -1) {
		$Output .= "<script type=\"text/javascript\">jkmegamenu.definemenu(\"user". $userobject->session ."\", \"menu". $userobject->session ."\", \"mouseover\")</script>";
	    $Output .= "<text id=\"user". $userobject->session ."\"> ". htmlentities($userobject->name, ENT_QUOTES, "UTF-8") ."</text>";
	    $Output .= "<div id=\"menu". $userobject->session ."\" class=\"megamenu\">";
		$Output .= "<div class=\"column\">";
		$Output .= "<h3>" . _view_channelviewer_menu_control . "</h3>";
		$Output .= "<ul>";
		//Menu ausgabe stumm stellen
		if ($userobject->mute == FALSE) {
			$Output .= "<li><a href=\"../user/index.php?section=mute_user_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_mute . "</a></li>";
		    } else {
		    	$Output .= "<li><a href=\"../user/index.php?section=unmute_user_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_unmute . "</a></li>";
		}
		//Menu ausgabe taub stellen
		if ($userobject->deaf == FALSE) {
			$Output .= "<li><a href=\"../user/index.php?section=deaf_user_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_deaf . "</a></li>";
			} else {
				$Output .= "<li><a href=\"../user/index.php?section=undeaf_user_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_undeaf . "</a></li>";
		}
		$Output .= "<li><a href=\"../user/index.php?section=kick_user_default_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_kick . "</a></li>";
		$Output .= "<li><a href=\"../user/index.php?section=message_to_user&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_sendmsg . "</a></li>";
		$Output .= "</ul>";
		$Output .= "</div>";
		$Output .= "<div class=\"column\">";
	    $Output .= "<h3>" . _view_channelviewer_menu_trade . "</h3>";
		$Output .= "<ul>";
		$Output .= "<li><a href=\"../user/index.php?section=edit_user&server_id=". $server_id ."&user_id=". $userobject->userid ."\">" . _view_channelviewer_menu_edit . "</a></li>";
		$Output .= "<li><a href=\"../user/index.php?section=delete_user&server_id=". $server_id ."&user_id=". $userobject->userid ."\">" . _view_channelviewer_menu_delete . "</a></li>";
		$Output .= "</ul>";
		$Output .= "</div>";
		$Output .= "<br style=\"clear: left\" />";
		$Output .= "<div class=\"column\">";
		$Output .= "<h3>" . _view_channelviewer_menu_connection . "</h3>";
		$Output .= "<ul>";
		$Output .= "<li>" . _view_channelviewer_menu_online . makeuserontime($userobject->onlinesecs) . "</li>";
		$Output .= "<li>" . _view_channelviewer_menu_idle . makeuserontime($userobject->idlesecs) . "</li>";
		$Output .= "<li>" . _view_channelviewer_menu_traffic . $userobject->bytespersec ." bps</li>";
		$Output .= "<li>" . _view_channelviewer_menu_client . $userobject->release ."</li>";
		$Output .= "<li>" . _view_channelviewer_menu_os . $userobject->os . " " . $userobject->osversion ."</li>";
		$Output .= "<li>" . _view_channelviewer_menu_ip . setMumbleIPaddress($userobject->address) ."</li>";
		$Output .= "</ul>";
		$Output .= "</div>";
		if ($userobject->comment != "") {
			$Output .= "<div class=\"column\">";
			$Output .= "<h3>" . _view_channelviewer_menu_comment . "</h3>";
			$Output .= "<ul>";
			$Output .= "<li>". $userobject->comment . "</li>";
			$Output .= "<li><a href=\"../user/index.php?section=edit_comment_user&server_id=". $server_id ."&user_id=". $userobject->userid ."\">" . _view_channelviewer_menu_edit_comment . "</a></li>";
			$Output .= "</ul>";
			$Output .= "</div>";
			$Output .= "<br style=\"clear: left\" />";
			} else {
				$Output .= "<div class=\"column\">";
				$Output .= "<h3>" . _view_channelviewer_menu_comment . "</h3>";
				$Output .= "<ul>";
				$Output .= "<li><a href=\"../user/index.php?section=create_comment_user&server_id=". $server_id ."&user_id=". $userobject->userid ."\">" . _view_channelviewer_menu_create_comment . "</a></li>";
				$Output .= "</ul>";
				$Output .= "</div>";
				$Output .= "<br style=\"clear: left\" />";
			
		}
		$Output .= "</div>";
		
		} else {														
			
			$Output .= "<script type=\"text/javascript\">jkmegamenu.definemenu(\"user". $userobject->session ."\", \"menu". $userobject->session ."\", \"mouseover\")</script>";
	        $Output .= "<text id=\"user". $userobject->session ."\"> ". htmlentities($userobject->name, ENT_QUOTES, "UTF-8") ."</text>";
	        $Output .= "<div id=\"menu". $userobject->session ."\" class=\"megamenu\">";
			$Output .= "<div class=\"column\">";
			$Output .= "<h3>" . _view_channelviewer_menu_control . "</h3>";
			$Output .= "<ul>";
			//Menu ausgabe stumm stellen
			if ($userobject->mute == FALSE) {
				$Output .= "<li><a href=\"../user/index.php?section=mute_user_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_mute . "</a></li>";
			    } else {
			    	$Output .= "<li><a href=\"../user/index.php?section=unmute_user_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_unmute . "</a></li>";
			}
			//Menu ausgabe taub stellen
			if ($userobject->deaf == FALSE) {
				$Output .= "<li><a href=\"../user/index.php?section=deaf_user_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_deaf . "</a></li>";
				} else {
					$Output .= "<li><a href=\"../user/index.php?section=undeaf_user_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_undeaf . "</a></li>";
			}
			$Output .= "<li><a href=\"../user/index.php?section=kick_user_default_db&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_kick . "</a></li>";
			$Output .= "<li><a href=\"../user/index.php?section=message_to_user&server_id=". $server_id ."&session=". $userobject->session ."\">" . _view_channelviewer_menu_sendmsg . "</a></li>";
			$Output .= "</ul>";
			$Output .= "</div>";
			$Output .= "<div class=\"column\">";
		    $Output .= "<h3>" . _view_channelviewer_menu_trade . "</h3>";
			$Output .= "<ul>";
			$Output .= "<li><a href=\"../user/index.php?section=register_user&server_id=". $server_id . "&name=". htmlentities($userobject->name, ENT_QUOTES, "UTF-8") ."\">" . _view_channelviewer_menu_register . "</a></li>";
			$Output .= "</ul>";
			$Output .= "</div>";
			$Output .= "<br style=\"clear: left\" />";
			$Output .= "<div class=\"column\">";
			$Output .= "<h3>" . _view_channelviewer_menu_connection . "</h3>";
			$Output .= "<ul>";
			$Output .= "<li>" . _view_channelviewer_menu_online . makeuserontime($userobject->onlinesecs) . "</li>";
			$Output .= "<li>" . _view_channelviewer_menu_idle . makeuserontime($userobject->idlesecs) . "</li>";
			$Output .= "<li>" . _view_channelviewer_menu_traffic . $userobject->bytespersec ." bps</li>";
			$Output .= "<li>" . _view_channelviewer_menu_client . $userobject->release ."</li>";
			$Output .= "<li>" . _view_channelviewer_menu_os . $userobject->os . " " . $userobject->osversion ."</li>";
			$Output .= "<li>" . _view_channelviewer_menu_ip . setMumbleIPaddress($userobject->address) ."</li>";
			$Output .= "</ul>";
			$Output .= "</div>";
			if ($userobject->comment != "") {
				$Output .= "<div class=\"column\">";
				$Output .= "<h3>" . _view_channelviewer_menu_comment . "</h3>";
				$Output .= "<ul>";
				$Output .= "<li>". $userobject->comment . "</li>";
				$Output .= "</ul>";
				$Output .= "</div>";
				$Output .= "<br style=\"clear: left\" />";
			}
			$Output .= "</div>";
	}

	if ($userobject->userid != -1) {
		$Output .= "<img class=view_status1 border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/auth.png>";
		$class = "1" + "1";	
		} else {
			$class = "1";	
	}
	
	if ($userobject->selfDeaf) {
		$Output .= "<img class=view_status".$class." border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/deafened_self.png>";	
		$class = $class + "1";
	}
	
	if ($userobject->deaf) {
		$Output .= "<img class=view_status".$class." border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/deafened_server.png>";	
		$class = $class + "1";
	}
	
	if ($userobject->selfMute) {
		$Output .= "<img class=view_status".$class." border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/muted_self.png>";	
		$class = $class + "1";
	}
	
	if ($userobject->mute) {
		$Output .= "<img class=view_status".$class." border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/muted_server.png>";	
		$class = $class + "1";
	}
	
	if ($userobject->suppress) {
		$Output .= "<img class=view_status".$class." border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/suppressed.png>";	
		$class = $class + "1";
	}
	
	if ($userobject->recording) {
		$Output .= "<img class=view_status".$class." border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/recording.png>";	
		$class = $class + "1";
	}
	
	if ($userobject->prioritySpeaker) {
		$Output .= "<img class=view_status".$class." border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/prioritySpeaker.png>";	
		$class = $class + "1";
	}

	if ($userobject->comment) {
		$Output .= "<img class=view_status".$class." border=0 src=../inc/tpl/".$tpldir."/images/channelviewer/comment.png>";	
		
	}
	
	$Output .= "<br></div>\n"; 
	
	return $menustatus;
}

//Generiere HTML Ausgabe des internen Channelviewer
function generateChannelviewer($server_id) {
	
	global $Output;
	global $tpldir;
	global $server_id;
	
	//Hole Server Tree
	$server = server_info($server_id);

	$Output .= "<div class=\"channel\">\n";
	viewtree($server['tree'], $server['url'], $server['name'], $server); 
	$Output .= "</div>\n";
	
	return $Output;
	
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>