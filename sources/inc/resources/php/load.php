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
//************************************************************************************************//
// **START**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//

//Setze Globale SESSIONS und GETTERS
include("../inc/resources/php/globals.php");

//Admin Funktionen (des Users)
if ($dir == "admin") {
	include("../inc/resources/php/gravatar.php");
}
include("../inc/resources/php/admin.php");

//Server-Funktionen
include("../inc/resources/php/objectHandler.php");
include("../inc/resources/php/slice.php");
include("../inc/resources/php/server.php");

//User Funktionen (der Server User)
include("../inc/resources/php/user.php");

//Log-Funktionen
include("../inc/resources/php/logging_query.php");

//Berechtigungs-Funktionen
include("../inc/resources/php/permissions.php");

//Channelviewer-Funktionen
include("../inc/resources/php/channelviewer.php");

//E-Mail-Funktionen
include("../inc/resources/php/email_smtp.php");
include("../inc/resources/php/email_framework.php");
include("../inc/resources/php/email.php");

//Google Recaptcha Funktion laden, wenn benötigt
if ($dir == "request" OR $dir == "operate") {
	include('../inc/resources/php/recaptchalib.php');
	include('../inc/resources/php/recaptcha.php');
}

//************************************************************************************************//
// **ENDE**  Deklariert wichtige globale Einstellungen !!!NICHT ÄNDERN!!!
//************************************************************************************************//
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>