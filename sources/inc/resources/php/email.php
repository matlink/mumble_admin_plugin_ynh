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

//Hole E-Mail Server Einstellungen
//$MailSettings = getMailSettings();
//email_header, email_footer, email_subject_prefix, email_delete_history, email_type, email_host, email_port, email_username, email_password
function getMailSettings() {
	
	global $database_prefix;
	
	//E-Mail --> Header          
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '36'");
    $get_email_header = mysql_fetch_array($qry);
    $settings['email_header'] = $get_email_header['value_2'];
    
    //E-Mail --> Footer
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '37'");
    $get_email_footer = mysql_fetch_array($qry);
    $settings['email_footer'] = $get_email_footer['value_2'];

	//E-Mail --> Betreff Präfix       
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '38'");
    $get_email_subject_prefix = mysql_fetch_array($qry);
    $settings['email_subject_prefix'] = $get_email_subject_prefix['value_2'];
        
    //E-Mail --> Historie löschen        
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '39'");
    $get_email_delete_history = mysql_fetch_array($qry);
    $settings['email_delete_history'] = $get_email_delete_history['value_2'];
        
    //E-Mail --> Mailer (Type)        
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '40'");
    $get_email_type = mysql_fetch_array($qry);
    $settings['email_type'] = $get_email_type['value_2'];
        
    //E-Mail --> Host        
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '41'");
    $get_email_host = mysql_fetch_array($qry);
    $settings['email_host'] = $get_email_host['value_2'];
        
    //E-Mail --> Port       
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '42'");
    $get_email_port = mysql_fetch_array($qry);
    $settings['email_port'] = $get_email_port['value_2'];
        
    //E-Mail --> Benutzernamen    
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '43'");
    $get_email_username = mysql_fetch_array($qry);
    $settings['email_username'] = $get_email_username['value_2'];
        
    //E-Mail --> Passwort   
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '44'");
    $get_email_password = mysql_fetch_array($qry);
    $settings['email_password'] = $get_email_password['value_2'];
    
	//E-Mail --> Absender   
    $qry = mysql_query("SELECT * FROM `".$database_prefix."settings` WHERE id = '47'");
    $get_email_sender = mysql_fetch_array($qry);
    $settings['email_sender'] = $get_email_sender['value_2'];
	
    return $settings;
	
}

//E-Mail Historie löschen wenn Wert in Settings angegeben
function deleteMailHistory() {
	
	global $database_prefix;
	
	//Hole E-Mail-Einstellungen
	$MailSettings = getMailSettings();
		
	//Lösche Einträge aus der Datenbank, wenn History nicht ausgeschaltet ist (AUS =0)
	if ($MailSettings['email_delete_history'] != "0") {
		//Definiere Datum, wie alt die Logs sein düfen
		$max_days = time()-60*60*24*$MailSettings['email_delete_history'];
		$unix_time = date("Y-m-d H:i:s",$max_days);
	
		//Hole alle Logs und Werte diese in einer While Schleife aus.
		$qry = mysql_query("SELECT * FROM `".$database_prefix."email_history` WHERE last_sent < '".$unix_time."'");
		while ($get = mysql_fetch_array($qry)) {
			//Wenn älter als Settings es wollen, löschen!
			mysql_query("DELETE FROM `".$database_prefix."email_history` WHERE email_id = '".$get['email_id']."' LIMIT 1");	
		}	
	}
}
if ($dir == "email") {
	deleteMailHistory();
}

//Hole E-Mail Sprache
function getMailLanguage($ToUserID, $template_id = FALSE) {

	if ($ToUserID != FALSE) {
		$ToUser = user_info($ToUserID);	
		} else {
			$ToUser['language'] = "english";
	}
	
	if ($ToUser['language'] == "english") {
		$MailLanguage = "en";
		} elseif ($ToUser['language'] == "german") {
			$MailLanguage = "de";
	}
	
	return $MailLanguage;
	
}

//Hole Template Werte us DB
function getMailTemplate($template_id) {
	
	global $database_prefix;
	
	//Hole Werte aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."email_templates` WHERE template_id = '".$template_id."'");
    $getTemplate = mysql_fetch_array($qry);
    
    $MailTemplate = array("template_id" => $getTemplate['template_id'],
    					  "name" => $getTemplate['name'],
    					  "lock" => $getTemplate['lock'],
    					  "user_id" => $getTemplate['user_id'],
    					  "date" => $getTemplate['date']);
	
	return $MailTemplate;
	
}

//Hole Platzhalter für $template_id aus DB
function getMailPlaceholder($template_id) {
	
	global $database_prefix;
	
	//Hole Werte aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."email_placeholder` WHERE template_id = '".$template_id."'");
	while ($getPlaceholder = mysql_fetch_array($qry)) {     															//Schreibe Index
		$Placeholder[$getPlaceholder['name']]['placeholder_id'] = $getPlaceholder['placeholder_id'];  					//...
		$Placeholder[$getPlaceholder['name']]['template_id'] = $getPlaceholder['template_id'];  						//...
		$Placeholder[$getPlaceholder['name']]['name'] = $getPlaceholder['name'];  										//...
		$Placeholder[$getPlaceholder['name']]['translation_name'] = $getPlaceholder['translation_name'];  				//...
		$Placeholder[$getPlaceholder['name']]['translation_discription'] = $getPlaceholder['translation_discription'];  //...
	}
	
	return $Placeholder;
	
}

//Hole Mail Content in def. Sprachen
//$language = "en" oder "de"
//$type = "body" oder "subject"
function getMailContent($template_id, $MailLanguage = "en", $type = "body") {
	
	global $database_prefix;
	
	//Hole Werte aus DB
	$qry = mysql_query("SELECT * FROM `".$database_prefix."email_content` WHERE template_id = '".$template_id."' AND language = '".$MailLanguage."' AND type = '".$type."'");
    $getContent = mysql_fetch_array($qry);
    
    $MailContect = array("content_id" => $getContent['content_id'],
    					 "template_id" => $getContent['template_id'],
    					 "language" => $getContent['language'],
    					 "type" => $getContent['type'],
    					 "text" => $getContent['text']);
    
    return $MailContect;
	
}

//Hole Betreff aus DB
function BuildMailSubject($template_id = FALSE, $Placeholder = FALSE, $AltSubject = FALSE, $ReplaceSubject = FALSE, $ToUserID = FALSE) {

	//Hole E-Mail-Einstellungen
	$MailSettings = getMailSettings();
	
	//Hole Betreff Sprache (en/de)
	$MailLanguage = getMailLanguage($ToUserID, $template_id);
	
	//Hole Betreff-Content
	if ($template_id != FALSE) {
		$SubjectContent = getMailContent($template_id, $MailLanguage, "subject");
		$SubjectContent = $SubjectContent['text'];
	}
	
	//Baue Subject zusammen
	$temporary = $SubjectContent;
	if ($Placeholder != FALSE) {
	    foreach($Placeholder as $value => $code) {
	        $temporary = str_replace("{".$value."}", $code, $temporary);
	    }
	}
    $SubjectContent = $temporary;
	
	//Baue Präfix an
	if ($ReplaceSubject == FALSE) {
		if($AltSubject != FALSE) {
			$MailSubject = $MailSettings['email_subject_prefix'] . $AltSubject;
				} else {
					$MailSubject = $MailSettings['email_subject_prefix'] . $SubjectContent;
			}
		} else {
			$MailSubject = $ReplaceSubject;
	}
	
	return $MailSubject;
	
}

//E-Mail Body erstellen
function BuildMailBody($template_id, $Placeholder = FALSE, $AltBody = FALSE, $ReplaceBody = FALSE, $ToUserID = FALSE) {
	
	//Hole E-Mail-Einstellungen
	$MailSettings = getMailSettings();
	
	//Hole Betreff Sprache (en/de)
	$MailLanguage = getMailLanguage($ToUserID, $template_id);
	
	//Hole Body-Content
	if ($template_id != FALSE) {
		$Body['Content'] = getMailContent($template_id, $MailLanguage, "body");
		$Body['Content'] = $Body['Content']['text'];
	}
	
	//Baue Body zusammen
	$temporary = $Body['Content'];
	if ($Placeholder != FALSE) {
	    foreach($Placeholder as $value => $code) {
	        $temporary = str_replace("{".$value."}", $code, $temporary);
	    }
	}
    $Body['ContentBody'] = $temporary;

	//Baue Header und Footer an
	$Body['Body'] = $MailSettings['email_header'] . $Body['ContentBody'] . $MailSettings['email_footer'];
	
	//Replacen, falls vorhanden
	if ($ReplaceBody != FALSE) {
		$Body['Body'] = $ReplaceBody;
	}
	
	if ($AltBody != FALSE) {
		$Body['AltBody'] = $AltBody;
		} else {
			$Body['AltBody'] = _email_mapmail_altbody;
	}	
	
	return $Body;
	
}

//E-Mail in DB speichern
function setMail($template_id, $subject, $text, $ToMail, $ToName, $ToUserID, $FromMail, $FromName, $FromUserID) {

	global $database_prefix;
	global $aktdate;
	$mails = FALSE;
	$MailStillExisting = FALSE;
	
	//Hole E-Mails
	$qry = mysql_query("SELECT * FROM `".$database_prefix."email_history` ORDER BY last_sent DESC");
    $i = "0";
	while ($get = mysql_fetch_array($qry)) {
		$mails[$i] = $get;
		$i++;
	}
	
	//Schreibe E-Mails in Liste
	if (isset($mails[0])) {
		foreach ($mails as $email) {
			//Checke Ob gleiche E-Mail bereits vorhanden
			if ($email['template_id'] == $template_id AND $email['subject'] == $subject AND $email['text'] == $text AND $email['to_mail'] == $ToMail AND $email['from_mail'] == $FromMail) {
				$MailStillExisting = TRUE;
				$email_id = $email['email_id'];
				$counter = $email['counter'];
				break;
				} else {
					$MailStillExisting = FALSE;
			}
		}
		} else {
			$MailStillExisting = FALSE;
	}
	
	//In DB-Schreiben
	$return = FALSE;
	if ($MailStillExisting == TRUE) {
		$counter++; 
		$return .= mysql_query("UPDATE `".$database_prefix."email_history` SET counter = '$counter' WHERE email_id = '$email_id'");
		$return .= mysql_query("UPDATE `".$database_prefix."email_history` SET last_sent = '$aktdate' WHERE email_id = '$email_id'");
		} else {
			//Speichere E-Mail in Datenbank
			$return = mysql_query("INSERT INTO `".$database_prefix."email_history` (`template_id`, `subject`, `text`, `to_mail`, `to_name`, `to_user_id`, `from_mail`, `from_name`, `from_user_id`, `counter`, `last_sent`) VALUES ('".$template_id."', '".$subject."', '".$text."', '".$ToMail."', '".$ToName."', '".$ToUserID."', '".$FromMail."', '".$FromName."', '".$FromUserID."', '1', '".$aktdate."');");
	}
		
	return $return;
} 

/**
 * E-Mail Mutterfunktion
 * @param string $FromMail E-Mail-Adresse des Absenders
 * @param string $FromName Name des Absenders
 * @param string $FromUserID user_id des Admins/Absenders
 * @param string $ToMail E-Mail-Adresse des Empfängers
 * @param string $ToName Name des Empfängers
 * @param string $ToUserID user_id des Admins/Empfängers
 * @param array $CcMail Liste mit Empfängern für CC 
 * @param array $BccMail Liste mit Empfängern für BCC
 * @param string $ReplyToMail Antwortadresse auf diese E-Mail
 * @param int $WordWrap Wenn kein HTML, wird hier der Absatz definiert
 * @param bool $IsHTML Setzt den HTML Status der E-Mail
 * @param int $template_id ID dieser E-Mail
 * @param array $Placeholder Platzhalter, mit denen Textabschnitte ersetzt werden sollen im bezug auf die $template_id
 * @param string $ReplaceSubject Ersetzt den KOMPLETTEN Betreff der Mail gegen den definierten String
 * @param string $ReplaceBody Ersetzt den komplette Text, der in der $template_id hinterlegt ist, mit dem definierten String
 * @param string $AltSubject Ersetzt nur den Text nach dem Betreff-Präfix der in der $template_id definiert ist
 * @param string $AltBody Wenn kein HTML dargestellt werden kann, wird der im String hinterlegte Text dargestellt
 * @return array Gibt die Inhalte der gesendeten Mail oder einen Fehlerbericht zurück
 */
function mapmail($FromMail, $FromName, $FromUserID, $ToMail, $ToName, $ToUserID, $CcMail = FALSE, 
				 $BccMail = FALSE, $ReplyToMail = FALSE, $WordWrap = "75", $IsHTML = TRUE,
				 $template_id = FALSE, $Placeholder = FALSE, $ReplaceSubject = FALSE, $ReplaceBody = FALSE,
				 $AltSubject = FALSE, $AltBody = FALSE) {
		
	//Hole E-Mail-Einstellungen
	$MailSettings = getMailSettings();
	
	//Hole E-Mail Objekt
	$Mailer = new PHPMailer();

	//Hole Infos vom Template
	$template = getMailTemplate($template_id);
	
	//Betreff erstellen
	$Subject = BuildMailSubject($template_id, $Placeholder, $AltSubject, $ReplaceSubject, $ToUserID);
	
	//Body erstellen
	$Body = BuildMailBody($template_id, $Placeholder, $AltBody, $ReplaceBody, $ToUserID);
	
	//Definiere MTA
	if ($MailSettings['email_type'] == "mail") {
		$Mailer->IsMail();
		} elseif ($MailSettings['email_type'] == "smtp") {
			$Mailer->IsSMTP();
			} elseif ($MailSettings['email_type'] == "sendmail") {
				$Mailer->IsSendmail();
				} elseif ($MailSettings['email_type'] == "qmail") {
					$Mailer->IsQmail();
	}
	
	//Definiere Verbindungsdaten
	$Mailer->Host = $MailSettings['email_host']; 
	$Mailer->Port = $MailSettings['email_port']; 
	$Mailer->SMTPAuth = true;     
	$Mailer->Username = $MailSettings['email_username'];  
	$Mailer->Password = $MailSettings['email_password'];
	
	//Definiere E-Mail Content	
	if ($MailSettings['email_sender'] == "") {
		$Mailer->From     = $FromMail;
		$Mailer->FromName = $FromName;
		} else {
			$Mailer->From     = $MailSettings['email_sender'];
	}
	$Mailer->AddAddress($ToMail, $ToName);
	//CC-Array:
	//$CcAdd[1][mail] = FALSE;
	//$CcAdd[1][name] = FALSE;
	//$CcAdd[2][mail] = FALSE;
	//$CcAdd[2][name] = FALSE;
	//....	
	if ($CcMail != FALSE) {
		foreach($CcMail as $CcAdd) {
			$Mailer->AddCC($CcAdd['mail'], $CcAdd['name']);
		}
	}
	if ($BccMail != FALSE) {
		foreach($BccMail as $BccAdd) {
			$Mailer->AddBCC($BccAdd['mail'], $BccAdd['name']);
		}
	}
	if ($ReplyToMail != FALSE) {
		foreach($ReplyToMail as $ReplyToAdd) {
			$Mailer->AddReplyTo($ReplyToAdd['mail'], $ReplyToAdd['name']);
		}
	}
	//Zeilenumbruch einstellen
	$Mailer->WordWrap = $WordWrap;    
	//Anhang - Funktion abgeschaltet!                          
	//$Mailer->AddAttachment("/var/tmp/file.tar.gz");      
	//$Mailer->AddAttachment("/tmp/image.jpg", "new.jpg");
	//als HTML-E-Mail senden
	$Mailer->IsHTML($IsHTML);                               
	//Betreff
	$Mailer->Subject = $Subject;
	//E-Mail Inhalt
	$Mailer->Body     =  $Body['Body'];
	$Mailer->AltBody  =  $Body['AltBody'];
	
	//Status setzen
	if ($template['lock'] == "0" OR $template['lock'] == FALSE) {
		$MAP_Mail['result'] = FALSE;
		$MAP_Mail['errors'] = _email_mapmail_locked;
		} elseif ($template['lock'] == "1") {
		if(!$Mailer->Send()) {
		    $MAP_Mail['result'] = FALSE;
		    $MAP_Mail['errors'] = $Mailer->ErrorInfo;
			} else {
				$MAP_Mail['result'] = TRUE;					//Ergebnis, ob Mail-Versand erfolgreich
				$MAP_Mail['errors'] = FALSE;				//Fehlermeldung, falls Mail-Versand nicht erfolgreich
				$MAP_Mail['subject'] = $Subject;			//Kompletter Betreff der E-Mail
				$MAP_Mail['body'] = $Body['Body'];  		//Mit ersetzten Platzhaltern und Header + Footer
				$MAP_Mail['content'] = $Body['Content'];	//Mit Platzhaltern im {}-Textformat, ohne Header + Footer
				//E-Mail in DB speichern
				if ($template_id != FALSE) {
					setMail($template_id, $Subject, $Body['Body'], $ToMail, $ToName, $ToUserID, $FromMail, $FromName, $FromUserID);	
				}
		}
	}
	
	return $MAP_Mail;
	
} 

//************************************************************************************************//
// Ende des Ausgabeinhalts
//************************************************************************************************//
?>