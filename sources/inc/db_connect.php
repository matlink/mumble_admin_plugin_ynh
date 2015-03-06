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
//Get Messages
$get_msg_1 = "The connection to the server: ";
$get_msg_2 = " has failed!";
$get_msg_3 = "Please change the file: ";
$get_msg_4 = "../inc/_mysql.php";
$get_msg_5 = " to connect with MySQL-Server!";
$get_msg_6 = "Following variables may be wrong: ";
$get_msg_7 = " or ";
$get_msg_8 = "The connection to the database: ";
$get_msg_9 = " is failed!";
$get_msg_10 = "Please change the file: ";
$get_msg_11 = "../inc/_mysql.php";
$get_msg_12 = " to connect with the database!";
$get_msg_13 = "Following variables may be wrong: ";
$get_msg_14 = " or ";

//DB Connect Function
$mysql = mysql_connect($mysql_host,$mysql_user,$mysql_pass);
if (!$mysql) {
    echo "$get_msg_1 <b> $mysql_host </b> $get_msg_2 <br> $get_msg_3 <b> $get_msg_4 </b> $get_msg_5 <br> $get_msg_6<b>\$mysql_host</b>, <b>\$mysql_user </b>$get_msg_7 <b>\$mysql_pass</b>!";
	break;
}
$mysql_db = mysql_select_db($database_name,$mysql);
if (!$mysql_db && $mysql) {
    echo "$get_msg_8 <b> $database_name </b> $get_msg_9 <br> $get_msg_10 <b> $get_msg_11 </b> $get_msg_12 <br> $get_msg_13<b>\$database_name</b> $get_msg_14 <b>\$database_prefix</b>!";
}

//Breche Script ab, wenn MySQL-Connect fehlgeschlagen
if ($dir != "start" && (!$mysql OR !$mysql_db)) {
    break;
}
//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>