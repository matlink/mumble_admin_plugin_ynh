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
//********************************************************************************************************************//
// **START**  Definiere globale MAP-Variablen
// **ACHTUNG** Ändere diese Variablen niemals ab, oder im MAP kommt es zu Fehlfunktionen. Dies ist keine Config-Datei!
// **WARNING** Never change this variables, this may cause errors in MAP! This is not a config file!
//********************************************************************************************************************//

	/*
	 * Listenzwischenspeicher für diverse Listenausgaben
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="admin"; section="default";
	 * -> dir="email"; section="list_templates";
	 * -> dir="email"; section="default";
	 * -> dir="log"; section="show_log";
	 * -> dir="log"; section="default";
	 * -> dir="permissions"; section="delete_group";
	 * -> dir="permissions"; section="list_admin";
	 * -> dir="permissions"; section="list_group";
	 * -> dir="request"; section="list_accounts";
	 * -> dir="server"; section="create_server";
	 * -> dir="server"; section="list_server";
	 * -> dir="user"; section="create_user";
	 * -> dir="user"; section="default";
	 * -> dir="view"; section="default";
	 */
	$list = FALSE;
	
	/*
	 * Wird benutzt im Keys zu ersetzen
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="email"; section="edit_template";
	 */
	$placeholder = FALSE;
	
	/*
	 * Zwischenspeicher für ID´s jeglicher Art
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="log"; section="delete_log_db";
	 */
	$ids = FALSE;
	
	/*
	 * ID eines virtuellen Servers
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="server"; section="show_server";
	 */
	$server_id = FALSE;
	
	/*
	 * URL eines virtuellen Servers im MAP
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="admin"; section="profile";
	 */
	$server_url = FALSE;
	
	/*
	 * Funktionsaufruf der Template-Funktionen im MAP
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="admin"; section="delete_admin";
	 * -> dir="request"; section="activate_account";
	 * -> dir="request"; section="delete_account";
	 * -> dir="server"; section="delete_server";
	 * -> dir="user"; section="delete_user";
	 */
	$index = FALSE;
	
	/*
	 * Definiert diverse Ergebniszwischenspeicher im MAP
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="email"; section="settings_header_db";
	 * -> dir="email"; section="settings_footer_db";
	 */
	$result = FALSE;
	
	/*
	 * Meldebox für Abschlussmeldungen im MAP
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="admin"; section="create_admin_db";
	 * -> dir="admin"; section="delete_admin_db";
	 * -> dir="admin"; section="delete_admin";
	 * -> dir="email"; section="delete_db";
	 * -> dir="email"; section="edit_db";
	 * -> dir="email"; section="send_again_db";
	 * -> dir="log"; section="delete_log_db";
	 * -> dir="request"; section="activate_account_db";
	 * -> dir="request"; section="delete_account_db";
	 * -> dir="request"; section="edit_account_db";
	 * -> dir="server"; section="edit_server_db";
	 * -> dir="server"; section="delete_server_db";
	 * -> dir="server"; section="delete_server";
	 * -> dir="server"; section="start_virtual_server";
	 * -> dir="server"; section="stop_virtual_server";
	 * -> dir="server"; section="restart_virtual_server";
	 * -> dir="user"; section="delete_user_db";
	 */
	$info = FALSE;
	
	/*
	 * Weiterleitungsoption
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="permissions"; section="edit_group_db";
	 * -> dir="permissions"; section="edit_admin_db";
	 * -> dir="server"; section="edit_server_db";
	 * -> dir="server"; section="edit_user_db";
	 */
	$true_change = FALSE;
	
	/*
	 * Parameterübergabe für Weiterleitungen im MAP
	 * FIXME Aktuell noch fehlerhafte Behandlung dieser Variable im MAP:
	 * -> dir="admin"; section="create_admin_db";
	 * -> dir="operate"; section="forgot_pwd_backroute";
	 * -> dir="server"; section="create_server_db";
	 * -> dir="server"; section="edit_server_db";
	 */
	$autoforward = FALSE;
	
//************************************************************************************************//
// **ENDE**  Definiere globale MAP-Variablen
//************************************************************************************************//
?>