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

// Definiere Globale Variablen
$_SLICE_ERR = FALSE;

//Lade ZeroC ICE Extesion und speichere Meta in $_SLICE und führe alle notwendigen funktionen aus 
//die mit slice in einer try schleife ausgeführt werden müssen
try {
	
	//Lade Slice wenn das Modul geladen wurde
	if (extension_loaded('ice')) {
		//Lade Slice Interface wenn Slice-Version >= 3.4.x
		if (function_exists('Ice_intVersion') && Ice_intVersion() > 30400) {
			//Lade Slice 3.4.x Framework
			include_once('../inc/resources/slice/framework_3.4.php');
			//Lade die aus Slice generierten PHP-Dateien (slice2php)
		  	//include_once('../inc/resources/murmur/murmur_1.2.2.php');
		  	//include_once('../inc/resources/murmur/murmur_1.2.3.php');
		  	include_once('../inc/resources/murmur/murmur_1.2.4.php');
			//Initialisiere Slice 3.4.x	
			$initData = new Ice_InitializationData;
			$initData->properties = Ice_createProperties();
			$initData->properties->setProperty('Ice.ImplicitContext', 'Shared');
			$ICE = Ice_initialize($initData);
			try {
				$_BASE = Murmur_MetaPrxHelper::checkedCast($ICE->stringToProxy("Meta:tcp -h ".$SliceHost." -p ".$SlicePort.""));
				$_SLICE = $_BASE->ice_context($SliceSecret);
				//Setze Slice-Secret von Murmur
				$_SLICE->icesecret = $SliceSecret;	
				} catch (Ice_ConnectionRefusedException $error) {
					$_SLICE_ERR = TRUE;
			}
			} else {
				//Lade Slice Interface wenn Slice-Version <= 3.3.1
				//Initialisiere Slice <= 3.3.x
	 			Ice_loadProfile();
	 			//Definiere Murmur Host
	 			$_BASE = $ICE->stringToProxy("Meta:tcp -h ".$SliceHost." -p ".$SlicePort."");
				$_SLICE = $_BASE->ice_checkedCast("::Murmur::Meta")->ice_context($SliceSecret);
				//Setze Slice-Secret von Murmur
				$_SLICE->icesecret = $SliceSecret;
		}
		} else {
			$_SLICE_ERR = TRUE;
	}

	//Starte mit getters und setters
	//Hole User und Channel eines Servers
	function ice_exeption($SLICE, $status, $operation, $val_A = FALSE, $val_B = FALSE,
														$val_C = FALSE, $val_D = FALSE, $val_E = FALSE) {

		if ($status == TRUE) {
			if ($operation == "addCallback") {
				/*
				 * Function: Add a callback. The callback will receive notifications about changes to users and channels.
				 * $val_A: http://mumble.sourceforge.net/slice/Murmur/ServerCallback.html
				 */
				$return = $SLICE->addCallback($val_A);
			}
			if ($operation == "removeCallback") {
				/*
				 * Function: Remove a callback.
				 * $val_A: http://mumble.sourceforge.net/slice/Murmur/ServerCallback.html
				 */
				$return = $SLICE->removeCallback($val_A);
			}
			if ($operation == "setAuthenticator") {
				/*
				 * Function: Set external authenticator. If set, all authentications from clients are forwarded to this proxy.
				 * $val_A: http://mumble.sourceforge.net/slice/Murmur/ServerAuthenticator.html
				 */
				$return = $SLICE->setAuthenticator($val_A);
			}
			if ($operation == "getLog") {
				/*
				 * Function: Fetch log entries.
				 * $val_A: int first
				 * $val_B: int last
				 */
				$return = $SLICE->getLog($val_A, $val_B);
			}
			if ($operation == "getUsers") {
				/*
				 * Function: Fetch all users. This returns all currently connected users on the server.
				 * Return: List of connected users.
				 */
				$return = $SLICE->getUsers();
			}
			if ($operation == "getChannels") {
				/*
				 * Function: Fetch all channels. This returns all defined channels on the server. The root channel is always channel 0.
				 * Return: List of defined channels.
				 */
				$return = $SLICE->getChannels();
			}	
			if ($operation == "getCertificateList") {
				/*
				 * Function: Fetch certificate of user. This returns the complete certificate chain of a user.
				 * $val_A: int session
				 * Return: Certificate list of user.
				 */
				$return = $SLICE->getCertificateList($val_A);
			}
			if ($operation == "getTree") {
				/*
				 * Function: Fetch all channels and connected users as a tree. This retrieves an easy-to-use representation of the server as a tree. This is primarily used for viewing the state of the server on a webpage.
				 * Return: Recursive tree of all channels and connected users.
				 */
				$return = $SLICE->getTree();
			}
			if ($operation == "getBans") {
				/*
				 * Function: Fetch all current IP bans on the server.
				 * Return: List of bans.
				 */
				$return = $SLICE->getBans();
			}
			if ($operation == "setBans") {
				/*
				 * Function: Set all current IP bans on the server. This will replace any bans already present, so if you want to add a ban, be sure to call getBans and then append to the returned list before calling this method.
				 * $val_A: BanList bans -> http://mumble.sourceforge.net/slice/Murmur/Ban.html
				 */
				$return = $SLICE->setBans($val_A);
			}
			if ($operation == "kickUser") {
				/*
				 * Function: Kick a user. The user is not banned, and is free to rejoin the server.
				 * $val_A: int session
				 * $val_B: string reason
				 */
				$return = $SLICE->kickUser($val_A, $val_B);
			}
			if ($operation == "getState") {
				/*
				 * Function: Get state of a single connected user.
				 * $val_A: int session
				 * Return: State of connected user.
				 */
				$return = $SLICE->getState($val_A);
			}				
			if ($operation == "setState") {
				/*
				 * Function: Set user state. You can use this to move, mute and deafen users.
				 * $val_A: User state -> http://mumble.sourceforge.net/slice/Murmur/User.html
				 */
				$return = $SLICE->setState($val_A);
			}
			if ($operation == "sendMessage") {
				/*
				 * Function: Send text message to a single user.
				 * $val_A: int session
				 * $val_B: string text
				 */
				$return = $SLICE->sendMessage($val_A, $val_B);
			}
			if ($operation == "hasPermission") {
				/*
				 * Function: Check if user is permitted to perform action.
				 * $val_A: int session
				 * $val_B: int channelid
				 * $val_C: int perm
				 * Return: true if any of the permissions in perm were set for the user.
				 */
				$return = $SLICE->hasPermission($val_A, $val_B, $val_C);
			}
			if ($operation == "addContextCallback") {
				/*
				 * Function: Add a context callback. This is done per user, and will add a context menu action for the user.
				 * $val_A: int session
				 * $val_B: string action
				 * $val_C: string text
				 * $val_D: ServerContextCallback* cb -> http://mumble.sourceforge.net/slice/Murmur/ServerContextCallback.html
				 * $val_E: int ctx -> Context this should be used in. Needs to be one or a combination of ContextServer, ContextChannel and ContextUser.
				 */
				$return = $SLICE->addContextCallback($val_A, $val_B, $val_C, $val_D, $val_E);
			}
			if ($operation == "removeContextCallback") {
				/*
				 * Function: Remove a callback.
				 * $val_A: ServerContextCallback* cb -> http://mumble.sourceforge.net/slice/Murmur/ServerContextCallback.html
				 */
				$return = $SLICE->removeContextCallback($val_A);
			}
			if ($operation == "getChannelState") {
				/*
				 * Function: Get state of single channel.
				 * $val_A: int channelid
				 * Return: State of channel.
				 */
				$return = $SLICE->getChannelState($val_A);
			}
			if ($operation == "setChannelState") {
				/*
				 * Function: Set state of a single channel. You can use this to move or relink channels.
				 * $val_A: Channel state -> http://mumble.sourceforge.net/slice/Murmur/Channel.html
				 */
				$return = $SLICE->setChannelState($val_A);
			}
			if ($operation == "removeChannel") {
				/*
				 * Function: Remove a channel and all its subchannels.
				 * $val_A: int channelid
				 */
				$return = $SLICE->removeChannel($val_A);
			}
			if ($operation == "addChannel") {
				/*
				 * Function: Add a new channel.
				 * $val_A: string name
				 * $val_B: int parent
				 * Return: ID of newly created channel.
				 */
				$return = $SLICE->addChannel($val_A, $val_B);
			}
			if ($operation == "sendMessageChannel") {
				/*
				 * Function: Send text message to channel or a tree of channels.
				 * $val_A: int channelid
				 * $val_B: bool tree
				 * $val_C: string text
				 */
				$return = $SLICE->sendMessageChannel($val_A, $val_B, $val_C);
			}
			if ($operation == "getACL") {
				/*
				 * Function: Retrieve ACLs and Groups on a channel.
				 * $val_A: int channelid
				 * $val_B: out ACLList acls -> List of ACLs on the channel. This will include inherited ACLs.
				 * $val_C: out GroupList groups -> List of groups on the channel. This will include inherited groups.
				 * $val_D: out bool inherit -> Does this channel inherit ACLs from the parent channel?
				 */
				$return = $SLICE->getACL($val_A, $val_B, $val_C, $val_D);
			}
			if ($operation == "setACL") {
				/*
				 * Function: Set ACLs and Groups on a channel. Note that this will replace all existing ACLs and groups on the channel.
				 * $val_A: int channelid -> Channel ID of channel to fetch from. See Channel::id.
				 * $val_B: ACLList acls -> List of ACLs on the channel.
				 * $val_C: GroupList groups -> List of groups on the channel.
				 * $val_D: bool inherit -> Should this channel inherit ACLs from the parent channel?
				 */
				$return = $SLICE->setACL($val_A, $val_B, $val_C, $val_D);
			}
			if ($operation == "addUserToGroup") {
				/*
				 * Function: Temporarily add a user to a group on a channel. This state is not saved, and is intended for temporary memberships.
				 * $val_A: int channelid
				 * $val_B: int session
				 * $val_C: string group
				 */
				$return = $SLICE->addUserToGroup($val_A, $val_B, $val_C);
			}
			if ($operation == "removeUserFromGroup") {
				/*
				 * Function: Remove a user from a temporary group membership on a channel. This state is not saved, and is intended for temporary memberships.
				 * $val_A: int channelid
				 * $val_B: int session
				 * $val_C: string group
				 */
				$return = $SLICE->removeUserFromGroup($val_A, $val_B, $val_C);
			}
			if ($operation == "redirectWhisperGroup") {
				/*
				 * Function: Redirect whisper targets for user. If set, whenever a user tries to whisper to group "source", the whisper will be redirected to group "target". To remove a redirect pass an empty target string. This is intended for context groups.
				 * $val_A: int session
				 * $val_B: string source
				 * $val_C: string target
				 */
				$return = $SLICE->redirectWhisperGroup($val_A, $val_B, $val_C);
			}
			if ($operation == "getUserNames") {
				/*
				 * Function: Map a list of User::userid to a matching name.
				 * $val_A: IdList ids -> List of ids
				 * Return: Matching list of names, with an empty string representing invalid or unknown ids.
				 */
				$return = $SLICE->getUserNames($val_A);
			}
			if ($operation == "getUserIds") {
				/*
				 * Function: Map a list of user names to a matching id. @reuturn List of matching ids, with -1 representing invalid or unknown user names.
				 * $val_A: NameList names -> List of names
				 */
				$return = $SLICE->getUserIds($val_A);
			}
			if ($operation == "registerUser") {
				/*
				 * Function: Register a new user.
				 * $val_A: UserInfoMap info -> http://mumble.sourceforge.net/slice/Murmur/UserInfo.html
				 * Return: The ID of the user. See RegisteredUser::userid.
				 */
				$return = $SLICE->registerUser($val_A);
			}
			if ($operation == "unregisterUser") {
				/*
				 * Function: Remove a user registration.
				 * $val_A: int userid
				 */
				$return = $SLICE->unregisterUser($val_A);
			}
			if ($operation == "updateRegistration") {
				/*
				 * Function: Update the registration for a user. You can use this to set the email or password of a user, and can also use it to change the user's name.
				 * $val_A: int userid
				 * $val_B: UserInfoMap info
				 */
				$return = $SLICE->updateRegistration($val_A, $val_B);
			}
			if ($operation == "getRegistration") {
				/*
				 * Function: Fetch registration for a single user.
				 * $val_A: int userid
				 * Return: Registration record.
				 */
				$return = $SLICE->getRegistration($val_A);
			}
			if ($operation == "getRegisteredUsers")	{
				/*
				 * Function: Fetch a group of registered users.
				 * $val_A: string filter
				 * Return: List of registration records.
				 */
				$return = $SLICE->getRegisteredUsers("");
			}
			if ($operation == "verifyPassword") {
				/*
				 * Function: Verify the password of a user. You can use this to verify a user's credentials.
				 * $val_A: string name
				 * $val_B: string pw
				 * Return: User ID of registered user (See RegisteredUser::userid), -1 for failed authentication or -2 for unknown usernames.
				 */
				$return = $SLICE->verifyPassword($val_A, $val_B);
			}
			if ($operation == "getTexture") {
				/*
				 * Function: Fetch user texture. Textures are stored as zlib compress()ed 600x60 32-bit BGRA data.
				 * $val_A: int userid
				 * Return: Custom texture associated with user or an empty texture.
				 */
				$return = $SLICE->getTexture($val_A);
			}
			if ($operation == "setTexture") {
				/*
				 * Function: Set user texture. The texture is a 600x60 32-bit BGRA raw texture, optionally zlib compress()ed.
				 * $val_A: int userid
				 * $val_B: Texture tex
				 */
				$return = $SLICE->setTexture($val_A, $val_B);
			}
			if ($operation == "getUptime") {
				/*
				 * Function: Get virtual server uptime.
				 * Return: Uptime of the virtual server in seconds
				 */
				$return = $SLICE->getUptime();
			}
			} else {
				$return = FALSE;
		}
		
		return $return;
		
	}

	//set und get Funktionen		
	//Hole Daten eines virtuellen Servers
	function getServer($server_id) {
		
		global $_SLICE;
		$server = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				$VERSION = $_SLICE->getVersion($major, $minor, $patch, $text);
				$server = array("online" => $SLICE->isRunning(),
								"ip" => $SLICE->getConf("host"),
								"port" => $SLICE->getConf("port"),
								"users" => $SLICE->getConf("users"),
								"password" => $SLICE->getConf("password"),
								"serverpassword" => $SLICE->getConf("serverpassword"),
								"timeout" => $SLICE->getConf("timeout"),
								"channelname" => $SLICE->getConf("channelname"),
								"username" => $SLICE->getConf("username"),
								"defaultchannel" => $SLICE->getConf("defaultchannel"),
								"registerHostname" => $SLICE->getConf("registerhostname"),
								"registerName" => $SLICE->getConf("registername"),
								"registerPassword" => $SLICE->getConf("registerpassword"),
								"registerUrl" => $SLICE->getConf("registerurl"),
								"registerLocation" => $SLICE->getConf("registerlocation"),
								"bandwidth" => $SLICE->getConf("bandwidth"),
				                "imagemessagelength" => $SLICE->getConf("imagemessagelength"),
							    "textmessagelength" => $SLICE->getConf("textmessagelength"),
							    "usersperchannel" => $SLICE->getConf("usersperchannel"),
								"sslca" => $SLICE->getConf("ca"), // "sslca" oder "ca" ????
								"sslcert" => $SLICE->getConf("certificate"),
								"sslkey" => $SLICE->getConf("key"),
								"sslpassphrase" => $SLICE->getConf("passphrase"),
								"channelnestinglimit" => $SLICE->getConf("channelnestinglimit"),
								"opusthreshold" => $SLICE->getConf("opusthreshold"),
								"suggestpositional" => $SLICE->getConf("suggestpositional"),
								"suggestpushtotalk" => $SLICE->getConf("suggestpushtotalk"),
								"suggestversion" => $SLICE->getConf("suggestversion"),
								"certrequired" => $SLICE->getConf("certrequired"),
							    "obfuscate" => $SLICE->getConf("obfuscate"),
							    "rememberchannel" => $SLICE->getConf("rememberchannel"),
							    "allowhtml" => $SLICE->getConf("allowhtml"),
							    "allowping" => $SLICE->getConf("allowping"),
								"sendversion" => $SLICE->getConf("sendversion"),
								"bonjour" => $SLICE->getConf("bonjour"),
								"welcometext" => $SLICE->getConf("welcometext"),			
								"connected_users" => ice_exeption($SLICE, $SLICE->isRunning(), "getUsers"),
								"registered_users" => ice_exeption($SLICE, $SLICE->isRunning(), "getRegisteredUsers"),		
								"channels" => ice_exeption($SLICE, $SLICE->isRunning(), "getChannels"),
								"tree" => ice_exeption($SLICE, $SLICE->isRunning(), "getTree"),
								"version" => $major.'.'.$minor.'.'.$patch,
								"version_detailed" => $text,
								"compiled" => $text,
								);
				$return = TRUE;
				} else {
					$return = FALSE;
		    		$_SLICE_ERR = TRUE;	
			}
			} catch (Exception $e) {
	    		$return = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
		return $server;

	}
	
	//Hole alle vorhandene virtuellen Server aus dem Slice Interface
	function getAllServers() {

		global $_SLICE;
		$return = FALSE;
		$ids = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				$servers = $_SLICE->getAllServers();
				foreach($servers as $server_id) {
		    		$ids[] = $server_id->ice_context($_SLICE->icesecret)->id();
				}
				$return = TRUE;
				} else {
					$return = FALSE;
	    			$_SLICE_ERR = TRUE;
			}			
			} catch (Exception $e) {
	    		$return = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
	    if (!$return) {
	    	$ids = FALSE;
	    }
	    
		return $ids;
		
	}

	//Setze Daten eines virtuellen Servers
	function setServer($server_id, $key, $value) {
		
		global $_SLICE;
		$return = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				$SLICE->setConf(utf8_encode($key), $value);
				$return = TRUE;
				} else {
					$return = FALSE;
	    			$_SLICE_ERR = TRUE;
			}	
			} catch (Exception $e) {
	    		$return = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }		
	    
		return $return;
		
	}
	
	//Erstelle einen neuen virtuellen Server
	function newServer() {
		
		global $_SLICE;
		$SLICE = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				$SLICE = $_SLICE->newServer()->ice_context($_SLICE->icesecret)->id();
				} else {
					$return = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$SLICE = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }		
	    	
		return $SLICE;
		
	}
	
	//Lösche einen virtuellen Server
	function deleteServer($server_id) {
		
		global $_SLICE;
		$return = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				$SLICE->delete();
				$return = TRUE;
				} else {
					$return = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$return = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }		
	    
		return $return;
		
	}
	
	//Server starten
	function setServerStart($server_id) {
		
		global $_SLICE;
		$return = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				$SLICE->start();
				$return = TRUE;
				} else {
					$return = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$return = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
		return $return;
		
	}
	
	//Server stoppen
	function setServerStop($server_id) {
		
		global $_SLICE;
		$return = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				$SLICE->stop();
				$return = TRUE;
				} else {
					$return = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$return = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
		
		return $return;
		
	}
	
	//Hole Laufzeit eines virtuellen Servers
	function getVirtualServerUptime($server_id) {
		
		global $_SLICE;
		$return = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Hole Regstrierungsdaten des Users
				$return = ice_exeption($SLICE, $SLICE->isRunning(), "getUptime");
				} else {
					$return = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$return = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
		return $return;
		
	}
	
	//Speichere neuen User
	function registerUser($server_id, $UserInfoMap) {
		
		global $_SLICE;
		$user_id = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Hole Regstrierungsdaten des Users
				$user_id = ice_exeption($SLICE, $SLICE->isRunning(), "registerUser", $UserInfoMap);
				} else {
					$user_id = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$user_id = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
		return $user_id;
		
	}
	
	//Hole user_id zu usernamen
	function getUserIds($server_id, $idListArray) {
		
		global $_SLICE;
		$idList = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Hole Regstrierungsdaten des Users
				$idList = ice_exeption($SLICE, $SLICE->isRunning(), "getUserIds", $idListArray);
				} else {
					$idList = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$idList = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
		return $idList;
		
	}

	//Hole Information zu Server User
	function getRegistration($server_id, $user_id) {
		
		global $_SLICE;
		$user = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Hole Regstrierungsdaten des Users
				$UserMap = ice_exeption($SLICE, $SLICE->isRunning(), "getRegistration", intval($user_id));
		
				$user = @array("name" => $UserMap[0],
							   "email" => $UserMap[1],
							   "comment" => $UserMap[2],
							   "hash" => $UserMap[3],
				               "password" => $UserMap[4],
							   "lastactive" => str_replace("T", " ", $UserMap[5]),
							   "user_id" => $user_id,
							   "server_id" => $server_id,
							   );
				} else {
					$user = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$user = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
		return $user;
		
	}
	
	//Speichere Information zu Server User
	function setRegistration($server_id, $user_id, $UserInfoMap) {
		
		global $_SLICE;
		$UserMap = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Hole Regstrierungsdaten des Users
				$UserMap = ice_exeption($SLICE, $SLICE->isRunning(), "updateRegistration", intval($user_id), $UserInfoMap);
				} else {
					$UserMap = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$UserMap = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    		  
		return $UserMap;
		
	}
	
	//User löschen
	function deleteUser($server_id, $user_id) {
		
		global $_SLICE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Hole Regstrierungsdaten des Users
				$UserMap = ice_exeption($SLICE, $SLICE->isRunning(), "unregisterUser", $user_id);
				} else {
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$_SLICE_ERR = TRUE;
	    }
	}
	
	//Passwort eines Users verifizieren
	function verifyUsersPassword($server_id, $name, $pw) {
		
		global $_SLICE;
		$return = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Hole Regstrierungsdaten des Users
				$UserMap = ice_exeption($SLICE, $SLICE->isRunning(), "verifyPassword", $name, $pw);
				
				if ($UserMap == "-1" OR $UserMap == "-2") { 
					$return['result'] = FALSE;
					$return['user_id'] = FALSE;
					} else { 
						$return['result'] = TRUE;
						$return['user_id'] = $UserMap;
				}
				} else {
					$return = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$return = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
		return $return;
	}
	
	//Hole den Status eines verbundenen Users
	function getUserState($server_id, $session) {
		
		global $_SLICE;
		$UserState = FALSE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Hole Status des Users
				$UserState = ice_exeption($SLICE, $SLICE->isRunning(), "getState", intval($session));
				} else {
					$UserState = FALSE;
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$UserState = FALSE;
	    		$_SLICE_ERR = TRUE;
	    }
	    
		return $UserState;
	}
	
	//Setze den Status eines verbundenen Users
	function setUserState($server_id, $UserState) {
		
		global $_SLICE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Setze Status des Users
				$UserState = ice_exeption($SLICE, $SLICE->isRunning(), "setState", $UserState);
				} else {
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$_SLICE_ERR = TRUE;
	    }
	}
	
	//Kicke einen verbundenen User
	function kickUser($server_id, $session, $reason) {
		
		global $_SLICE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Kicke den User
				$UserState = ice_exeption($SLICE, $SLICE->isRunning(), "kickUser", intval($session), $reason);
				} else {
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$_SLICE_ERR = TRUE;
	    }
	}
	
	//sende eine Nachricht an verbundenen User
	function sendMessagetoUser($server_id, $session, $text) {
		
		global $_SLICE;
		
		try {
			if (is_object($_SLICE)) {
				//Hole Server
				$SLICE = $_SLICE->getServer(intval($server_id))->ice_context($_SLICE->icesecret);
				
				//Kicke den User
				$UserState = ice_exeption($SLICE, $SLICE->isRunning(), "sendMessage", intval($session), $text);
				} else {
	    			$_SLICE_ERR = TRUE;
			}
			} catch (Exception $e) {
	    		$_SLICE_ERR = TRUE;
	    }
	}
	
	//set und get Funktionen ertellen, damit keine undefined function zurück gegeben wird
	//Gebe Slice errors aus
	function getSliceErrors() {
		
		global $_SLICE_ERR;
		global $dir;
		$error = FALSE;
			
		if ($_SLICE_ERR) {
			if ($dir == "server" OR $dir == "view" OR $dir == "user" OR $dir == "settings") {
				$error .= "<div align=\"center\"><div class='sliceerror'>"._slice_error_server_offline."</div></div>";
			}
			return $error;
			} else {
				return FALSE;
		}
	}

	//Falls Slice nicht geladen werden kann, wenn z.B. Server Offline, führe catch schleife aus
	} catch (Ice_Exception $error) {
		switch ($error->unknown) {
			case 'Murmur::InvalidSecretException':
				$_SLICE_ERR = TRUE;
			break;
			case 'Murmur::ServerFailureException':
				$_SLICE_ERR = TRUE;
			break;
			default:
				$_SLICE_ERR = TRUE;
		}
		
		//set und get Funktionen ertellen, damit keine undefined function zurück gegeben wird
		function getServer($server_id) {
			
			//Wichtig: Definiere $_SLICE_ERR für getSliceErrors, damit Fehlermeldung ausgegeben wird
			global $_SLICE_ERR;				
			$_SLICE_ERR = TRUE;
			
			$server = array("online" => "",
							"ip" => _slice_error_server_offline_small,
							"port" => _slice_error_server_offline_small,
							"users" => _slice_error_server_offline_small,
							"conected_users" => _slice_error_server_offline_small,
							"registered_users" => _slice_error_server_offline_small,
							"channels" => _slice_error_server_offline_small,
							);
		
			return $server;

		}
		
		//Hole Alle ServerID´s
		function getAllServers() {
			
			//Wichtig: Definiere $_SLICE_ERR für getSliceErrors, damit Fehlermeldung ausgegeben wird
			global $_SLICE_ERR;				
			$_SLICE_ERR = TRUE;
		
			//Gebe Leeren Array zurück um Fehlermeldungen zu vermeiden
			$return = array();
			
			return $return;
			
		}
		
		//Hole Alle ServerID´s
		function setServer() {
			
			//Wichtig: Definiere $_SLICE_ERR für getSliceErrors, damit Fehlermeldung ausgegeben wird
			global $_SLICE_ERR;				
			$_SLICE_ERR = TRUE;
		
			//Gebe Leeren Array zurück um Fehlermeldungen zu vermeiden
			$return = array();
			
			return $return;
			
		}
				
		//Server starten
		function setServerStart($server_id) {
			
			//Wichtig: Definiere $_SLICE_ERR für getSliceErrors, damit Fehlermeldung ausgegeben wird
			global $_SLICE_ERR;				
			$_SLICE_ERR = TRUE;
		
			return FALSE;
			
		}
		
		//Server stoppen
		function setServerStop($server_id) {
			
			//Wichtig: Definiere $_SLICE_ERR für getSliceErrors, damit Fehlermeldung ausgegeben wird
			global $_SLICE_ERR;				
			$_SLICE_ERR = TRUE;
		
			return FALSE;
			
		}
		
		//Hole Laufzeit eines virtuellen Servers
		function getVirtualServerUptime($server_id) {
			
			//Wichtig: Definiere $_SLICE_ERR für getSliceErrors, damit Fehlermeldung ausgegeben wird
			global $_SLICE_ERR;				
			$_SLICE_ERR = TRUE;
		
			return FALSE;
			
		}
		
		//Gebe Slice errors aus
		function getSliceErrors() {
			
			global $_SLICE_ERR;
			global $dir;
			$error = FALSE;
			
			if ($_SLICE_ERR) {
				if ($dir == "server" OR $dir == "view" OR $dir == "user" OR $dir == "settings") {
					$error .= "<div align=\"center\"><div class='sliceerror'>"._slice_error_server_offline."</div></div>";
				}
			}
			return $error;
		}
		
		return $error;

}
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>