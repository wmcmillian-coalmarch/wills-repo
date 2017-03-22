// DOM ready function from [dustindiaz](http://dustindiaz.com/smallest-domready-ever)
function r(f){/in/.test(document.readyState)?setTimeout('r('+f+')',9):f()}

var $ = document;
var cssID = 'style';

r(function() {

	// add the stylesheet
	if(!$.getElementById(cssID)) {
		var head = $.getElementsByTagName('head')[0];
		var link = $.createElement('link');
		link.id = cssID;
		link.rel = 'stylesheet';
		link.type = 'text/css';
		link.href = /*  */ 'http://medschool.duke.edu/files/branding/style.css';
		link.media = 'all';
		head.appendChild(link);
	}

	// determine which tagline should be used
	var tagline = $.getElementById('som-brandbar').getAttribute('data-tagline');

	var taglineCopy = '';

	if (tagline == 1) {
		taglineCopy = 'Education, Research, Patient Care';
	} else if (tagline == 2) {
		taglineCopy = 'Research, Patient Care, Education';
	} else if (tagline == 3) {
		taglineCopy = 'Patient Care, Research, Education';
	}

	// plop in the HTML
	$.getElementById('som-brandbar').innerHTML = '<div class="som-wrap" onClick="window.location.href=\'http://medschool.duke.edu/\'"><div class="som-title"><span>Duke</span> University School of Medicine</div><div class="som-tagline">'+ taglineCopy +'</div></div>';

});