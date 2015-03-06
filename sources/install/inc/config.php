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

//Definiere MAP Versionsmummer
$map_version = "V2.5.2";
$version_num = $map_version;

//Definiere MAP Versionsdatum
$map_date = "2013-02-24";
$version_date = $map_date;

//Definiere MAP Versionsbuild
$map_build = "6194";
$version_build = $map_build;

//Definiere Template Verzeichnis
$template_dir = '../inc/tpl/';

//Definiere Standart Template Name
$tpldir = "default";
if (isset($_POST["template"])) {
    $tpldir = $_POST["template"];
}

//Definiere Sprachdateien
$language_dir = '../inc/_lang/';

//Definiere Standart Sprache
$lang = "english";
if (isset($_POST["language"])) {
    $lang = $_POST["language"];
}

//Definiere Installationsverzeichnis
$dir = "install";

//Definiere Copyrigth Informationen
$copyrigth = '<a href=\"mailto:%73%75%70%70%6F%72%74%40%6D%75%6D%62%31%65%2E%64%65\">&copy;2009-2013|by P.M.</a>';

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>