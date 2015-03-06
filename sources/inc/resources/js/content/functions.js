/*
 * Lade Funktionen wenn Dokument fertig geladen ist
 */
function init(){

	

/*
 * Tabs aktivieren auf div mit id tabs
 */
$('div#tabs').tabs();


/*
 * Lade Infofenster in Tabs beim Hovern
 */
$('a').tooltip({  
  track: true, 
  delay: 0, 
  showURL: false, 
  showBody: " - ", 
  fade: 250 
});




/*
 * Listen sortier Funktionen laden
 * Berechtigungsgruppen anzeigen (liste)
 */
var lastID = $("table#listing thead tr th:last").index();
$("table#listing")
.tablesorter({ 
	widthFixed: true, 
	widgets: ['zebra']
    /*headers: { 
        6: { 
            sorter: false 
        }, 
        7: { 
            sorter: false 
        } 
    } */
})
.tablesorterPager({container: $("#pager")}); 




/*
 * Tabellensuche Funktion Laden usw.
 */

var theTable = $('table#listing')

theTable.find("tbody > tr").find("td:eq(1)").mousedown(function(){
  $(this).prev().find(":checkbox").click()
});

$("#filter").keyup(function() {
  $.uiTableFilter( theTable, this.value );
})

$('#filter-form').submit(function(){
  theTable.find("tbody > tr:visible > td:eq(1)").mousedown();
  return false;
}).focus(); //Give focus to input field



/*
 * Tabellensuche Funktion Laden usw.
 * Text in Input Feld ausblenden
 */
$("input#filter").focus(function () {
    $(this).val("");
});
;



/*
 * Checkboxen in Listenansicht markieren
 */
$(document).ready(function() {
    $('img.check_all').click(function() {
      /*
       * Für alle Listeneinträge mit Checkbox name = "id"
       */
      var content = $("input[name='id[]']");
      content.attr("checked", !content.attr("checked"));
      /*
       * Für alle Listeneinträge mit Checkbox name = "server_id"
       */
      var content = $("input[name='server_id[]']");
      content.attr("checked", !content.attr("checked"));
      /*
       * Für alle Listeneinträge mit Checkbox name = "user_id"
       */
      var content = $("input[name='user_id[]']");
      content.attr("checked", !content.attr("checked"));
      /*
       * Für alle Listeneinträge mit Checkbox name = "email_id"
       */
      var content = $("input[name='email_id[]']");
      content.attr("checked", !content.attr("checked"));
      /*
       * Für alle Listeneinträge mit Checkbox name = "template_id"
       */
      var content = $("input[name='template_id[]']");
      content.attr("checked", !content.attr("checked"));
      return false;
    });
 });


}

/*
 **********************************************
 * Globale Funktionen                         *
 * Werden direkt ausgeführt                   *
 **********************************************
 */

/*
 *  Definiere reCaptcha Template
 *  Farbe: white
 */


function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
var coockieLanguage = readCookie('language')
if (coockieLanguage == 'german') {
	var RecaptchaOptionsVal = 'de';
}
if (coockieLanguage == 'english') {
	var RecaptchaOptionsVal = 'en';
}
var RecaptchaOptions = {
		lang : RecaptchaOptionsVal,
		theme : 'white'
};




