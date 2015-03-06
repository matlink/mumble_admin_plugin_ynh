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

// Content - Navi
if (!isset($content_headers['navi_on'])) {$content_headers['navi_on'] = FALSE;}
if (!isset($_SERVER['HTTP_REFERER'])) {$_SERVER['HTTP_REFERER'] = FALSE;}
if ($content_headers['navi_on'] == TRUE) {
	// Lädt HTML-Datei
	//Standart Navi
	if ($content_headers['navi_type'] == "default") {
		$_ContentNavi = show("content/navi_default", array("back" => _layout_navi_default_back,
														   "referer" => $_SERVER['HTTP_REFERER'],
														   "tpldir" => $tpldir,
		                                                   ));
	}
	//virtuelle Server auflisten
	if ($content_headers['navi_type'] == "list_server") {
		$_ContentNavi = show("content/navi_list_server", array("show" => _layout_navi_list_sever_show,
															   "start" => _layout_navi_list_sever_start,
															   "stop" => _layout_navi_list_sever_stop,
															   "restart" => _layout_navi_list_sever_restart,
															   "edit" => _layout_navi_list_server_edit,
															   "delete" => _layout_navi_list_server_delete,
															   "add" => _layout_navi_list_server_add_server,
															   "back" => _layout_navi_default_back,
															   "referer" => $_SERVER['HTTP_REFERER'],
														       "tpldir" => $tpldir,
															   "search" => _layout_navi_list_server_search,
															   "check_all" => _layout_navi_list_server_check_all,
		                                                       ));
	}
	//virtuelle Server editieren
	if ($content_headers['navi_type'] == "create_server") {
		$_ContentNavi = show("content/navi_create_server", array("back" => _layout_navi_default_back,
															     "referer" => $_SERVER['HTTP_REFERER'],
															     "tpldir" => $tpldir,
	                                                       	     "save" => _create_server_do,
		                                                  	     ));
	}
	//virtuelle Server editieren
	if ($content_headers['navi_type'] == "edit_server") {
		$_ContentNavi = show("content/navi_edit_server", array("back" => _layout_navi_default_back,
															   "referer" => $_SERVER['HTTP_REFERER'],
															   "tpldir" => $tpldir,
	                                                       	   "save" => _edit_server_do,
		                                                  	   ));
	}
	//virtuelle Server löschen
	if ($content_headers['navi_type'] == "delete_server") {
		$_ContentNavi = show("content/navi_delete_server", array("back" => _layout_navi_default_back,
															     "referer" => $_SERVER['HTTP_REFERER'],
															     "tpldir" => $tpldir,
	                                                       	     "save" => _delete_server_do,
		                                                  	     ));
	}
	//Massenmail an User des Servers
	if ($content_headers['navi_type'] == "mass_mail_server") {
		$_ContentNavi = show("content/navi_mass_mail_server", array("back" => _layout_navi_default_back,
															        "referer" => $_SERVER['HTTP_REFERER'],
															        "tpldir" => $tpldir,
	                                                       	        "save" => _mass_mail_server_send_do,
		                                                  	        ));
	}
	//Massenmail an Kunden des Servers
	if ($content_headers['navi_type'] == "admin_mail_server") {
		$_ContentNavi = show("content/navi_admin_mail_server", array("back" => _layout_navi_default_back,
															         "referer" => $_SERVER['HTTP_REFERER'],
															         "tpldir" => $tpldir,
	                                                       	         "save" => _admin_mail_server_send_do,
		                                                  	         ));
	}
	//Massenmail an Reseller
	if ($content_headers['navi_type'] == "mail_reseller") {
		$_ContentNavi = show("content/navi_mail_reseller", array("back" => _layout_navi_default_back,
															         "referer" => $_SERVER['HTTP_REFERER'],
															         "tpldir" => $tpldir,
	                                                       	         "save" => _map_admin_mail_server_send_do,
		                                                  	         ));
	}
	//Channelviewer Einstellungen speichern
	if ($content_headers['navi_type'] == "settings_channelviewer") {
		$_ContentNavi = show("content/navi_settings_channelviewer", array("back" => _layout_navi_default_back,
															        	  "referer" => $_SERVER['HTTP_REFERER'],
																          "tpldir" => $tpldir,
		                                                       	          "save" => _view_settings_do,
			                                                  	   	      ));
	}
	//User auflisten
	if ($content_headers['navi_type'] == "list_user") {
		$_ContentNavi = show("content/navi_list_user", array("edit" => _layout_navi_list_user_edit,
															 "delete" => _layout_navi_list_user_delete,
															 "back" => _layout_navi_default_back,
															 "add" => _layout_navi_list_server_add_user,
															 "referer" => $_SERVER['HTTP_REFERER'],
														     "tpldir" => $tpldir,
															 "search" => _layout_navi_list_user_search,
															 "check_all" => _layout_navi_list_user_check_all,
		                                                     ));
	}
	//User editieren
	if ($content_headers['navi_type'] == "create_user") {
		$_ContentNavi = show("content/navi_create_user", array("back" => _layout_navi_default_back,
															   "referer" => $_SERVER['HTTP_REFERER'],
														       "tpldir" => $tpldir,
															   "save" => _create_user_do,
		                                                       ));
	}
	//User editieren
	if ($content_headers['navi_type'] == "edit_user") {
		$_ContentNavi = show("content/navi_edit_user", array("back" => _layout_navi_default_back,
															 "referer" => $_SERVER['HTTP_REFERER'],
														     "tpldir" => $tpldir,
															 "save" => _edit_user_do,
		                                                     ));
	}
	//User löschen
	if ($content_headers['navi_type'] == "delete_user") {
		$_ContentNavi = show("content/navi_delete_user", array("back" => _layout_navi_default_back,
															   "referer" => $_SERVER['HTTP_REFERER'],
														       "tpldir" => $tpldir,
															   "save" => _delete_user_do,
		                                                       ));
	}
	//Nachricht an verbundenen User senden
	if ($content_headers['navi_type'] == "message_to_user") {
		$_ContentNavi = show("content/navi_message_to_user", array("back" => _layout_navi_default_back,
															       "referer" => $_SERVER['HTTP_REFERER'],
																   "tpldir" => $tpldir,
		                                                       	   "save" => _message_to_server_user_do,
			                                                  	   ));
	}
	//Verbundenen User registrieren
	if ($content_headers['navi_type'] == "register_user") {
		$_ContentNavi = show("content/navi_register_user", array("back" => _layout_navi_default_back,
															     "referer" => $_SERVER['HTTP_REFERER'],
																 "tpldir" => $tpldir,
		                                                       	 "save" => _register_server_user_do,
			                                                  	 ));
	}
	//Verbundenen User Kommentar erstellen
	if ($content_headers['navi_type'] == "create_comment_user") {
		$_ContentNavi = show("content/navi_create_comment_user", array("back" => _layout_navi_default_back,
															           "referer" => $_SERVER['HTTP_REFERER'],
																       "tpldir" => $tpldir,
		                                                       	       "save" => _create_comment_server_user_do,
			                                                  	       ));
	}
	//Verbundenen User Kommentar editieren
	if ($content_headers['navi_type'] == "edit_comment_user") {
		$_ContentNavi = show("content/navi_edit_comment_user", array("back" => _layout_navi_default_back,
															         "referer" => $_SERVER['HTTP_REFERER'],
																     "tpldir" => $tpldir,
		                                                       	     "save" => _edit_comment_server_user_do,
			                                                  	     ));
	}
	//Admins auflisten
	if ($content_headers['navi_type'] == "list_admin") {
		$_ContentNavi = show("content/navi_list_admin", array("profile" => _layout_navi_list_admin_profile,
															  "edit" => _layout_navi_list_admin_edit,
															  "delete" => _layout_navi_list_admin_delete,
															  "lock" => _layout_navi_list_admin_lock,
															  "back" => _layout_navi_default_back,
															  "add" => _layout_navi_list_server_add_admin,
															  "referer" => $_SERVER['HTTP_REFERER'],
														      "tpldir" => $tpldir,
															  "search" => _layout_navi_list_admin_search,
															  "check_all" => _layout_navi_list_admin_check_all,
		                                                      ));
	}
	//Admins erstellen
	if ($content_headers['navi_type'] == "create_admin") {
		$_ContentNavi = show("content/navi_create_admin", array("back" => _layout_navi_default_back,
															    "referer" => $_SERVER['HTTP_REFERER'],
															    "tpldir" => $tpldir,
	                                                       	    "save" => _create_admin_do,
		                                                  	    ));
	}
	//Admins bearbeiten
	if ($content_headers['navi_type'] == "edit_admin") {
		$_ContentNavi = show("content/navi_edit_admin", array("back" => _layout_navi_default_back,
															  "referer" => $_SERVER['HTTP_REFERER'],
															  "tpldir" => $tpldir,
	                                                       	  "save" => _edit_admin_do,
		                                                  	  ));
	}
	//Admins löschen
	if ($content_headers['navi_type'] == "delete_admin") {
		$_ContentNavi = show("content/navi_delete_admin", array("back" => _layout_navi_default_back,
															    "referer" => $_SERVER['HTTP_REFERER'],
															    "tpldir" => $tpldir,
	                                                       	    "save" => _delete_admin_do,
		                                                  	    ));
	}
	//Admins Avatar bearbeiten
	if ($content_headers['navi_type'] == "avatar_admin") {
		$_ContentNavi = show("content/navi_avatar_admin", array("back" => _layout_navi_default_back,
															    "referer" => $_SERVER['HTTP_REFERER'],
															    "tpldir" => $tpldir,
	                                                       	    "save" => _avatar_admin_do,
		                                                  	    ));
	}
	//Konto Beantragen Frontend
	if ($content_headers['navi_type'] == "request_frontend") {
		$_ContentNavi = show("content/navi_request_frontend", array("back" => _layout_navi_default_back,
															    	"referer" => $_SERVER['HTTP_REFERER'],
															    	"tpldir" => $tpldir,
	                                                       	    	"save" => _request_account_do,
		                                                  	   		));
	}
	//Konto Beantragen Backend
	if ($content_headers['navi_type'] == "request_backend") {
		$_ContentNavi = show("content/navi_request_backend", array("back" => _layout_navi_default_back,
															       "referer" => $_SERVER['HTTP_REFERER'],
															       "tpldir" => $tpldir,
	                                                       	       "save" => _request_account_do,
		                                                  	   	   ));
	}
	//Konto Beantragen Listenansicht
	if ($content_headers['navi_type'] == "list_accounts") {
		$_ContentNavi = show("content/navi_list_accounts", array("edit" => _list_requested_accounts_edit,
															 	 "delete" => _list_requested_accounts_delete,
																 "activate" => _list_requested_accounts_activate,
																 "back" => _layout_navi_default_back,
															 	 "referer" => $_SERVER['HTTP_REFERER'],
														     	 "tpldir" => $tpldir,
																 "search" => _layout_navi_list_user_search,
																 "check_all" => _layout_navi_list_user_check_all,
		                                                    	 ));
	}
	//Konto Beantragen Editieren
	if ($content_headers['navi_type'] == "request_edit") {
		$_ContentNavi = show("content/navi_request_edit", array("back" => _layout_navi_default_back,
															    "referer" => $_SERVER['HTTP_REFERER'],
															    "tpldir" => $tpldir,
	                                                       	    "save" => _edit_account_do,
		                                                  	   	));
	}
	//Konto Beantragen Ablehnen/Löschen
	if ($content_headers['navi_type'] == "request_delete") {
		$_ContentNavi = show("content/navi_request_delete", array("back" => _layout_navi_default_back,
															      "referer" => $_SERVER['HTTP_REFERER'],
															      "tpldir" => $tpldir,
	                                                       	      "save" => _defeat_account_do,
		                                                  	   	  ));
	}
	//Konto Beantragen freischalten
	if ($content_headers['navi_type'] == "request_activate") {
		$_ContentNavi = show("content/navi_request_activate", array("back" => _layout_navi_default_back,
															        "referer" => $_SERVER['HTTP_REFERER'],
															        "tpldir" => $tpldir,
	                                                       	        "save" => _activate_account_do,
		                                                  	   	    ));
	}
	//Vergessen Funktion Adminname zusenden
	if ($content_headers['navi_type'] == "forgot_uname_map") {
		$_ContentNavi = show("content/navi_forgot_uname_map", array("back" => _layout_navi_default_back,
															        "referer" => $_SERVER['HTTP_REFERER'],
															        "tpldir" => $tpldir,
	                                                       	        "save" => _operate_forgot_uname_map_do,
		                                                  	   	    ));
	}
	//Vergessen Funktion Username zusenden
	if ($content_headers['navi_type'] == "forgot_uname_server") {
		$_ContentNavi = show("content/navi_forgot_uname_server", array("back" => _layout_navi_default_back,
															           "referer" => $_SERVER['HTTP_REFERER'],
															           "tpldir" => $tpldir,
	                                                       	           "save" => _operate_forgot_uname_server_do,
		                                                  	   	       ));
	}
	//Vergessen Funktion Admin - Passwort zusenden
	if ($content_headers['navi_type'] == "forgot_pwd_map") {
		$_ContentNavi = show("content/navi_forgot_pwd_map", array("back" => _layout_navi_default_back,
															      "referer" => $_SERVER['HTTP_REFERER'],
															      "tpldir" => $tpldir,
	                                                       	      "save" => _operate_forgot_pwd_map_do,
		                                                  	   	  ));
	}
	//Vergessen Funktion User - Passwort zusenden
	if ($content_headers['navi_type'] == "forgot_pwd_server") {
		$_ContentNavi = show("content/navi_forgot_pwd_server", array("back" => _layout_navi_default_back,
															         "referer" => $_SERVER['HTTP_REFERER'],
															         "tpldir" => $tpldir,
	                                                       	         "save" => _operate_forgot_pwd_server_do,
		                                                  	   	     ));
	}
	//Vergessen Funktion Passwort bestätigen
	if ($content_headers['navi_type'] == "forgot_pwd_backroute") {
		$_ContentNavi = show("content/navi_forgot_pwd_backroute", array("back" => _layout_navi_default_back,
															            "referer" => $_SERVER['HTTP_REFERER'],
															            "tpldir" => $tpldir,
	                                                       	            "save" => _operate_forgot_pwd_backroute_do,
		                                                  	   	        ));
	}
	//MAP-Logs anzeigen
	if ($content_headers['navi_type'] == "list_log") {
		$_ContentNavi = show("content/navi_list_log", array("back" => _layout_navi_default_back,
															"referer" => $_SERVER['HTTP_REFERER'],
															"tpldir" => $tpldir,
	                                                       	"delete" => _delete_log_do,
															"search" => _layout_navi_list_groups_search,
															"check_all" => _layout_navi_list_groups_check_all,
		                                                  	));
	}
	//E-Mail: Liste der gesendeten E-Mails anzeigen
	if ($content_headers['navi_type'] == "email_list_mails") {
		$_ContentNavi = show("content/navi_email_list_mails", array("back" => _layout_navi_default_back,
																	"show" => _layout_navi_list_email_show,
																	"send_again_db" => _layout_navi_list_email_send_again,
																	"edit" => _layout_navi_list_email_edit,
															 		"delete_db" => _layout_navi_list_email_delete,
																	"referer" => $_SERVER['HTTP_REFERER'],
																    "tpldir" => $tpldir,
																	"search" => _layout_navi_list_user_search,
																	"check_all" => _layout_navi_list_user_check_all,
				                                                    ));
	}
	//E-Mail: gesendete E-Mail anzeigen
	if ($content_headers['navi_type'] == "email_show_mail") {
		$_ContentNavi = show("content/navi_email_show_mail", array("back" => _layout_navi_default_back,
																   "send_again" => _layout_navi_list_email_send_again,
																   "edit" => _layout_navi_list_email_edit,
															       "delete" => _layout_navi_list_email_delete,
																   "referer" => $_SERVER['HTTP_REFERER'],
																   "tpldir" => $tpldir,
																   "email_id" => $email_id,
				                                                   ));
	}
	//E-Mail: gesendete E-Mail bearbeiten und erneut versenden
	if ($content_headers['navi_type'] == "email_edit_mail") {
		$_ContentNavi = show("content/navi_email_edit_mail", array("back" => _layout_navi_default_back,
															       "referer" => $_SERVER['HTTP_REFERER'],
															       "tpldir" => $tpldir,
	                                                       	       "save" => _layout_navi_edit_mail_do,
		                                                  	   	   ));
	}
	//E-Mail: Liste der E-Mail Templates anzeigen
	if ($content_headers['navi_type'] == "email_list_templates") {
		$_ContentNavi = show("content/navi_email_list_templates", array("back" => _layout_navi_default_back,
																		"edit_template" => _layout_navi_list_template_edit,
																 		"lock" => _layout_navi_list_template_lock,
																		"referer" => $_SERVER['HTTP_REFERER'],
																	    "tpldir" => $tpldir,
																		"search" => _layout_navi_list_user_search,
																		"check_all" => _layout_navi_list_user_check_all,
					                                                    ));
	}
	//E-Mail: Templates bearbeiten
	if ($content_headers['navi_type'] == "email_edit_template") {
		$_ContentNavi = show("content/navi_email_edit_template", array("back" => _layout_navi_default_back,
															       	   "referer" => $_SERVER['HTTP_REFERER'],
															           "tpldir" => $tpldir,
	                                                       	           "save" => _layout_navi_edit_email_template_do,
		                                                  	   	       ));
	}
	//E-Mail: Header bearbeiten
	if ($content_headers['navi_type'] == "settings_header") {
		$_ContentNavi = show("content/navi_settings_header", array("back" => _layout_navi_default_back,
															       "referer" => $_SERVER['HTTP_REFERER'],
															       "tpldir" => $tpldir,
	                                                       	       "save" => _settings_header_do,
		                                                  	       ));
	}
	//E-Mail: Footer bearbeiten
	if ($content_headers['navi_type'] == "settings_footer") {
		$_ContentNavi = show("content/navi_settings_footer", array("back" => _layout_navi_default_back,
															       "referer" => $_SERVER['HTTP_REFERER'],
															       "tpldir" => $tpldir,
	                                                       	       "save" => _settings_footer_do,
		                                                  	       ));
	}
	//Berechtigungsgruppen auflisten
	if ($content_headers['navi_type'] == "list_groups") {
		$_ContentNavi = show("content/navi_list_groups", array("edit" => _layout_navi_list_groups_edit,
															   "delete" => _layout_navi_list_groups_delete,
															   "back" => _layout_navi_default_back,
															   "add" => _layout_navi_list_server_add_group,
															   "referer" => $_SERVER['HTTP_REFERER'],
														       "tpldir" => $tpldir,
															   "search" => _layout_navi_list_groups_search,
															   "check_all" => _layout_navi_list_groups_check_all,
		                                                       ));
	}
	//Berechtigungsgrupe erstellen
	if ($content_headers['navi_type'] == "create_group") {
		$_ContentNavi = show("content/navi_create_group", array("back" => _layout_navi_default_back,
															    "referer" => $_SERVER['HTTP_REFERER'],
																"tpldir" => $tpldir,
	                                                       		"save" => _perm_create_group_do,
		                                                  	    ));
	}
	//Berechtigungsgrupe editieren
	if ($content_headers['navi_type'] == "edit_group") {
		$_ContentNavi = show("content/navi_edit_group", array("back" => _layout_navi_default_back,
															  "referer" => $_SERVER['HTTP_REFERER'],
															  "tpldir" => $tpldir,
	                                                       	  "save" => _perm_edit_group_do,
		                                                  	  ));
	}
	//Berechtigungsgrupe löschen
	if ($content_headers['navi_type'] == "delete_group") {
		$_ContentNavi = show("content/navi_delete_group", array("back" => _layout_navi_default_back,
															    "referer" => $_SERVER['HTTP_REFERER'],
															    "tpldir" => $tpldir,
	                                                         	"save" => _perm_delete_group_do,
		                                                    	));
	}
	//Admin Berechtigungen auflisten
	if ($content_headers['navi_type'] == "perm_list_admin") {
		$_ContentNavi = show("content/navi_perm_list_admin", array("edit" => _layout_navi_list_admin_edit,
															  	   "back" => _layout_navi_default_back,
															  	   "referer" => $_SERVER['HTTP_REFERER'],
														           "tpldir" => $tpldir,
															       "search" => _layout_navi_list_groups_search,
		                                                           ));
	}
	//Admin Berechtigungen editieren
	if ($content_headers['navi_type'] == "edit_admin") {
		$_ContentNavi = show("content/navi_perm_edit_admin", array("back" => _layout_navi_default_back,
															       "referer" => $_SERVER['HTTP_REFERER'],
															  	   "tpldir" => $tpldir,
	                                                       	       "save" => _perm_edit_admin_do,
		                                                  	       ));
	}
	//Globale MAP Einstellungen
	if ($content_headers['navi_type'] == "settings") {
		$_ContentNavi = show("content/navi_settings", array("back" => _layout_navi_default_back,
															"referer" => $_SERVER['HTTP_REFERER'],
															"tpldir" => $tpldir,
															"mail_check" => _settings_show_mail_check,
															"mail_header" => _settings_show_mail_header,
															"mail_footer" => _settings_show_mail_footer,
															"mail_template" => _settings_show_mail_template,
	                                                        "save" => _settings_show_do,
		                                                    ));
	}
	} else {
		$_ContentNavi = FALSE;
}

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>